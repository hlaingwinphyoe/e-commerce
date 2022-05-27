<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Status;
use App\Models\Unit;
use App\Models\DiscountType;
use Illuminate\Support\Str;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            // 'order' => ['Pending', 'Order Confirmed', 'Completed', 'Cancel'],
            // 'sku-status' => ['Ordered', 'Order Accepted', 'Pre Ordered', 'Sku Cancelled'],
            'price' => ['Percent', 'Fixed'],
            // 'transaction' => ['In', 'Out'],
            // 'payment-type' => ['Cash', 'Bank Transfer', 'KPay', 'CBPay', 'AYAPay', 'WaveMoney'],
            // 'waste' => ['Adjust', 'Lost', 'Damage', 'Wrong', 'Expired']
        ];

        foreach ($statuses as $index => $status) {
            foreach ($status as $s) {
                $name = $s == 'Percent' ? '%' : $s;
                $name = $s == 'Fixed' ? 'Ks' : $s;
                $status = Status::create([
                    'slug' => Str::slug($s),
                    'name' => $name == 'percent' ? '%' : $name,
                    'type' => $index
                ]);
            }
        }
    }
}
