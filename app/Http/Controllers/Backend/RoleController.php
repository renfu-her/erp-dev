<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $this->authorizeManage($request);

        $roles = Role::query()
            ->with('permissions')
            ->orderBy('level', 'desc')
            ->orderBy('display_name')
            ->paginate(perPage: $request->integer('per_page', 20));

        return RoleResource::collection($roles);
    }

    public function store(StoreRoleRequest $request): RoleResource
    {
        $data = $request->validated();
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $role = Role::create($data);
        $this->syncPermissions($role, $permissions);

        return RoleResource::make($role->load('permissions'));
    }

    public function show(Request $request, Role $role): RoleResource
    {
        $this->authorizeManage($request);

        return RoleResource::make($role->load('permissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RoleResource
    {
        $data = $request->validated();
        $permissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        $role->update($data);

        if ($permissions !== null) {
            $this->syncPermissions($role, $permissions);
        }

        return RoleResource::make($role->load('permissions'));
    }

    public function destroy(Request $request, Role $role): JsonResponse
    {
        $this->authorizeManage($request);

        if ($role->slug === 'system-owner') {
            return response()->json(['message' => 'System Owner role cannot be deleted'], 403);
        }

        $role->delete();

        return response()->json(['message' => 'Role removed']);
    }

    public function assign(AssignRoleRequest $request, Role $role): JsonResponse
    {
        $data = $request->validated();
        $user = User::findOrFail($data['user_id']);

        $rules = $data['rules'] ?? [];

        if (! empty($data['scope_type'])) {
            $rules['scope'] = [
                'type' => $data['scope_type'],
                'id' => $data['scope_id'] ?? null,
            ];
        }

        $role->users()->syncWithoutDetaching([
            $user->id => ['rules' => $rules],
        ]);

        return response()->json([
            'message' => 'Role assigned',
            'user' => $user->load('roles'),
        ]);
    }

    protected function authorizeManage(Request $request): void
    {
        abort_unless(
            $request->user()?->hasPermission('role.manage'),
            403,
        );
    }

    protected function syncPermissions(Role $role, array $permissions): void
    {
        $permissionIds = Permission::query()
            ->whereIn('slug', $permissions)
            ->pluck('id');

        $role->permissions()->sync($permissionIds);
    }
}
