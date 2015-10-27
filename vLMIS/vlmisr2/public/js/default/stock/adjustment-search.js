$(function () {
    $("#expiry_date").datepicker({
        minDate: "-10Y",
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    var startDateTextBox = $('#date_from');
    var endDateTextBox = $('#date_to');

    startDateTextBox.datepicker({
        minDate: "-10Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            }
            else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    });
    endDateTextBox.datepicker({
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            }
            else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    });    
});
$('#product').change(function () {
    $("#available").val('');

    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-running-batches",
        data: {item_id: $('#product').val(), page: "adjustment"},
        dataType: 'html',
        success: function (data) {

            $('#batch_no').html(data);
        }
    });

});

if ($('#product').val() !== "") {
    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-running-batches",
        data: {item_id: $('#product').val(), page: "adjustment"},
        dataType: 'html',
        success: function (data) {

            $('#batch_no').html(data);
            $('#batch_no').val($('#hdn_batch_no').val());
        }
    });
}

$('#print_stock').click(
        function () {
            var adjustment_no, adjustment_type, product, batch_no, date_from, date_to, all_arguments;
            adjustment_no = $('#adjustment_no').val();
            adjustment_type = $('#adjustment_type').val();
            product = $('#product').val();
            batch_no = $('#batch_no').val();
            date_from = $('#date_from').val();
            date_to = $('#date_to').val();
            all_arguments = "?adjustment_no=" + adjustment_no + "&adjustment_type=" + adjustment_type + "&product=" + product + "&batch_no=" + batch_no + "&date_from=" + date_from + "&date_to=" + date_to;
            window.open('stock-adjustment-print' + all_arguments, '_blank', 'scrollbars=1,width=860,height=595');
        }
);

$('#reset').click(function () {

    window.location.href = appName + '/stock/adjustment-search';
});