<div>
    @section('title', 'Penjualan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item active">Penjualan</li>
    @endsection

    <h1 class="page-header">Penjualan</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <a href="javascript:;" wire:click="print" x-init="$($el).on('click', function() {
                setTimeout(() => {
                    $('#modal-cetak').modal('show')
                }, 1000)
            })" class="btn btn-warning">
                Cetak</a>
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <input type="date" class="form-control w-auto" wire:model.lazy="tanggal1">&nbsp;s/d&nbsp;
                    <input type="date" class="form-control w-auto" wire:model.lazy="tanggal2">&nbsp;
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive">
            @include('livewire.laporan.penjualan.cetak', ['cetak' => false])
        </div>
    </div>
    <x-alert />
    <x-modal.cetak judul="Laporan Penjualan" />
</div>
