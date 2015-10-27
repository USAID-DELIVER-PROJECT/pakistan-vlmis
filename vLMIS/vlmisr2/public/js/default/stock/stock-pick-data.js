$(function() {
    $("#btn-loading").click(function() {
        var sum = 0;
        var q = 0;
        var inp = $('.qty');
        for (var i = 0; i < inp.length; i++) {
            if (inp[i].value != '') {
                sum += parseInt(inp[i].value);
                q++;
                if (parseInt(inp[i].value) > parseInt(inp[i].getAttribute('max'))) {
                    alert('Quantity can not be greater than ' + inp[i].getAttribute('max'));
                    inp[i].focus();
                    return false;
                }
            }
        }

        var pick_qty = parseInt($('#u_qty').val());

        if (q == 0) {
            alert('Please enter at least one quantity');
            return false;
        } else if (sum > pick_qty) {
            alert('You can\'t pick more then ' + pick_qty + ' quantity!');
            return false;
        } else {
            varform = $('#stockpickdata').serialize();
            $.ajax({
                type: "POST",
                url: appName + "/stock/pick-stock-quantity?" + varform,
                data: {},
                dataType: 'html',
                success: function(data) {
                    window.location.href = appName + '/stock/pick-stock?success=1';
                }
            });
        }
    });
});