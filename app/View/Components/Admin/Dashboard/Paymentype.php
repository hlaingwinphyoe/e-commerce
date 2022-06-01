<?php

namespace App\View\Components\Admin\Dashboard;

use Illuminate\View\Component;

use App\Models\Status;

class Paymentype extends Component
{
    public $paymentypes, $total_in_count, $total_out_count;

    public function __construct()
    {
        $this->paymentypes = Status::isType('payment-type')->orderBy('name')->get();

         $this->total_in_count = $this->paymentypes->reduce(function($total, $paymentype){
            return $total + $paymentype->getTotalTransactionsByMonth('in')['qty'];
        },0);

         $this->total_out_count = $this->paymentypes->reduce(function($total, $paymentype){
            return $total + $paymentype->getTotalTransactionsByMonth('out')['qty'];
        },0);
    }


    public function render()
    {
        return view('components.admin.dashboard.paymentype');
    }
}
