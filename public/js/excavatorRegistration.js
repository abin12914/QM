$(function () {

    $('body').on("change", "#rent_type", function () {
        rentType = $(this).val();
        if(rentType == 'monthly') {
            $('#rent_type_hour_div').hide();
            $('#rent_type_month_div').show();
            $('#rate_bucket').val(0);
            $('#rate_breaker').val(0);
        } else {
            $('#rent_type_month_div').hide();
            $('#rent_type_hour_div').show();
            $('#rate_monthly').val(0);
        }
    });
});