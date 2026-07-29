<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id ?? $this->route('employee');

        return [
            // Security & Account
            'employee_id' => ['required', 'string', 'max:50', 'unique:xin_employees,employee_id,' . $employeeId . ',user_id'],
            'card_no' => ['nullable', 'string', 'max:50'],
            'username' => ['nullable', 'string', 'max:50', 'unique:xin_employees,username,' . $employeeId . ',user_id'],
            'email' => ['required', 'email', 'max:150', 'unique:xin_employees,email,' . $employeeId . ',user_id'],
            'password' => ['nullable', 'string', 'min:6'],

            // Personal & Demographics
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'place_of_birth' => ['nullable', 'string', 'max:150'],
            'marital_status' => ['nullable', 'string', 'max:20'],
            'mother_tongue' => ['nullable', 'string', 'max:50'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'pan_number' => ['nullable', 'string', 'max:30'],
            'aadhar_no' => ['nullable', 'string', 'max:30'],

            // Job & Organizational
            'department_id' => ['nullable', 'integer'],
            'sub_department' => ['nullable', 'integer'],
            'designation_id' => ['nullable', 'integer'],
            'manager_id' => ['nullable', 'integer'],
            'company_id' => ['nullable', 'integer'],
            'office_shift_id' => ['nullable', 'integer'],
            'date_of_joining' => ['nullable', 'date'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'probation_status' => ['nullable', 'integer'],
            'probation_end_date' => ['nullable', 'date'],
            'reporting_location' => ['nullable', 'string', 'max:150'],
            'employee_source' => ['nullable', 'string', 'max:50'],

            // Financials & Compensation
            'salary' => ['nullable', 'numeric'],
            'salary_template' => ['nullable', 'integer'],
            'hourly_grade_id' => ['nullable', 'integer'],
            'monthly_grade_id' => ['nullable', 'integer'],
            'earned_leave' => ['nullable', 'integer'],
            'casual_leave' => ['nullable', 'integer'],
            'corporate_bank_account' => ['nullable', 'string', 'max:100'],
            'pf_opted' => ['nullable', 'boolean'],
            'health_ins_opted' => ['nullable', 'boolean'],

            // Contact & Social
            'contact_no' => ['nullable', 'string', 'max:30'],
            'official_contact_no' => ['nullable', 'string', 'max:30'],
            'email_personal' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'skype_id' => ['nullable', 'string', 'max:100'],
            'linkdedin_link' => ['nullable', 'string', 'max:255'],
            'twitter_link' => ['nullable', 'string', 'max:255'],
            'facebook_link' => ['nullable', 'string', 'max:255'],

            // Photo Upload
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'is_active' => ['nullable', 'integer', 'in:0,1,2,3,4,5'],
        ];
    }
}
