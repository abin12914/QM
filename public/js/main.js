$(function () {
    var today = new Date();
    //new vehicle registration link
    vehicleRegistrationLink = "No results found. <a href='/vehicle/register'>Register as new truck</a>";

    //Date picker
    $('#datepicker').datepicker({
        todayHighlight: true,
        startDate: today,
        format: 'dd/mm/yyyy',
        autoclose: true,
    });

    //setting current date as selected
    $('#datepicker').datepicker('setDate', today);

    //hide flash messages
    dismissAlert();

    //Initialize Select2 Elements
    $(".select2").select2({
        language: {
             noResults: function() {
                return vehicleRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Timepicker
    $(".timepicker").timepicker({
        minuteStep : 5,
        showInputs: false
    });

    $('body').on("change", "#financial_status", function () {
        financialStatus = this.value;
        if(financialStatus == 'none') {
            $('#opening_balance').val('0');
            $('#opening_balance').prop("readonly",true);
        } else {
            $('#opening_balance').val('');
            $('#opening_balance').prop("readonly",false);
        }
    });

    $('body').on("change", "#employee_type", function () {
        employeeType = this.value;
        if(employeeType == 'staff') {
            $('#daily_wage_div').hide();
            $('#wage').val('0');
            $('#wage').prop("disabled",true);

            $('#salary_div').show();
            $('#salary').val('');
            $('#salary').prop("disabled",false);
        } else if(employeeType == 'labour') {
            $('#salary_div').hide();
            $('#salary').val('0');
            $('#salary').prop("disabled",true);

            $('#daily_wage_div').show();
            $('#wage').val('');
            $('#wage').prop("disabled",false);
        }
    });

    $('body').on("change", "#account_type", function () {
        accountType = this.value;
        if(accountType != 'personal') {
            $('#real_account_flag_message').show();
            $('#personal_account_details').hide();
            $('#name').val("This organization");
            $('#phone').val("0000000000");
            $('#address').val("Address of this organization");
            $('#relation_type').val("other").change();
            $('#name').prop('disabled',true);
            $('#phone').prop('disabled',true);
            $('#address').prop('disabled',true);
            $('#relation_type').prop('disabled',true);
        } else {
            $('#real_account_flag_message').hide();
            $('#personal_account_details').show();
            $('#name').val("");
            $('#phone').val("");
            $('#address').val("");
            $('#relation_type').val("").change();
            $('#name').prop('disabled',false);
            $('#phone').prop('disabled',false);
            $('#address').prop('disabled',false);
            $('#relation_type').prop('disabled',false);
        }
    });
    
    // for checking if the pressed key is a number
    $('body').on("keypress", ".number_only", function (evt) {
        var fieldValue = this.value;
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if(this.id == 'phone') {
            if(fieldValue.length == 0 && charCode == 43) {
                return true;
            }
            if(fieldValue.length >= 13) {
                evt.preventDefault();
                $(this).data("title", "Phone number must be between 10 and 13 digits!").tooltip("show");
                return false;
            }
        }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            evt.preventDefault();
            $(this).data("title", "Only numbers are allowed!").tooltip("show");
            return false;
        }
        $(this).data("title", "").tooltip("destroy");
        return true;
    });

    // for checking if the pressed key is a number or decimal
    $('body').on("keypress", ".decimal_number_only", function (evt) {
        // attaching 1 to the end for number like 1.0
        var fieldValue = this.value+'1';
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57))) {
            evt.preventDefault();
            $(this).data("title", "Only numbers are allowed!").tooltip("show");
            return false;
        }
        if(charCode == 46 && (fieldValue % 1 != 0)) {
            evt.preventDefault();
            $(this).data("title", "Only numbers and decimal point are allowed!").tooltip("show");
            return false;
        }

        $(this).data("title", "").tooltip("destroy");
        return true;
    });

    //append to another textbox
    $('body').on("keyup", ".number_only", function (evt) {
        var fieldValue  = this.value;
        //append to another textbox
        appendRegistrationNumber();

        if(this.id == 'vehicle_reg_number_region_code') {
            if(fieldValue.length >=2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                //$(this).data("title", "Maximum two digits are allowed for regional code").tooltip("show");
                $('#vehicle_reg_number_unique_alphabet').focus();
                return false;
            }
        } else if(this.id == 'vehicle_reg_number_unique_digit') {
            if(fieldValue.length >=4 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                $(this).data("title", "Maximum four digits are allowed in this section").tooltip("show");
                return false;
            }
        }
    });

    // for checking if the pressed key is a alphabet
    $('body').on("keypress", ".alpha_only", function (evt) {
        var fieldValue = this.value;
        var charCode = (evt.which) ? evt.which : event.keyCode;

        if (!((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122))) {
            evt.preventDefault();
            $(this).data("title", "Only alphabets are allowed!").tooltip("show");
            return false;
        }
        $(this).data("title", "").tooltip("destroy");
        this.value = (this.value).toUpperCase();
        return true;
    });

    //convert to uppper case and append to another textbox
    $('body').on("keyup", ".alpha_only", function (evt) {
        this.value      = (this.value).toUpperCase();
        var fieldValue  = this.value;
        //append to another textbox
        appendRegistrationNumber();

        if(this.id == 'vehicle_reg_number_state_code') {
            if(fieldValue.length >= 2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                //$(this).data("title", "Maximum two characters are allowed for state code").tooltip("show");
                evt.preventDefault();
                $('#vehicle_reg_number_region_code').focus();
                return false;
            }
        } else if(this.id == 'vehicle_reg_number_unique_alphabet') {
            if(fieldValue.length >= 2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                evt.preventDefault();
                //$(this).data("title", "Maximum two characters are allowed in this section").tooltip("show");
                $('#vehicle_reg_number_unique_digit').focus();
                return false;
            }
        }
    });

    //convert to uppper case
    $('body').on("change", ".alpha_only", function (evt) {
        this.value = (this.value).toUpperCase();
    });

    //convert to uppper case and append to another textbox
    $('body').on("change", "#vehicle_reg_number_region_code", function (evt) {
        if((this.value).length == 1 && this.value != 0) {
            this.value = '0' + this.value;
            //append to another textbox
            appendRegistrationNumber();
        } else if(this.value == 0) {
            evt.preventDefault();
            $(this).data("title", "Invalid region code!").tooltip("show");;
            $(this).focus();
            $(this).trigger('mouseenter');
            return false;
        }
    });
});
function dismissAlert() {
	$("#alert-message").fadeTo(5000, 500).slideUp(500, function(){
        $("#alert-message").slideUp(500);
    });
}
function appendRegistrationNumber() {
    var stateCode   = $('#vehicle_reg_number_state_code').val();
    var regionCode  = $('#vehicle_reg_number_region_code').val();
    var alphaCode   = $('#vehicle_reg_number_unique_alphabet').val();
    var numerisCode = $('#vehicle_reg_number_unique_digit').val();

    if(alphaCode) {
        var registrationNumber = stateCode + '-' + regionCode + ' ' + alphaCode + '-' + numerisCode;
    } else {
        var registrationNumber = stateCode + '-' + regionCode + ' ' + numerisCode;
    }
    $('#vehicle_reg_number').val(registrationNumber);
}