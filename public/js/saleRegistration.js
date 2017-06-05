$(function () {
    var today = new Date();
    //new vehicle registration link for select2
    vehicleRegistrationLink = "No results found. <a href='/vehicle/register'>Register new truck</a>";
    //new account registration link for select2
    accountRegistrationLink = "No results found. <a href='/account/register'>Register new account</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        startDate: today,
        format: 'dd/mm/yyyy',
        autoclose: true,
    });

    //setting current date as selected
    $('.datepicker').datepicker('setDate', today);

    //Timepicker
    $(".timepicker").timepicker({
        minuteStep : 1,
        showInputs : false,
        showMeridian : false
    });

    // update timepicker value
    setInterval(function() { updateTimepicker() }, 60000);

    //Initialize Select2 Element for vehicler number select box
    $(".vehicle_number").select2({
        language: {
             noResults: function() {
                return vehicleRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for vehicler number select box
    $(".purchaser").select2({
        language: {
             noResults: function() {
                return accountRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for vehicler number select box
    $(".product").select2();

    //select default values for the selected vehicle based on last sale
    $('body').on("change", ".vehicle_number", function () {
        var elementId = $(this).attr('id');
        var vehicleId = $('#'+elementId).val();
        var volume    = $('#'+elementId).find(':selected').data('volume');
        var bodyType  = $('#'+elementId).find(':selected').data('bodytype');

        switch(bodyType) {
            case 'level':
                bodyType = 'Level';
                break;
            case 'extra-1':
                bodyType = 'Extended';
                break;
            case 'extra-2':
                bodyType = 'Extra Extended';
                break;
            default :
                bodyType = 'Body Type : Unknown';
                break;
        }

        if(elementId == 'vehicle_number_credit') {
            $('#quantity_credit').val(volume);
            updateCreditBillDetail();
        } else if(elementId == 'vehicle_number_cash') {
            $('#quantity_cash').val(volume);
            updateCashBillDetail();
        } else {
            $('.quantity').val('');
            updateCreditBillDetail();
            updateCashBillDetail();
        }
        
        if(vehicleId) {
            $.ajax({
                url: "/sales/get/last/vehicle/" + vehicleId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        productId           = result.productId;
                        purchaserAccountId  = result.purchaserAccountId;
                        measureType         = result.measureType;

                        if(elementId == 'vehicle_number_credit') {
                            $('#product_credit').val(productId);
                            $('#purchaser_credit').val(purchaserAccountId);

                            if(measureType == 1) {
                                $('#measure_type_volume_credit').prop('checked',true);
                            } else {
                                $('#measure_type_weighment_credit').prop('checked',true);
                            }
                        } else if(elementId == 'vehicle_number_cash') {
                            $('#product_cash').val(productId);
                            $('#purchaser_cash').val(purchaserAccountId);
                        } else {
                            $('#measure_type_volume_credit').prop('checked',true);
                            $('.product').val('');
                            $('.purchaser').val('');
                        }
                    } else {
                        $('#measure_type_volume_credit').prop('checked',true);
                        $('.product').val('');
                        $('.purchaser').val('');
                    }

                    $('.purchaser').trigger('change');
                    $('.product').trigger('change');
                    $('.measure_type').trigger('change');
                },
                error: function () {
                    $('#measure_type_volume_credit').prop('checked',true);
                    $('.product').val('');
                    $('.purchaser').val('');

                    $('.purchaser').trigger('change');
                    $('.product').trigger('change');
                    $('.measure_type').trigger('change');
                }
            });
        }
    });

    //set rate for the selected product
    $('body').on("change", ".product", function () {
        var elementId   = $(this).attr('id');
        var ratePerFeet = $('#'+elementId).find(':selected').data('rate-feet');

        if(elementId == 'product_credit') {
            $('#rate_credit').val(ratePerFeet);
            updateCreditBillDetail();
        } else if(elementId == 'product_cash') {
            $('#rate_cash').val(ratePerFeet);
            updateCashBillDetail();
        } else {
            $('.rate').val('');
            updateCreditBillDetail();
            updateCashBillDetail();
        }
    });

    //show cash payment options for cash transaction only
    $('body').on("change", ".measure_type", function () {
        if($('#measure_type_weighment_credit').is(':checked')) {
            $('#quantity_credit').prop('disabled',true);
            $('#rate_credit').prop('disabled',true);
            $('#bill_amount_credit').prop('disabled',true);
            $('#discount_credit').prop('disabled',true);
            $('#deducted_total_credit').prop('disabled',true);
            $('#measure_volume_details').hide();
        } else {
            $('#measure_volume_details').show();
            $('#quantity_credit').prop('disabled',false);
            $('#rate_credit').prop('disabled',false);
            $('#bill_amount_credit').prop('disabled',false);
            $('#discount_credit').prop('disabled',false);
            $('#deducted_total_credit').prop('disabled',false);
        }
    });

    //invoke modal for cash transactions
    $('body').on("click", "#submit_button", function (e) {
        e.preventDefault();
        var totalCredit = $('#total').val();
        var payment     = $('#paid_amount').val();
        var balance     = $('#balance').val();
        if(!totalCredit || !payment || !balance) {
            alert('Fill all fields');
            return false;
        }
        $('#modal_total_credit_amount').html(totalCredit);
        $('#modal_payment').html(payment);
        $('#modal_balance').html(balance);

        if(payment > totalCredit) {
            $('#modal_warning').show();
            $('.modal_balance_over').html('Advance Amount<p class="pull-right">:</p>');
        } else if(payment < totalCredit) {
            $('#modal_warning').show();
            $('.modal_balance_over').html('Balance Amount<p class="pull-right">:</p>');
        } else {
            $('#modal_warning').hide();
        }

        $('#payment_with_sale_modal').modal('show');
    });

    //show warning details on modal
    $('body').on("click", "#modal_warning_more_button", function (e) {
        if($('#modal_warning_more_button').hasClass('fa-chevron-down')) {
            $('#modal_warning_more_button').removeClass('fa-chevron-down');
            $('#modal_warning_more_button').addClass('fa-chevron-up');
            $('#modal_warning_more').show();
        } else {
            $('#modal_warning_more_button').removeClass('fa-chevron-up');
            $('#modal_warning_more_button').addClass('fa-chevron-down');
            $('#modal_warning_more').hide();
        }
    });

    //invoke modal for cash transactions
    $('body').on("click", "#btn_cash_sale_modal_submit", function (e) {
        e.preventDefault();
        $("#cash_sale_form").submit();
    });

    $('body').on("keyup", ".quantity", function () {
        var elementId   = $(this).attr('id');
        if(elementId == 'quantity_credit') {
            updateCreditBillDetail();
        } else {
            updateCashBillDetail();
        }
    });

    $('body').on("keyup", ".rate", function () {
        var elementId   = $(this).attr('id');
        if(elementId == 'rate_credit') {
            updateCreditBillDetail();
        } else {
            updateCashBillDetail();
        }
    });

    $('body').on("keyup", ".discount", function () {
        var elementId   = $(this).attr('id');
        if(elementId == 'discount_credit') {
            updateCreditBillDetail();
        } else {
            updateCashBillDetail();
        }
    });

    $('body').on("keyup", "#paid_amount", function () {
        updateCashBillDetail();
    });
});
//update credit bill details fields
function updateCreditBillDetail() {
    var quantity    = ($('#quantity_credit').val() > 0 ? $('#quantity_credit').val() : 0 );
    var rate        = ($('#rate_credit').val() > 0 ? $('#rate_credit').val() : 0 );
    var discount    = ($('#discount_credit').val() > 0 ? $('#discount_credit').val() : 0 );
    var billAmount, deductedTotal = 0;

    billAmount  = quantity * rate;
    if(billAmount >=0) {
        if((billAmount/2) > discount) {
            deductedTotal   = billAmount - discount;
        } else if(discount > 0){
            alert("Error !!\nDiscount amount exceeded the limit. Maxium of 50% discount is allowed!");
            $('#discount_credit').val('');
            deductedTotal   = billAmount;
        }
    } else {
        deductedTotal   = 0;
    }
    if(!($('#discount_credit').val())) {
        $('#discount_credit').val(0);
    } else {
        //for removing the preceding zero
        discount = discount * 1;
        $('#discount_credit').val(discount);
    }
    $('#bill_amount_credit').val(billAmount);
    $('#deducted_total_credit').val(deductedTotal);
}

//update cash bill details fields
function updateCashBillDetail() {
    var quantity    = ($('#quantity_cash').val() > 0 ? $('#quantity_cash').val() : 0 );
    var rate        = ($('#rate_cash').val() > 0 ? $('#rate_cash').val() : 0 );
    var discount    = ($('#discount_cash').val() > 0 ? $('#discount_cash').val() : 0 );
    var oldBalance  = ($('#old_balance').val() > 0 ? $('#old_balance').val() : 0 );
    var paidAmount  = ($('#paid_amount').val() > 0 ? $('#paid_amount').val() : 0 );
    var billAmount, deductedTotal = 0, total, balance;

    billAmount  = quantity * rate;
    if(billAmount >=0) {
        if((billAmount/2) > discount) {
            deductedTotal   = billAmount - discount;
        } else if(discount > 0){
            alert("Error !!\nDiscount amount exceeded the limit. Maxium of 50% discount is allowed!");
            $('#discount_cash').val('');
            deductedTotal   = billAmount;
        }
    } else {
        deductedTotal   = 0;
    }
    if(!($('#discount_cash').val())) {
        $('#discount_cash').val(0);
    } else {
        //for removing the preceding zero
        discount = discount * 1;
        $('#discount_cash').val(discount);
    }
    
    total   = deductedTotal + oldBalance;
    balance = total - paidAmount;

    if(!($('#paid_amount').val())) {
        $('#paid_amount').val(0);
    } else {
        //for removing the preceding zero
        paidAmount = paidAmount * 1;
        $('#paid_amount').val(paidAmount);
    }

    if(!($('#old_balance').val())) {
        $('#old_balance').val(0);
    }

    $('#bill_amount_cash').val(billAmount);
    $('#deducted_total_cash').val(deductedTotal);
    $('#total').val(total);
    $('#balance').val(balance);
}

// timepicker value updation
function updateTimepicker() {
    currentDate     = new Date();
    currentHour     = currentDate.getHours();
    currentMinute   = currentDate.getMinutes();

    if(currentHour < 10) {
        currentHour = '0' + currentHour;
    }
    if(currentMinute < 10) {
        currentMinute = '0' + currentMinute;
    }

    currentTime = currentHour + ':' + currentMinute;
    $(".timepicker").val(currentTime);  
}