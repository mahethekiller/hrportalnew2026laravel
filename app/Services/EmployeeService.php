<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use App\Repositories\EmployeeRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepository $repository
    ) {}

    /**
     * Get paginated employee list.
     */
    public function getEmployees(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    /**
     * Get active employee list for selection dropdowns.
     */
    public function getActiveEmployees()
    {
        return $this->repository->getActiveEmployees();
    }

    /**
     * Get employee details by ID.
     */
    public function getEmployeeById(int $id): ?Employee
    {
        return $this->repository->findById($id);
    }

    /**
     * Create new employee record with linked User account.
     */
    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            // Handle profile picture upload
            if (isset($data['profile_picture']) && $data['profile_picture'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data['profile_picture'];
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile'), $filename);
                $data['profile_picture'] = $filename;
            }

            $empCode = (!empty($data['employee_id']) && $data['employee_id'] !== '0')
                ? $data['employee_id']
                : (!empty($data['username']) ? $data['username'] : 'EMP-' . sprintf('%04d', rand(100, 9999)));

            $targetUserId = $data['user_id'] ?? rand(10000, 99999);
            if (Employee::where('user_id', $targetUserId)->exists()) {
                $maxId = (int) DB::table('xin_employees')->max('user_id');
                $targetUserId = $maxId + 1;
            }

            $data['user_id'] = $targetUserId;
            $data['employee_id'] = $empCode;
            $numericCardNo = preg_replace('/\D/', '', (string)($data['card_no'] ?? $empCode));
            $data['card_no'] = !empty($numericCardNo) ? (int)$numericCardNo : (int)$targetUserId;
            $data['username'] = $data['username'] ?? $empCode;
            $data['office_shift_id'] = $data['office_shift_id'] ?? 1;
            $data['user_role_id'] = $data['user_role_id'] ?? 1;
            $data['department_id'] = $data['department_id'] ?? 1;
            $data['designation_id'] = $data['designation_id'] ?? 1;
            $data['company_id'] = $data['company_id'] ?? 1;
            $data['date_of_birth'] = $data['date_of_birth'] ?? '1990-01-01';
            $data['date_of_joining'] = $data['date_of_joining'] ?? date('Y-m-d');
            $data['gender'] = $data['gender'] ?? 'Male';
            $data['e_status'] = is_numeric($data['e_status'] ?? null) ? (int)$data['e_status'] : 1;
            $data['marital_status'] = $data['marital_status'] ?? 'Single';
            $data['contact_no'] = $data['contact_no'] ?? '0000000000';
            $data['salary'] = is_numeric($data['salary'] ?? ($data['basic_salary'] ?? null)) ? ($data['salary'] ?? $data['basic_salary']) : 0;
            $data['manager_id'] = $data['manager_id'] ?? 0;
            $data['sub_manager_id'] = $data['sub_manager_id'] ?? 0;
            $data['sub_department'] = $data['sub_department'] ?? 0;
            $data['salary_template'] = $data['salary_template'] ?? 0;
            $data['hourly_grade_id'] = $data['hourly_grade_id'] ?? 0;
            $data['monthly_grade_id'] = $data['monthly_grade_id'] ?? 0;
            $data['address'] = $data['address'] ?? '';
            $data['profile_picture'] = $data['profile_picture'] ?? 'default.jpg';
            $data['profile_background'] = $data['profile_background'] ?? 'default.jpg';
            $data['resume'] = $data['resume'] ?? '';
            $data['reporting_location'] = $data['reporting_location'] ?? 1;
            $data['employee_source'] = $data['employee_source'] ?? 'Direct';
            $data['ref_emp_id'] = $data['ref_emp_id'] ?? 0;
            $data['rejoin_emp_id'] = $data['rejoin_emp_id'] ?? 0;
            $data['has_rejoined'] = $data['has_rejoined'] ?? 0;
            $data['created_by'] = $data['created_by'] ?? 1;
            $data['mother_tongue'] = $data['mother_tongue'] ?? 'English';
            $data['age'] = $data['age'] ?? 30;
            $data['place_of_birth'] = $data['place_of_birth'] ?? '';
            $data['blood_group'] = $data['blood_group'] ?? 'O+';
            $data['pan_number'] = $data['pan_number'] ?? '';
            $data['aadhar_no'] = $data['aadhar_no'] ?? '';
            $data['category'] = $data['category'] ?? 1;
            $data['employment_type'] = $data['employment_type'] ?? 'Full Time';
            $data['date_of_leaving'] = $data['date_of_leaving'] ?? '';
            $data['skype_id'] = $data['skype_id'] ?? '';
            $data['facebook_link'] = $data['facebook_link'] ?? '';
            $data['twitter_link'] = $data['twitter_link'] ?? '';
            $data['blogger_link'] = $data['blogger_link'] ?? '';
            $data['linkdedin_link'] = $data['linkdedin_link'] ?? '';
            $data['google_plus_link'] = $data['google_plus_link'] ?? '';
            $data['instagram_link'] = $data['instagram_link'] ?? '';
            $data['pinterest_link'] = $data['pinterest_link'] ?? '';
            $data['youtube_link'] = $data['youtube_link'] ?? '';
            $data['probation_status'] = $data['probation_status'] ?? 0;
            $data['probation_end_date'] = $data['probation_end_date'] ?? '';
            $data['resign_date'] = $data['resign_date'] ?? '';
            $data['confirmation_date'] = $data['confirmation_date'] ?? '';
            $data['last_login_date'] = $data['last_login_date'] ?? '';
            $data['last_logout_date'] = $data['last_logout_date'] ?? '';
            $data['last_login_ip'] = $data['last_login_ip'] ?? '';
            $data['is_logged_in'] = $data['is_logged_in'] ?? 0;
            $data['online_status'] = $data['online_status'] ?? 0;
            $data['email_personal'] = $data['email_personal'] ?? '';
            $data['date_of_birth_doc'] = $data['date_of_birth_doc'] ?? '';
            $data['address_com'] = $data['address_com'] ?? '';
            $data['earned_leave'] = $data['earned_leave'] ?? 0;
            $data['casual_leave'] = $data['casual_leave'] ?? 0;
            $data['other_leaves_taken_days'] = $data['other_leaves_taken_days'] ?? 0;
            $data['paytm_no'] = $data['paytm_no'] ?? '';
            $data['vehicle_no'] = $data['vehicle_no'] ?? '';
            $data['pf_opted'] = $data['pf_opted'] ?? 0;
            $data['health_ins_opted'] = $data['health_ins_opted'] ?? 0;
            $data['official_contact_no'] = $data['official_contact_no'] ?? '';
            $data['vehicle_type'] = $data['vehicle_type'] ?? '';
            $data['city_temp'] = $data['city_temp'] ?? '';
            $data['city'] = $data['city'] ?? '';
            $data['state_temp'] = $data['state_temp'] ?? '';
            $data['state'] = $data['state'] ?? '';
            $data['pin_temp'] = $data['pin_temp'] ?? '';
            $data['pincode'] = $data['pincode'] ?? '';
            $data['corporate_bank_account'] = $data['corporate_bank_account'] ?? '';
            $data['prob_mail_status'] = is_numeric($data['prob_mail_status'] ?? null) ? (int)$data['prob_mail_status'] : 0;
            $data['experience'] = is_numeric($data['experience'] ?? null) ? $data['experience'] : 0;
            $data['kra_doc'] = $data['kra_doc'] ?? '';
            $data['kpi_doc'] = $data['kpi_doc'] ?? '';
            $data['notice_period'] = is_numeric($data['notice_period'] ?? null) ? (int)$data['notice_period'] : 0;
            $data['password'] = Hash::make($data['password'] ?? '12345678');
            $data['is_active'] = $data['is_active'] ?? 1;

            if (\Illuminate\Support\Facades\Schema::hasColumn('xin_employees', 'created_at')) {
                $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            }

            return $this->repository->create($data);
        });
    }

    /**
     * Update employee details.
     */
    public function updateEmployee(Employee $employee, array $data): bool
    {
        return DB::transaction(function () use ($employee, $data) {
            // Handle profile picture upload
            if (isset($data['profile_picture']) && $data['profile_picture'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data['profile_picture'];
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile'), $filename);
                $data['profile_picture'] = $filename;
            }
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
                if ($employee->user) {
                    $employee->user->update(['password' => $data['password']]);
                }
            } else {
                unset($data['password']);
            }

            if ($employee->user) {
                $userUpdates = [];
                if (isset($data['first_name'])) $userUpdates['first_name'] = $data['first_name'];
                if (isset($data['last_name'])) $userUpdates['last_name'] = $data['last_name'];
                if (isset($data['first_name']) || isset($data['last_name'])) {
                    $userUpdates['name'] = trim(($data['first_name'] ?? $employee->first_name) . ' ' . ($data['last_name'] ?? $employee->last_name));
                }
                if (isset($data['email'])) $userUpdates['email'] = $data['email'];
                if (isset($data['username'])) $userUpdates['username'] = $data['username'];
                if (isset($data['contact_no'])) $userUpdates['contact_number'] = $data['contact_no'];
                if (isset($data['gender'])) $userUpdates['gender'] = $data['gender'];
                if (isset($data['department_id'])) $userUpdates['department_id'] = $data['department_id'];
                if (isset($data['designation_id'])) $userUpdates['designation_id'] = $data['designation_id'];
                if (isset($data['company_id'])) $userUpdates['company_id'] = $data['company_id'];
                if (isset($data['is_active'])) $userUpdates['is_active'] = $data['is_active'];

                if (!empty($userUpdates)) {
                    $employee->user->update($userUpdates);
                }
            }

            if (array_key_exists('manager_id', $data)) {
                $data['manager_id'] = (int) $data['manager_id'];
            }
            if (array_key_exists('sub_manager_id', $data)) {
                $data['sub_manager_id'] = (int) $data['sub_manager_id'];
            }

            // Prevent setting NOT NULL database columns to null
            $notNullDefaults = [
                'salary' => 0.00,
                'earned_leave' => 0,
                'casual_leave' => 0,
                'sub_department' => 0,
                'salary_template' => 0,
                'hourly_grade_id' => 0,
                'monthly_grade_id' => 0,
                'pf_opted' => 0,
                'health_ins_opted' => 0,
                'probation_status' => 0,
                'is_active' => 1,
            ];

            foreach ($notNullDefaults as $key => $defaultValue) {
                if (array_key_exists($key, $data) && is_null($data[$key])) {
                    $data[$key] = $employee->$key ?? $defaultValue;
                }
            }

            // Clean up any remaining null values if column was previously set or has a default
            foreach ($data as $key => $val) {
                if ($key !== 'manager_id' && $key !== 'sub_manager_id' && is_null($val) && isset($employee->$key) && !is_null($employee->$key)) {
                    $data[$key] = $employee->$key;
                }
            }

            return $this->repository->update($employee, $data);
        });
    }

    /**
     * Delete employee record.
     */
    public function deleteEmployee(Employee $employee): bool
    {
        return $this->repository->delete($employee);
    }
}
