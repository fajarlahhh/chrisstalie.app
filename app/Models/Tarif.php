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
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed()->withTrashed();
    }

    /**
     * Get all of the kasirPelayananTindakan for the Tarif
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kasirPelayananTindakan(): HasMany
    {
        return $this->hasMany(KasirPelayananTindakan::class);
    }
}
