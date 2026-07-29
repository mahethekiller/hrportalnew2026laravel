<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => 'Super Admin',
            'user_type' => 1,
            'is_active' => 1,
            'company_name' => 'Antigravity',
            'company_logo' => 'logo.png',
            'profile_photo' => 'photo.jpg',
            'profile_background' => 'bg.jpg',
            'contact_number' => '1234567890',
            'gender' => 'Male',
            'address_1' => '123 Main St',
            'address_2' => '',
            'city' => 'Anytown',
            'state' => 'State',
            'zipcode' => '12345',
            'country' => 1,
            'last_login_date' => now()->toDateTimeString(),
            'last_login_ip' => '127.0.0.1',
            'is_logged_in' => 0,
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_id' => rand(1000, 9999),
            'card_no' => rand(100, 999),
            'office_shift_id' => 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => '1990-01-01',
            'gender' => 'Male',
            'e_status' => 1,
            'user_role_id' => 1,
            'department_id' => 1,
            'designation_id' => 1,
            'manager_id' => 1,
            'salary_template' => '',
            'hourly_grade_id' => 1,
            'monthly_grade_id' => 1,
            'date_of_joining' => '2020-01-01',
            'date_of_leaving' => '',
            'marital_status' => 'Single',
            'salary' => '5000',
            'address' => '123 Street',
            'profile_picture' => '',
            'profile_background' => '',
            'resume' => '',
            'skype_id' => '',
            'contact_no' => '1234567890',
            'facebook_link' => '',
            'twitter_link' => '',
            'blogger_link' => '',
            'linkdedin_link' => '',
            'google_plus_link' => '',
            'instagram_link' => '',
            'pinterest_link' => '',
            'youtube_link' => '',
            'reporting_location' => 'HO',
            'employee_source' => 'Recruiter',
            'ref_emp_id' => 0,
            'probation_status' => 'Probation',
            'probation_end_date' => '',
            'resign_date' => '',
            'confirmation_date' => '',
            'rejoin_emp_id' => 0,
            'has_rejoined' => 'no',
            'is_active' => true,
            'last_login_date' => '',
            'last_logout_date' => '',
            'last_login_ip' => '127.0.0.1',
            'is_logged_in' => 0,
            'online_status' => 0,
            'created_by' => 1,
            'email_personal' => $request->email,
            'date_of_birth_doc' => '',
            'mother_tongue' => 'English',
            'age' => '30',
            'place_of_birth' => 'Anytown',
            'blood_group' => 'O+',
            'pan_number' => 'ABCDE1234F',
            'aadhar_no' => '123456789012',
            'category' => 'General',
            'address_com' => '123 Street',
            'earned_leave' => '0',
            'casual_leave' => '0',
            'other_leaves_taken_days' => 0,
            'paytm_no' => '',
            'vehicle_no' => '',
            'pf_opted' => 'no',
            'health_ins_opted' => 'no',
            'official_contact_no' => '',
            'vehicle_type' => '',
            'city_temp' => 'Anytown',
            'city' => 'Anytown',
            'state_temp' => 'State',
            'state' => 'State',
            'pin_temp' => '12345',
            'pincode' => '12345',
            'corporate_bank_account' => 'no',
            'prob_mail_status' => null,
            'employment_type' => 'permanent',
            'experience' => 0.0,
            'notice_period' => 0,
        ]);

        event(new Registered($employee));

        Auth::login($employee);

        return redirect(route('dashboard', absolute: false));
    }
}
