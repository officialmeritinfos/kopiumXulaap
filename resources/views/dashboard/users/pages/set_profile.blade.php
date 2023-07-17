@extends('dashboard.users.layouts.base')
@section('content')
    <div class="page-title-area">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="page-title">
                        <h3>{{$pageName}}</h3>
                    </div>
                    <p class="mt-2" style="font-size:14px;">Complete your profile and start selling</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <div class="ui-kit-cards grid mb-24">
            @empty($user->profilePhoto)
                <form  id="updateProfilePic" method="post" class="row g-3 shadow-none
                                submit-property-form product-upload fileLoader" enctype="multipart/form-data">

                    <div class="col-md-6 mx-auto" style="margin-bottom: 5rem;">
                        <div class="form-group">
                            <div class="file-upload">
                                <input type="file" name="profilePic" id="file" class="inputfile"
                                       data-link="{{route('user.setupProfile.updatePic')}}">
                                <label class="upload" for="file">
                                    <i class="ri-image-2-fill"></i>
                                    Upload Photo
                                </label>
                            </div>
                        </div>
                    </div>

                </form>
            @else
                <div class="text-center">
                    <img src="{{$user->profilePhoto}}"
                         style="width: 100px;" class="rounded-circle"/>
                </div>
                <form  id="updateProfilePic" method="post" class="row g-3 shadow-none
                                submit-property-form product-upload fileLoader" enctype="multipart/form-data">
                    <div class="col-md-6 mx-auto" style="margin-bottom: 2rem;">
                        <div class="input-group mb-3">
                            <input type="file" name="profilePic" id="file" class="form-control"
                                   data-link="{{route('user.setupProfile.updatePic')}}">
                            <label class="input-group-text" for="inputGroupFile02">Upload</label>
                        </div>
                    </div>

                </form>
            @endempty

            <form class="row g-3" id="updateProfile" method="post" action="{{route('user.setupProfile.submitProfile')}}" enctype="multipart/form-data">
                <div class="row g-3" id="directorId">
                    <div class="col-md-6">
                        <label for="inputUsername" class="form-label">
                            Country
                            <i class="ri-question-line" data-bs-toggle="tooltip" data-placement="top"
                               title="We need to know where you are from for AML purposes"></i>
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
                            Account Currency
                            <i class="ri-question-line" data-bs-toggle="tooltip" data-placement="top"
                               title="The default currency by which you will be charged for any subscription.s"></i>
                        </label>
                        <select class="form-control selectize" id="inputPassword4" name="currency">
                            <option value="">Select an Option</option>
                            @foreach($fiats as $fiat)
                                <option value="{{$fiat->code}}">{{$fiat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="inputState" name="phone" value="{{$user->phone}}">
                    </div>
                    <div class="col-md-3">
                        <label for="inputState" class="form-label">State/Region</label>
                        <input type="text" class="form-control" id="inputState" name="state" value="{{$user->state}}">
                    </div>
                    <div class="col-md-3">
                        <label for="inputState" class="form-label">City</label>
                        <input type="text" class="form-control" id="inputState" name="city" value="{{$user->city}}">
                    </div>
                    <div class="col-md-2">
                        <label for="inputZip" class="form-label">Zip</label>
                        <input type="text" class="form-control" id="inputZip" name="zip" value="{{$user->zipCode}}">
                    </div>
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">Address</label>
                        <textarea type="text" class="form-control" id="inputAddress"
                                  placeholder="1234 Main St" name="address" >{{$user->address}}</textarea>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary" type="submit" id="submitProfile">Submit</button>
                </div>
            </form>


        </div>

    </div>





    @push('js')
        <script src="{{asset('dashboard/js/requests/users/setupProfile.js')}}"></script>
    @endpush
@endsection
