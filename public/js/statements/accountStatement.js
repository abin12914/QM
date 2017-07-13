$(function () {

    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();

    //new account registration link for select2
    accountRegistrationLink    = "No accounts found. <a href='/account/register'>Register new account</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Initialize Select2 Element for account select box
    $("#account_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return employeeRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
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