<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class SearchBox extends Component
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }
        
    public function render()
    {
        return view('components.admin.search-box');
    }
}
