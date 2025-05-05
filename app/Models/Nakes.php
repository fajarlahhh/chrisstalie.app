<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nakes extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'nakes';

    public function scopeDoctor(Builder $query): void
    {
        $query->where('dokter', 1);
    }

    public function scopeNotDcotor(Builder $query): void
    {
        $query->where('dokter', 0);
    }

    /**
     * Get the pengguna that owns the Nakes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }

    /**
     * Get the pegawai that owns the Nakes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class)->withTrashed();
    }

    /**
     * Get all of the paymentTreatment for the Nakes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentTreatment(): HasMany
    {
        return $this->hasMany(paymentTreatment::class);
    }
}
