$(document).ready(function () {
	$(".imgProduitPetit").click(function() {
		var codeProduit = $(this).attr("data-noProduit");
		var url = "./produitDetails.php?noProduit=" + codeProduit;

		$.get(url, function(data){
			$('#myModal').load(url, function(){
				$(this).modal({
					show: true,
					backdrop: true,
					keyboard: true
				});
			});
		});
		
	});
});
