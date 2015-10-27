$(function() {
    $("#batch_no").select2();
    $('#available_div').hide();
    $('#product').change(function() {
        $("#available").val('');

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-running-batches",
            data: {item_id: $('#product').val(), page: "adjustment"},
            dataType: 'html',
            success: function(data) {

                $('#batch_no').html(data);
            }
        });
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-product-batches",
            data: {item_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#product-unit').html(data);
            }
        });
    });

    $("#batch_no").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-get-batch-locations",
            data: {batch_id: $(this).val(), item_id: $('#product').val()},
            dataType: 'html',
            success: function(data) {
                $('#location_id').html(data);
            }
        });
    });

    $("#show_locations").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-get-product-locations",
            data: {product_id: $("#product").val()},
            dataType: 'html',
            success: function(data) {
                $('#location_id').html(data);
            }
        });
    });

    $('#quantity').priceFormat({
        prefix: '',
        thousandsSeparator: ',',
        suffix: '',
        centsLimit: 0,
        limit: 10
    });

    /*$('#quantity').keyup(function (e) {
     var ava_qty = $("#available").val();
     ava_qty = parseInt(ava_qty.replace(/,/g, ""), 10);
     
     var qty = $(this).val();
     qty = parseInt(qty.replace(/,/g, ""), 10);
     
     if (qty > ava_qty) {
     alert("Quantity should not be greater than " + ava_qty + ".");
     $(this).focus();
     }
     });*/

    /*$('#batch_search').submit(function (e) {
     var ava_qty = $("#available").val();
     ava_qty = parseInt(ava_qty.replace(/,/g, ""), 10);
     
     var qty = $('#quantity').val();
     qty = parseInt(qty.replace(/,/g, ""), 10);
     
     if (qty > ava_qty) {
     e.preventDefault();
     alert("Quantity should not be greater than " + ava_qty + ".");
     $('#quantity').focus();
     }
     });*/

    $('#batch_no').change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-running-batches",
            data: {number: $('#batch_no').val(), page: "adjustment"},
            dataType: 'html',
            success: function(data) {

                $('#available_div').fadeIn(1000);
                $('#itembatches').html(data);
            }
        });
    });

    $('#adjustment_date').datepicker({
        minDate: "-2Y",
        maxDate: 0,
        dateFormat: "dd/mm/yy",
        constrainInput: false
    });

    $('#adjustment_date').mask('00/00/0000');

});



$("#product").change(function() {

    var item_id = $('#product').val();

    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-check-product-category",
        data: {
            item_id: item_id
        },
        dataType: 'html',
        success: function(data) {
            if (data == 1) {
                //  $("#location_vvm_quantity").show();

                $("#location_vvm_quantity").css('visibility', 'visible');

                $("#vvm_stage_div").show();
                $("#old_vvm_div").show();



            } else {
                $("#location_vvm_quantity").css('visibility', 'hidden');
                $("#vvm_stage_div").hide();
                $("#old_vvm_div").hide();

                // $("#location_vvm_quantity").hide();
            }
        }
    });
});


$("#batch_search").validate({
    rules: {
        'product': {
            required: true
        },
        'batch_no': {
            required: true
        },
        'quantity': {
            required: true
        },
        'adjustment_type': {
            required: true
        },
        old_vvm: {
            required: true
        }
    },
    messages: {
        'product': {
            required: "Please select product."
        },
        'batch_no': {
            required: "Please enter batch number."
        },
        'quantity': {
            required: "Please enter quantity."
        },
        'adjustment_type': {
            required: "Please enter adjustment type."
        },
        old_vvm: {
            required: "Please select vvm stage."
        }
    }
});

$("#new-batch-form").validate({
    rules: {
        product_id: {
            required: true
        },
        batch: {
            required: true
        },
        manufacturer: {
            required: true
        },
        expiry_date: {
            required: true
        },
        vvm_type_id: {
            required: true
        },
        unit_price: {
            required: true
        }
    },
    messages: {
        product_id: {
            required: "Please select product."
        },
        batch: {
            required: "Please enter batch number."
        },
        manufacturer: {
            required: "Please select manufacturer."
        },
        expiry_date: {
            required: "Please select expiry date."
        },
        vvm_type_id: {
            required: "Please select vvm type."
        },
        unit_price: {
            required: "Please enter unit price."
        }
    }
});

$('#expiry_date').datepicker({
    minDate: 0,
    maxDate: "+10Y",
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
});
$('#production_date').datepicker({
    minDate: "-10Y",
    maxDate: 0,
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
});

$("#product_id").change(function() {
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-get-manufacturer-by-product",
        data: {
            item_id: $(this).val()
        },
        dataType: 'html',
        success: function(data) {
            $('#manufacturer').html(data);
        }
    });
});

$("#add-new-batch").click(function(e) {
    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/stock/add-new-batch",
        data: $("#new-batch-form").serialize(),
        dataType: 'html',
        success: function(data) {
            $.ajax({
                type: "POST",
                url: appName + "/stock-batch/ajax-running-batches",
                data: {item_id: $('#product').val(), page: "adjustment"},
                dataType: 'html',
                success: function(data) {
                    $('#batch_no').html(data);
                }
            });
            $(".close").trigger("click");
            Metronic.stopPageLoading();
        }
    });
});

$('#batch').keyup(function() {
    $(this).val($(this).val().toUpperCase());
});

$("#btn-loading").click(function(e) {
    e.preventDefault();
    var validator = $("#batch_search").validate();
    if ($("#batch_search").valid()) {
        if ($("#quantity").val() <= 0) {
            validator.showErrors({
                "quantity": "Quantity should greater then 0."
            });
        } else {
            $("#batch_search").submit();
        }
    }
});

$("#reset").click(function() {
    $('#available_div').fadeOut(1000);
    $('#batch_no').empty();
});