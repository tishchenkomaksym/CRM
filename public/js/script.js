
$(document).ready(function () {
    $(".department").prop("disabled", true);
    // $(".team").prop("disabled", true);
    $("#selectid option:selected").attr('disabled', 'disabled');
    $(".office").change(function () {
        var id = $(this).children("option:selected").val();
        $(".department option[data-OfficeId=" + id + "]").show();
        $(".department option[data-OfficeId!=" + id + "]").hide();
        $(".department").prop("disabled", false);
        $('.department option').prop('selected', function () {
            return this.defaultSelected;
        });
        $('.team option').prop('selected', function () {
            return this.defaultSelected;
        });
    });

    $(".department").change(function () {
        var id = $(this).children("option:selected").val();

        $(".team option[data-departmentid=" + id + "]").show();
        console.log($(".team option[data-departmentid=" + id + "]"));
        if ($(".team option[data-departmentid=" + id + "]").length>0) {
            $('.team').attr('required', true);
        }
        else
        {
            $('.team').attr('required', false);
        }

        $(".team option[data-DepartmentId!=" + id + "]").hide();
        $(".team").prop("disabled", false);
        $('.team option').prop('selected', function () {
            return this.defaultSelected;
        });


    });

});