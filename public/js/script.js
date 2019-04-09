
$(document).ready(function () {
    $("#vacancy_department").prop("disabled", true);
    $("#vacancy_team").prop("disabled", true);
    $("#selectid option:selected").attr('disabled', 'disabled');
    $("#vacancy_office").change(function () {
        var id = $(this).children("option:selected").val();
        $("#vacancy_department option[data-OfficeId=" + id + "]").show();
        $("#vacancy_department option[data-OfficeId!=" + id + "]").hide();
        $("#vacancy_department").prop("disabled", false);
        $('#vacancy_department option').prop('selected', function () {
            return this.defaultSelected;
        });
        $('#vacancy_team option').prop('selected', function () {
            return this.defaultSelected;
        });
    });

    $("#vacancy_department").change(function () {
        var id = $(this).children("option:selected").val();

        $("#vacancy_team option[data-departmentid=" + id + "]").show();
        console.log($("#vacancy_team option[data-departmentid=" + id + "]"));
        if ($("#vacancy_team option[data-departmentid=" + id + "]").length>0) {
            $('#vacancy_team').attr('required', true);
        }
        else
        {
            $('#vacancy_team').attr('required', false);
        }

        $("#vacancy_team option[data-DepartmentId!=" + id + "]").hide();
        $("#vacancy_team").prop("disabled", false);
        $('#vacancy_team option').prop('selected', function () {
            return this.defaultSelected;
        });
    });

});