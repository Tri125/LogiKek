$(document).ready(function () {
	$(".imgProduitPetit").click(function() {
		var codeProduit = $(this).attr("data-codeProduit");
		var url = "./produitDetails.php?codeProduit=" + codeProduit;

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
