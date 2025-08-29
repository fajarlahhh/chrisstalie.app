<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    //
    protected $table = 'verifikasi';

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
