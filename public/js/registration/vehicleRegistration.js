$(function () {

    //Initialize Select2 Element for vehicle type select box
    $("#vehicle_type").select2({
        minimumResultsForSearch: 5
    });

    //Initialize Select2 Element for body type select box
    $("#body_type").select2({
        minimumResultsForSearch: 5
    });

    //append to another textbox
    $('body').on("keyup", ".number_only", function (evt) {
        var fieldValue  = $(this).val();
        var elementId   = $(this).attr("id");

        if(fieldValue) {
            if(elementId == 'vehicle_reg_number_region_code') {
                if(fieldValue.length >=2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                    //$(this).data("original-title", "Maximum two digits are allowed for regional code").tooltip("show");
                    $('#vehicle_reg_number_unique_alphabet').focus();
                    if(fieldValue.length > 2) {
                        $('#vehicle_reg_number_region_code').val('');
                    }
                }
            } else if(elementId == 'vehicle_reg_number_unique_digit') {
                if(fieldValue.length >=4 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                    $("#vehicle_reg_number_unique_digit").data("title", "Maximum four digits are allowed in this section").tooltip("show");
                    if(fieldValue.length > 4) {
                        $('#vehicle_reg_number_unique_digit').val('');
                    }
                }
            }
            //append to another textbox
            appendRegistrationNumber();
        }
    });

    //convert to uppper case and append to another textbox
    $('body').on("keyup", ".alpha_only", function (evt) {
        var fieldValue  = $(this).val();
        var elementId   = $(this).attr("id");
        
        if(fieldValue) {
            fieldValue = fieldValue.toUpperCase();
            $(this).val(fieldValue);

            if(elementId == 'vehicle_reg_number_state_code') {
                if(fieldValue.length >= 2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                    //$(this).data("original-title", "Maximum two characters are allowed for state code").tooltip("show");
                    evt.preventDefault();
                    $('#vehicle_reg_number_region_code').focus();
                    if(fieldValue.length > 2) {
                        $('#vehicle_reg_number_state_code').val('');
                    }
                }
            } else if(elementId == 'vehicle_reg_number_unique_alphabet') {
                if(fieldValue.length >= 2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                    evt.preventDefault();
                    //$(this).data("original-title", "Maximum two characters are allowed in this section").tooltip("show");
                    $('#vehicle_reg_number_unique_digit').focus();
                    if(fieldValue.length > 2) {
                        $('#vehicle_reg_number_unique_alphabet').val('');
                    }
                }
            }

            //append to another textbox
            appendRegistrationNumber();
        }
    });

    //convert to uppper case
    $('body').on("change", ".alpha_only", function (evt) {
        var fieldValue  = $(this).val();
        if(fieldValue) {
            fieldValue = fieldValue.toUpperCase();
            $(this).val(fieldValue);
        }
    });

    //convert to uppper case and append to another textbox
    $('body').on("change", "#vehicle_reg_number_region_code", function (evt) {
        var fieldValue  = $("#vehicle_reg_number_region_code").val();
        if(fieldValue) {
            if(fieldValue.length == 1 && fieldValue != 0) {
                fieldValue = '0' + fieldValue;
                $("#vehicle_reg_number_region_code").val(fieldValue);
            } else if(fieldValue == 0) {
                evt.preventDefault();
                $("#vehicle_reg_number_region_code").data("title", "Invalid region code!").tooltip("show");;
                $("#vehicle_reg_number_region_code").focus();
                $("#vehicle_reg_number_region_code").trigger('mouseenter');
            }

            //append to another textbox
            appendRegistrationNumber();
        }
    });
});
function appendRegistrationNumber() {
    var stateCode   = $('#vehicle_reg_number_state_code').val();
    var regionCode  = $('#vehicle_reg_number_region_code').val();
    var alphaCode   = $('#vehicle_reg_number_unique_alphabet').val();
    var numerisCode = $('#vehicle_reg_number_unique_digit').val();

    if(alphaCode) {
        var registrationNumber = stateCode + '-' + regionCode + ' ' + alphaCode + '-' + numerisCode;
    } else {
        var registrationNumber = stateCode + '-' + regionCode + ' ' + numerisCode;
    }
    $('#vehicle_reg_number').val(registrationNumber);
}