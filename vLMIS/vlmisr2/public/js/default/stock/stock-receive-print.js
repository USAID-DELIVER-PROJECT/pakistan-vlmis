$(function(){
	printCont();
})
function printCont()
{
	$('#printButt').hide();
	window.print();
	setTimeout(function() {
		// Do something after 5 seconds
		$('#printButt').show();
	}, 5000);
}
