<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_code' => (!empty($this->employee_id) && $this->employee_id !== '0') ? $this->employee_id : 'EMP-' . sprintf('%04d', $this->id),
            'employee_id' => (!empty($this->employee_id) && $this->employee_id !== '0') ? $this->employee_id : 'EMP-' . sprintf('%04d', $this->id),
            'card_no' => $this->card_no,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => trim($this->first_name . ' ' . $this->last_name),
            'username' => $this->username,
            'email' => $this->email,
            'contact_no' => $this->contact_no,
            'official_contact_no' => $this->official_contact_no,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
            'marital_status' => $this->marital_status,
            'mother_tongue' => $this->mother_tongue,
            'blood_group' => $this->blood_group,
            'pan_number' => $this->pan_number,
            'aadhar_no' => $this->aadhar_no,
            'address' => $this->address,
            'profile_picture' => $this->profile_picture ? asset('uploads/profile/' . $this->profile_picture) : null,
            'department' => [
                'id' => $this->department_id,
                'name' => $this->department->department_name ?? $this->department->name ?? null,
            ],
            'designation' => [
                'id' => $this->designation_id,
                'name' => $this->designation->designation_name ?? $this->designation->name ?? null,
            ],
            'company' => [
                'id' => $this->company_id,
                'name' => $this->company->name ?? $this->company->company_name ?? null,
            ],
            'employment_type' => $this->employment_type,
            'date_of_joining' => $this->date_of_joining,
            'salary' => (float) $this->salary,
            'status' => [
                'code' => (int) $this->is_active,
                'label' => \App\Models\Employee::STATUSES[$this->is_active] ?? 'Unknown',
            ],
            'social_links' => [
                'skype_id' => $this->skype_id,
                'linkedin' => $this->linkdedin_link,
                'twitter' => $this->twitter_link,
                'facebook' => $this->facebook_link,
            ],
        ];
    }
}
