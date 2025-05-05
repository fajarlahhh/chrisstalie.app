<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasirToolMaterial extends Model
{
    //
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
