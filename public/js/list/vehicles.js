$(function () {
    //new account registration link for select2
    vehicleRegistrationLink    = "No result found. <a href='/vehicle/register'>Register new vehicle</a>";

    //Initialize Select2 Element for vehicle type select box
    $("#vehicle_type_id").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for body type select box
    $("#body_type").select2({
        minimumResultsForSearch: 5,
    });

    //Initialize Select2 Element for account select box
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
});