$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    selectTriggerFlag = 0;
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        //startDate: today,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Initialize Select2 Element for account select box
    $("#account_id").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for vehicle select box
    $("#vehicle_id").select2({
        minimumResultsForSearch: 5,
    });

    //update dates based on from date selection
    $('body').on("change", "#from_date", function () {
        var startDate = $('#from_date').val();
        
        if(startDate) {
            $('#to_date').datepicker('setStartDate', startDate);
        } else {
           $('#to_date').datepicker('setStartDate', '');
        }
    });
});