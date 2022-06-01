<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'order' => ['Pending', 'Order Confirmed', 'Completed', 'Cancel'],
            'sku-status' => ['Ordered', 'Order Accepted', 'Pre Ordered', 'Sku Cancelled'],
            'transaction' => ['In', 'Out'],
            'payment-type' => ['Cash', 'Bank Transfer', 'KPay', 'CBPay', 'AYAPay', 'WaveMoney'],
            'waste' => ['Adjust', 'Lost', 'Damage', 'Wrong', 'Expired']
        ];

        foreach ($statuses as $index => $status) {
            foreach ($status as $s) {
                $status = Status::create([
                    'slug' => Str::slug($s),
                    'name' => $s,
                    'type' => $index
                ]);
            }
        }

        $status = Status::create([
            'slug' => 'fixed',
            'name' => 'Ks',
            'type' => 'price',
        ]);

        $status = Status::create([
            'slug' => 'percent',
            'name' => '%',
            'type' => 'price',
        ]);

        $status = Status::create([
            'slug' => 'phone',
            'name' => ' ',
            'type' => 'phone'
        ]);

        $status = Status::create([
            'slug' => 'address',
            'name' => ' ',
            'type' => 'address'
        ]);

        $status = Status::create([
            'slug' => 'general',
            'name' => ' ',
            'type' => 'general'
        ]);

        $status  = Status::create([
            'slug' => 'delivery',
            'name' => ' ',
            'type' => 'delivery'
        ]);

        $status  = Status::create([
            'slug' => 'reset',
            'name' => 'Reset',
            'type' => 'reset'
        ]);
    }
}
