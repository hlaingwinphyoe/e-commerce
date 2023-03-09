<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = Type::firstOrCreate([
            'slug' => 'meter-bill'
        ], [
            'type' => 'expense',
            'slug' => 'meter-bill',
            'name' => 'Meter Bill',
            'user_id' => 1,
        ]);

        $type = Type::firstOrCreate([
            'slug' => 'internet-bill',
        ], [
            'type' => 'expense',
            'slug' => 'internet-bill',
            'name' => 'Internet Bill',
            'user_id' => 1,
        ]);


        $type = Type::firstOrCreate([
            'slug' => 'petty-cash',
        ], [
            'type' => 'expense',
            'slug' => 'petty-cash',
            'name' => 'Petty Cash',
            'user_id' => 1,
        ]);


        $roles = Role::where('type', 'Developer')->orWhere('slug', 'admin')->pluck('id');

        $permissions = [
            'expense' => ['Access expense', 'Create expense', 'Edit expense', 'Delete expense']
        ];

        foreach ($permissions as $index => $perm) {
            foreach ($perm as $name) {
                $permission = Permission::firstOrCreate([
                   'slug' => Str::slug($name),
                ],[
                    'slug' => Str::slug($name),
                    'name' => $name,
                    'type' => $index
                ]);

                $permission->roles()->sync($roles);
            }
        }

    }
}
