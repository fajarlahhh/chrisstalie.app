<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelayananPendaftaran extends Model
{
    use HasFactory;
    protected $table = 'pelayanan_pendaftaran';
    protected $primaryKey = 'nomor';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $cast = [
        'tanggal' => 'date',
        'baru' => 'boolean'
    ];

    /**
     * Get the pasien that owns the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Get the nakes that owns the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nakes(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }

    /**
     * Get the pengguna that owns the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get the pelayananPemeriksaanAwal associated with the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pelayananPemeriksaanAwal(): HasOne
    {
        return $this->hasOne(PelayananPemeriksaanAwal::class, 'nomor');
    }

    /**
     * Get the pelayananDiagnosa associated with the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pelayananDiagnosa(): HasMany
    {
        return $this->hasMany(PelayananDiagnosa::class);
    }

    /**
     * Get all of the pelayananTindakan for the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pelayananTindakan(): HasMany
    {
        return $this->hasMany(PelayananTindakan::class);
    }

    /**
     * Get the kasir associated with the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function kasir(): HasOne
    {
        return $this->hasOne(Kasir::class);
    }

    /**
     * Get all of the toolMaterial for the PelayananPendaftaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toolMaterial(): HasMany
    {
        return $this->hasMany(ToolMaterial::class);
    }
}
