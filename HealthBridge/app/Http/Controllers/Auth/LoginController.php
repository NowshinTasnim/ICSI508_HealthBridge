<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function showLoginForm(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        return view('auth.login');
    }

    /**
     * @throws ValidationException
     */


    public function login(Request $request)
    {
        if (Session::get('logged_in')) {
            // Redirect based on the user's type
            switch (Session::get('user_type')) {
                case 0:
                    return redirect()->route('admin.dashboard');
                case 1:
                    return redirect()->route('patient_dashboard');
                case 2:
                    return redirect()->route('Lab.dashboard');
                case 3:
                    return redirect()->route('Insurance.dashboard');
            }
        }
        // Validate the form inputs
        $request->validate([
            'Login_ID' => 'required|string',
            'Log_Password' => 'required|min:8',
        ]);

        // Attempt to find the user by Login_ID
        $user = DB::table('credentials')->where('Login_ID', $request->Login_ID)->first();

        // Check if Login_ID exists
        if (!$user) {
            return back()->withErrors([
                'login_error' => 'The Login ID does not exist.',
            ])->withInput();
        }

        // Verify the password
        if ($user->User_type != 0){
            if (!Hash::check($request->Log_Password, $user->Log_Password)) {
                return back()->withErrors([
                    'login_error' => 'The password is incorrect.',
                ])->withInput();
            }
        }
        else {
            if ($request->Log_Password !== $user->Log_Password) {
                return back()->withErrors([
                    'login_error' => 'The password is incorrect.',
                ])->withInput();
            }
        }

        // Successful login: Set session data
        Session::put('Login_ID', $request->Login_ID);
        Session::put('credential_id', $user->CredentialID);
        Session::put('logged_in', true);
        Session::put('user_type',$user->User_type);

        // Redirect based on user type
        switch ($user->User_type) {
            case 0: // Admin
                return redirect()->route('admin.dashboard');

            case 1: // Patient
                $patientid = DB::table("Patient")
                    ->where("CredentialID", $user->CredentialID)
                    ->pluck('PatientID')
                    ->first();

                if ($patientid) {
                    Session::put('patient_id', $patientid);

                    return redirect()->route('patient_dashboard');
                } else {
                    return back()->with('error', 'Lab details not found for this user.');
                }


            case 2: // Lab
                $labid = DB::table("Lab")
                    ->where("CredentialID", $user->CredentialID)
                    ->pluck('LabID')
                    ->first();

                if ($labid) {
                    Session::put('lab_id', $labid);

                    return redirect()->route('Lab.dashboard');
                } else {
                    return back()->with('error', 'Lab details not found for this user.');
                }
            case 3: // Insurance
                $insuranceId = DB::table("insurance_company")
                    ->where("CredentialID", $user->CredentialID)
                    ->pluck('InsuranceID')
                    ->first();

                if ($insuranceId) {
                    Session::put('insurance_id', $insuranceId);
                    return redirect()->route('Insurance.dashboard');
                } else {
                    return back()->withErrors(['login_error' => 'Insurance details not found for this user.']);
                }

            default: // Unrecognized user type
                return back()->withErrors(['login_error' => 'Invalid user type.']);
        }
    }


    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Session::flush();
        return redirect()->route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
}
