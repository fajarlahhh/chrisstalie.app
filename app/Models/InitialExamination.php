<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialExamination extends Model
{
    //
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get all of the physicalExamination for the InitialExamination
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function physicalExamination(): HasMany
    {
        return $this->hasMany(PhysicalExamination::class);
    }

    /**
     * Get all of the ttv for the InitialExamination
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ttv(): HasMany
    {
        return $this->hasMany(Ttv::class);
    }

    /**
     * Get the registration that owns the InitialExamination
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
