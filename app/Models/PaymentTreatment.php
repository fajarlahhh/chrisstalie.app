<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasirPelayananTindakan extends Model
{
    //

    /**
     * Get the nakes that owns the KasirPelayananTindakan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nakes(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }

    /**
     * Get the beautician that owns the KasirPelayananTindakan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beautician(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }

    /**
     * Get the kasir that owns the KasirPelayananTindakan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(Kasir::class);
    }

    /**
     * Get the tarif that owns the KasirPelayananTindakan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tarif(): BelongsTo
    {
        return $this->belongsTo(Tarif::class);
    }
}
