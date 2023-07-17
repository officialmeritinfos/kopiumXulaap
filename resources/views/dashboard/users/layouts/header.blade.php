<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="{{$siteName}}"/>
<meta name="description" content="{{$web->description}}"/>
<meta name="keywords" content="{{$web->keywords}}">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<!-- Bootstrap Min CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/bootstrap.min.css')}}">
<!-- Owl Theme Default Min CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/owl.theme.default.min.css')}}">
<!-- Owl Carousel Min CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/owl.carousel.min.css')}}">
<!-- Animate Min CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/animate.min.css')}}">
<!-- Remixicon CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/remixicon.css')}}">
<!-- boxicons CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/boxicons.min.css')}}">
<!-- MetisMenu Min CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/metismenu.min.css')}}">
<!-- Simplebar Min CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/simplebar.min.css')}}">
<!-- Style CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/style.css')}}">
<link rel="stylesheet" href="{{asset('dashboard/vendors/summernote/summernote-bs5.css')}}">
<link rel="stylesheet" href="{{asset('dashboard/css/boxed-check.min.css')}}">
<!-- Dark Mode CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/dark-mode.css')}}">
<!-- Responsive CSS -->
<link rel="stylesheet" href="{{asset('dashboard/css/responsive.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<!-- Selectize -->
<link rel="stylesheet" href="{{asset('dashboard/css/selectize.bootstrap5.css')}}">

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{asset($web->favicon)}}">
<!-- Title -->
<title>{{$pageName}} - {{$siteName}}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
      integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>

    .modal-content {
        border-radius:1rem;
    }
    .modal-title {
        text-align: center;
    }
</style>

<style>
    .post {
        width: 220px;
        height: 80px;
    }
    .post .avatar {
        float: left;
        width: 52px;
        height: 52px;
        background-color: #ccc;
        border-radius: 25%;
        margin: 8px;
        background-image: linear-gradient(90deg, #ddd 0px, #e8e8e8 40px, #ddd 80px);
        background-size: 600px;
        animation: shine-avatar 1.6s infinite linear;
    }
    .post .line {
        float: left;
        width: 140px;
        height: 16px;
        margin-top: 12px;
        border-radius: 7px;
        background-image: linear-gradient(90deg, #ddd 0px, #e8e8e8 40px, #ddd 80px);
        background-size: 600px;
        animation: shine-lines 1.6s infinite linear;
    }
    .post .avatar + .line {
        margin-top: 11px;
        width: 100px;
    }
    .post .line ~ .line {
        background-color: #ddd;
    }


    @keyframes shine-lines {
        0% {
            background-position: -100px; }
        40%, 100% {
            background-position: 140px; } }

    @keyframes shine-avatar {
        0% {
            background-position: -32px; }
        40%, 100% {
            background-position: 208px; } }

</style>
@stack('css')
