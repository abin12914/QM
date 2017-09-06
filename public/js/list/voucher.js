$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    selectTriggerFlag = 0;

    //new account registration link for select2
    accountRegistrationLink    = "No account found. <a href='/account/register'>Register new account</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        //startDate: today,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Initialize Select2 Element for employee name select box
    $("#transaction_type").select2({
        minimumResultsForSearch: 4
    });

    //Initialize Select2 Element for cash voucher account select box
    $(".account_id").select2({
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

    //Initialize Select2 Element for cash voucher account select box
    $(".machine").select2({
        minimumResultsForSearch: 5
    });

    //update dates based on from date selection
    $('body').on("change", "#credit_voucher_from_date", function () {
        var startDate = $('#credit_voucher_from_date').val();
        
        if(startDate) {
            $('#credit_voucher_to_date').datepicker('setStartDate', startDate);
        } else {
           $('#credit_voucher_to_date').datepicker('setStartDate', '');
        }
    });

    //update dates based on from date selection
    $('body').on("change", "#cash_voucher_from_date", function () {
        var startDate = $('#cash_voucher_from_date').val();
        
        if(startDate) {
            $('#cash_voucher_to_date').datepicker('setStartDate', startDate);
        } else {
           $('#cash_voucher_to_date').datepicker('setStartDate', '');
        }
    });

    $('body').on("change", "#machine_voucher_excavator_id", function () {
        excavator_id = $('#machine_voucher_excavator_id').val();
        
        if(excavator_id) {
            $('#machine_voucher_jackhammer_id').val('');
            $('#machine_voucher_jackhammer_id').trigger('change');
        }
    });

    $('body').on("change", "#machine_voucher_jackhammer_id", function () {
        excavator_id = $('#machine_voucher_jackhammer_id').val();
        
        if(excavator_id) {
            $('#machine_voucher_excavator_id').val('');
            $('#machine_voucher_excavator_id').trigger('change');
        }
    });
});