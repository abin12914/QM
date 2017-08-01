$(function () {
    //Initialize Select2 Element for account type select box
    $("#account_type").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for relation select box
    $("#relation_type").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for financial status select box
    $("#financial_status").select2({
        minimumResultsForSearch: 5
    });
    //acccount type change event
    /*$('body').on("change", "#account_type", function () {
        accountType = $(this).val();
        if(accountType != 'personal') {
            $('#real_account_flag_message').show();
            $('#personal_account_details').hide();
            $('#name').val("This organization");
            $('#phone').val("0000000000");
            $('#address').val("Address of this organization");
            $('#relation_type').val("other").change();
            $('#name').prop('disabled',true);
            $('#phone').prop('disabled',true);
            $('#address').prop('disabled',true);
            $('#relation_type').prop('disabled',true);
        } else {
            $('#real_account_flag_message').hide();
            $('#personal_account_details').show();
            $('#name').val("");
            $('#phone').val("");
            $('#address').val("");
            $('#relation_type').val("").change();
            $('#name').prop('disabled',false);
            $('#phone').prop('disabled',false);
            $('#address').prop('disabled',false);
            $('#relation_type').prop('disabled',false);
        }
    });*/
});