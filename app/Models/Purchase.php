<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;
    
    /**
     * Get all of the purchaseDetail for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseDetail(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    /**
     * Get the supplier that owns the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all of the stokMasuk for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stokMasuk(): HasMany
    {
        return $this->hasMany(IncomingStok::class);
    }

    /**
     * Get the pengguna that owns the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get the expenditure associated with the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function expenditure(): HasOne
    {
        return $this->hasOne(Expenditure::class);
    }


    public function scopeConsignment($query)
    {
        return $query->whereNotNull('konsinyasi');
    }

    public function scopeGeneral($query)
    {
        return $query->whereNull('konsinyasi');
    }
}
