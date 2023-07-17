const setupProfileRequest=function (){
    //update profile
    const submitProfile=function (){
        //process the form submission
        $('#updateProfile').submit(function(e) {
            e.preventDefault();
            var baseURL = $('#updateProfile').attr('action');
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
                    $('#submitProfile').attr('disabled', true);
                    $("#updateProfile :input").prop("readonly", true);
                    $("#submitProfile").LoadingOverlay("show",{
                        text        : "updating ...",
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
                            $('#submitProfile').attr('disabled', false);
                            $("#submitProfile").LoadingOverlay("hide");
                            $("#updateProfile :input").prop("readonly", false);
                        }, 3000);
                    }
                    if(data.error === 'ok')
                    {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.info(data.message);

                        setTimeout(function(){
                            $('#submitProfile').attr('disabled', false);
                            $("#submitProfile").LoadingOverlay("hide");
                            $("#updateProfile :input").prop("readonly", false);
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
                    $("#updateProfile :input").prop("readonly", false);
                    $('#submitProfile').attr('disabled', false);
                    $("#submitProfile").LoadingOverlay("hide");
                },
            });
        });
    }
    //upload photo
    var updateProfilePhoto = function (){
        $('#updateProfilePic').on('submit',(function(e) {

            var baseURL = $('#file').data('link');
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url:baseURL,
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType:"json",
                beforeSend:function(){
                    $('.fileLoader').attr('disabled', true);
                    $("#updateProfilePic :input").prop("readonly", true);
                    $(".fileLoader").LoadingOverlay("show",{
                        text        : "uploading",
                        size        : "20"
                    });
                },
                success:function(data)
                {
                    if(data.error ===true)
                    {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.error(data.data.error);
                        //return to natural stage
                        setTimeout(function(){
                            $('.fileLoader').attr('disabled', false);
                            $(".fileLoader").LoadingOverlay("hide");
                            $("#updateProfilePic :input").prop("readonly", false);
                        }, 3000);
                    }
                    if(data.error === 'ok')
                    {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.success(data.message);
                        //return to natural stage
                        setTimeout(function(){
                            $('.fileLoader').attr('disabled', false);
                            $(".fileLoader").LoadingOverlay("hide");
                            location.reload();
                        }, 3000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    toastr.options = {
                        "closeButton" : true,
                        "progressBar" : true
                    }
                    toastr.error(errorThrown);
                    //return to natural stage
                    setTimeout(function(){
                        $('.fileLoader').attr('disabled', false);
                        $(".fileLoader").LoadingOverlay("hide");
                        $("#updateProfilePic :input").prop("readonly", false);
                    }, 3000);
                }
            });
        }));
        $("#file").on("change", function() {
            $("#updateProfilePic").submit();
        });
    }
    return {
        init: function() {
            submitProfile();
            updateProfilePhoto();
        }
    };
}();

jQuery(document).ready(function() {
    setupProfileRequest.init();
});
