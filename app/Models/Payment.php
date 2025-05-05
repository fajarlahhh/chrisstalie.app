<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    //

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get all of the paymentTreatment for the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentTreatment(): HasMany
    {
        return $this->hasMany(PaymentTreatment::class);
    }

    /**
     * Get all of the paymentToolMaterial for the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentToolMaterial(): HasMany
    {
        return $this->hasMany(PaymentToolMaterial::class);
    }

    /**
     * Get the registration that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the sale associated with the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }
}
