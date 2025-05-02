<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentToolMaterial extends Model
{
    //
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class);
    }
}
