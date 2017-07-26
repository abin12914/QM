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
    $("#emp_salary_employee_id").select2({
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
    $("#emp_salary_account_id").select2({
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
    $('body').on("change", "#emp_salary_account_id", function () {
        var accountId = $('#emp_salary_account_id').val();
        // selectTriggerFlag is used for escaping from infinte execution of change event(attendance_employee_id and attendance_account_id)
        if(selectTriggerFlag == 0){
            selectTriggerFlag = 1;
            $('#emp_salary_employee_id').val('');
            if(accountId) {
                $.ajax({
                    url: "/employee/get/account/" + accountId,
                    method: "get",
                    success: function(result) {
                        if(result && result.flag) {
                            var employeeId  = result.employeeId;
                            var salary      = result.wage;
                            var salaryDate  = result.salaryDate;
                            
                            $('#emp_salary_employee_id').val(employeeId);
                            $('#emp_salary_salary').val(salary);
                            if(salaryDate) {
                                var today = new Date();
                                var salaryDateField = new Date(salaryDate);
                                if(salaryDateField < today) {
                                    var day     = salaryDateField.getDate();
                                    var month   = salaryDateField.getMonth()+1;
                                    var year    = salaryDateField.getFullYear();
                                    salaryDate  = day+'-'+month+'-'+year;

                                    //$('.datepicker').datepicker('setStartDate', salaryDate);
                                    $('#emp_salary_start_date').datepicker('setDate', salaryDate);
                                }
                            }
                        } else {
                            $('#emp_salary_account_id').val('');
                        }

                        $('#emp_salary_employee_id').trigger('change');
                    },
                    error: function () {
                        $('#emp_salary_account_id').val('');
                    }
                });
            } else {
                $('#emp_salary_employee_id').trigger('change');
            }
        } else {
            selectTriggerFlag = 0;
        }
    });

    //select employee name for the selected account
    $('body').on("change", "#emp_salary_employee_id", function () {
        var employeeId = $('#emp_salary_employee_id').val();
        // selectTriggerFlag is used for escaping from infinte execution of change event(emp_salary_employee_id and emp_salary_account_id)
        if(selectTriggerFlag == 0){
            selectTriggerFlag = 1;
            $('#emp_salary_account_id').val('');
            if(employeeId) {
                $.ajax({
                    url: "/employee/get/employee/" + employeeId,
                    method: "get",
                    success: function(result) {
                        if(result && result.flag) {
                            var accountId   = result.accountId;
                            var salary      = result.wage;
                            var salaryDate  = result.salaryDate;

                            $('#emp_salary_account_id').val(accountId);
                            $('#emp_salary_salary').val(salary);

                            if(salaryDate) {
                                var today = new Date();
                                var salaryDateField = new Date(salaryDate);
                                if(salaryDateField < today) {
                                    var day     = salaryDateField.getDate();
                                    var month   = salaryDateField.getMonth()+1;
                                    var year    = salaryDateField.getFullYear();
                                    salaryDate  = day+'-'+month+'-'+year;

                                    //$('.datepicker').datepicker('setStartDate', salaryDate);
                                    $('#emp_salary_start_date').datepicker('setDate', salaryDate);
                                }
                            }
                        } else {
                            $('#emp_salary_employee_id').val('');
                        }
                        $('#emp_salary_account_id').trigger('change');
                    },
                    error: function () {
                        $('#emp_salary_employee_id').val('');
                    }
                });
            } else {
               $('#emp_salary_account_id').trigger('change'); 
            }
        } else {
            selectTriggerFlag = 0;
        }
    });

    //select contractor details for the selected jackhammer
    $('body').on("change", "#excavator_id", function () {
        var excavatorId = $('#excavator_id').val();
        
        $('#excavator_contractor_name').val('');
        if(excavatorId) {
            $.ajax({
                url: "/get/account/by/excavator/" + excavatorId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var accountName   = result.accountName;
                        var rent = result.rent;
                        var excavatorLastRentDate  = result.excavatorLastRentDate;

                        $('#excavator_contractor_name').val(accountName);
                        $('#excavator_rent').val(rent);
                        
                        if(excavatorLastRentDate) {
                            var today = new Date();
                            var salaryDateField = new Date(excavatorLastRentDate);
                            if(salaryDateField < today) {
                                var day     = salaryDateField.getDate();
                                var month   = salaryDateField.getMonth()+1;
                                var year    = salaryDateField.getFullYear();
                                excavatorLastRentDate  = day+'-'+month+'-'+year;

                                $('#excavator_from_date').datepicker('setDate', excavatorLastRentDate);
                            }
                        }
                    } else {
                        $('#excavator_id').val('');
                    }
                },
                error: function () {
                    $('#excavator_id').val('');
                }
            });
        } else {
           $('#excavator_id').val('');
        }
    });

    //update start date based on from date selection - employee
    $('body').on("change", "#emp_salary_start_date", function () {
        var startDate = $('#emp_salary_start_date').val();
        
        if(startDate) {
            $('#emp_salary_end_date').datepicker('setStartDate', startDate);
        } else {
           $('#emp_salary_end_date').datepicker('setStartDate', '');
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