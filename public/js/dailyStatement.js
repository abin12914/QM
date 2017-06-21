$(function () {
    var today = new Date();
    selectTriggerFlag = 0;

    //new employee registration link for select2
    employeeRegistrationLink    = "No employees found. <a href='/hr/employee/register'>Register new employee</a>";
    //new excavator registration link for select2
    excavatorRegistrationLink   = "No excavator found. <a href='/machine/excavator/register'>Register new excavator</a>";
    //new jackhammer registration link for select2
    jackhammerRegistrationLink  = "No jackhammer found. <a href='/machine/excavator/register'>Register new jackhammer</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        startDate: today,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //setting current date as selected
    $('.datepicker').datepicker('setDate', today);


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
                            var wage        = result.wage;
                            
                            $('#attendance_employee_id').val(employeeId);
                            $('#attendance_wage').val(wage);
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
                            var wage        = result.wage;
                            
                            $('#attendance_account_id').val(accountId);
                            $('#attendance_wage').val(wage);
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

    //select excavator details for the selected excavator
    $('body').on("change", "#excavator_id", function () {
        var excavatorId = $('#excavator_id').val();
        
        $('#excavator_contractor_name').val('');
        if(excavatorId) {
            $.ajax({
                url: "/employee/get/employee/" + excavatorId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var accountId   = result.accountId;
                        var wage        = result.wage;
                        
                        $('#attendance_account_id').val(accountId);
                        $('#attendance_wage').val(wage);
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
    });
});