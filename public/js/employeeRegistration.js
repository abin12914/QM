$(function () {

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