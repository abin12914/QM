$(function () {
    var today = new Date();

    //new employee registration link for select2
    employeeRegistrationLink = "No results found. <a href='/employee/register'>Register new account</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        startDate: today,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //setting current date as selected
    $('.datepicker').datepicker('setDate', today);


    //Initialize Select2 Element for vehicler number select box
    $(".account").select2({
        language: {
             noResults: function() {
                return employeeRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //select employee name for the selected account
    $('body').on("change", "#attendance_account_id", function () {
        var accountId = $('#attendance_account_id').val();
        
        $('#employee_name').val('');
        if(accountId) {
            $.ajax({
                url: "/employee/get/account/" + accountId,
                method: "get",
                success: function(result) { console.log(result);
                    if(result && result.flag) {
                        var employeeName    = result.employeeName;
                        var wage            = result.wage;
                        
                        $('#employee_name').val(employeeName);
                        $('#wage').val(wage);
                    } else {
                        $('#attendance_account_id').val('');
                        $('#attendance_account_id').trigger('change');
                    }
                },
                error: function () {
                    $('#attendance_account_id').val('');
                    $('#attendance_account_id').trigger('change');
                }
            });
        }
    });
});