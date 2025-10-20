<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlockEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\ActivityLog;
use App\Models\ApprovalRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $this->authorizeView($request);

        $employees = Employee::query()
            ->with(['company', 'department', 'position'])
            ->when($request->filled('company_id'), fn ($query) => $query->where('company_id', $request->integer('company_id')))
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->integer('department_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->boolean('blocked'), fn ($query) => $query->whereNotNull('blocked_at'))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(perPage: $request->integer('per_page', 25));

        return EmployeeResource::collection($employees);
    }

    public function store(StoreEmployeeRequest $request): EmployeeResource
    {
        $data = $request->validated();
        $contacts = $data['contacts'] ?? [];
        $addresses = $data['addresses'] ?? [];
        unset($data['contacts'], $data['addresses']);

        /** @var \App\Models\Employee $employee */
        $employee = DB::transaction(function () use ($data, $contacts, $addresses) {
            $employee = Employee::create($data);

            if ($contacts) {
                $employee->contacts()->createMany($contacts);
            }

            if ($addresses) {
                $employee->addresses()->createMany($addresses);
            }

            return $employee;
        });

        ActivityLog::create([
            'event' => 'employee.created',
            'user_id' => $request->user()->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'properties' => ['payload' => $data],
        ]);

        return EmployeeResource::make(
            $employee->load(['company', 'department', 'position', 'contacts', 'addresses', 'contracts'])
        );
    }

    public function show(Request $request, Employee $employee): EmployeeResource
    {
        $this->authorizeView($request);

        return EmployeeResource::make(
            $employee->load([
                'company',
                'department',
                'position',
                'contacts',
                'addresses',
                'contracts',
            ])
        );
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): EmployeeResource
    {
        $employee->fill($request->validated());
        $dirty = $employee->getDirty();
        $employee->save();

        if ($dirty) {
            ActivityLog::create([
                'event' => 'employee.updated',
                'user_id' => $request->user()->id,
                'subject_type' => Employee::class,
                'subject_id' => $employee->id,
                'properties' => ['changes' => $dirty],
            ]);
        }

        return EmployeeResource::make(
            $employee->fresh()->loadMissing(['company', 'department', 'position', 'contacts', 'addresses', 'contracts'])
        );
    }

    public function destroy(Request $request, Employee $employee): JsonResponse
    {
        $this->authorizeManage($request);

        $employee->delete();

        ActivityLog::create([
            'event' => 'employee.deleted',
            'user_id' => $request->user()->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
        ]);

        return response()->json(['message' => 'Employee removed']);
    }

    public function block(BlockEmployeeRequest $request, Employee $employee): EmployeeResource
    {
        $data = $request->validated();
        $employee->fill([
            'status' => 'blocked',
            'blocked_at' => now(),
            'blocked_reason' => $data['reason'],
        ])->save();

        ApprovalRequest::create([
            'type' => 'employee.block',
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'requested_by' => $request->user()->id,
            'status' => 'pending',
            'reason' => $data['reason'],
        ]);

        ActivityLog::create([
            'event' => 'employee.blocked',
            'user_id' => $request->user()->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'properties' => ['reason' => $data['reason']],
        ]);

        return EmployeeResource::make(
            $employee->fresh()->loadMissing(['company', 'department', 'position', 'contacts', 'addresses', 'contracts'])
        );
    }

    public function unblock(Request $request, Employee $employee): EmployeeResource
    {
        $this->authorizeBlock($request);

        $employee->fill([
            'status' => 'active',
            'blocked_at' => null,
            'blocked_reason' => null,
        ])->save();

        ActivityLog::create([
            'event' => 'employee.unblocked',
            'user_id' => $request->user()->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
        ]);

        return EmployeeResource::make(
            $employee->fresh()->loadMissing(['company', 'department', 'position', 'contacts', 'addresses', 'contracts'])
        );
    }

    protected function authorizeView(Request $request): void
    {
        abort_unless(
            $request->user()?->hasPermission('employee.view'),
            403,
        );
    }

    protected function authorizeManage(Request $request): void
    {
        abort_unless(
            $request->user()?->hasPermission('employee.manage'),
            403,
        );
    }

    protected function authorizeBlock(Request $request): void
    {
        abort_unless(
            $request->user()?->hasPermission('employee.block'),
            403,
        );
    }
}
