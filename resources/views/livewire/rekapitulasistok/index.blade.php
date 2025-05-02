<div>
    @section('title', 'Rekapitulasi Stok')

    @section('breadcrumb')
        <li class="breadcrumb-item active">Rekapitulasi Stok</li>
    @endsection

    <h1 class="page-header">Rekapitulasi Stok</h1>

    <form wire:submit.prevent="submit" wire:loading.remove>
        <div class="panel panel-inverse" data-sortable-id="table-basic-2">
            <div class="panel-heading">
                Form
            </div>
            <div class="panel-body">
                <div class="mb-3">
                    <label class="form-label">Bulan</label>
                    <select wire:model="month" class="form-control">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02s', $i) }}">
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select wire:model="year" class="form-control">
                        @for ($i = 2025; $i <= date('Y'); $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="panel-footer">
                @unlessrole(config('app.name') . '-guest')
                    <button type="submit" class="btn btn-success">Submit</button>
                @endunlessrole
            </div>
        </div>
        <x-alert />
    </form>
</div>
