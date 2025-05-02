<?php

namespace App\Livewire\Informasiharga;

use App\Models\ActionRate;
use App\Models\Goods;
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
        $goods = Goods::orderBy('name')->with('availableStock')->get()->map(fn($q) => [
            'id' => 'goods' . Str::random(10),
            'goods_id' => $q->id,
            'medical_treatment_id' => null,
            'name' => ucFirst($q->name),
            'unit' => $q->unit,
            'type' =>  $q->type,
            'price' => $q->price,
            'stock' => $q->availableStock->count(),
            'kode' => $q->kfa,
        ])->toArray();

        $medicalTreatment = ActionRate::orderBy('name')->get()->map(fn($q) => [
            'id' => 'medicaltreatment' . Str::random(10),
            'goods_id' => $q->id,
            'medical_treatment_id' => $q->id,
            'name' => ucFirst($q->name),
            'unit' => null,
            'type' =>  "Tindakan Medis",
            'price' => $q->price,
            'stock' => null,
            'kode' => $q->icd_9_cm_code,
        ])->toArray();

        $this->informationData = collect(array_merge($goods, $medicalTreatment))->sortBy('name');
    }

    public function render()
    {
        return view('livewire.informasiharga.index');
    }
}
