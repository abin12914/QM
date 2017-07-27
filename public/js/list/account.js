$(function () {
    //new account registration link for select2
    accountRegistrationLink    = "No account found. <a href='/account/register'>Register new account</a>";

    //Initialize Select2 Element for account type select box
    $("#type").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for account relation select box
    $("#relation").select2({
        minimumResultsForSearch: 5,
    });

    //Initialize Select2 Element for account select box
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
});