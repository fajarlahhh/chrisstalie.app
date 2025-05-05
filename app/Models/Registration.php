<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;

    /**
     * Get the pasien that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Get the nakes that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nakes(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }

    /**
     * Get the pengguna that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get the initialExamination associated with the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function initialExamination(): HasOne
    {
        return $this->hasOne(InitialExamination::class);
    }

    /**
     * Get the diagnosis associated with the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function diagnosis(): HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }

    /**
     * Get all of the treatment for the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function treatment(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /**
     * Get the payment associated with the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get all of the toolMaterial for the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toolMaterial(): HasMany
    {
        return $this->hasMany(ToolMaterial::class);
    }
}
