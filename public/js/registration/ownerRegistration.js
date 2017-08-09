$(function () {
    var today = new Date();
    //Date picker
    $('#datepicker').datepicker({
        todayHighlight: true,
        startDate: today,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Initialize Select2 Element for financial status select box
    $("#financial_status").select2({
        minimumResultsForSearch: 5
    });

    $('body').on("click", "#royalty_owner", function () {
        if($('#royalty_owner').is(':checked')) {
            $('#royalty_owner').prop('checked',false);
            $('#royalty_owner_confirm_modal').modal('show');
        }
    });

    //execute modal action
    $('body').on("click", "#btn_royalty_owner_confirm_modal_confirm", function (e) {
        e.preventDefault();
        $('#royalty_owner').prop('checked',true);
    });

    //execute modal action
    $('body').on("click", "#btn_royalty_owner_confirm_modal_cancel", function (e) {
        e.preventDefault();
        $('#royalty_owner').prop('checked',false);
    });
});