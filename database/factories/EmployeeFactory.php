<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'employee_id' => fake()->unique()->numberBetween(1000, 9999),
            'card_no' => fake()->numberBetween(100, 999),
            'office_shift_id' => 1,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'date_of_birth' => '1990-01-01',
            'gender' => 'Male',
            'e_status' => 1,
            'user_role_id' => 1,
            'department_id' => 1,
            'sub_department' => '',
            'designation_id' => 1,
            'manager_id' => 1,
            'sub_manager_id' => null,
            'company_id' => null,
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
            'email_personal' => fake()->safeEmail(),
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
        ];
    }
}
