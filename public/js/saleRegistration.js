$(function () {
    var today = new Date();
    //new vehicle registration link for select2
    vehicleRegistrationLink = "No results found. <a href='/vehicle/register'>Register new truck</a>";
    //new account registration link for select2
    accountRegistrationLink = "No results found. <a href='/account/register'>Register new account</a>";
    
    //Date picker
    $('#datepicker').datepicker({
        todayHighlight: true,
        startDate: today,
        format: 'dd/mm/yyyy',
        autoclose: true,
    });

    //setting current date as selected
    $('#datepicker').datepicker('setDate', today);

    //Timepicker
    $(".timepicker").timepicker({
        minuteStep : 1,
        showInputs : false,
        showMeridian : false
    });

    // update timepicker value
    setInterval(function() { updateTimepicker() }, 60000);

    

    //Initialize Select2 Element for vehicler number select box
    $("#vehicle_number").select2({
        language: {
             noResults: function() {
                return vehicleRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for vehicler number select box
    $("#purchaser").select2({
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
    $("#product").select2();
});
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
    $("#time").val(currentTime);  
}