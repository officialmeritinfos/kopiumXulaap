@extends('dashboard.users.layouts.base')
@section('content')

    <div class="page-title-area">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="page-title">
                        <h3>{{$pageName}}</h3>
                    </div>
                    <p class="mt-2" style="font-size:12px;">
                        Manage your branches
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
                            Add New Branch
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
                            <th col="25%">NAME</th>
                            <th col="25%">REFERENCE</th>
                            <th col="25%">ACTION</th>
                        </tr>
                        </thead>
                        <tbody class="searches">
                        @inject('injected','App\Custom\Regular')
                        @foreach($branches as $branch)
                            <tr>
                                <td>
                                    {{$branch->name}}
                                    @if($branch->status==1)
                                        <span class="badge bg-success">LIVE</span>
                                    @else
                                        <span class="badge bg-danger">DEACTIVATED</span>
                                    @endif
                                </td>
                                <td>
                                    {{$branch->reference}}
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" href="#editBranch" data-bs-toggle="modal"
                                                data-value="{{$branch->id}}" data-name="{{$branch->name}}">
                                                    Edit
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                            </li>
                                            @if($branch->status==1)
                                                <li>
                                                    <a class="dropdown-item " href="{{route('user.school.branch.status.edit',['ref'=>$branch->reference,'type'=>'deactivate'])}}">
                                                        Deactivate
                                                        <i class="fa fa-ban"></i>
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item" href="{{route('user.school.branch.status.edit',['ref'=>$branch->reference,'type'=>'activate'])}}">
                                                        Activate
                                                        <i class="ri-checkbox-multiple-line"></i>
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="{{route('user.school.branch.delete',['slug'=>$school->slug,'ref'=>$branch->reference])}}">
                                                    Delete
                                                    <i class="ri-delete-bin-2-line text-danger"></i>
                                                </a>
                                            </li>


                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$branches->links()}}
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
                        <h5 class="modal-title" id="staticBackdropLabel">Add school Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div>
                                <form class="row g-3 submit-property-form product-upload shadow-none" method="post"
                                      action="{{route('user.school.branches.add')}}"
                                      enctype="multipart/form-data" id="addNewBranch">
                                    @csrf

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="inputUsername" class="form-label">
                                                Name of Branch
                                            </label>
                                            <input type="text" class="form-control" id="storeName"
                                                   aria-label="Text input with dropdown button"
                                                   name="name">
                                        </div>

                                        <div class="col-md-12" style="display: none;">
                                            <label for="inputState" class="form-label">School</label>
                                            <input type="text" class="form-control" id="inputState" name="school"
                                                   value="{{$school->reference}}">
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit"
                                                    class="btn btn-outline-primary rounded submit">
                                                Add Branch
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

        <!-- Modal -->
        <div class="modal fade" id="editBranch" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Add school Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div>
                                <form class="row g-3 submit-property-form product-upload shadow-none" method="post"
                                      action="{{route('user.school.branches.edit')}}"
                                      enctype="multipart/form-data" id="updateBranch">
                                    @csrf

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="inputUsername" class="form-label">
                                                Name of Branch
                                            </label>
                                            <input type="text" class="form-control" id="storeName"
                                                   aria-label="Text input with dropdown button"
                                                   name="name">
                                        </div>

                                        <div class="col-md-12" style="display: none;">
                                            <label for="inputState" class="form-label">School</label>
                                            <input type="text" class="form-control" id="inputState" name="school"
                                                   value="{{$school->reference}}">
                                        </div>

                                        <div class="col-md-12" style="display: none;">
                                            <label for="inputState" class="form-label">School</label>
                                            <input type="text" class="form-control" id="inputState" name="branch">
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit"
                                                    class="btn btn-outline-primary rounded submit">
                                                Update Branch
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

        <script src="{{asset('dashboard/js/requests/users/school_branches.js')}}"></script>
    @endpush
@endsection
