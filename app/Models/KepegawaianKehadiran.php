<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KepegawaianKehadiran extends Model
{
    //
    protected $table = 'kepegawaian_kehadiran';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function kepegawaianPegawai(): BelongsTo
    {
        return $this->belongsTo(KepegawaianPegawai::class);
    }
}
