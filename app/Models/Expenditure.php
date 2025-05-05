<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenditure extends Model
{
    //

    public function scopeRoutine(Builder $query): void
    {
        $query->where('routine', 1);
    }

    public function scopeWages(Builder $query): void
    {
        $query->where('routine', 1)->where('gaji', 1);
    }

    public function scopeNotRoutine(Builder $query): void
    {
        $query->where(fn($q) => $q->whereNull('routine')->orWhere('routine', 0));
    }
    
    /**
     * Get the pengguna that owns the Expenditure
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }

    /**
     * Get the purchase that owns the Expenditure
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the pegawai that owns the Expenditure
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class)->withTrashed();
    }

    /**
     * Get all of the expenditureDetail for the Expenditure
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenditureDetail(): HasMany
    {
        return $this->hasMany(ExpenditureDetail::class);
    }    
}
