<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Symfony\Component\Mime\Header\all;

class PatientController extends Controller
{


    public function index(){
        $patientId = Session::get('patient_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$patientId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        return view('Patient.patient_dashboard',['patientId'=>$patientId,'logged_in'=>$logged_in, 'log'=>$log]);
    }


    public function showTests(){
        $patientId = Session::get('patient_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$patientId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        // retrieve data from test_availability table
        // raw sql query "SELECT Lab_Name, Test_name, Available_date, Start_time, End_time from Lab natural join Available_dates natural join Test_availability natural join Lab_Test"
        $labs = DB::table('Lab')
            ->join('Test_availability', 'Lab.LabID', '=', 'Test_availability.LabID')
            ->join('Lab_Test', 'Lab_Test.TestID', '=', 'Test_availability.TestID')
            ->join('Available_dates', 'Available_dates.AvailableID', '=', 'Test_availability.AvailableID')
            ->select('Test_availability.LabID', 'Lab_Name', 'Test_availability.TestID', 'Test_name', 'Available_date', 'Start_time', 'End_time')
            ->where('Available_dates.Available_date', '>=', '2024-12-05')
            ->get();

        return view('Patient.show_labs',[
            'labs'=>$labs, 'patientId'=>$patientId, 'logged_in'=>$logged_in, 'log'=>$log
        ]);

    }

    public function showAppointments(){
        $patientId = Session::get('patient_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$patientId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        // retrieve data from appointments table
        // raw sql query "select App_Date, App_Time, Test_Status, Test_name, Pt_Name, Lab_Name, Report_Status from Appointments, Lab, Patient, Lab_Test where Appointments.LabID = Lab.LabID and Appointments.PatientID = Patient.PatientID and Appointments.TestID = Lab_Test.TestID"
        $appointments = DB::table('Appointments')
            ->join('Lab', 'Appointments.LabID', '=', 'Lab.LabID')
            ->join('Patient', 'Appointments.PatientID', '=', 'Patient.PatientID')
            ->join('Lab_Test', 'Appointments.TestID', '=', 'Lab_Test.TestID')
            ->select('App_Date', 'App_Time', 'Test_Status', 'Test_name', 'Pt_Name', 'Lab_Name', 'Report_Status')
            ->where('Appointments.PatientID', $patientId)
            ->orderBy('AppointmentID', 'DESC')
            ->get();

        return view('Patient.show_appointments',[
            'appointments'=>$appointments, 'patientId'=>$patientId, 'logged_in'=>$logged_in, 'log'=>$log
        ]);

    }




    public function createAppointment($date, $time, $labID, $testID){
        $patientId = Session::get('patient_id');
        $log = Session::get('Login_ID');
        $logged_in = Session::get('logged_in');

        if (!$patientId && !$logged_in ) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        // insert into appointments table
        $appointments = DB::table('Appointments')
            ->insert([
                'App_Date'=> $date,
                'App_Time'=> $time,
                'TestID'=> $testID,
                'LabID'=> $labID,
                'Test_Status'=> 'Not Done',
                'Report_Status'=> 'Not Uploaded',
                'PatientID'=> $patientId,
            ]);
        // reroute to show appointments page for that patient
        return redirect()->route('appointment_list',['patientId'=>$patientId]);
//        return redirect()->route('route.name', [$param]);
    }

}
