<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Upload extends Component
{
    public $fileDiupload, $fileDihapus, $koordinat;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($fileDiupload = [], $fileDihapus = [], $koordinat = 1)
    {
        $this->koordinat = $koordinat;
        $this->fileDihapus = $fileDihapus;
        $this->fileDiupload = $fileDiupload;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.upload');
    }
}
