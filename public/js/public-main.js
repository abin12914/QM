$(function () {
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    });
    dismissAlert();
});
function dismissAlert() {
	$("#alert-message").fadeTo(5000, 500).slideUp(500, function(){
        $("#alert-message").slideUp(500);
    });
}