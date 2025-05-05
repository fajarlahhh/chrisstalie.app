<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    //

    /**
     * Get the goods that owns the SaleDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the sale that owns the SaleDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function konsinyasi(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function nakes(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }
}
