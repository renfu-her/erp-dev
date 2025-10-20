<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\EmploymentContract */
class EmploymentContractResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'contract_type' => $this->contract_type,
            'starts_on' => optional($this->starts_on)?->toDateString(),
            'ends_on' => optional($this->ends_on)?->toDateString(),
            'base_salary' => $this->base_salary,
            'currency' => $this->currency,
            'terms' => $this->terms,
            'is_active' => $this->is_active,
        ];
    }
}
