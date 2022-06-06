<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Type;

class GenerateCoupon extends Component
{
    public $maintypes;

    public function __construct()
    {
        $this->maintypes = Type::orderBy('name')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.generate-coupon');
    }
}
