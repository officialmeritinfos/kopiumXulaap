@extends('dashboard.users.layouts.base')
@section('content')
@push('css')
    <style>
        .small-button {
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 2;
            border-radius: 1rem;
            display: block;
            width: 8rem;
            text-align: center;
        }
    </style>
@endpush
    <div class="page-title-area">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="page-title">
                        <h3>{{$pageName}}</h3>
                    </div>
                    <p class="mt-2" style="font-size:14px;">Hi {{$user->name}}, welcome to {{$siteName}} ⚡️</p>
                </div>
            </div>
        </div>
    </div>

@if($user->completedProfile!=1)
    <div class="container-fluid">
        <div class="col-md-10 col-12 mx-auto">

            <div class="ui-kit-card mb-24">
                <div class="card-title mb-4">
                    <h3>Let’s get you started</h3>
                    <p class="mt-0"  style="font-size:15px;">
                        <i class="bx bxs-badge-check text-success"></i>
                        In order to start using {{$siteName}}, you need to complete the following
                    </p>
                </div>
                @if($user->twoFactor!=1)
                    <div class="card shadow mb-3">
                        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                            <div class="flex-grow-1 mb-3 mb-md-0">
                                <h5 class="card-title">
                                    <i class="bx bx-badge-check"></i> Connect Authenticator
                                </h5>
                                <p class="card-text">
                                    Connect to an Authenticator app to add extra layer of protection to
                                    your account.
                                </p>
                            </div>
                            <button class="btn btn-outline-success rounded-pill btn-sm small-button"
                                    data-bs-target="#twoFactor" data-bs-toggle="modal">
                                Connect
                            </button>
                        </div>
                    </div>
                @endif
                @empty($user->country)
                    <div class="card shadow mb-3">
                        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                            <div class="flex-grow-1 mb-3 mb-md-0">
                                <h5 class="card-title">
                                    <i class="bx bx-badge-check"></i> Complete your basic profile
                                </h5>
                                <p class="card-text">
                                    Add your country, profile picture, bio and others
                                </p>
                            </div>
                            <a href="{{route('user.setupProfile')}}" class="btn btn-outline-primary rounded-pill btn-sm small-button">
                                Profile
                            </a>
                        </div>
                    </div>
                @endempty

            </div>
        </div>
    </div>
@else
    <div class="ui-kit-card mb-24">

    </div>
@endif

@endsection
