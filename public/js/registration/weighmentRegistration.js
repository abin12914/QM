$(function () {
    updateBillDetail();
    $('body').on("keyup", "#quantity", function () {
        updateBillDetail();
    });

    $('body').on("keyup", "#rate", function () {
        updateBillDetail();
    });

    $('body').on("keyup", "#discount", function () {
        updateBillDetail();
    });
});
//update credit bill details fields
function updateBillDetail() {
    var quantity    = ($('#quantity').val() > 0 ? $('#quantity').val() : 0 );
    var rate        = ($('#rate').val() > 0 ? $('#rate').val() : 0 );
    var discount    = ($('#discount').val() > 0 ? $('#discount').val() : 0 );
    var billAmount, deductedTotal = 0;

    billAmount  = quantity * rate;
    if(billAmount >=0) {
        if((billAmount/2) > discount) {
            deductedTotal   = billAmount - discount;
        } else if(discount > 0){
            alert("Error !!\nDiscount amount exceeded the limit. Maxium of 50% discount is allowed!");
            $('#discount').val('');
            deductedTotal   = billAmount;
        }
    } else {
        deductedTotal   = 0;
    }
    if(!($('#discount').val())) {
        $('#discount').val(0);
    } else {
        //for removing the preceding zero
        discount = discount * 1;
        $('#discount').val(discount);
    }
    $('#bill_amount').val(billAmount);
    $('#deducted_total').val(deductedTotal);
}