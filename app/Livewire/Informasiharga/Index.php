<?php

namespace App\Livewire\Informasiharga;

use App\Models\Tarif;
use App\Models\Barang;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Treatment;

class Index extends Component
{
    public $informationData = [], $information_id, $information;

    public function updatedInformationId($value)
    {
        $this->information = collect($this->informationData)->where('id', $value)->first();
    }

    public function mount()
    {
        $goods = Barang::orderBy('nama')->with('availableStok')->get()->map(fn($q) => [
            'id' => 'goods' . Str::random(10),
            'goods_id' => $q->id,
            'medical_treatment_id' => null,
            'nama' => ucFirst($q->nama),
            'satuan' => $q->satuan,
            'type' =>  $q->type,
            'harga' => $q->harga,
            'stok' => $q->availableStok->count(),
            'kode' => $q->kfa,
        ])->toArray();

        $medicalTreatment = Tarif::orderBy('nama')->get()->map(fn($q) => [
            'id' => 'medicaltreatment' . Str::random(10),
            'goods_id' => $q->id,
            'medical_treatment_id' => $q->id,
            'nama' => ucFirst($q->nama),
            'satuan' => null,
            'type' =>  "Tindakan Medis",
            'harga' => $q->harga,
            'stok' => null,
            'kode' => $q->icd_9_cm,
        ])->toArray();

        $this->informationData = collect(array_merge($goods, $medicalTreatment))->sortBy('nama');
    }

    public function render()
    {
        return view('livewire.informasiharga.index');
    }
}
