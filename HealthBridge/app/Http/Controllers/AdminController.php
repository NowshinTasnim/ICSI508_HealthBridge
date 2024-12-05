<?php

namespace App\Http\Controllers;

use App\Models\Patient;
//use App\Models\Claim;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function dashboard()
    {
        $log_bool = Session::get("logged_in");
        if (!$log_bool) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        return view('admin.dashboard');
    }

    public function requestClaim(Request $request): \Illuminate\Foundation\Application|View|Factory|Application|RedirectResponse
    {
        $log_bool = Session::get("logged_in");
        if (!$log_bool) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');

        }

        try {
            $search = $request->input('search');

            $patients = DB::table('Appointments')
                ->join('Patient', 'Appointments.PatientID', '=', 'Patient.PatientID')
                ->leftJoin('Claim', 'Patient.PatientID', '=', 'Claim.PatientID')
                ->leftJoin('Report', function ($join) {
                    $join->on('Report.PatientID', '=', 'Appointments.PatientID')
                        ->on('Report.LabID', '=', 'Appointments.LabID');
                })
                ->leftJoin('Bill', function ($join) {
                    $join->on('Bill.PatientID', '=', 'Appointments.PatientID')
                        ->on('Bill.LabID', '=', 'Appointments.LabID');
                })
                ->select(
                    'Patient.PatientID',
                    'Patient.Pt_Name',
                    'Patient.InsuranceID',
                    'Appointments.LabID',
                    'Appointments.AppointmentID',
                    'Report.File as report_file',
                    'Bill.File as bill_file'
                )
                ->where('Appointments.Report_Status', 'Uploaded')
                ->where('Appointments.Bill_Status', 'Uploaded')
                ->distinct()
                ->when($search, function ($query, $search) {
                    return $query->where('Patient.Pt_Name', 'LIKE', '%' . $search . '%');
                })
                ->orderBy('Patient.Pt_Name')
                ->get();

            return view('admin.request-claim', ['patients' => $patients, 'search' => $search]);
        } catch (\Exception $e) {
            Log::error('Error in requestClaim: ' . $e->getMessage());
            return back()->with('error', 'Error loading patients');
        }
    }

    public function viewReport(Request $request){
        $patientId = $request->input('PatientID');
        $labId = $request->input('LabID');
        $appointmentId = $request->input('AppointmentID');
        $fileName = "{$appointmentId}_{$patientId}_{$labId}.pdf";
        $path = "Report/{$fileName}";
        if (!Storage::exists($path)) {
            return back()->with('error', 'Report file not found.');
        }
        return response()->file(storage_path('app/' . $path));
    }
    public function viewBill(Request $request){
        $patientId = $request->input('PatientID');
        $labId = $request->input('LabID');
        $appointmentId = $request->input('AppointmentID');
        $fileName = "{$appointmentId}_{$patientId}_{$labId}.pdf";
        $path = "Bill/{$fileName}";
        if (!Storage::exists($path)) {
            return back()->with('error', 'Bill file not found.');
        }
        return response()->file(storage_path('app/' . $path));
    }
    public function submitClaim(Request $request)
    {
        $log_bool = Session::get("logged_in");
        if (!$log_bool) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');

        }

        $request->validate([
            'claim_file' => 'required|file|mimes:png,jpg,jpeg,pdf|max:10240',
            'patient_id' => 'required|integer',
            'lab_id' => 'required|integer',
            'insurance_id' => 'required|integer',
        ]);

        $appointmentId = $request->input('appointment_id');
        $patientId = $request->input('patient_id');
        $labId = $request->input('lab_id');
        $insuranceId = $request->input('insurance_id');


        $credentialId = DB::table('Insurance_Company')
            ->where('InsuranceID', $insuranceId)
            ->value('CredentialID');

        if (!$credentialId) {
            return redirect()->back()->with('error', 'Invalid Insurance ID.');
        }

        // Generate file name
        $fileName = "{$appointmentId}_{$patientId}_{$labId}." . $request->file('claim_file')->getClientOriginalExtension();

        // Save file
        $request->file('claim_file')->storeAs('Claim', $fileName);

        $user_type = Session::get('user_type');
        if($user_type == 0){
            // Insert claim
            DB::table('Claim')->insert([
                'File' =>"{$appointmentId}_{$patientId}_{$labId}",
                'Filing_status' => 'Filled',
                'Approval_status' => 'None',
                'PatientID' => $patientId,
                'LabID' => $labId,
                'InsuranceID' => $credentialId,
            ]);

            return back()->with('success', 'Claim submitted successfully.');
        }
    }


    public function submittedClaims()
    {
        $log_bool = Session::get("logged_in");
        if (!$log_bool) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');

        }

        $claims = DB::table('Claim')
            ->join('Patient', 'Claim.PatientID', '=', 'Patient.PatientID')
            ->select('Claim.*', 'Patient.Pt_Name')
            ->orderBy('Claim.ClaimID', 'desc')
            ->where('Filing_status', 'Filled')
            ->get();

        return view('admin.submitted-claims', compact('claims'));
    }
    public function approvedClaims()
    {
        $log_bool = Session::get("logged_in");
        if (!$log_bool) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');

        }
        $claims = DB::table('Claim')
            ->join('Patient', 'Claim.PatientID', '=', 'Patient.PatientID')
            ->join('Lab', 'Claim.LabID', '=', 'Lab.LabID')
            ->select('Claim.*', 'Patient.Pt_Name', 'Lab.Lab_Name')
            ->orderBy('Claim.ClaimID', 'desc')
            ->where('Approval_status', 'Approved')
            ->get();

        return view('admin.approved-claims', compact('claims'));
    }

    public function rejectedClaims()
    {
        $log_bool = Session::get("logged_in");
        if (!$log_bool) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');

        }
        $claims = DB::table('Claim')
            ->join('Patient', 'Claim.PatientID', '=', 'Patient.PatientID')
            ->join('Lab', 'Claim.LabID', '=', 'Lab.LabID')
            ->select('Claim.*', 'Patient.Pt_Name', 'Lab.Lab_Name', 'Claim.Reason_for_rejection')
            ->orderBy('Claim.ClaimID', 'desc')
            ->where('Approval_status', 'Reject')
            ->get();

        return view('admin.rejected-claims', compact('claims'));
    }
}
