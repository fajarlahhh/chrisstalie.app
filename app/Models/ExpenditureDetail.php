<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenditureDetail extends Model
{
    //
    /**
     * Get the employee that owns the ExpenditureDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    /**
     * Get the expenditure that owns the ExpenditureDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expenditure(): BelongsTo
    {
        return $this->belongsTo(Expenditure::class);
    }
}
