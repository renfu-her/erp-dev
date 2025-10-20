<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Employee */
class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'department_id' => $this->department_id,
            'department' => DepartmentResource::make($this->whenLoaded('department')),
            'position_id' => $this->position_id,
            'position' => PositionResource::make($this->whenLoaded('position')),
            'user_id' => $this->user_id,
            'employee_no' => $this->employee_no,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'date_of_birth' => optional($this->date_of_birth)?->toDateString(),
            'gender' => $this->gender,
            'national_id' => $this->national_id,
            'personal_data' => $this->personal_data,
            'status' => $this->status,
            'hired_at' => optional($this->hired_at)?->toDateString(),
            'terminated_at' => optional($this->terminated_at)?->toDateString(),
            'blocked_at' => optional($this->blocked_at)?->toDateTimeString(),
            'blocked_reason' => $this->blocked_reason,
            'contacts' => EmployeeContactResource::collection($this->whenLoaded('contacts')),
            'addresses' => EmployeeAddressResource::collection($this->whenLoaded('addresses')),
            'contracts' => EmploymentContractResource::collection($this->whenLoaded('contracts')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
