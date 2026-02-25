<div>
    <div wire:ignore.self class="modal fade" id="modal-konfirmasi">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h6>Harap Periksa Kembali Data Yang Anda Inputkan!!!<br>Jika sudah yakin benar, silakan klik tombol <strong class="text-success">Ya</strong> untuk melanjutkan atau <strong class="text-danger">Batal</strong> untuk membatalkan.</h6>
                    <input type="submit" class="btn btn-primary" x-init="$($el).on('click', function() {
                        $('#modal-konfirmasi').modal('hide');
                    })" value="Ya">
                    <input type="button" class="btn btn-danger" x-init="$($el).on('click', function() {
                        $('#modal-konfirmasi').modal('hide');
                    })" value="Batal">
                </div>
            </div>
        </div>
    </div>
</div>
