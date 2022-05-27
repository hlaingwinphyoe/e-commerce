<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FAQTypeSeeder extends Seeder
{
    public function run()
    {
        $type = \App\Models\FaqType::create([
            'slug' => 'faq', 
            'name' => 'FAQ',  
            'user_id' => 1,          
        ]);

        $type = \App\Models\FaqType::create([
            'slug' => 'terms', 
            'name' => 'Terms and Conditions',   
            'user_id' => 1,         
        ]);
    }
}
