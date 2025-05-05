<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseDetail extends Model
{
    use HasFactory;

    /**
     * Get the goods that owns the PurchaseDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
