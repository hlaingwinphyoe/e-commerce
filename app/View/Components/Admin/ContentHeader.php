<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class ContentHeader extends Component
{
    public $navs;

    public function __construct($navs)
    {
        $this->navs = $navs;
    }

    public function render()
    {
        return view('components.admin.content-header');
    }
}
