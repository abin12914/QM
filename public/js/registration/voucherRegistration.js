$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    //selectTriggerFlag = 0;

    //new account registration link for select2
    accountRegistrationLink    = "No account found. <a href='/account/register'>Register new account</a>";
    
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

    //Timepicker
    $(".timepicker").timepicker({
        minuteStep : 5,
        showInputs : false,
        showMeridian : false
    });

    // update timepicker value
    setInterval(function() { updateTimepicker() }, 300000);

    //Initialize Select2 Element for account select box
    initializeSelect2();

    $('#machine_voucher_credit_account_id').prop('disabled', true);

    $("#machine_voucher_machine_class").select2({
        minimumResultsForSearch: 4
    });

    $(".machine_voucher_machine_id").select2({
        minimumResultsForSearch: 5
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

    //select name for the selected account
    $('body').on("change", "#cash_voucher_account_id", function () {
        var accountId = $('#cash_voucher_account_id').val();

        $('#cash_voucher_account_name').val('');
        if(accountId) {
            $.ajax({
                url: "/get/details/by/account/" + accountId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var name  = result.name;
                        
                        $('#cash_voucher_account_name').val(name);
                    } else {
                        $('#cash_voucher_account_name').val('');
                    }
                },
                error: function () {
                    $('#cash_voucher_account_name').val('');
                }
            });
        } else {
            $('#cash_voucher_account_name').val('');
        }
    });

    //select name for the selected account
    $('body').on("change", "#credit_voucher_debit_account_id", function () {
        var debitAccountId = $('#credit_voucher_debit_account_id').val();
        var creditAccountId = $('#credit_voucher_credit_account_id').val();

        if(debitAccountId && (debitAccountId == creditAccountId)) {
            alert("Debit account and credit account should not be same.");
            $('#credit_voucher_debit_account_id').val("");
            $('#credit_voucher_debit_account_id').trigger("change");
            return false;
        }

        if(debitAccountId) {
            $('#credit_voucher_debit_account_id, #credit_voucher_credit_account_id').not($('#credit_voucher_debit_account_id'))
            .children('option[value=' + debitAccountId + ']')
            .attr('disabled', true)
            .siblings().removeAttr('disabled');
        } else {
            $('#credit_voucher_debit_account_id, #credit_voucher_credit_account_id').not($('#credit_voucher_debit_account_id'))
            .children('option[value=""]')
            .siblings().removeAttr('disabled');
        }

        //reinitializing select2 elements for resetting disabled options
        initializeSelect2();

        $('#credit_voucher_debit_account_name').val('');
        if(debitAccountId) {
            $.ajax({
                url: "/get/details/by/account/" + debitAccountId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var name  = result.name;
                        
                        $('#credit_voucher_debit_account_name').val(name);
                    } else {
                        $('#credit_voucher_debit_account_name').val('');
                    }
                },
                error: function () {
                    $('#credit_voucher_debit_account_name').val('');
                }
            });
        } else {
            $('#credit_voucher_debit_account_name').val('');
        }
    });

    //select name for the selected account
    $('body').on("change", "#credit_voucher_credit_account_id", function () {
        var creditAccountId = $('#credit_voucher_credit_account_id').val();
        var debitAccountId = $('#credit_voucher_debit_account_id').val();

        if(creditAccountId && (debitAccountId == creditAccountId)) {
            alert("Debit account and credit account should not be same.");
            $('#credit_voucher_credit_account_id').val("");
            $('#credit_voucher_credit_account_id').trigger("change");
            return false;
        }

        if(creditAccountId) {
            $('#credit_voucher_credit_account_id, #credit_voucher_debit_account_id').not($('#credit_voucher_credit_account_id'))
            .children('option[value=' + creditAccountId + ']')
            .attr('disabled', true)
            .siblings().removeAttr('disabled');
        } else {
            $('#credit_voucher_credit_account_id, #credit_voucher_debit_account_id').not($('#credit_voucher_credit_account_id'))
            .children('option[value=""]')
            .siblings().removeAttr('disabled');
        }

        //reinitializing select2 elements for resetting disabled options
        initializeSelect2();

        $('#credit_voucher_credit_account_name').val('');
        if(creditAccountId) {
            $.ajax({
                url: "/get/details/by/account/" + creditAccountId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var name  = result.name;
                        
                        $('#credit_voucher_credit_account_name').val(name);
                    } else {
                        $('#credit_voucher_credit_account_name').val('');
                    }
                },
                error: function () {
                    $('#credit_voucher_credit_account_name').val('');
                }
            });
        } else {
            $('#credit_voucher_credit_account_name').val('');
        }
    });

    //select name for the selected account
    $('body').on("change", "#machine_voucher_machine_class", function () {
        var type = $('#machine_voucher_machine_class').val();

        if(type == 1) {
            $('#machine_voucher_excavator_id').prop('disabled', false);
            $('#machine_voucher_jackhammer_id').prop('disabled', true);
            $('#class_excavator').show();
            $('#class_jackhammer').hide();
        } else if(type == 2) {
            $('#machine_voucher_excavator_id').prop('disabled', true);
            $('#machine_voucher_jackhammer_id').prop('disabled', false);
            $('#class_jackhammer').show();
            $('#class_excavator').hide();
        }

        $('#machine_voucher_credit_account_id').val("");
        $('#machine_voucher_credit_account_id').trigger('change');
    });

    //select related account for the selected jackhammer
    $('body').on("change", "#machine_voucher_excavator_id", function () {
        var excavatorId = $('#machine_voucher_excavator_id').val();
        var debitAccountId = $('#machine_voucher_debit_account_id').val();

        if(excavatorId) {
            $.ajax({
                url: "/get/account/by/excavator/" + excavatorId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var accountId  = result.accountId;
                        if(debitAccountId != accountId){
                            $('#machine_voucher_credit_account_id').val(accountId);
                            $('#machine_voucher_credit_account_id').trigger('change');
                        } else {
                            $("#machine_voucher_excavator_id").val("");
                            $("#machine_voucher_excavator_id").trigger('change');
                            alert('Selected debit account and selected machine contractor should be diffrent');
                        }
                    } else {
                        $('#machine_voucher_credit_account_id').val("");
                        $('#machine_voucher_credit_account_id').trigger('change');
                    }
                },
                error: function () {
                    $('#machine_voucher_credit_account_id').val("");
                    $('#machine_voucher_credit_account_id').trigger('change');
                }
            });
        } else {
            $('#machine_voucher_credit_account_id').val("");
            $('#machine_voucher_credit_account_id').trigger('change');
        }
    });

    //select related account for the selected jackhammer
    $('body').on("change", "#machine_voucher_jackhammer_id", function () {
        var jackhammerId = $('#machine_voucher_jackhammer_id').val();
        var debitAccountId = $('#machine_voucher_debit_account_id').val();

        if(jackhammerId) {
            $.ajax({
                url: "/get/account/by/jackhammer/" + jackhammerId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var accountId  = result.accountId;
                        
                        if(debitAccountId != accountId){
                            $('#machine_voucher_credit_account_id').val(accountId);
                            $('#machine_voucher_credit_account_id').trigger('change');
                        } else {
                            $("#machine_voucher_jackhammer_id").val("");
                            $("#machine_voucher_jackhammer_id").trigger('change');
                            alert('Selected debit account and selected machine contractor should be diffrent');
                        }
                    } else {
                        $('#machine_voucher_credit_account_id').val("");
                        $('#machine_voucher_credit_account_id').trigger('change');
                    }                    
                },
                error: function () {
                    $('#machine_voucher_credit_account_id').val("");
                    $('#machine_voucher_credit_account_id').trigger('change');
                }
            });
        } else {
            $('#machine_voucher_credit_account_id').val("");
            $('#machine_voucher_credit_account_id').trigger('change');
        }
    });

    //select name for the selected account
    $('body').on("change", "#machine_voucher_credit_account_id", function () {
        var creditAccountId = $('#machine_voucher_credit_account_id').val();
        var debitAccountId = $('#machine_voucher_debit_account_id').val();

        if(creditAccountId && (debitAccountId == creditAccountId)) {
            alert("Debit account and credit account should not be same.");
            $('#machine_voucher_credit_account_id').val("");
            $('#machine_voucher_credit_account_id').trigger("change");
            $('#machine_voucher_excavator_id').val("");
            $('#machine_voucher_excavator_id').trigger('change');
            $('#machine_voucher_jackhammer_id').val("");
            $('#machine_voucher_jackhammer_id').trigger('change');
            return false;
        }

        /*if(creditAccountId) {
            $('#machine_voucher_credit_account_id, #machine_voucher_debit_account_id').not($('#machine_voucher_credit_account_id'))
            .children('option[value=' + creditAccountId + ']')
            .attr('disabled', true)
            .siblings().removeAttr('disabled');
        } else {
            $('#machine_voucher_credit_account_id, #machine_voucher_debit_account_id').not($('#machine_voucher_credit_account_id'))
            .children('option[value=""]')
            .siblings().removeAttr('disabled');
        }*/

        //reinitializing select2 elements for resetting disabled options
        initializeSelect2();

        $('#machine_voucher_credit_account_name').val('');
        if(creditAccountId) {
            $.ajax({
                url: "/get/details/by/account/" + creditAccountId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var name  = result.name;
                        
                        $('#machine_voucher_credit_account_name').val(name);
                    } else {
                        $('#machine_voucher_credit_account_name').val('');
                    }
                },
                error: function () {
                    $('#machine_voucher_credit_account_name').val('');
                }
            });
        } else {
            $('#machine_voucher_credit_account_name').val('');
        }
    });

    //select name for the selected account
    $('body').on("change", "#machine_voucher_debit_account_id", function () {
        var debitAccountId = $('#machine_voucher_debit_account_id').val();

        $('#machine_voucher_excavator_id').val('');
        $('#machine_voucher_jackhammer_id').val('');
        $('#machine_voucher_excavator_id').trigger('change');
        $('#machine_voucher_jackhammer_id').trigger('change');

        if(debitAccountId) {
            $('#machine_voucher_debit_account_id, #machine_voucher_credit_account_id').not($('#machine_voucher_debit_account_id'))
            .children('option[value=' + debitAccountId + ']')
            .attr('disabled', true)
            .siblings().removeAttr('disabled');

            $('#machine_voucher_debit_account_id, #machine_voucher_excavator_id').not($('#machine_voucher_debit_account_id'))
            .children('option[data-excavator-contractor-account-id=' + debitAccountId + ']')
            .attr('disabled', true)
            .siblings().removeAttr('disabled');

            $('#machine_voucher_debit_account_id, #machine_voucher_jackhammer_id').not($('#machine_voucher_debit_account_id'))
            .children('option[data-jackhammer-contractor-account-id=' + debitAccountId + ']')
            .attr('disabled', true)
            .siblings().removeAttr('disabled');
        } else {
            $('#machine_voucher_debit_account_id, #machine_voucher_credit_account_id').not($('#machine_voucher_debit_account_id'))
            .children('option[value=""]')
            .siblings().removeAttr('disabled');

            $('#machine_voucher_debit_account_id, #machine_voucher_excavator_id').not($('#machine_voucher_debit_account_id'))
            .children('option[data-excavator-contractor-account-id=""]')
            .siblings().removeAttr('disabled');

            $('#machine_voucher_debit_account_id, #machine_voucher_jackhammer_id').not($('#machine_voucher_debit_account_id'))
            .children('option[data-jackhammer-contractor-account-id=""]')
            .siblings().removeAttr('disabled');
        }

        //reinitializing select2 elements for resetting disabled options
        initializeSelect2();

        $(".machine_voucher_machine_id").select2({
            minimumResultsForSearch: 5
        });

        $('#machine_voucher_debit_account_name').val('');
        if(debitAccountId) {
            $.ajax({
                url: "/get/details/by/account/" + debitAccountId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        var name  = result.name;
                        
                        $('#machine_voucher_debit_account_name').val(name);
                    } else {
                        $('#machine_voucher_debit_account_name').val('');
                    }
                },
                error: function () {
                    $('#machine_voucher_debit_account_name').val('');
                }
            });
        } else {
            $('#machine_voucher_debit_account_name').val('');
        }
    });

    $('body').on("submit", "#machine_voucher_form", function () {
        $('#machine_voucher_credit_account_id').prop('disabled', false);
    });
});
// timepicker value updation
function updateTimepicker() {
    currentDate     = new Date();
    currentHour     = currentDate.getHours();
    currentMinute   = currentDate.getMinutes();

    if(currentHour < 10) {
        currentHour = '0' + currentHour;
    }
    if(currentMinute < 10) {
        currentMinute = '0' + currentMinute;
    }

    currentTime = currentHour + ':' + currentMinute;
    $(".timepicker").val(currentTime);  
}

//Initialize Select2 Element for account select box
function initializeSelect2() {
    $(".account_select").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return accountRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
}