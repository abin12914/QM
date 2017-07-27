$(function () {
    //new account registration link for select2
    employeeRegistrationLink    = "No account found. <a href='/hr/employee/register'>Register new employee</a>";

    //Initialize Select2 Element for account type select box
    $("#type").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for employee select box
    $("#employee_id").select2({
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
});