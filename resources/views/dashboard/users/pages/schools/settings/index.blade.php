@extends('dashboard.users.layouts.base')
@section('content')

    <div class="page-title-area">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="page-title">
                        <h3>{{$pageName}}</h3>
                    </div>
                    <p class="mt-2" style="font-size:10px;">
                        {{$pageName}}
                    </p>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid">

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-calendar-2-line"></i> Academic Sessions
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage sessions
                    </p>
                </div>
                <a href="{{route('user.school.sessions',['slug'=>$school->slug])}}"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-calendar-event-line"></i> Academic Semesters/Terms
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                       Manage Semester/Term
                    </p>
                </div>
                <a href="{{route('user.school.terms',['slug'=>$school->slug])}}"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage
                </a>
            </div>
        </div>



        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-building-2-line"></i> Classes
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage classes
                    </p>
                </div>
                <a href="{{route('user.school.classes',['slug'=>$school->slug])}}"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-file-4-line"></i> Subjects/Courses
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage subjects
                    </p>
                </div>
                <a href="#"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-folder-add-line"></i> Subject Combination
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Add subjects to classes
                    </p>
                </div>
                <a href="#"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage
                </a>
            </div>
        </div>


    </div>

@endsection
