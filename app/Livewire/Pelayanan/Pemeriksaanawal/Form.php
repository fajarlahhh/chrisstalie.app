<?php

namespace App\Livewire\Pelayanan\Pemeriksaanawal;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\PelayananPemeriksaanAwal;
use App\Models\PelayananPemeriksaanFisik;
use App\Models\PelayananPemeriksaanTtv;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $date, $data, $complaint, $snomedCode, $anamnesis, $pemeriksaanFisik = [], $ttv = [];

    public function mount(Pendaftaran $data)
    {
        $this->date = $this->date ?: date('Y-m-d');
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
        $this->ttv = [
            'Berat Badan' => '',
            'Sistole' => '120',
            'Diastole' => '80',
            'Kesadaran' => '01',
        ];
        if ($data->pelayananPemeriksaanAwal) {
            
            $this->fill($this->data->pelayananPemeriksaanAwal->toArray());
            foreach ($this->data->pelayananPemeriksaanAwal->pemeriksaanFisik as $key => $row) {
                $this->pemeriksaanFisik[$row->key] = $row->value;
            }
            foreach ($this->data->pelayananPemeriksaanAwal->ttv as $key => $row) {
                $this->ttv[$row->key] = $row->value;
            }
        }
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'complaint' => 'required',
        ]);

        DB::transaction(function () {
            PelayananPemeriksaanAwal::where('pendaftaran_id', $this->data->id)->delete();

            $pelayananPemeriksaanAwal = new PelayananPemeriksaanAwal();
            $pelayananPemeriksaanAwal->pendaftaran_id = $this->data->id;
            $pelayananPemeriksaanAwal->date = $this->date;
            $pelayananPemeriksaanAwal->complaint = $this->complaint;
            $pelayananPemeriksaanAwal->snomed_code = $this->snomedCode;
            $pelayananPemeriksaanAwal->pengguna_id = auth()->id();
            $pelayananPemeriksaanAwal->save();

            PelayananPemeriksaanFisik::insert(collect($this->pemeriksaanFisik)->map(fn($q, $key) => [
                'initial_examination_id' => $pelayananPemeriksaanAwal->id,
                'key' => $key,
                'value' => $q
            ])->toArray());

            PelayananPemeriksaanTtv::insert(collect($this->ttv)->map(fn($q, $key) => [
                'initial_examination_id' => $pelayananPemeriksaanAwal->id,
                'key' => $key,
                'value' => $q
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/pemeriksaanawal');
    }


    public function render()
    {
        return view('livewire.pelayanan.pemeriksaanawal.form');
    }
}
