<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aset extends Model
{
    protected $table = 'aset';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }
    
    protected static function booted()
    {
        static::addGlobalScope('kantor_apotek', function ($query) {
            $query->where('kantor', 'Apotek');
        });
    }
}
