<?php

namespace App\Class;

use App\Models\Jurnal;
use Illuminate\Support\Str;

class JurnalClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function pengeluaranBarang($data, $detail)
    {
        $id = Str::uuid();

        $jurnal = new Jurnal();
        $jurnal->id = $id;
        $jurnal->jenis = 'Pengeluaran Barang';
        $jurnal->tanggal = $data['tanggal'];
        $jurnal->uraian = $data['uraian'];
        $jurnal->kantor = $data['kantor'];
        $jurnal->pengguna_id = auth()->id();
        $jurnal->save();

        $jurnal->jurnalDetail()->delete();
        $jurnal->jurnalDetail()->insert(collect($detail)->map(fn($q, $index) => [
            'uraian' => $q['uraian'],
            'debit' => $q['debit'],
            'kredit' => $q['kredit'],
            'kode_akun_id' => $q['kode_akun_id']
        ])->toArray());
    }
}
