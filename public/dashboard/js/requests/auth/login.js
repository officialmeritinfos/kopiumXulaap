const loginRequest=function (){
    const initiateLogin=function (){
        //process the form submission
        $('#login').submit(function(e) {
            e.preventDefault();
            var baseURL = $('#login').attr('action');
            var baseURLs='';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL,
                method: "POST",
                data:$(this).serialize(),
                dataType:"json",
                beforeSend:function(){
                    $('#submit').attr('disabled', true);
                    $("#login :input").prop("readonly", true);
                    $("#submit").LoadingOverlay("show",{
                        text        : "please wait ...",
                        size        : "20"
                    });
                },
                success:function(data)
                {
                    if(data.error===true)
                    {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.error(data.data.error);

                        //return to natural stage
                        setTimeout(function(){
                            $('#submit').attr('disabled', false);
                            $("#submit").LoadingOverlay("hide");
                            $("#login :input").prop("readonly", false);
                            $("#login")[0].reset();
                        }, 3000);
                    }
                    if(data.error === 'ok')
                    {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.info(data.message);

                        if (data.data.showOtp){
                            $('#twoFactor').modal('show')
                            $('#submit').attr('disabled', false);
                            $("#submit").LoadingOverlay("hide");
                        }else{
                            //return to natural stage
                            setTimeout(function(){
                                $('#submit').attr('disabled', false);
                                $("#submit").LoadingOverlay("hide");
                                $("#login :input").prop("readonly", false);
                                window.location.replace(data.data.redirectTo)
                            }, 5000);
                        }
                    }
                },
                error:function (jqXHR, textStatus, errorThrown){
                    toastr.options = {
                        "closeButton" : true,
                        "progressBar" : true
                    }
                    toastr.error(errorThrown);
                    $("#login :input").prop("readonly", false);
                    $('#submit').attr('disabled', false);
                    $("#submit").LoadingOverlay("hide");
                },
            });
        });
    }

    const verifyOtp=function (){
        var baseURL = $('#verifyOtp').attr('action');

        $('#twoFactorCode').on('input', function(e) {

            var value = $(this).val();

            if (value.length === 6) {
                // Prepare the data for the Ajax request
                var data =$(this).serialize();

                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: baseURL,
                    method: "POST",
                    data:data,
                    dataType:"json",
                    beforeSend:function(){
                        $('#submitOtp').attr('disabled', true);
                        $("#verifyOtp :input").prop("readonly", true);
                        $("#submitOtp").LoadingOverlay("show",{
                            text        : "verifying ...",
                            size        : "20"
                        });
                    },
                    success:function(data)
                    {
                        if(data.error===true)
                        {
                            toastr.options = {
                                "closeButton" : true,
                                "progressBar" : true
                            }
                            toastr.error(data.data.error);

                            //return to natural stage
                            setTimeout(function(){
                                $('#submitOtp').attr('disabled', false);
                                $("#submitOtp").LoadingOverlay("hide");
                                $("#verifyOtp :input").prop("readonly", false);
                                $("#verifyOtp")[0].reset();
                            }, 3000);
                        }
                        if(data.error === 'ok')
                        {
                            toastr.options = {
                                "closeButton" : true,
                                "progressBar" : true
                            }
                            toastr.info(data.message);
                            //return to natural stage
                            setTimeout(function(){
                                $('#submitOtp').attr('disabled', false);
                                $("#submitOtp").LoadingOverlay("hide");
                                $("#verifyOtp :input").prop("readonly", false);
                                window.location.replace(data.data.redirectTo)
                            }, 5000);
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown){
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.error(errorThrown);
                        $("#verifyOtp :input").prop("readonly", false);
                        $('#submitOtp').attr('disabled', false);
                        $("#submitOtp").LoadingOverlay("hide");
                    },
                });

            }
        });

        //process the form submission
        $('#verifyOtp').submit(function(e) {
            e.preventDefault();
            var baseURL = $('#verifyOtp').attr('action');
            var baseURLs='';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL,
                method: "POST",
                data:$(this).serialize(),
                dataType:"json",
                beforeSend:function(){
                    $('#submitOtp').attr('disabled', true);
                    $("#verifyOtp :input").prop("readonly", true);
                    $("#submitOtp").LoadingOverlay("show",{
                        text        : "verifying ...",
                        size        : "20"
                    });
                },
                success:function(data)
                {
                    if(data.error===true)
                    {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.error(data.data.error);

                        //return to natural stage
                        setTimeout(function(){
                            $('#submitOtp').attr('disabled', false);
                            $("#submitOtp").LoadingOverlay("hide");
                            $("#verifyOtp :input").prop("readonly", false);
                            $("#verifyOtp")[0].reset();
                        }, 3000);
                    }
                    if(data.error === 'ok')
                    {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.info(data.message);

                        //return to natural stage
                        setTimeout(function(){
                            $('#submitOtp').attr('disabled', false);
                            $("#submitOtp").LoadingOverlay("hide");
                            $("#verifyOtp :input").prop("readonly", false);
                            window.location.replace(data.data.redirectTo)
                        }, 5000);
                    }
                },
                error:function (jqXHR, textStatus, errorThrown){
                    toastr.options = {
                        "closeButton" : true,
                        "progressBar" : true
                    }
                    toastr.error(errorThrown);
                    $("#verifyOtp :input").prop("readonly", false);
                    $('#submitOtp').attr('disabled', false);
                    $("#submitOtp").LoadingOverlay("hide");
                },
            });
        });
    }

    return {
        init: function() {
            initiateLogin();
            verifyOtp();
        }
    };
}();

jQuery(document).ready(function() {
    loginRequest.init();
});

