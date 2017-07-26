$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    selectTriggerFlag = 0;

    //new employee registration link for select2
    accountRegistrationLink    = "No account found. <a href='/account/register'>Register new account</a>";

    //new vehicle registration link for select2
    vehicleRegistrationLink    = "No truck found. <a href='/vehicle/register'>Register new truck</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        //startDate: today,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Initialize Select2 Element for purchaser account select box
    $("#account_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return accountRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for vehicle select box
    $("#vehicle_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return vehicleRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for product select box
    $("#product_id").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for vehicle type select box
    $("#vehicle_type_id").select2({
        minimumResultsForSearch: 5
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