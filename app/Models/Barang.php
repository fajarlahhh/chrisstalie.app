<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';
    /**
     * Get the pengguna that owns the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }

    /**
     * Get the konsinyasi that owns the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function konsinyasi(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'konsinyator_id')->withTrashed();
    }

    /**
     * Get the availableStok that owns the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableStok(): HasMany
    {
        return $this->hasMany(Stok::class)->whereNull('date_out_stok')->whereNull('sale_id')->whereNull('selling_harga');
    }

    /**
     * Get all of the stok for the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class);
    }

    public function stokSold(): HasMany
    {
        return $this->hasMany(Stok::class)->sold();
    }

    public function scopeAlkes(Builder $query): void
    {
        $query->where('type', 'Alat Kesehatan');
    }

    public function scopeObat(Builder $query): void
    {
        $query->where('type', 'Obat');
    }

    /**
     * Get all of the goodsBalance for the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goodsBalance(): HasMany
    {
        return $this->hasMany(GoodsBalance::class);
    }

    /**
     * Get all of the stokMasuk for the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stokMasuk(): HasMany
    {
        return $this->hasMany(IncomingStok::class);
    }

    /**
     * Get all of the saleDetail for the Barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetail(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
