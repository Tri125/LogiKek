$(document).ready(function () {
	$(".imgProduitPetit").click(function() {
		var codeProduit = $(this).attr("data-codeProduit");
		var url = "/produitDetails.php?codeProduit=" + codeProduit;
		alert(url);
	});
});