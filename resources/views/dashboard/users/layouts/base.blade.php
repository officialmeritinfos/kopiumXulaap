<!doctype html>
<html lang="zxx">
<head>
    @include('dashboard.users.layouts.header')
</head>

<body class="body-bg-f5f5f5">
<!-- Start Preloader Area -->
<div class="preloader">
    <div class="content">
        <div class="box"></div>
    </div>
</div>
<!-- End Preloader Area -->

@include('dashboard.users.layouts.menu')

@yield('content')

@include('dashboard.users.layouts.footer')
