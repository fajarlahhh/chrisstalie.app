<div>
    @if (Session::has('danger'))
        <br>
        <div class="alert alert-danger alert-dismissible fade show h-100 mb-0">
            {!! Session::get('danger') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <br>
    @endif
    @if (Session::has('warning'))
        <br>
        <div class="alert alert-warning alert-dismissible fade show h-100 mb-0">
            {!! Session::get('warning') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <br>
    @endif
    @if (Session::has('info'))
        <br>
        <div class="alert alert-info alert-dismissible fade show h-100 mb-0">
            {!! Session::get('info') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <br>
    @endif
    @if (Session::has('success'))
        <br>
        <div class="alert alert-success alert-dismissible fade show h-100 mb-0">
            {!! Session::get('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <br>
    @endif
</div>
