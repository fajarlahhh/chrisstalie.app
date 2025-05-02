<?php

namespace App\Exports;

use App\Models\Practitioner;
use App\Models\PaymentTreatment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PembagianjasapelayananExport implements FromView
{
    use Exportable;
    private $date1, $date2, $kategori, $audit;

    public function __construct(string $date1, string $date2)
    {
        $this->date1 = $date1;
        $this->date2 = $date2;
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
            $dicount = ($q->price * $q->discount / 100) * $q->qty;
            $profitAfterDicount = $q->profit - $dicount;
            $practitionerPortion = ($profitAfterDicount - $q->beautician_fee) * $q->practitioner_portion;
            return [
                'id' => $q->action_rate_id,
                'name' => $q->actionRate->name,
                'price' => $q->price,
                'discount_percent' => $q->discount,
                'qty' => $q->qty,
                'capital' => $q->capital,
                'discount' => $dicount,
                'profit' => $profitAfterDicount,
                'beautician_fee' => $q->beautician_fee,
                'practitioner_portion' => $q->practitioner_id ? $practitionerPortion : 0,
                'office_portion' => ($profitAfterDicount - $q->beautician_fee) * $q->office_portion + ($q->practitioner_id ? 0 : $practitionerPortion) + ($q->beautician_id ? 0 : $q->beautician_fee),
                'beautician_id' => $q->beautician_id,
                'practitioner_id' => $q->practitioner_id,
            ];
        })->toArray());
    }

    public function view(): View
    {
        return view('livewire.laporan.jasapelayanan.cetak', [
            'cetak' => true,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'data' => ($this->getData()),
            'practitioner' => ($this->getPractitioner()),
        ]);
    }
}