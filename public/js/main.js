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
});
function dismissAlert() {
	$("#alert-message").fadeTo(5000, 500).slideUp(500, function(){
        $("#alert-message").slideUp(500);
    });
}