
$(function() {
    printCont();

    $("#print-p").click(function() {
        printCont();
    });
});

function printCont()
{
    $('#print-p').hide();
    window.print();
    setTimeout(function() {
        // Do something after 5 seconds
        $('#print-p').show();
    }, 5000);
}
