<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Pasien;

class Ulangtahun extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ulangtahun', [
            'data' => Pasien::whereRaw('DATE_FORMAT(tanggal_lahir, "%m-%d") = ?', [date('m-d')])->get(),
        ]);
    }
}
