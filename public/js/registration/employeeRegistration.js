$(function () {

    //Initialize Select2 Element for employee type select box
    $("#employee_type").select2({
        minimumResultsForSearch: 4
    });

    //Initialize Select2 Element for financial status select box
    $("#financial_status").select2({
        minimumResultsForSearch: 5
    });

    $('body').on("change", "#employee_type", function () {
        employeeType = $(this).val();
        if(employeeType == 'staff') {
            $('#daily_wage_div').hide();
            $('#wage').val('0');
            $('#wage').prop("disabled",true);

            $('#salary_div').show();
            $('#salary').val('');
            $('#salary').prop("disabled",false);
        } else if(employeeType == 'labour') {
            $('#salary_div').hide();
            $('#salary').val('0');
            $('#salary').prop("disabled",true);

            $('#daily_wage_div').show();
            $('#wage').val('');
            $('#wage').prop("disabled",false);
        }
    });
});