$(function () {
    //new account registration link for select2
    jackhammerRegistrationLink    = "No result found. <a href='/machine/jackhammer/register'>Register new jackhammer</a>";

    //Initialize Select2 Element for excavator select box
    $("#jackhammer_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return jackhammerRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for account select box
    $("#account_id").select2({
        minimumResultsForSearch: 5,
        language: {
             noResults: function() {
                return jackhammerRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
});