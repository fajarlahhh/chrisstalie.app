<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarif extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarif';
    /**
     * Get the pengguna that owns the Tarif
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed()->withTrashed();
    }

    /**
     * Get all of the paymentTreatment for the Tarif
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentTreatment(): HasMany
    {
        return $this->hasMany(PaymentTreatment::class);
    }
}
