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

    //setting current date as selected
    $('.datepicker').datepicker('setDate', new Date());    
    //$(".datepicker").datepicker("update", new Date());

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

    //total pit depth calculation
    $('body').on("change", "#jackhammer_depth_per_pit", function () {
        calculateTotalPitDepth();
    });

    //total pit depth calculation
    $('body').on("keyup", "#jackhammer_depth_per_pit", function () {
        calculateTotalPitDepth();
    });

    //total pit depth calculation
    $('body').on("change", "#jackhammer_no_of_pit", function () {
        calculateTotalPitDepth();
    });

    //total pit depth calculation
    $('body').on("keyup", "#jackhammer_no_of_pit", function () {
        calculateTotalPitDepth();
    });
});

function calculateTotalPitDepth() {
    var depthPerPit     = $('#jackhammer_depth_per_pit').val();
    var noOfPit         = $('#jackhammer_no_of_pit').val();
    var totalPitDepth   = 0;
    if(depthPerPit > 0 && noOfPit > 0) {
        totalPitDepth = depthPerPit * noOfPit;
    }
    $('#jackhammer_total_pit_depth').val(totalPitDepth);
}