<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTreatment extends Model
{
    //

    /**
     * Get the practitioner that owns the PaymentTreatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    /**
     * Get the beautician that owns the PaymentTreatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beautician(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
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
        return $this->belongsTo(ActionRate::class);
    }
}
