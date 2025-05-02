<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('includes.head')
    @livewireStyles
</head>
@php
    $appSidebarHide = !empty($appSidebarHide) ? $appSidebarHide : '';
    $appHeaderHide = !empty($appHeaderHide) ? $appHeaderHide : '';
    $appSidebarTwo = !empty($appSidebarTwo) ? $appSidebarTwo : '';
    $appSidebarSearch = !empty($appSidebarSearch) ? $appSidebarSearch : '';
@endphp

<body>
    @include('includes.component.page-loader')

    <div id="app" class="app app-header-fixed app-sidebar-fixed app-with-wide-sidebar ">

        @includeWhen(!$appHeaderHide, 'includes.header')

        @includeWhen(!$appSidebarHide, 'includes.sidebar', [
            'menu' => collect(config('sidebar.menu'))->sortBy('title')->toArray(),
        ])

        <div id="content" class="app-content">
            @yield('content')
            <div id="footer" class="app-footer mx-0 px-0">
                &copy; 2024 {{ config('app.name') }} v2024.11.18.1233 - {{ config('app.organization') }}
            </div>
        </div>

        @include('includes.component.scroll-top-btn')

        @include('includes.component.theme-panel')

    </div>

    @yield('outside-content')

    @include('includes.page-js')
    @livewireScripts
</body>

</html>
