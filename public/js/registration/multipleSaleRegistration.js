$(function () {
    var datepickerenddate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);//
    datepickerenddate = datepickerenddate.getDate()+'-'+(datepickerenddate.getMonth()+1)+'-'+datepickerenddate.getFullYear();
    //new vehicle registration link for select2
    vehicleRegistrationLink = "No results found. <a href='/vehicle/register'>Register new truck</a>";
    //new account registration link for select2
    accountRegistrationLink = "No results found. <a href='/account/register'>Register new account</a>";
    
    //Date picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        //startDate: today,
        endDate: datepickerenddate,
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Timepicker
    $(".timepicker").timepicker({
        minuteStep : 1,
        showInputs : false,
        showMeridian : false
    });

    //setting current date as selected
    $('.datepicker').datepicker('setDate', new Date());

    //Initialize Select2 Element for vehicler number select box
    $("#vehicle_number_credit").select2({
        language: {
             noResults: function() {
                return vehicleRegistrationLink;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    //Initialize Select2 Element for purchaser select box
    $("#purchaser_credit").select2({
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
    $('body').on("change", "#vehicle_number_credit", function () {
        var vehicleId = $('#vehicle_number_credit').val();
        var bodyType  = $('#vehicle_number_credit').find(':selected').data('bodytype');

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
        
        if(vehicleId) {
            $.ajax({
                url: "/sales/get/last/vehicle/" + vehicleId,
                method: "get",
                success: function(result) {
                    if(result && result.flag) {
                        productId           = result.productId;
                        purchaserAccountId  = result.purchaserAccountId;
                        measureType         = result.measureType;
                        rate                = result.rate;

                        $('#product_credit').val(productId);
                        $('#purchaser_credit').val(purchaserAccountId);
                        if(measureType == 3) {
                            $('#rate_credit').val(rate);
                        }
                    } else {
                        $('#product_credit').val('');
                        $('#purchaser_credit').val('');
                    }

                    $('#product_credit').trigger('change');
                    $('#purchaser_credit').trigger('change');
                },
                error: function () {
                    $('#product_credit').val('');
                    $('#purchaser_credit').val('');

                    $('#purchaser_credit').trigger('change');
                    $('#product_credit').trigger('change');
                }
            });
            updateCreditBillDetail();
        }
    });

    $('body').on("keyup", "#quantity_credit", function () {
        updateCreditBillDetail();
    });

    $('body').on("keyup", "#rate_credit", function () {
        updateCreditBillDetail();
    });
});
//update credit bill details fields
function updateCreditBillDetail() {
    var quantity    = ($('#quantity_credit').val() > 0 ? $('#quantity_credit').val() : 0 );
    var rate        = ($('#rate_credit').val() > 0 ? $('#rate_credit').val() : 0 );

    billAmount  = quantity * rate;

    $('#bill_amount_credit').val(billAmount);
}