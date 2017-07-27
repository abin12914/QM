$(function () {
    //new account registration link for select2
    vehicleTypeRegistrationLink    = "No result found. <a href='/vehicle-type/register'>Register new truck type</a>";

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