@extends('dashboard.users.layouts.base')
@section('content')
    <div class="page-title-area">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="page-title">
                        <h3>{{$pageName}}</h3>
                    </div>
                    <p class="mt-2" style="font-size:14px;"></p>
                </div>
            </div>
        </div>
    </div>


    @if($schools->count()<1)

        <div class="product-area">
            <div class="container-fluid">

                <div class="row" style="margin-top:10rem;">
                    <div class="col-xl-6 col-sm-6 mx-auto">
                        <div class=" shadow-none mb-3">
                            <div class="card-body text-center">
                                <button class="btn btn-primary btn-lg small-button" data-bs-toggle="modal"
                                        data-bs-target="#createStore">
                                    Add School
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="container-fluid">

            <div class="row" style="margin-top:1rem;">
                <div class="col-xl-6 col-sm-6 mx-auto">
                    <div class=" shadow-none mb-3">
                        <div class="card-body text-center">
                            <button class="btn btn-primary btn-lg small-button" data-bs-toggle="modal"
                                    data-bs-target="#createStore">
                                Add School
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-4 g-4">



                @foreach($schools as $school)

                    <div class="col-xl-4 col-sm-6" >
                        <div class="single-products ">
                            <div class="products-img" style="max-height:6rem;">
                                <img src="{{empty($school->logo)?asset('dashboard/images/storeBackground.jpeg'):$school->logo}}"
                                     alt="Images">

                                <div class="add-to-cart">
                                    <a href="{{route('user.school.detail',['slug'=>$school->slug])}}" class="default-btn">
                                        Manage School
                                        <i class="ri-arrow-right-line"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="products-content d-flex">
                                <div class="product-title flex-grow-1">
                                    <h3>{{$school->name}} </h3>
                                    <span class="price">
                                        <p>
                                            {!! \Illuminate\Support\Str::words($school->about,5) !!}
                                        </p>
                                    </span>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <span class="reviews {{($school->status==1)?'text-success':'text-danger'}} badge bg-white">
                                        {{($school->status==1)?'Live':'Not live'}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>

        <div class="ui-kit-card grid" style="margin-top: 20rem;">
            {{$schools->links()}}
        </div>
    @endif


    @push('js')
        <!-- Modal -->
        <div class="modal fade" id="createStore" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Create a School</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div>
                                <form class="row g-3 submit-property-form product-upload shadow-none" method="post"
                                      action="{{route('user.school.create')}}"
                                      enctype="multipart/form-data" id="addNewSchool">
                                    @csrf

                                    <div class="col-md-6 mx-auto" style="margin-bottom: 5rem;">
                                        <div class="form-group">
                                            <div class="file-upload">
                                                <input type="file" name="schoolIcon" id="file" class="inputfile">
                                                <label class="upload" for="file">
                                                    <i class="ri-image-2-fill"></i>
                                                    Upload School Icon
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="inputUsername" class="form-label">
                                                What is your School Name?
                                            </label>
                                            <input type="text" class="form-control" id="storeName"
                                                   aria-label="Text input with dropdown button"
                                                   name="name">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="inputUsername" class="form-label">
                                                Enter Short tagline for your school
                                            </label>
                                            <input type="text" class="form-control" aria-label="Text input with dropdown button"
                                                   name="tagline" placeholder="Tagline e.g ({{$siteName}} - go selling)">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="inputUsername" class="form-label">
                                                School catchy name
                                            </label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text bg-primary text-white"
                                                      id="basic-addon3">{{$web->storeLink}}</span>
                                                <input type="text" class="form-control" id="slug"
                                                       aria-describedby="basic-addon3" name="slug">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">
                                                Tell us about your school.
                                            </label>
                                            <textarea class="form-control summernote" id="inputAddress" name="about" rows="5"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputUsername" class="form-label">
                                                Which Country is your school in?
                                            </label>
                                            <select class="form-control selectize" id="inputPassword4" name="country">
                                                <option value="">Select an Option</option>
                                                @foreach($countries as $country)
                                                    @if($country->iso3 ==$user->countryCode)
                                                        <option value="{{$country->iso3}}" selected>{{$country->name}}</option>
                                                    @endif
                                                    <option value="{{$country->iso3}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="inputUsername" class="form-label">
                                                What currency would you accept?
                                            </label>
                                            <select class="form-control selectize" id="inputPassword4" name="currency">
                                                <option value="">Select an Option</option>
                                                @foreach($fiats as $fiat)
                                                    @if($fiat->code ==$user->currency)
                                                        <option value="{{$fiat->code}}" selected>{{$fiat->name}}</option>
                                                    @endif
                                                    <option value="{{$fiat->code}}">{{$fiat->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="inputState" class="form-label">Email</label>
                                            <input type="text" class="form-control" id="inputState" name="email"
                                                   value="{{$user->email}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="inputState" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="inputState" name="phone"
                                                   value="{{$user->phone}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="inputState" class="form-label">State/Region</label>
                                            <input type="text" class="form-control" id="inputState" name="state"
                                                   value="{{$user->state}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="inputState" class="form-label">City</label>
                                            <input type="text" class="form-control" id="inputState" name="city"
                                                   value="{{$user->city}}">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">
                                                Address
                                            </label>
                                            <textarea class="form-control" id="inputAddress" name="address" rows="3"></textarea>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit" id="submitProfile"
                                                    class="btn btn-outline-primary rounded submit">
                                                Create New School
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


        <script src="{{asset('dashboard/js/requests/users/schools.js')}}"></script>
    @endpush
@endsection
