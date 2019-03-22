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
        $agent = Role::firstOrcreate(['name' => 'Agent']);

        // Create permissions
        Permission::truncate();
         $permissions = [
            "admin.access" => [
                'Super User',
                'Owner',
                'Staff',
                'Agent'
            ],
            "admins.manage" => [
                'Super User',
            ],
            "admins.create" => [
                'Super User',
            ],
            "category.create" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "category.manage" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "product.create" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "product.manage" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "product.view" => [
                'Super User',
                'Owner',
                'Staff',
                'Agent'
            ],
            "customer.create" => [
                'Super User',
                'Owner',
                'Staff',
                'Agent'
            ],
            "customer.manage" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "customer.view" => [
                'Super User',
                'Owner',
                'Staff',
                'Agent'
            ],
            "supplier.create" => [
                'Super User',
                'Owner',
                'Staff',
                'Agent'
            ],
            "supplier.manage" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "supplier.view" => [
                'Super User',
                'Owner',
                'Staff',
                'Agent'
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
                'Staff',
                'Agent'
            ],
            "sell.manage" => [
                'Super User',
                'Owner'
            ],
            "return.create" => [
                'Super User',
                'Owner',
                'Staff',
                'Agent'
            ],
            "purchase.create" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "purchase.manage" => [
                'Super User',
                'Owner',
                'Agent'
            ],
            "transaction.view" => [
                'Super User',
                'Owner',
                'Agent'
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
                'Owner',
                'Agent'
            ],
            "report.view" => [
                'Super User',
                'Owner',
                'Agent'
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
