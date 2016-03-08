//---------------------------------
// Script permettant de faire une validation
// côté client de l'unicité du nom d'utilisateur lors
// de la création d'un nouveau compte client.
//---------------------------------


//Variable de validation
var nomUtilisateurUnique = true;


$(document).ready(function(){
	//Cache les deux messages d'indication du statut du nom d'utilisateur
	$('#utilisateurUnique').hide();
	$('#utilisateurNonUnique').hide();

	//Bind le handler à l'event blur de l'élément #nomUtilisateur.
	//Exécution à chaque fois que le champ en question pert le focus.
	//C'est juste horrible. Pourquoi pas l'événement "change"?
    $('#nomUtilisateur').blur(function(){

    	$('#utilisateurUnique').hide();
		$('#utilisateurNonUnique').hide();

		//Récupère la valeur dans l'élément, le nom d'utilisateur.
    	var nomUtilisateur = $('#nomUtilisateur').val();

    	//Si null ou vide
    	if (!nomUtilisateur || nomUtilisateur.trim().length == 0)
    	{
    		nomUtilisateurUnique = false;
    		return;
    	}

    	//Requête GET ajax utilisant l'API de notre site web pour savoir si oui ou non le nom d'utilisateur est déjà utilisé
    	//Cache à false puisque le résultat peut changer en tout temps.
    	$.ajax({
			url : './verifierUniciteUsager.php',
			dataType: "text",
			contentType: "text/html; charset=UTF-8",
			data: {'nomUtilisateur': nomUtilisateur},
			cache: false,
			type: "GET",
			success: function(data) {
				//Si le script a été contacté et le retour est 1, est unique.
				if (data == 1)
				{
					nomUtilisateurUnique = true;
					//Affiche le message de nom d'utilisateur libre
					$('#utilisateurUnique').show();
				}
				else
					//Si le script retourne 0, est non unique.
					if (data == 0)
					{
						nomUtilisateurUnique = false;
						//Affiche le message de nom d'utilisateur déjà utilisé.
						$('#utilisateurNonUnique').show();
					}
					//Pour tout autre résultat
					else
					{
						//Sans doute une erreur côté serveur, on ne veux pas bloquer l'utilisateur par une vérification
						//erroné côté client, donc assume que le nom d'utilisateur est libre.
						nomUtilisateurUnique = true;
						//Affiche le message de nom d'utilisateur libre.
						$('#utilisateurUnique').show();
					}
			},
			failure: function(data){
				//Échec de la requête ajax.
				//Sans doute une erreur de transmission ou du côté serveur, on ne veux pas bloquer l'utilisateur par une vérification
				//erroné côté client, donc assume que le nom d'utilisateur est libre.				
				nomUtilisateurUnique = true;
				//Affiche le message de nom d'utilisateur libre.
				$('#utilisateurUnique').show();
			}
		});
	});

    //Handler de l'événement submit du formulaire
    $('#formInscription').submit(function(){
    	//Empêche l'envoi au serveur lorsque le nom d'utilisateur n'est pas unique.
    	return nomUtilisateurUnique;
    });

});