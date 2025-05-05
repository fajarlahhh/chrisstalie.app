<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTreatment extends Model
{
    //

    /**
     * Get the nakes that owns the PaymentTreatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nakes(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }

    /**
     * Get the beautician that owns the PaymentTreatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beautician(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }

    /**
     * Get the payment that owns the PaymentTreatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the actionRate that owns the PaymentTreatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actionRate(): BelongsTo
    {
        return $this->belongsTo(Tarif::class);
    }
}
