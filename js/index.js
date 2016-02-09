$(document).ready(function () {
	//Rajout un événement click sur les éléments de la classe imgProduitPetit (les petites images du catalogue)
	$(".imgProduitPetit").click(function() {
		//Récupère le code du produit qui a été mit sous l'attribut de donnée personnalisé data-noProduit
		var codeProduit = $(this).attr("data-noProduit");
		var url = "./produitDetails.php?noProduit=" + codeProduit;
		//Récupère le contenu de l'url.
		$.get(url, function(data){
			//Charge le contenu de l'url dans notre élément myModal pour faire notre fenêtre modal de produit détaillé
			$('#myModal').load(url, function(){
				//Ouvre la fenêtre
				$(this).modal({
					show: true,
					backdrop: true,
					keyboard: true
				});
			});
		});
		
	});
});
