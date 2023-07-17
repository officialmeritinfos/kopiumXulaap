const setupTwoFactorRequest=function (){
    const initiateSetup=function (){
        var baseURL = $('#setup2Fa').attr('action');

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
                        $('#submit').attr('disabled', true);
                        $("#setup2Fa :input").prop("readonly", true);
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
                                $("#setup2Fa :input").prop("readonly", false);
                                $("#setup2Fa")[0].reset();
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
                                $('#submit').attr('disabled', false);
                                $("#submit").LoadingOverlay("hide");
                                $("#setup2Fa :input").prop("readonly", false);
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
                        $("#setup2Fa :input").prop("readonly", false);
                        $('#submit').attr('disabled', false);
                        $("#submit").LoadingOverlay("hide");
                    },
                });

            }
        });
        //process the form submission
        $('#setup2Fa').submit(function(e) {
            e.preventDefault();

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
                    $("#setup2Fa :input").prop("readonly", true);
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
                            $("#setup2Fa :input").prop("readonly", false);
                            $("#setup2Fa")[0].reset();
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
                            $('#submit').attr('disabled', false);
                            $("#submit").LoadingOverlay("hide");
                            $("#setup2Fa :input").prop("readonly", false);
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
                    $("#setup2Fa :input").prop("readonly", false);
                    $('#submit').attr('disabled', false);
                    $("#submit").LoadingOverlay("hide");
                },
            });
        });
    }

    return {
        init: function() {
            initiateSetup();
        }
    };
}();

jQuery(document).ready(function() {
    setupTwoFactorRequest.init();
});

