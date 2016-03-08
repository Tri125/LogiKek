//------------------------------
// Fonction qui valide les champs d'un formulaire passé en paramètre
// et affiche un message d'erreur à l'usager.
// Retourne true si les champs sont valides, sinon false.
//------------------------------
function validerChamps(data)
{
	var estValide = true;
	//Récupère les valeurs des champs du formulaire.
	var actuel = $('#mdpActuel').val();
	var nouveau = $('#mdpNouveau').val();
	var confirmer = $('#mdpConfirmer').val();

	//Vide le corps de la fenêtre modal qui affichera les messages d'erreurs.
	$(".modal-body").empty();
	
	//$("#modalMessage").text('');
	
	//Vérifie que les champs ont des données
	if ( estVide(actuel) || estVide(nouveau) || estVide(confirmer))
	{
		var paragraph = $("<p></p>").text("Champ vide.");
		//Rajoute le message d'erreur à la fenêtre modal
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	
	//Si le nouveau mot de passe est le même que le mot de passe actuel
	if (nouveau == actuel)
	{
		var paragraph = $("<p></p>").text("Le nouveau mot de passe est le même que l'ancien. Aucun changement nécessaire.");
		//Rajoute le message d'erreur à la fenêtre modal
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	
	//Si la longueur du nouveau mot de passe est moins que 5 caractères ou plus que 10
	if (nouveau.length < 5 || nouveau.length > 10)
	{
		var paragraph = $("<p></p>").text("Le nouveau mot de passe doit être au moins 5 caractères et au plus 10.");
		//Rajoute le message d'erreur à la fenêtre modal
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	
	//La confirmation du nouveau mot de passe n'est pas égal au nouveau mot de passe
	if (nouveau != confirmer)
	{
		var paragraph = $("<p></p>").text("Le champ de confirmation est différent que le nouveau mot de passe.");
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	//Si le formulaire n'est pas valide, affiche la fenêtre modal.
	if (!estValide)
		$("#modalErreur").modal('show');
	
	return estValide;
}

//------------------------------
// Fonction qui retourne true si str est une string vide
// Sinon false
//------------------------------
function estVide(str)
{
	//Si est null ou de longueur 0
	if (!str || str.length === 0)
		return true;
	else
		return false;
}