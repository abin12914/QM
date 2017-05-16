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
        if(accountType == 'real') {
            $('#personal_detail_div').hide();
            $('#name').prop("disabled",true);
            $('#phone').prop("disabled",true);
            $('#address').prop("disabled",true);
        } else {
            $('#personal_detail_div').show();
            $('#name').prop("disabled",false);
            $('#phone').prop("disabled",false);
            $('#address').prop("disabled",false);
        }
    });
    
    // for checking if the pressed key is a number
    $('body').on("keypress", ".number_only", function (evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            evt.preventDefault();
            return false;
        }
        return true;
    });

    // for checking if the pressed key is a number or decimal
    $('body').on("keypress", ".decimal_number_only", function (evt) {
        // attaching 1 to the end for number like 1.0
        var fieldValue = this.value+'1';
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57))) {
            return false;
        }
        if(charCode == 46 && (fieldValue % 1 != 0)) {
            return false;
        }
        return true;
    });
});
function dismissAlert() {
	$("#alert-message").fadeTo(5000, 500).slideUp(500, function(){
        $("#alert-message").slideUp(500);
    });
}