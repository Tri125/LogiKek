$(document).ready(function () {
	$(".imgProduitPetit").click(function() {
		var codeProduit = $(this).attr("data-codeProduit");
		var url = "/produitDetails.php?codeProduit=" + codeProduit;
		//alert(url);

		$.get(url, function(data){
			$('#myModal').load(url, function(){
				$(this).modal("show");
			});
		});
		
	});
});
