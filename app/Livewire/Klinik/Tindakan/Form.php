<?php

namespace App\Livewire\Klinik\Tindakan;

use Livewire\Component;
use App\Models\Registrasi;
use App\Models\Tindakan;
use App\Models\TarifTindakan;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $tindakan = [], $dataTindakan = [];
    public $data;

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        if ($data->tindakan->count() > 0) {
            $this->tindakan = $data->tindakan->map(fn($q) => [
                'id' => $q->tarif_tindakan_id,
                'harga' => $q->harga,
                'catatan' => $q->catatan,
                'membutuhkan_inform_consent' => $q->membutuhkan_inform_consent == 1 ? true : false,
                'membutuhkan_sitemarking' => $q->membutuhkan_sitemarking == 1 ? true : false,
            ])->toArray();
        } else {
            $this->tindakan[] = [
                'id' => null,
                'harga' => null,
                'catatan' => null,
                'membutuhkan_inform_consent' => false,
                'membutuhkan_sitemarking' => false,
            ];
        }
        $this->dataTindakan = TarifTindakan::orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama,
            'biaya_total' => $q->biaya_total
        ])->toArray();
    }

    public function tambahTindakan()
    {
        $this->tindakan[] = [
            'id' => null,
            'harga' => null,
            'catatan' => null,
            'membutuhkan_inform_consent' => false,
        ];
    }

    public function submit()
    {
        $this->validate([
            'tindakan' => 'required|array',
            'tindakan.*.id' => 'required|distinct',
        ]);

        DB::transaction(function () {
            Tindakan::where('id', $this->data->id)->delete();
            $tindakan = collect($this->tindakan)->map(fn($q) => [
                'id' => $this->data->id,
                'tarif_tindakan_id' => $q['id'],
                'pasien_id' => $this->data->pasien_id,
                'biaya' => collect($this->dataTindakan)->firstWhere('id', $q['id'])['biaya_total'],
                'catatan' => $q['catatan'],
                'membutuhkan_inform_consent' => $q['membutuhkan_inform_consent'],
                'membutuhkan_sitemarking' => $q['membutuhkan_sitemarking'],
                'pengguna_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();
            Tindakan::insert($tindakan);
            session()->flash('success', 'Berhasil menyimpan data');
        });
    }

    public function hapusTindakan($index)
    {
        unset($this->tindakan[$index]);
        $this->tindakan = array_merge($this->tindakan);
    }

    public function render()
    {
        return view('livewire.klinik.tindakan.form');
    }
}
