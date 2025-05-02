<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('includes.head')
</head>
@php
    $bodyClass = !empty($appBoxedLayout) ? 'boxed-layout ' : '';
    $bodyClass .= !empty($paceTop) ? 'pace-top ' : $bodyClass;
    $bodyClass .= !empty($bodyClass) ? $bodyClass . ' ' : $bodyClass;
@endphp

<body class="{{ $bodyClass }}">
    @include('includes.component.page-loader')

    @if (isset($login))
    @else
        @yield('content')
    @endif

    @include('includes.page-js')
</body>

</html>
