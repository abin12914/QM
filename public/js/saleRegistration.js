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
        } else if(elementId == 'vehicle_number_cash') {
            $('#quantity_cash').val(volume);
        } else {
            $('.quantity').val('');
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
        } else if(elementId == 'product_cash') {
            $('#rate_cash').val(ratePerFeet);
        } else {
            $('.quantity').val('');
        }
    });

    //show cash payment options for cash transaction only
    $('body').on("change", ".measure_type", function () {
        if($('#measure_type_weighment_credit').is(':checked')) {
            $('#measure_volume_details').hide();
        } else {
            $('#measure_volume_details').show();
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
            $('.modal_balance_label').html('Over :');
            $('.modal_debit_credit').html('debited to');
        } else if(payment < totalCredit) {
            $('#modal_warning').show();
            $('.modal_balance_label').html('Balance amount :');
            $('.modal_debit_credit').html('credited from');
        } else {
            $('#modal_warning').hide();
        }

        $('#payment_with_sale_modal').modal('show');
    });

    //invoke modal for cash transactions
    $('body').on("click", "#btn_cash_sale_modal_submit", function (e) {
        e.preventDefault();
        $("#cash_sale_form").submit();
    });

    $('body').on("keyup", "#quantity", function () {
        //updateBillDetail();
    });
    $('body').on("keyup", "#rate", function () {
        //updateBillDetail();
    });
    $('body').on("keyup", "#discount", function () {
        //updateBillDetail();
    });
});
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

//update bill details
function updateBillDetail() {
    var quantity    = ($('#quantity').val() > 0 ? $('#quantity').val() : 0 );
    var rate        = ($('#rate').val() > 0 ? $('#rate').val() : 0 );
    var discount    = ($('#discount').val() > 0 ? $('#discount').val() : 0 );
    var amount, total = 0;

    amount  = quantity * rate;
    if(amount >=0) {
        if((amount/2) > discount) {
            total   = amount - discount;
        } else if(discount > 0){
            alert("Error !!\nDiscount amount exceeded the limit. Maxium of 50% discount is allowed!");
            $('#discount').val('');
            total   = amount;
        }
    } else {
        total   = 0;
    }
    $('#amount').val(amount);
    $('#total').val(total);
}