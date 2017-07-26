$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    selectTriggerFlag = 0;

    //new employee registration link for select2
    employeeRegistrationLink    = "No employees found. <a href='/hr/employee/register'>Register new employee</a>";
    //new excavator registration link for select2
    excavatorRegistrationLink   = "No excavator found. <a href='/machine/excavator/register'>Register new excavator</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        //startDate: today,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Initialize Select2 Element for employee name select box
    $("#salary_employee_id").select2({
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
    $("#salary_account_id").select2({
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

    //Initialize Select2 Element for excavator contractor account select box
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
    $('body').on("change", "#salary_account_id", function () {
        var accountId = $('#salary_account_id').val();
        // selectTriggerFlag is used for escaping from infinte execution of change event(attendance_employee_id and attendance_account_id)
        if(selectTriggerFlag == 0){
            selectTriggerFlag = 1;
            $('#salary_employee_id').val('');
            if(accountId) {
                $.ajax({
                    url: "/employee/get/account/" + accountId,
                    method: "get",
                    success: function(result) {
                        if(result && result.flag) {
                            var employeeId  = result.employeeId;
                            
                            $('#salary_employee_id').val(employeeId);
                        } else {
                            $('#salary_account_id').val('');
                        }

                        $('#salary_employee_id').trigger('change');
                    },
                    error: function () {
                        $('#salary_account_id').val('');
                    }
                });
            } else {
                $('#salary_employee_id').trigger('change');
            }
        } else {
            selectTriggerFlag = 0;
        }
    });

    //select employee name for the selected account
    $('body').on("change", "#salary_employee_id", function () {
        var employeeId = $('#salary_employee_id').val();
        // selectTriggerFlag is used for escaping from infinte execution of change event(salary_employee_id and salary_account_id)
        if(selectTriggerFlag == 0){
            selectTriggerFlag = 1;
            $('#salary_account_id').val('');
            if(employeeId) {
                $.ajax({
                    url: "/employee/get/employee/" + employeeId,
                    method: "get",
                    success: function(result) {
                        if(result && result.flag) {
                            var accountId   = result.accountId;

                            $('#salary_account_id').val(accountId);
                        } else {
                            $('#salary_employee_id').val('');
                        }
                        $('#salary_account_id').trigger('change');
                    },
                    error: function () {
                        $('#salary_employee_id').val('');
                    }
                });
            } else {
               $('#salary_account_id').trigger('change'); 
            }
        } else {
            selectTriggerFlag = 0;
        }
    });

    //update start date based on from date selection - employee
    $('body').on("change", "#salary_from_date", function () {
        var startDate = $('#salary_from_date').val();
        
        if(startDate) {
            $('#salary_to_date').datepicker('setStartDate', startDate);
        } else {
           $('#salary_to_date').datepicker('setStartDate', '');
        }
    });

    //update start date based on from date selection - excavator
    $('body').on("change", "#excavator_from_date", function () {
        var fromDate = $('#excavator_from_date').val();
        
        if(fromDate) {
            $('#excavator_to_date').datepicker('setStartDate', fromDate);
        } else {
           $('#excavator_to_date').datepicker('setStartDate', '');
        }
    });
});