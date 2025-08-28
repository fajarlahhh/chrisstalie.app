<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeAkun extends Model
{
    //
    protected $table = 'kode_akun';

    public function parent()
    {
        return $this->belongsTo(KodeAkun::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(KodeAkun::class, 'parent_id');
    }
    
    protected static function booted()
    {
        static::addGlobalScope('kantor_apotek', function ($query) {
            $query->where('kantor', 'Apotek');
        });
    }
}
