<?php

namespace App\Class;

use App\Models\JurnalKeuangan;
use Illuminate\Support\Str;

class JurnalkeuanganClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getNomor($tanggal)
    {
        $terakhir = JurnalKeuangan::where('tanggal', 'like', substr($tanggal, 0, 7) . '%')
            ->orderBy('id', 'desc')
            ->first();
        $nomorTerakhir = $terakhir ? (int)substr($terakhir->id, 6, 5) : 0;
        // dd(substr($terakhir->id, 6, 5));
        $nomor = 'JURNAL/' . str_replace('-', '/', substr($tanggal, 0, 7)) . '/' . sprintf('%05d', $nomorTerakhir + 1);
        return Str::uuid();
    }

    public static function insert($jenis, $sub_jenis = null, $tanggal, $uraian, $system = 0, $foreign_key = null, $foreign_id = null, $detail)
    {
        $nomor = self::getNomor($tanggal);

        $jurnalKeuangan = new JurnalKeuangan();
        $jurnalKeuangan->id = str_replace('/', '', substr($nomor, 6, 14));
        $jurnalKeuangan->nomor = $nomor;
        $jurnalKeuangan->jenis = $jenis;
        $jurnalKeuangan->sub_jenis = $sub_jenis;
        $jurnalKeuangan->tanggal = $tanggal;
        $jurnalKeuangan->uraian = $uraian;
        $jurnalKeuangan->system = $system;

        // Pastikan foreign_key adalah string dan tidak null lalu set jika benar
        if ($foreign_key !== null && is_string($foreign_key) && $foreign_key !== '') {
            $jurnalKeuangan->{$foreign_key} = $foreign_id;
        }

        $jurnalKeuangan->pengguna_id = auth()->id();
        $jurnalKeuangan->save();

        $jurnalKeuangan->jurnalKeuanganDetail()->delete();
        $jurnalKeuangan->jurnalKeuanganDetail()->insert(collect($detail)->map(fn($q) => [
            'jurnal_keuangan_id' => $jurnalKeuangan->id,
            'debet' => $q['debet'],
            'kredit' => $q['kredit'],
            'kode_akun_id' => $q['kode_akun_id'],
        ])->toArray());

        return $jurnalKeuangan;
    }
}
