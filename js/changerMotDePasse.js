function validerChamps(data)
{
	var estValide = true;
	
	var actuel = $('#mdpActuel').val();
	var nouveau = $('#mdpNouveau').val();
	var confirmer = $('#mdpConfirmer').val();

	var text = '';
	$("#modalMessage").text('');
	
	if ( estVide(actuel) || estVide(nouveau) || estVide(confirmer))
	{
		$("#modalMessage").text("Champ vide.");
		estValide = false;
	}
	
	if (nouveau == actuel)
	{
		text = $("#modalMessage").text();
		$("#modalMessage").text(text + '\n' + "Le nouveau mot de passe est le même que l'ancien. Aucun changement nécessaire.");
		estValide = false;
	}
	
	if (nouveau.length < 5)
	{
		text = $("#modalMessage").text();
		$("#modalMessage").text(text + '\n' + "Le nouveau mot de passe doit être plus long que 5 caractères.");
		estValide = false;
	}
	
	if (nouveau != confirmer)
	{
		text = $("#modalMessage").text();
		$("#modalMessage").text(text + '\n' + "Le champ de confirmation est différent que le nouveau mot de passe.");
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