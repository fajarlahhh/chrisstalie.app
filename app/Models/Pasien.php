<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Pasien extends Model
{
    //
    protected $table = 'pasien';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }

    public function getUmurAttribute()
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }
}
