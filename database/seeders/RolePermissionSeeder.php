<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    private $permissions = [
        'manage_dashboard', 'view_dashboard_overview',
        'upload_file', 'delete_file',
        'create_user', 'view_user', 'update_user', 'delete_user',
        'create_currency', 'view_currency', 'update_currency', 'delete_currency',
        'create_unit', 'view_unit', 'update_unit', 'delete_unit',
        'create_tax', 'view_tax', 'update_tax', 'delete_tax',
        'create_warehouse', 'view_warehouse', 'update_warehouse', 'delete_warehouse',
        'create_brand', 'view_brand', 'update_brand', 'delete_brand',
        'create_product_category', 'view_product_category', 'update_product_category', 'delete_product_category',
        'create_product', 'view_product', 'update_product', 'delete_product',
        'create_customer', 'view_customer', 'update_customer', 'delete_customer',
        'create_supplier', 'view_supplier', 'update_supplier', 'delete_supplier',
        'create_sale', 'view_sale', 'update_sale', 'delete_sale',
        'create_purchase', 'view_purchase', 'update_purchase', 'delete_purchase',
        'create_account', 'view_account', 'update_account', 'delete_account',
        'create_account_adjustment', 'view_account_adjustment', 'update_account_adjustment', 'delete_account_adjustment',
        'create_payment', 'view_payment', 'update_payment', 'delete_payment',
        'create_payment_method', 'view_payment_method', 'update_payment_method', 'delete_payment_method',
    ];

    public function run(): void
    {
        // create a general role 'subscriber' for all registered user
        Role::create(['name' => 'subscriber']);

        // get super-admin role. if not found create super-admin role
        $super_admin = Role::where('name', 'super-admin')->first();
        if (empty($super_admin)) {
            $super_admin = Role::create(['name' => 'super-admin']);
        }

        // give all permission to superadmin
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
            $super_admin->givePermissionTo($permission);
        }

    }
}
