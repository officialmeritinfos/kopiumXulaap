@extends('dashboard.users.layouts.base')
@section('content')

    <div class="page-title-area">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="page-title">
                        <h3>{{$pageName}}</h3>
                    </div>
                    <p class="mt-2" style="font-size:14px;">
                        Manage {{$school->name}}
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
                        <i class="ri-building-2-line"></i> School Branches
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage branches
                    </p>
                </div>
                <a href="{{route('user.school.branches',['slug'=>$school->slug])}}"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage Branches
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-settings-5-line"></i> School Set-up
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Manage sessions, semesters/terms, classes
                    </p>
                </div>
                <a href="{{route('user.school.settings',['slug'=>$school->slug])}}"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage setups
                </a>
            </div>
        </div>



        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-admin-line"></i> School Teachers
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage teachers
                    </p>
                </div>
                <a href="#"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage Teachers
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-user-add-line"></i> School Students
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage students
                    </p>
                </div>
                <a href="#"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage students
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-team-line"></i> School Staff
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage staff(For Payroll)
                    </p>
                </div>
                <a href="#"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage Staff
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-question-answer-line"></i> School Quizzes
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage tests
                    </p>
                </div>
                <a href="#"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage tests
                </a>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                <div class="flex-grow-1 mb-3 mb-md-0">
                    <h5 class="card-title">
                        <i class="ri-file-text-line"></i> School Exams
                    </h5>
                    <p class="card-text" style="word-break: break-word;">
                        Create and manage Exams
                    </p>
                </div>
                <a href="#"
                   class="btn btn-outline-primary rounded-pill btn-sm small-button">
                    Manage exams
                </a>
            </div>
        </div>



    </div>

@endsection
