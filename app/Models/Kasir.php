<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kasir extends Model
{
    //
    protected $table = 'kasir';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get all of the kasirPelayananTindakan for the Kasir
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kasirPelayananTindakan(): HasMany
    {
        return $this->hasMany(KasirPelayananTindakan::class);
    }

    /**
     * Get all of the kasirToolMaterial for the Kasir
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kasirToolMaterial(): HasMany
    {
        return $this->hasMany(KasirToolMaterial::class);
    }

    /**
     * Get the pendaftaran that owns the Kasir
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PelayananPendaftaran::class);
    }

    /**
     * Get the sale associated with the Kasir
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }
}
