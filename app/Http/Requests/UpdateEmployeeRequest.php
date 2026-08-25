<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');
        $employeeId = is_object($employee) ? ($employee->user_id ?? $employee->id) : $employee;

        return [
            // Security & Account
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('xin_employees', 'employee_id')->ignore($employeeId, 'user_id')],
            'card_no' => ['nullable', 'string', 'max:50'],
            'username' => ['nullable', 'string', 'max:50', Rule::unique('xin_employees', 'username')->ignore($employeeId, 'user_id')],
            'email' => ['required', 'email', 'max:150', Rule::unique('xin_employees', 'email')->ignore($employeeId, 'user_id')],
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
            'department_id' => ['nullable'],
            'sub_department' => ['nullable'],
            'designation_id' => ['nullable'],
            'manager_id' => ['nullable'],
            'sub_manager_id' => ['nullable'],
            'company_id' => ['nullable'],
            'office_shift_id' => ['nullable'],
            'date_of_joining' => ['nullable'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'probation_status' => ['nullable'],
            'probation_end_date' => ['nullable'],
            'reporting_location' => ['nullable', 'string', 'max:150'],
            'employee_source' => ['nullable', 'string', 'max:50'],

            // Financials & Compensation
            'salary' => ['nullable', 'numeric'],
            'salary_template' => ['nullable'],
            'hourly_grade_id' => ['nullable'],
            'monthly_grade_id' => ['nullable'],
            'earned_leave' => ['nullable'],
            'casual_leave' => ['nullable'],
            'corporate_bank_account' => ['nullable', 'string', 'max:100'],
            'pf_opted' => ['nullable'],
            'health_ins_opted' => ['nullable'],

            // Contact & Social
            'contact_no' => ['nullable', 'string', 'max:30'],
            'official_contact_no' => ['nullable', 'string', 'max:30'],
            'email_personal' => ['nullable'],
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
            'is_active' => ['nullable'],
        ];
    }
}
