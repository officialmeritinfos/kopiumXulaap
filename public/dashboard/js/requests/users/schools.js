const schoolRequest=function (){
    //create store
    const createNewSchool=function (){
        //process the form submission
        $('#addNewSchool').submit(function(e) {
            e.preventDefault();
            var baseURL = $('#addNewSchool').attr('action');

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
                    $('.submit').attr('disabled', true);
                    $("#addNewSchool :input").prop("readonly", true);
                    $(".submit").LoadingOverlay("show",{
                        text        : "creating...",
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
                            $('.submit').attr('disabled', false);
                            $(".submit").LoadingOverlay("hide");
                            $("#addNewSchool :input").prop("readonly", false);
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
                            $('.submit').attr('disabled', false);
                            $(".submit").LoadingOverlay("hide");
                            $("#addNewSchool :input").prop("readonly", false);
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
                    $("#addNewSchool :input").prop("readonly", false);
                    $('.submit').attr('disabled', false);
                    $(".submit").LoadingOverlay("hide");
                },
            });
        });
        //perform some dom
        $('#storeName').on('input', function(e) {

            var vl = $(this).val();
            vl = $.trim(vl);
            vl = vl.replace(/([-,.€~!@#$%^&*()_+=`{}\[\]\|\\:;'<>])+/g, '');
            vl = vl.replace(/\s+/g, '-').toLowerCase();
            $('#slug').val(vl);
        });
    }
    return {
        init: function() {
            createNewSchool();
        }
    };
}();

jQuery(document).ready(function() {
    schoolRequest.init();
});
