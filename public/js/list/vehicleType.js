$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    
    //new account registration link for select2
    vehicleTypeRegistrationLink    = "No result found. <a href='/vehicle-type/register'>Register new truck type</a>";

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

    //Initialize Select2 Element for product select box
    $("#product_id").select2({
        minimumResultsForSearch: 5,
    });

    //Initialize Select2 Element for vehicle type select box
    $("#vehicle_type_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return vehicleTypeRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
});