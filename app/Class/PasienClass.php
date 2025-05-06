<?php

namespace App\Class;

use App\Models\Pasien;
use App\Models\Pendaftaran;

class PasienClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getNoRm()
    {
        $last = Pasien::where('created_at', 'like', date('Y-m') . '%')->orderBy('created_at', 'desc')->first();
        $rm = date('y.m.') . ($last ? sprintf('%04s', substr($last->rm, 6, 4) + 1) : '0001');
        return $rm;
    }
}
