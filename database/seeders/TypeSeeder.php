<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Baking Soda', 'BB Cream', 'Bode Wash', 'Coffee', 'Cosmetic',
            'Cream', 'Cotton Pad', 'Dryer',
            'Eye Cream', 'Face Mask', 'Facial Wash', 'Hair Care'
        ];

        foreach($permissions as $perm) {
            $type = Type::create([
                'slug' => Str::slug($perm),
                'name' => $perm,
                'user_id' => 1,
                'type' => 'cate'
            ]);
        }
    }
}
