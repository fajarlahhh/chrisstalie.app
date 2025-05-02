<?php

namespace App\Livewire\Laporan\Jasapelayanan;

use App\Exports\PembagianjasapelayananExport;
use Livewire\Component;
use App\Models\Treatment;
use App\Models\ActionRate;
use App\Models\Practitioner;
use Livewire\Attributes\Url;
use App\Models\PaymentTreatment;

class Index extends Component
{
    #[Url]
    public $date1, $date2;

    public function mount()
    {
        $this->date1 = $this->date1 ?: date('Y-m-01');
        $this->date2 = $this->date2 ?: date('Y-m-d');
    }

    public function export()
    {
        return (new PembagianjasapelayananExport($this->date1, $this->date2))->download('pembagianjasapelayanan' . $this->date1 . '-' . $this->date2 . '.xls');
    }

    public function getPractitioner()
    {
        return (Practitioner::with('employee')->withTrashed()->whereIn('id', PaymentTreatment::whereHas('payment', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->get()->map(function ($item) {
            return [
                'practitioner_id' => $item->practitioner_id,
                'beautician_id' => $item->beautician_id,
            ];
        })->flatten()->unique()->filter(function ($value) {
            return $value !== null;
        })->toArray())->get()->map(fn($q) => [
            'id' => $q->id,
            'name' => $q->employee ? $q->employee->name : $q->name,
        ]));
    }

    public function getData()
    {
        return (PaymentTreatment::with('actionRate')->whereHas('payment', fn($r) => $r->whereBetween('date', [$this->date1, $this->date2]))->get()->map(function ($q) {
            $price = $q->price * $q->qty;
            $profit = $q->profit * $q->qty;
            $capital = $q->capital * $q->qty;
            $beauticianFee = $q->beautician_fee * $q->qty;
            $dicount = ($q->price * $q->discount / 100) * $q->qty;
            $profitAfterDicount = $profit - $dicount;
            $practitionerPortion = ($profitAfterDicount - $beauticianFee) * $q->practitioner_portion;
            return [
                'id' => $q->action_rate_id,
                'name' => $q->actionRate->name,
                'price' => $price,
                'discount_percent' => $q->discount,
                'qty' => $q->qty,
                'capital' => $capital,
                'discount' => $dicount,
                'profit' => $profitAfterDicount,
                'beautician_fee' => $beauticianFee,
                'practitioner_portion' => $q->practitioner_id ? $practitionerPortion : 0,
                'office_portion' => ($profitAfterDicount - $beauticianFee) * $q->office_portion
                    + ($q->practitioner_id ? 0 : $practitionerPortion)
                    + ($q->beautician_id ? 0 : $beauticianFee),
                'beautician_id' => $q->beautician_id,
                'practitioner_id' => $q->practitioner_id,
            ];
        })->toArray());
    }

    public function render()
    {
        return view('livewire.laporan.jasapelayanan.index', [
            'data' => ($this->getData()),
            'practitioner' => ($this->getPractitioner()),
        ]);
    }
}
