<?php

namespace App\Class;

use App\Models\Pendaftaran;

class PelayananClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getNomor($tanggal)
    {
        $data = Pendaftaran::orderBy('nomor', 'desc')->where('tanggal', $tanggal)->first();
        if ($data) {
            $nomor = str_replace('-', '', $tanggal) . sprintf('%05s', (int) substr($data->nomor, 8, 5) + 1);
        } else {
            $nomor = str_replace('-', '', $tanggal) . '00001';
        }
        return $nomor;
    }
}
