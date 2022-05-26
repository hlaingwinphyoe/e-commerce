<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class DeleteAll extends Component
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.delete-all');
    }
}
