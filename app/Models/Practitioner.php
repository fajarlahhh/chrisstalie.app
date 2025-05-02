<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Practitioner extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeDoctor(Builder $query): void
    {
        $query->where('doctor', 1);
    }

    public function scopeNotDcotor(Builder $query): void
    {
        $query->where('doctor', 0);
    }

    /**
     * Get the user that owns the Practitioner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Get the employee that owns the Practitioner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    /**
     * Get all of the paymentTreatment for the Practitioner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentTreatment(): HasMany
    {
        return $this->hasMany(paymentTreatment::class);
    }
}
