<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $role = Role::create([
            'slug' => 'admin',
            'name' => 'Admin',
            'type' => 'Operation',
        ]);

        $role = Role::create([
            'slug' => 'developer',
            'name' => 'Developer',
            'type' => 'Developer'
        ]);

        $role->users()->create([
            'name' => 'futuresky',
            'email' => 'dev.futuresky@gmail.com',
            'phone' => 'futuresky',
            'password' => bcrypt('F$e@2021')
        ]);

        $role = Role::create([
            'slug' => 'operator',
            'name' => 'Operator',
            'type' => 'Operation'
        ]);

        $role = Role::create([
            'slug' => 'customer',
            'name' => 'Customer',
            'type' => 'Customer',
            'priority' => 1
        ]);
    }
}
