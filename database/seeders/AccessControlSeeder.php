<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'backend.access',
            'company.manage',
            'department.manage',
            'position.manage',
            'employee.view',
            'employee.manage',
            'employee.block',
            'attendance.manage',
            'leave.manage',
            'payroll.view',
            'payroll.manage',
            'frontend.portal.access',
            'frontend.leave.submit',
            'role.manage',
            'approval.manage',
        ];

        $permissionModels = collect($permissions)->mapWithKeys(function (string $slug) {
            return [
                $slug => Permission::firstOrCreate(['slug' => $slug]),
            ];
        });

        $roles = [
            'system-owner' => [
                'display_name' => 'System Owner',
                'level' => 100,
                'permissions' => $permissions,
            ],
            'hr-admin' => [
                'display_name' => 'HR Admin',
                'level' => 80,
                'permissions' => [
                    'backend.access',
                    'company.manage',
                    'department.manage',
                    'position.manage',
                    'employee.view',
                    'employee.manage',
                    'employee.block',
                    'attendance.manage',
                    'leave.manage',
                    'payroll.view',
                    'payroll.manage',
                    'approval.manage',
                ],
            ],
            'hr-officer' => [
                'display_name' => 'HR Officer',
                'level' => 60,
                'permissions' => [
                    'backend.access',
                    'employee.view',
                    'attendance.manage',
                    'leave.manage',
                    'approval.manage',
                ],
            ],
            'company-manager' => [
                'display_name' => 'Company Manager',
                'level' => 40,
                'permissions' => [
                    'frontend.portal.access',
                    'frontend.leave.submit',
                    'employee.view',
                ],
            ],
            'employee' => [
                'display_name' => 'Employee',
                'level' => 10,
                'permissions' => [
                    'frontend.portal.access',
                    'frontend.leave.submit',
                ],
            ],
        ];

        foreach ($roles as $slug => $data) {
            /** @var Role $role */
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'display_name' => $data['display_name'],
                    'level' => $data['level'],
                ]
            );

            $role->permissions()->sync(
                $permissionModels->only($data['permissions'])->pluck('id')
            );
        }
    }
}
