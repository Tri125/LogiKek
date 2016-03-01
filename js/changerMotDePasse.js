function validerChamps(data)
{
	var estValide = true;
	
	var actuel = $('#mdpActuel').val();
	var nouveau = $('#mdpNouveau').val();
	var confirmer = $('#mdpConfirmer').val();

	$(".modal-body").empty();
	
	$("#modalMessage").text('');
	
	if ( estVide(actuel) || estVide(nouveau) || estVide(confirmer))
	{
		var paragraph = $("<p></p>").text("Champ vide.");
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	
	if (nouveau == actuel)
	{
		var paragraph = $("<p></p>").text("Le nouveau mot de passe est le même que l'ancien. Aucun changement nécessaire.");
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	
	if (nouveau.length < 5)
	{
		var paragraph = $("<p></p>").text("Le nouveau mot de passe doit être plus long que 5 caractères.");
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	
	if (nouveau != confirmer)
	{
		var paragraph = $("<p></p>").text("Le champ de confirmation est différent que le nouveau mot de passe.");
		$(".modal-body").append(paragraph);
		estValide = false;
	}
	if (!estValide)
		$("#modalErreur").modal('show');
	
	console.log(estValide);
	return estValide;
}


function estVide(str)
{
	if (!str || str.length === 0)
		return true;
	else
		return false;
}