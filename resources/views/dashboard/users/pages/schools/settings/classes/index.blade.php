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


    <div class="order-details-area mb-0">
        <div class="container-fluid">
            <div class="row mb-5">
                <div class="col-lg-6 col-sm-6">
                    <form class="search-bar d-flex">
                        <i class="ri-search-line"></i>
                        <input class="form-control search" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="add-new-orders">
                        <a data-bs-toggle="modal" href="#newBranch" class="new-orders">
                            Add New Class
                            <i class="ri-add-circle-line"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="latest-transaction-area">
                <div class="table-responsive" data-simplebar>
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th col="25%">BRANCH</th>
                            <th col="25%">ACTION</th>
                        </tr>
                        </thead>
                        <tbody class="searches">
                        @inject('injected','App\Custom\Regular')
                        @foreach($classes as $class)
                            <tr>
                                <td>
                                    {{$injected->getBranchFromId($class->branch)->name??'N/A'}}

                                    ({{$injected->classesUnderBranch($class->branch)->count()??'N/A'}})
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{route('user.school.classes.details',['slug'=>$school->slug,'branch'=>$class->branch])}}" >
                                                    View Classes
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$classes->links()}}
                </div>
            </div>
        </div>
    </div>


    @push('js')
        <!-- Modal -->
        <div class="modal fade" id="newBranch" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Add academic Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div>
                                <form class="row g-3 submit-property-form product-upload shadow-none" method="post"
                                      action="{{route('user.school.classes.add')}}"
                                      enctype="multipart/form-data" id="addNewBranch">
                                    @csrf

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="inputUsername" class="form-label">
                                                Class Name
                                            </label>
                                            <input type="text" class="form-control" aria-label="Text input with dropdown button"
                                                   name="name">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="inputUsername" class="form-label">
                                                Branch
                                            </label>
                                            <select class="form-control selectize" multiple aria-label="Text input with dropdown button"
                                                    name="branch[]">
                                                @foreach($branches as $branch)
                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" value="1" type="checkbox" id="fgf" name="initialClass">
                                                <label class="form-check-label" for="fgf">
                                                    This will be marked as the initial class
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" value="1" type="checkbox" id="739" name="finalClass">
                                                <label class="form-check-label" for="739">
                                                    Final class towards graduation
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="display: none;">
                                            <label for="inputState" class="form-label">School</label>
                                            <input type="text" class="form-control" id="inputState" name="school"
                                                   value="{{$school->reference}}">
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit"
                                                    class="btn btn-outline-primary rounded submit">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{asset('dashboard/js/requests/users/school_classes.js')}}"></script>
    @endpush
@endsection
