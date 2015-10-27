$(function() {
    printCont();
    $("#print").click(function() {
        printCont();
    });
});

function printCont() {
    $('#print').hide();
    window.print();
    setTimeout(function() {
        // Do something after 5 seconds
        $('#print').show();
    }, 5000);
}