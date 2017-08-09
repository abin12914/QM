$(function () {

    var today = new Date();
    //Date picker
    $('#datepicker').datepicker({
        todayHighlight: true,
        startDate: today,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Initialize Select2 Element for user role select box
    $("#role").select2({
        minimumResultsForSearch: 5
    });
});