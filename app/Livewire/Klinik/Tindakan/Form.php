<?php

namespace App\Livewire\Klinik\Tindakan;

use App\Models\Nakes;
use Livewire\Component;
use App\Models\Tindakan;
use App\Models\Registrasi;
use App\Models\TarifTindakan;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $tindakan = [], $dataTindakan = [], $dataNakes = [];
    public $data;

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        if ($data->tindakan->count() > 0) {
            $this->tindakan = $data->tindakan->map(fn($q) => [
                'id' => $q->tarif_tindakan_id,
                'qty' => $q->qty,
                'harga' => $q->harga,
                'catatan' => $q->catatan,
                'membutuhkan_inform_consent' => $q->membutuhkan_inform_consent == 1 ? true : false,
                'membutuhkan_sitemarking' => $q->membutuhkan_sitemarking == 1 ? true : false,
                'dokter_id' => $q->dokter_id,
                'perawat_id' => $q->perawat_id,
                'biaya_jasa_dokter' => $q->biaya_jasa_dokter > 0 ? 1 : ($q->dokter_id ? 1 : 0),
                'biaya_jasa_perawat' => $q->biaya_jasa_perawat > 0 ? 1 : ($q->perawat_id ? 1 : 0),
                'biaya' => $q->biaya,
            ])->toArray();
        } else {
            $this->tindakan[] = [
                'id' => null,
                'qty' => 1,
                'harga' => null,
                'catatan' => null,
                'membutuhkan_inform_consent' => false,
                'membutuhkan_sitemarking' => false,
                'dokter_id' => auth()->user()->dokter?->id,
                'perawat_id' => null,
                'biaya_jasa_dokter' => 0,
                'biaya_jasa_perawat' => 0,
                'biaya' => 0,
            ];
        }
        $this->dataNakes = Nakes::orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama,
            'dokter' => $q->dokter,
        ])->toArray();
        $this->dataTindakan = TarifTindakan::orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama,
            'biaya_jasa_dokter' => $q->biaya_jasa_dokter,
            'biaya_jasa_perawat' => $q->biaya_jasa_perawat,
            'tarif' => $q->tarif
        ])->toArray();
    }

    public function submit()
    {
        $this->validate([
            'tindakan' => 'required|array',
            'tindakan.*.id' => 'required|distinct',
            'tindakan.*.qty' => 'required|min:1',
            'tindakan.*.dokter_id' => function ($attribute, $value, $fail) {
                $index = explode('.', $attribute)[1];
                if (
                    isset($this->tindakan[$index]['biaya_jasa_dokter']) &&
                    $this->tindakan[$index]['biaya_jasa_dokter'] > 0 &&
                    (empty($value) || $value < 1)
                ) {
                    $fail('The dokter id field is required.');
                }
            },
            'tindakan.*.perawat_id' => function ($attribute, $value, $fail) {
                $index = explode('.', $attribute)[1];
                if (
                    isset($this->tindakan[$index]['biaya_jasa_perawat']) &&
                    $this->tindakan[$index]['biaya_jasa_perawat'] > 0 &&
                    (empty($value) || $value < 1)
                ) {
                    $fail('The perawat id field is required.');
                }
            },
        ]);

        DB::transaction(function () {
            foreach ($this->tindakan as $tindakan) {
                Tindakan::where('id', $this->data->id)->where('tarif_tindakan_id', $tindakan['id'])->update([
                    'dokter_id' => $tindakan['dokter_id'],
                    'perawat_id' => $tindakan['perawat_id'],
                    'tanggal_penugasan' => now(),
                ]);
            }
            session()->flash('success', 'Berhasil menyimpan data');
        });
    }

    public function render()
    {
        return view('livewire.klinik.tindakan.form');
    }
}
