<?php

namespace App\Models;

use App\Models\Stok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_masuk';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }

    public function stok(): HasOne
    {
        return $this->hasOne(Stok::class, 'id', 'id');
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }
}
