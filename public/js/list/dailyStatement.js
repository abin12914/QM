$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    selectTriggerFlag = 0;

    //new employee registration link for select2
    employeeRegistrationLink    = "No employees found. <a href='/hr/employee/register'>Register new employee</a>";
    //new excavator registration link for select2
    excavatorRegistrationLink   = "No excavator found. <a href='/machine/excavator/register'>Register new excavator</a>";
    //new jackhammer registration link for select2
    jackhammerRegistrationLink  = "No jackhammer found. <a href='/machine/jackhammer/register'>Register new jackhammer</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        //startDate: today,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //handle link to tabs
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs-custom a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs-custom a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    //Initialize Select2 Element for employee name select box
    $("#attendance_employee_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return employeeRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for employee account select box
    $("#attendance_account_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return employeeRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for excavator select box
    $("#excavator_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return excavatorRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for excavator account select box
    $("#excavator_account_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return excavatorRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for jackhammer select box
    $("#jackhammer_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return jackhammerRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for jackhammer contractor account select box
    $("#jackhammer_account_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return jackhammerRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //handle link to tabs
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs-custom a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs-custom a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    //select employee name for the selected account
    $('body').on("change", "#attendance_account_id", function () {
        var accountId = $('#attendance_account_id').val();
        // selectTriggerFlag is used for escaping from infinte execution of change event(attendance_employee_id and attendance_account_id)
        if(selectTriggerFlag == 0){
            selectTriggerFlag = 1;
            $('#attendance_employee_id').val('');
            if(accountId) {
                $.ajax({
                    url: "/employee/get/account/" + accountId,
                    method: "get",
                    success: function(result) {
                        if(result && result.flag) {
                            var employeeId  = result.employeeId;
                            
                            $('#attendance_employee_id').val(employeeId);
                        } else {
                            $('#attendance_account_id').val('');
                        }

                        $('#attendance_employee_id').trigger('change');
                    },
                    error: function () {
                        $('#attendance_account_id').val('');
                    }
                });
            } else {
                $('#attendance_employee_id').trigger('change');
            }
        } else {
            selectTriggerFlag = 0;
        }
    });

    //select employee name for the selected account
    $('body').on("change", "#attendance_employee_id", function () {
        var employeeId = $('#attendance_employee_id').val();
        // selectTriggerFlag is used for escaping from infinte execution of change event(attendance_employee_id and attendance_account_id)
        if(selectTriggerFlag == 0){
            selectTriggerFlag = 1;
            $('#attendance_account_id').val('');
            if(employeeId) {
                $.ajax({
                    url: "/employee/get/employee/" + employeeId,
                    method: "get",
                    success: function(result) {
                        if(result && result.flag) {
                            var accountId   = result.accountId;
                            
                            $('#attendance_account_id').val(accountId);
                        } else {
                            $('#attendance_employee_id').val('');
                        }
                        $('#attendance_account_id').trigger('change');
                    },
                    error: function () {
                        $('#attendance_employee_id').val('');
                    }
                });
            } else {
               $('#attendance_account_id').trigger('change'); 
            }
        } else {
            selectTriggerFlag = 0;
        }
    });

    //select contractor details for the selected jackhammer
    $('body').on("change", "#jackhammer_id", function () {
        var jackhammerId = $('#jackhammer_id').val();
        
        $('#jackhammer_contractor_account').val('');
        if(jackhammerId) {
            $.ajax({
                url: "/get/account/by/jackhammer/" + jackhammerId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var accountName   = result.accountName;
                        $('#jackhammer_contractor_account').val(accountName);
                    } else {
                        $('#jackhammer_id').val('');
                    }
                },
                error: function () {
                    $('#jackhammer_id').val('');
                }
            });
        } else {
           $('#jackhammer_id').val('');
        }
    });

    //invoke confirmation on delete
    $('body').on("click", ".employee_delete_button", function () {
        var deleteId = $(this).data('employee-delete-id');

        if(deleteId && deleteId != 0) {
            $('#employee_delete_confirmation_modal_confirm').data('employee-delete-modal-id', deleteId);
            $('#employee_delete_confirmation_modal').modal('show');
        } else {
            alert("Something went wrong! Please reload the page.");
            $('#employee_delete_confirmation_modal').modal('hide');
        }
    });

    // for disabling submit button to prevent multiple submition on delete confirmation modal
    $('body').on("click", "#employee_delete_confirmation_modal_confirm", function () {
        var deleteId = $(this).data('employee-delete-modal-id');

        $('#employee_delete_confirmation_modal_confirm').prop('disabled', true);

        if(deleteId && deleteId != 0) {
            $("#employee_delete_"+ deleteId).submit();
        } else {
            alert("Something went wrong! Please reload the page.");
            $('#employee_delete_confirmation_modal').modal('hide');
        }
    });

    //invoke confirmation on delete
    $('body').on("click", ".excavator_delete_button", function () {
        var deleteId = $(this).data('excavator-delete-id');

        if(deleteId && deleteId != 0) {
            $('#excavator_delete_confirmation_modal_confirm').data('excavator-delete-modal-id', deleteId);
            $('#excavator_delete_confirmation_modal').modal('show');
        } else {
            alert("Something went wrong! Please reload the page.");
            $('#excavator_delete_confirmation_modal').modal('hide');
        }
    });

    // for disabling submit button to prevent multiple submition on delete confirmation modal
    $('body').on("click", "#excavator_delete_confirmation_modal_confirm", function () {
        var deleteId = $(this).data('excavator-delete-modal-id');

        $('#excavator_delete_confirmation_modal_confirm').prop('disabled', true);

        if(deleteId && deleteId != 0) {
            $("#excavator_delete_"+ deleteId).submit();
        } else {
            alert("Something went wrong! Please reload the page.");
            $('#excavator_delete_confirmation_modal').modal('hide');
        }
    });

    //invoke confirmation on delete
    $('body').on("click", ".jackhammer_delete_button", function () {
        var deleteId = $(this).data('jackhammer-delete-id');

        if(deleteId && deleteId != 0) {
            $('#jackhammer_delete_confirmation_modal_confirm').data('jackhammer-delete-modal-id', deleteId);
            $('#jackhammer_delete_confirmation_modal').modal('show');
        } else {
            alert("Something went wrong! Please reload the page.");
            $('#jackhammer_delete_confirmation_modal').modal('hide');
        }
    });

    // for disabling submit button to prevent multiple submition on delete confirmation modal
    $('body').on("click", "#jackhammer_delete_confirmation_modal_confirm", function () {
        var deleteId = $(this).data('jackhammer-delete-modal-id');

        $('#jackhammer_delete_confirmation_modal_confirm').prop('disabled', true);

        if(deleteId && deleteId != 0) {
            $("#jackhammer_delete_"+ deleteId).submit();
        } else {
            alert("Something went wrong! Please reload the page.");
            $('#jackhammer_delete_confirmation_modal').modal('hide');
        }
    });
});