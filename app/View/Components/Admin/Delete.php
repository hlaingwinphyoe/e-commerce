<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Delete extends Component
{
    public $id;
    public $url;

    public function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    public function render()
    {
        return view('components.admin.delete');
    }
}
