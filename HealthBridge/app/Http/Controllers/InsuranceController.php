<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Facade\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class InsuranceController extends Controller
{
    //
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function dashboard()
    {
        //$insuranceId = 5; // Replace this with dynamic InsuranceID if needed

        // Retrieve InsuranceID from session
        $insuranceId = Session::get('insurance_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$insuranceId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

// Total number of claims with Filing_status = 'Filled' for the specific InsuranceID
        $totalFilledClaims = DB::table('claim')
            ->where('Filing_status', 'Filled')
            ->where('InsuranceID', $insuranceId)
            ->count();

// Status-wise counts (Approved, Rejected, None)
        $statuses = DB::table('claim')
            ->select('Approval_status', DB::raw('COUNT(*) as count'))
            ->where('Filing_status', 'Filled') // Only consider claims with Filing_status = 'Filled'
            ->where('InsuranceID', $insuranceId) // For the specific InsuranceID
            ->groupBy('Approval_status')
            ->get();

// Initialize percentages
        $chartData = [
            'Approved' => 0,
            'Rejected' => 0,
            'None' => 0,
        ];

// Calculate percentages based on the counts
        foreach ($statuses as $status) {
            if ($totalFilledClaims > 0) { // Avoid division by zero
                if ($status->Approval_status === 'Approved') {
                    $chartData['Approved'] = ($status->count / $totalFilledClaims) * 100;
                } elseif ($status->Approval_status === 'Reject' || $status->Approval_status === 'Rejected') {
                    $chartData['Rejected'] = ($status->count / $totalFilledClaims) * 100;
                } elseif ($status->Approval_status === 'None') {
                    $chartData['None'] = ($status->count / $totalFilledClaims) * 100;
                }
            }
        }

// Pass data to the view
        return view('Insurance.dashboard', compact('chartData'), ['log' =>$log]);

        //return view('Lab.dashboard',['lab' => $lab,'log' =>$log]);



// Debugging: Check the final chart data
        //dd($chartData);

// Pass data to the view
       // return view('Insurance.dashboard', compact('chartData'));

    }


    public function claim()
    {
        // Retrieve LabID from session
        $insuranceId = Session::get('insurance_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$insuranceId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        return view('Insurance.claim', ['log' =>$log] );
    }


    public function claim_list()
    {
        //$insuranceId = 5;
        // Retrieve LabID from session
        $insuranceId = Session::get('insurance_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$insuranceId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        // Fetch claims and related data
        $claims = DB::table('claim')
            ->join('lab', 'claim.LabID', '=', 'lab.LabID') // Join claim with lab on LabID
            ->join('patient', 'claim.PatientID', '=', 'patient.PatientID') // Join claim with patient on PatientID
            ->leftJoin(
                DB::raw('(SELECT PatientID, LabID, MAX(AppointmentID) as AppointmentID FROM appointments GROUP BY PatientID, LabID) as latest_appointments'),
                function ($join) {
                    $join->on('claim.PatientID', '=', 'latest_appointments.PatientID')
                        ->on('claim.LabID', '=', 'latest_appointments.LabID');
                }
            ) // Join with the latest appointments
            ->select(
                'claim.ClaimID',
                'claim.PatientID', // Fetch PatientID
                'claim.LabID', // Fetch LabID
                'lab.Lab_Name', // Fetch Lab_Name from lab table
                'patient.Pt_Name', // Fetch Pt_Name from patient table
                'claim.File as Claim_File', // Claim file path
                'claim.Approval_status', // Approval status (Accepted/Rejected)
                'latest_appointments.AppointmentID' // Fetch the latest AppointmentID
            )
            ->where('claim.Filing_status', 'Filled') // Filter by Filing_status = 'Filled'
            ->where('claim.InsuranceID', $insuranceId) // Filter by InsuranceID
            ->get(); //dd($claims);

        $totalFilled = $claims->count();

        // Pass data to the view
        return view('Insurance.claim', compact('claims', 'totalFilled'), ['log' =>$log]);

        // Pass the claims data to the Blade view
        //return view('Insurance.claim', compact('claims'));
    }
    public function updateApprovalStatus(Request $request)
    {
        // Retrieve LabID from session
        $insuranceId = Session::get('insurance_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$insuranceId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        //dd($request->action);
        $claimId = $request->input('ClaimID');
        $patientId = $request->input('PatientID');
        $labId = $request->input('LabID');
        $appointmentId = $request->input('AppointmentID');

        // Check if the action is "accept" or "reject"
        if ($request->action === 'accept') {
            // Update status to "Accepted"
            DB::table('claim')->where('ClaimID', $claimId)->update([
                'Approval_status' => 'Approved',
            ]);
            return redirect()->back()->with('success', 'Claim accepted successfully!');
        }

        if ($request->action === 'reject') {
            // Get the rejection reason from the request
            $reason = $request->input('Reason_for_rejection');

            // Update status to "Rejected" and store the reason
            DB::table('claim')->where('ClaimID', $claimId)->update([
                'Approval_status' => 'Reject',
                'Reason_for_rejection' => $reason,
            ]);

            return redirect()->back()->with('success', 'Claim rejected successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

//    public function downloadFile(Request $request)
//    {
//        $request->validate([
//            'report' => 'required|file|mimes:png,jpg,pdf|max:10240', // Restrict file type and size
//        ]);
//
//        $patientId = $request->input('PatientID');
//        $labId = $request->input('LabID');
//        $appointmentId = $request->input('AppointmentID');
//
//        // Generate the file name
//        $fileName = "{$appointmentId}_{$patientId}_{$labId}.pdf" ;
//
//        $path = 'Claim/' . $fileName;
//
//        if(!Storage::exists($path)) {
//            return redirect()->back()->with('error', 'File not found.');
//        }
//        return response()->download(storage_path('app/' . $path), $fileName);
//
//
//    }

    public function downloadFile(Request $request)
    {
        $patientId = $request->input('PatientID');
        $labId = $request->input('LabID');
        $appointmentId = $request->input('AppointmentID');

        // Generate the file name
        $fileName = "{$appointmentId}_{$patientId}_{$labId}.pdf";

        // Path to the file in the 'storage/app/Claim' folder
        $path = "Claim/{$fileName}";

        // Check if the file exists in the storage
        if (!Storage::exists($path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Use Storage to download the file
        return Storage::download($path, $fileName);
    }

    public function profile()
    {
        $insuranceId = Session::get('insurance_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        $insuranceId = DB::table('insurance_company')
            ->where('InsuranceID', $insuranceId)
            ->first();

        return view('Insurance.profile', [
            'insuranceId' => $insuranceId,
            'log' => $log,
            'logged_in' => $logged_in,

        ]);

    }

//    With hash
//    public function updatePassword(Request $request)
//    {
//        $request->validate([
//            'current_password' => 'required',
//            'new_password' => 'required|min:8|confirmed',
//        ]);
//
//        $insuranceId = Session::get('insurance_id');
//
//        // Retrieve the current password from the database
//        $insurance = DB::table('insurance_company')
//            ->where('InsuranceID', $insuranceId)
//            ->first();
//
////        if (!$insurance || !Hash::check($request->current_password, $insurance->password)) {
////            return redirect()->back()->with('error', 'Current password is incorrect.');
////        }
//
//
//
//
//        // Update the password
//        DB::table('insurance_company')
//            ->where('InsuranceID', $insuranceId)
//            ->update([
////                'password' => Hash::make($request->new_password),
//
//            ]);
//
//        return redirect()->back()->with('success', 'Password updated successfully.');
//    }
//

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        // Get the logged-in user's ID from the session
        $credentialID = Session::get('credential_id'); // Ensure 'credential_id' is set in the session during login

        if (!$credentialID) {
            return redirect()->route('login')->withErrors(['error' => 'You are not logged in.']);
        }

        // Retrieve the current user's password from the database
        $user = DB::table('credentials')->where('CredentialID', $credentialID)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found.']);
        }

        //Verify current password
        if (!Hash::check($request->current_password, $user->Log_Password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
         // Verify the current password (plain text comparison)
//         if ($request->current_password !== $user->Log_Password) {
//             return back()->withErrors(['current_password' => 'The current password is incorrect.']);
//         }

        // Verify the new password with current one
        if ($request->new_password === $request->current_password) {
            return back()->withErrors(['new_password' => 'The new password must be different from the current password.']);
        }
        // Verify the new password with current one
        if ($request->new_password !== $request->new_password_confirmation) {
            return back()->withErrors(['new_password_confirmation' => 'The Confirmation does not match with the new password.']);
        }

        // Update the password in the database
        DB::table('credentials')
            ->where('CredentialID', $credentialID)
            ->update(['Log_Password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password updated successfully!');
    }



}
