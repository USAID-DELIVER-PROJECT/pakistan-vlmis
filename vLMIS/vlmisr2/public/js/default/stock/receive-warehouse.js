$(function () {
    /*$("#rec_date").datepicker({
     minDate: $("#issue_date").val(),
     maxDate: 0,
     dateFormat: 'dd/mm/yy'
     });*/

    $('#rec_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'd/m/Y h:i A',
        minDate: new Date($("#issue_year").val(), $("#issue_month").val() - 1, $("#issue_day").val()),
        maxDate: 0
    });

    $("input[id$='-stage1']").change(function () {
        var value = $(this).attr("id");
        var id = value.replace("-stage1", "");

        var stage1 = parseInt($("#" + id + "-stage1").val());
        var stage2 = parseInt($("#" + id + "-stage2").val());
        var stage3 = parseInt($("#" + id + "-stage3").val());
        var quantity = parseInt($("#" + id + "-qty").val());
        if (isNaN(stage1)) {
            stage1 = 0;
        }
        if (isNaN(stage2)) {
            stage2 = 0;
        }
        if (isNaN(stage3)) {
            stage3 = 0;
        }
        var total = stage1 + stage2 + stage3;
        $("#" + id + "-received").val(total);

        if (total > quantity) {
            alert("Qauntity should not greater than " + quantity);
        }
    });

    $("input[id$='-stage2']").change(function () {
        var value = $(this).attr("id");
        var id = value.replace("-stage2", "");

        var stage1 = parseInt($("#" + id + "-stage1").val());
        var stage2 = parseInt($("#" + id + "-stage2").val());
        var stage3 = parseInt($("#" + id + "-stage3").val());
        var quantity = parseInt($("#" + id + "-qty").val());
        if (isNaN(stage1)) {
            stage1 = 0;
        }
        if (isNaN(stage2)) {
            stage2 = 0;
        }
        if (isNaN(stage3)) {
            stage3 = 0;
        }
        var total = stage1 + stage2 + stage3;
        $("#" + id + "-received").val(total);

        if (total > quantity) {
            alert("Qauntity should not greater than " + quantity);
        }
    });

    $("input[id$='-stage3']").change(function () {
        var value = $(this).attr("id");
        var id = value.replace("-stage3", "");

        var stage1 = parseInt($("#" + id + "-stage1").val());
        var stage2 = parseInt($("#" + id + "-stage2").val());
        var stage3 = parseInt($("#" + id + "-stage3").val());
        var quantity = parseInt($("#" + id + "-qty").val());
        if (isNaN(stage1)) {
            stage1 = 0;
        }
        if (isNaN(stage2)) {
            stage2 = 0;
        }
        if (isNaN(stage3)) {
            stage3 = 0;
        }
        var total = stage1 + stage2 + stage3;
        $("#" + id + "-received").val(total);

        if (total > quantity) {
            alert("Qauntity should not greater than " + quantity);
        }
    });

    $('#checkall').attr('checked', false);

    $("select[id$='-types']").attr("disabled", true);

    $('#estimated_life').priceFormat({
        prefix: '',
        thousandsSeparator: '',
        suffix: '',
        centsLimit: 0,
        limit: 2
    });

    $('#save').click(function (e) {
        e.preventDefault();
        var flag = 'true';

        if ($('#receive_stock').find('input[type=checkbox]:checked').length == 0) {
            alert('Please select atleast one checkbox');
            flag = 'false';
        }

        $("input[id$='-received']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("-received", "");
            var qty = $('#' + id + '-qty').val();
            var received = $('#' + id + '-received').val();

            if (parseInt(received) > parseInt(qty)) {
                alert("Received quantity should not be greater than actual quantity.");
                $(this).focus();
                flag = 'false';
            }
        });

        $("input[id$='-missing']").each(function () {
            var value = $(this).attr("id");
            var id = value.replace("-missing", "");
            $('#' + id + '-types').attr("required", true);
            var qty = $(this).val();
            var avaqty = $('#' + id + '-qty').val() - $('#' + id + '-received').val();

            if (parseInt(qty) > parseInt(avaqty)) {
                alert("Adjustment quantity should not be greater than available quantity.");
                $(this).focus();
                flag = 'false';
            }
            if (qty != '' && !$.isNumeric(qty)) {
                alert("Adjustment quantity should be an integer value");
                $(this).focus();
                flag = 'false';
            }
        });

        if (flag == 'true') {
            if (confirm('Are you sure you received all the items?')) {
                var checkedAtLeastOne = false;
                $('input[type="checkbox"]').each(function () {
                    if ($(this).is(":checked")) {
                        $('#receive_stock').submit();
                        return false;
                    }
                });
            }
        }

    });

    $("input[id$='-missing']").keyup(function (e) {
        var value = $(this).attr("id");
        var id = value.replace("-missing", "");
        $('#' + id + '-types').attr("required", true);
        var qty = $(this).val();
        var avaqty = $('#' + id + '-qty').val() - $('#' + id + '-received').val();
        if (parseInt(qty) > parseInt(avaqty)) {
            alert("Adjustment quantity should not be greater than available quantity.");
            $(this).focus();
        }
        if (qty != '' && !$.isNumeric(qty)) {
            alert("Adjustment quantity should be an integer value");
            $('#' + id + '-types').attr("disabled", true);
            $(this).focus();
        }
        if (qty <= 0) {
            $('#' + id + '-types').attr("disabled", true);
        } else {
            $('#' + id + '-types').attr("disabled", false);
        }
    });
});