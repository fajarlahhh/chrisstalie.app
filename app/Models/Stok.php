<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function stokMasuk(): BelongsTo
    {
        return $this->belongsTo(StokMasuk::class);
    }

    public function stokKeluar(): BelongsTo
    {
        return $this->belongsTo(StokKeluar::class);
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->whereNull('stok_keluar_id');
    }
}
