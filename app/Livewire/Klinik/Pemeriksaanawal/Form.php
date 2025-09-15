<?php

namespace App\Livewire\Klinik\Pemeriksaanawal;

use Livewire\Component;
use App\Models\Registrasi;
use Illuminate\Support\Facades\DB;
use App\Models\PemeriksaanAwal;
use App\Models\PemeriksaanAwalFisik;
use App\Models\PemeriksaanAwalTandaTandaVital;

class Form extends Component
{
    public $data, $keluhan, $pemeriksaanFisik = [], $pemeriksaanTtv = [];

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        $this->pemeriksaanFisik = [
            'Kepala' => 'Normal',
            'Mata' => 'Normal',
            'Telinga' => 'Normal',
            'Hidung' => 'Normal',
            'Mulut' => 'Normal',
            'Leher' => 'Normal',
            'Thorax' => 'Normal',
            'Paru' => 'Normal',
            'Jantung' => 'Normal',
            'Abdomen' => 'Normal',
            'Inguinal' => 'Normal',
            'Extrimitas Atas' => 'Normal',
            'Extrimitas Bawah' => 'Normal',
            'Kulit' => 'Normal',
            'Genital' => 'Normal',
            'Lain-lain' => 'Normal'
        ];
        $this->pemeriksaanTtv = [
            'Berat Badan' => '',
            'Sistole' => '120',
            'Diastole' => '80',
            'Kesadaran' => '01',
        ];
        if ($data->pemeriksaanAwal) {

            $this->fill($this->data->pemeriksaanAwal->toArray());
            foreach ($this->data->pemeriksaanAwal->pemeriksaanAwalFisik as $key => $row) {
                $this->pemeriksaanFisik[$row->key] = $row->value;
            }
            foreach ($this->data->pemeriksaanAwal->pemeriksaanAwalTandaTandaVital as $key => $row) {
                $this->pemeriksaanTtv[$row->key] = $row->value;
            }
        }
    }

    public function submit()
    {
        $this->validate([
            'keluhan' => 'required',
            'pemeriksaanTtv.*' => 'required',
            'pemeriksaanFisik.*' => 'required',
        ]);

        DB::transaction(function () {
            PemeriksaanAwal::where('id', $this->data->id)->delete();

            $pemeriksaanAwal = new PemeriksaanAwal();
            $pemeriksaanAwal->id = $this->data->id;
            $pemeriksaanAwal->keluhan = $this->keluhan;
            $pemeriksaanAwal->pasien_id = $this->data->pasien_id;
            $pemeriksaanAwal->pengguna_id = auth()->id();
            $pemeriksaanAwal->save();

            PemeriksaanAwalFisik::insert(collect($this->pemeriksaanFisik)->map(fn($q, $key) => [
                'pemeriksaan_awal_id' => $pemeriksaanAwal->id,
                'key' => $key,
                'value' => $q
            ])->toArray());

            PemeriksaanAwalTandaTandaVital::insert(collect($this->pemeriksaanTtv)->map(fn($q, $key) => [
                'pemeriksaan_awal_id' => $pemeriksaanAwal->id,
                'key' => $key,
                'value' => $q
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/klinik/pemeriksaanawal');
    }

    public function render()
    {
        return view('livewire.klinik.pemeriksaanawal.form');
    }
}
