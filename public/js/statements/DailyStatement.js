$(function () {

    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //update start date based on from date selection - employee
    $('body').on("change", "#from_date", function () {
        var fromDate = $('#from_date').val();
        
        if(fromDate) {
            $('#to_date').val('');
            $('#to_date').datepicker('setStartDate', fromDate);
        } else {
           $('#to_date').datepicker('setStartDate', '');
        }
    });
});