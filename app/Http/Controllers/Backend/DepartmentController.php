<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartmentController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $this->authorizeDepartmentManage($request);

        $departments = Department::query()
            ->with('company:id,name')
            ->when($request->filled('company_id'), fn ($query) => $query->where('company_id', $request->integer('company_id')))
            ->orderBy('name')
            ->paginate(perPage: $request->integer('per_page', 20));

        return DepartmentResource::collection($departments);
    }

    public function store(StoreDepartmentRequest $request): DepartmentResource
    {
        $department = Department::create($request->validated());

        return DepartmentResource::make($department);
    }

    public function show(Request $request, Department $department): DepartmentResource
    {
        $this->authorizeDepartmentManage($request);

        return DepartmentResource::make($department->load('company:id,name'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): DepartmentResource
    {
        $department->update($request->validated());

        return DepartmentResource::make($department);
    }

    public function destroy(Request $request, Department $department): JsonResponse
    {
        $this->authorizeDepartmentManage($request);

        $department->delete();

        return response()->json(['message' => 'Department removed']);
    }

    protected function authorizeDepartmentManage(Request $request): void
    {
        abort_unless(
            $request->user()?->hasPermission('department.manage'),
            403,
        );
    }
}
