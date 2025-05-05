<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treatment extends Model
{
    use HasFactory;

    /**
     * Get the pengguna that owns the Treatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed()->withTrashed();
    }

    /**
     * Get the actionRate that owns the Treatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actionRate(): BelongsTo
    {
        return $this->belongsTo(Tarif::class);
    }
}
