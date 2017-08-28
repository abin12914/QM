$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    //new account registration link for select2
    accountRegistrationLink = "No results found. <a href='/account/register'>Register new account</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        //startDate: today,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //setting current date as selected
    $('.datepicker').datepicker('setDate', new Date());

    //Timepicker
    $(".timepicker").timepicker({
        minuteStep : 1,
        showInputs : false,
        showMeridian : false
    });

    // update timepicker value
    setInterval(function() { updateTimepicker() }, 60000);

    //Initialize Select2 Element for vehicler number select box
    $(".supplier").select2({
        language: {
             noResults: function() {
                return accountRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for vehicler number select box
    $(".product").select2();

    //show quantity options for explosive purchase only
    $('body').on("change", "#product", function () {
        var productId = $('#product').val();
        if(productId == 2) {
            $('#explosive_quantity_cap').prop('disabled',false);
            $('#explosive_quantity_gel').prop('disabled',false);
            $('#explosive_quantity_div').show();
        } else {
            $('#explosive_quantity_cap').prop('disabled',true);
            $('#explosive_quantity_gel').prop('disabled',true);
            $('#explosive_quantity_div').hide();
        }
    });

    //show supplier options for cash transaction only
    $('body').on("change", ".transaction_type", function () {
        if($('#transaction_type_cash').is(':checked')) {
            $('#supplier').prop('disabled',true);
            $('#supplier_div').hide();
        } else {
            $('#supplier_div').show();
            $('#supplier').prop('disabled',false);
        }
    });
});

// timepicker value updation
function updateTimepicker() {
    currentDate     = new Date();
    currentHour     = currentDate.getHours();
    currentMinute   = currentDate.getMinutes();

    if(currentHour < 10) {
        currentHour = '0' + currentHour;
    }
    if(currentMinute < 10) {
        currentMinute = '0' + currentMinute;
    }

    currentTime = currentHour + ':' + currentMinute;
    $(".timepicker").val(currentTime);  
}