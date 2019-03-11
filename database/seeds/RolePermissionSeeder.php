<?php


use App\User;
use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Role::truncate();
        //Create some roles
        $su = Role::firstOrcreate(['name' => 'Super User']);
        $admin = Role::firstOrcreate(['name' => 'Owner']);
        $moderator = Role::firstOrcreate(['name' => 'Staff']);

        // Create permissions
        Permission::truncate();
         $permissions = [
            "admin.access" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "admins.manage" => [
                'Super User',
            ],
            "admins.create" => [
                'Super User',
            ],
            "category.create" => [
                'Super User',
                'Owner'
            ],
            "category.manage" => [
                'Super User',
                'Owner'
            ],
            "product.create" => [
                'Super User',
                'Owner'
            ],
            "product.manage" => [
                'Super User',
                'Owner',
            ],
            "product.view" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "customer.create" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "customer.manage" => [
                'Super User',
                'Owner'
            ],
            "customer.view" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "supplier.create" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "supplier.manage" => [
                'Super User',
                'Owner'
            ],
            "supplier.view" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "user.create" => [
                'Super User',
                'Owner'
            ],
            "user.manage" => [
                'Super User',
                'Owner'
            ],
            "sell.create" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "sell.manage" => [
                'Super User',
                'Owner'
            ],
            "return.create" => [
                'Super User',
                'Owner',
                'Staff'
            ],
            "purchase.create" => [
                'Super User',
                'Owner'
            ],
            "purchase.manage" => [
                'Super User',
                'Owner'
            ],
            "transaction.view" => [
                'Super User',
                'Owner'
            ],
            "expense.create" => [
                'Super User',
                'Owner'
            ],
            "expense.manage" => [
                'Super User',
                'Owner'
            ],
            "settings.manage" => [
                'Super User',
                'Owner'
            ],
            "acl.manage" => [
                'Super User',
                'Owner'
            ],
            "acl.set" => [
                'Super User',
                'Owner'
            ],
            "tax.actions" => [
                'Super User',
                'Owner'
            ],
            "branch.create" => [
                'Super User',
                'Owner'
            ],
            "report.view" => [
                'Super User',
                'Owner'
            ],
            "profit.view" => [
                'Super User',
                'Owner'
            ],
            "cash.view" => [
                'Super User',
                'Owner'
            ],
            "profit.graph" => [
                'Super User',
                'Owner'
            ],
        ];

        foreach ($permissions as $permission => $roleName) {
            $permissionObject = Permission::createPermission($permission);
            $rolesIds = Role::whereIn('name', $roleName)->pluck('id')->toArray();
            $permissionObject->roles()->sync($rolesIds);
        }

        // Create initial user
        User::truncate();

        $su = User::firstOrCreate(
            [ 'email' => 'super_user@ism.app' ],
            [
                'first_name' => 'Super',
                'last_name' => 'User',
                'password' => 'super_user'
            ]
        );

        $su->roles()->sync([1]);

    }
}
