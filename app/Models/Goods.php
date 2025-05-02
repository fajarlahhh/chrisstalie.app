<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goods extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns the Goods
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Get the consignment that owns the Goods
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consignment(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withTrashed();
    }

    /**
     * Get the availableStock that owns the Goods
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableStock(): HasMany
    {
        return $this->hasMany(Stock::class)->whereNull('date_out_stock')->whereNull('sale_id')->whereNull('selling_price');
    }

    /**
     * Get all of the stock for the Goods
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function stockSold(): HasMany
    {
        return $this->hasMany(Stock::class)->sold();
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
     * Get all of the goodsBalance for the Goods
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goodsBalance(): HasMany
    {
        return $this->hasMany(GoodsBalance::class);
    }

    /**
     * Get all of the incomingStock for the Goods
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomingStock(): HasMany
    {
        return $this->hasMany(IncomingStock::class);
    }

    /**
     * Get all of the saleDetail for the Goods
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetail(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
