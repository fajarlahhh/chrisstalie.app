<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPengadaan extends Model
{
    //
    protected $table = 'verifikasi_pengadaan';

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
