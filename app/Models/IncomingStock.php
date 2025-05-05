<?php

namespace App\Models;

use App\Models\Stok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncomingStok extends Model
{
    use HasFactory;

    /**
     * Get the goods that owns the IncomingStok
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the supplier that owns the IncomingStok
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the pengguna that owns the IncomingStok
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }

    /**
     * Get all of the stok for the IncomingStok
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class);
    }

    /**
     * Get all of the availableStok for the IncomingStok
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableStok(): HasMany
    {
        return $this->hasMany(Stok::class)->available();
    }

    /**
     * Get the purchase that owns the IncomingStok
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
