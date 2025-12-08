@extends('layouts.default')
@php
    $currentUrl = Request::path() != '/' ? '/' . Request::path() : '/';
@endphp
@section('content')
    <x-ulangtahun name="John Doe" />
    <!-- begin breadcrumb -->
    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        @yield('breadcrumb')
    </ol>
    {{ $slot }}
@endsection
