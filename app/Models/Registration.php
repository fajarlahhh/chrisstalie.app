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
     * Get the patient that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the practitioner that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    /**
     * Get the user that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
