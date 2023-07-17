const schoolBranchesRequest=function (){
    //create branch
    const createNewBranch=function (){
        //process the form submission
        $('#addNewBranch').submit(function(e) {
            e.preventDefault();
            var baseURL = $('#addNewBranch').attr('action');

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
                    $("#addNewBranch :input").prop("readonly", true);
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
                            $("#addNewBranch :input").prop("readonly", false);
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
                            $("#addNewBranch :input").prop("readonly", false);
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
                    $("#addNewBranch :input").prop("readonly", false);
                    $('.submit').attr('disabled', false);
                    $(".submit").LoadingOverlay("hide");
                },
            });
        });
    }
    const domOperation = function (){
        //edit branch
        $('#editBranch').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var branch = button.data('value');
            var name = button.data('name');
            $('input[name="branch"]').val(branch);
            $('input[name="name"]').val(name);
        })
    }
    //edit branch
    const editBranch=function () {
        //process the form submission
        $('#updateBranch').submit(function (e) {
            e.preventDefault();
            var baseURL = $('#updateBranch').attr('action');

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: baseURL,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function () {
                    $('.submit').attr('disabled', true);
                    $("#updateBranch :input").prop("readonly", true);
                    $(".submit").LoadingOverlay("show", {
                        text: "updating...",
                        size: "20"
                    });
                },
                success: function (data) {
                    if (data.error === true) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        }
                        toastr.error(data.data.error);

                        //return to natural stage
                        setTimeout(function () {
                            $('.submit').attr('disabled', false);
                            $(".submit").LoadingOverlay("hide");
                            $("#updateBranch :input").prop("readonly", false);
                        }, 3000);
                    }
                    if (data.error === 'ok') {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        }
                        toastr.info(data.message);

                        setTimeout(function () {
                            $('.submit').attr('disabled', false);
                            $(".submit").LoadingOverlay("hide");
                            $("#updateBranch :input").prop("readonly", false);
                            window.location.replace(data.data.redirectTo)
                        }, 5000);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error(errorThrown);
                    $("#updateBranch :input").prop("readonly", false);
                    $('.submit').attr('disabled', false);
                    $(".submit").LoadingOverlay("hide");
                },
            });
        });
    }


    return {
        init: function() {
            createNewBranch();
            domOperation();
            editBranch()
        }
    };
}();

jQuery(document).ready(function() {
    schoolBranchesRequest.init();
});
