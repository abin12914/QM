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
});
function dismissAlert() {
	$("#alert-message").fadeTo(5000, 500).slideUp(500, function(){
        $("#alert-message").slideUp(500);
    });
}