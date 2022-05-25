<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $role = Role::where('type', 'Developer')->orWhere('slug', 'admin')->pluck('id');

        $permissions = [
            'slide' => ['Access slide', 'Create slide'],
            'general' => ['Edit General Information'],

            'pos' => ['Access POS', 'Create POS', 'Edit POS', 'Delete POS'],
            'order' => ['Access order', 'Create order', 'Edit order', 'Delete order'],
            'sale' => ['Access sale', 'Edit sale', 'Delete sale'],
            'report' => ['Access Report'],
            'transaction' => ['Access transaction', 'Create transaction', 'Edit transaction', 'Delete transaction'],
            'user-order' => ['Access user order', 'Create user order', 'Edit user order', 'Delete user order'],

            'item' => ['Access item', 'Create item', 'Edit item', 'Delete item', 'Restore item', 'Permenent Delete item'],
            'type' => ['Access type', 'Create type', 'Edit type', 'Delete type'],
            'unit' => ['Access unit', 'Create unit', 'Edit unit', 'Delete unit'],
            'brand' => ['Access brand', 'Create brand', 'Edit brand', 'Delete brand'],
            'discount-type' => ['Access discount-type', 'Create discount-type', 'Edit discount-type', 'Delete discount-type'],
            'discount' => ['Access discount', 'Create discount', 'Edit discount', 'Delete discount'],
            'item-discount' => ['Access item-discount', 'Create item-discount', 'Edit item-discount', 'Delete item-discount'],
            'coupon' => ['Access coupon', 'Create coupon', 'Edit coupon', 'Delete coupon'],
            'bonus-point' => ['Access Bonus Points', 'Create Bonus Points', 'Edit Bonus Points', 'Delete Bonus Points'],
            'faq' => ['Access faq', 'Create faq', 'Edit faq', 'Delete faq'],
            'faq-type' => ['Access faq-type', 'Create faq-type', 'Edit faq-type', 'Delete faq-type'],
            'exchangerate' => ['Access exchangerate', 'Create exchangerate', 'Edit exchangerate', 'Delete exchangerate'],
            'supplier' => ['Access supplier', 'Create supplier', 'Edit supplier', 'Delete supplier'],

            'delifee' => ['Access delifee', 'Create delifee', 'Edit delifee', 'Delete delifee'],
            'delivery' => ['Access delivery', 'Create delivery', 'Edit delivery', 'Delete delivery'],

            'country' => ['Access country', 'Create country', 'Edit country', 'Delete country'],
            'region' => ['Access region', 'Create region', 'Edit region', 'Delete region'],
            'township' => ['Access township', 'Create township', 'Edit township', 'Delete township'],

            'inventory' => ['Access inventory', 'Create inventory', 'Edit inventory', 'Delete inventory', 'Add amount'],
            'stock' => ['Access stock', 'Create stock', 'Edit stock', 'Delete stock'],
            'waste' => ['Access waste', 'Create waste', 'Edit waste', 'Delete waste'],
            'return' => ['Access return', 'Create return', 'Edit return', 'Delete return'],

            'gift' => ['Access gift', 'Create gift', 'Edit gift', 'Delete gift'],
            'user-gift' => ['Access user gift', 'Create user gift', 'Edit user gift', 'Delete user gift'],
            'gift-inventory' => ['Access Gift Inventory', 'Create Gift Inventory', 'Edit Gift Inventory', 'Delete Gift Inventory'],
            'gift-log' => ['Access gift-log', 'Create gift-log', 'Edit gift-log', 'Delete gift-log'],

            'customer' => ['Access customer', 'Create customer', 'Edit customer', 'Delete customer'],
            'user' => ['Access user', 'Create user', 'Edit user', 'Delete user'],
            'role' => ['Access role', 'Create role', 'Edit role', 'Delete role'],

            'notification' => ['Access notification', 'Create notification', 'Edit notification', 'Delete notification', 'Order Notification', 'Order Confirmed', 'Access Gift Noti'],

            


        ];

        foreach ($permissions as $index => $perm) {
            foreach ($perm as $name) {
                $permission = Permission::create([
                    'slug' => Str::slug($name),
                    'name' => $name,
                    'type' => $index
                ]);

                $permission->roles()->attach($role);
                // $role->permissions()->attach($permission->id);
            }
        }
    }
}
