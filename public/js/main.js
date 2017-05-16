$(function () {
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
    dismissAlert();

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
            $('#wage').prop("disabled",true);

            $('#salary_div').show();
            $('#salary').prop("disabled",false);
        } else if(employeeType == 'labour') {
            $('#salary_div').hide();
            $('#salary').prop("disabled",true);

            $('#daily_wage_div').show();
            $('#wage').prop("disabled",false);
        }
    });

    $('body').on("change", "#account_type", function () {
        accountType = this.value;
        // account type 1 are real accounts
        if(accountType == 1) {
            $('#real_account_flag_message').show();
            $('#name').val("This organization");
            $('#phone').val("0000000000");
            $('#address').val("Address of this organization");
            $('#name').prop('readonly',true)
            $('#phone').prop('readonly',true)
            $('#address').prop('readonly',true)
        } else {
            $('#real_account_flag_message').hide();
            $('#name').val("");
            $('#phone').val("");
            $('#address').val("");
            $('#name').prop('readonly',false)
            $('#phone').prop('readonly',false)
            $('#address').prop('readonly',false)
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
            $(this).data("title", "Only numbers are allowed!").tooltip("show");
            return false;
        }
        $(this).data("title", "").tooltip("destroy");
        return true;
    });
});
function dismissAlert() {
	$("#alert-message").fadeTo(5000, 500).slideUp(500, function(){
        $("#alert-message").slideUp(500);
    });
}