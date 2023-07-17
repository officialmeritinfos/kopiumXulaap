<div class="flex-grow-1"></div>

<div class="footer-area">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="copy-right">
                    <p>Copyright @ {{date('Y')}} {{$siteName}}.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="social-link">
                    <ul>
                        <li>
                            <a href="https://www.facebook.com/karyopay" target="_blank">
                                <i class="ri-facebook-fill"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com/karyopay" target="_blank">
                                <i class="ri-twitter-fill"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Main Content Area -->

<!-- Start Go Top Area -->
<div class="go-top">
    <i class="ri-arrow-up-s-fill"></i>
    <i class="ri-arrow-up-s-fill"></i>
</div>
<!-- End Go Top Area -->

<!-- Jquery Min JS -->
<script src="{{asset('dashboard/js/jquery.min.js')}}"></script>
<!-- Bootstrap Bundle Min JS -->
<script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
<!-- Owl Carousel Min JS -->
<script src="{{asset('dashboard/js/owl.carousel.min.js')}}"></script>
<!-- Metismenu Min JS -->
<script src="{{asset('dashboard/js/metismenu.min.js')}}"></script>
<!-- mixitup Min JS -->
<script src="{{asset('dashboard/js/mixitup.min.js')}}"></script>
<!-- Dark Mode Switch Min JS -->
<script src="{{asset('dashboard/js/dark-mode-switch.min.js')}}"></script>
<!-- Charts Custom Min JS -->
<script src="{{asset('dashboard/js/charts-custom.js')}}"></script>
<!-- Form Validator Min JS -->
<script src="{{asset('dashboard/js/form-validator.min.js')}}"></script>
<!-- Contact JS -->
<script src="{{asset('dashboard/js/contact-form-script.js')}}"></script>
<!-- Ajaxchimp Min JS -->
<script src="{{asset('dashboard/js/ajaxchimp.min.js')}}"></script>
<!-- Custom JS -->
<script src="{{asset('dashboard/js/custom.js')}}"></script>
<script src="{{asset('dashboard/vendors/summernote/summernote-bs5.js')}}"></script>
<script src="{{asset('dashboard/js/selectize.min.js')}}"></script>
@include('basicInclude')
@stack('js')

@if($user->twoFactor!=1 && url()->current()==route('user.dashboard'))
    <!-- Modal -->
    <div class="modal fade" id="twoFactor" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Setup Two-factor Authentication</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body" style="text-align: center;">
                        <p>
                            Set up your two factor authentication by scanning the barcode below.
                            Alternatively, you can use the code <strong>{{ $secret }}</strong>
                        </p>
                        <div class="mb-5">
                            {!! $qrCode !!}
                        </div>
                        <div>
                            <form class="row g-3" method="post" action="{{route('user.dashboard.set2Fa')}}"
                                  id="setup2Fa">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="formGroupExampleInput" class="form-label">
                                        Enter OTP Here
                                    </label>
                                    <input type="number" class="form-control"
                                           aria-label="Text input with dropdown button"
                                           name="one_time_password" id="twoFactorCode">
                                </div>
                                <div class="form-group mb-3">
                                    <button class="btn btn-outline-success" type="submit"
                                            id="submit">
                                        Verify Setup
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('dashboard/js/requests/auth/setupTwoFactor.js')}}"></script>
@endif
<script>
    $('.selectize').selectize();
    $('.selectizeAdd').selectize({
        create:true,
        showAddOptionOnCreate:true,
        createOnBlur:true,
        highlight:true,
        hideSelected:true
    });
</script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 150,
        });
    });
</script>
<script>
    $(document).ready(function(){
        $(".search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".searches tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</body>
</html>
