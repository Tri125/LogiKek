//---------------------------------
// Script permettant de faire une validation
// côté client de la carte de crédit utilisé
// lors d'une commande.
//---------------------------------


$(document).ready(function () {
	//Cache le message d'erreur une fois le DOM chargé.
	$('#messageErreur').hide();
});

//------------------------------
// Fonction pour valider une carte de crédit
//------------------------------
function ValiderCarte(data)
{
	//Cache le message d'erreur
	$('#messageErreur').hide();
	//Assume est valide au départ
	var estValide = true;
	var date = new Date();

	//Récupère les informations des champs du formulaire
	var typeCarte = $("input[name=carte]:checked").val();
	var numero = $("#txtNumero").val();
	var expMois = $("#mois option:selected").val();
	var expAnnee = $("#annee option:selected").val();

	//Si un des champs est vide, affiche le message d'erreur
	if ( estVide(typeCarte) || estVide(numero) || estVide(mois) || estVide(annee))
	{
		estValide = false;
		$('#messageErreur').show();
		return estValide;
	}

	switch (typeCarte)
	{
		case 'visa':
			if (!validationVisa(numero))
				estValide = false;
			break;

		case 'mastercard':
			if (!validationMasterCard(numero))
				estValide = false;
			break;

		case 'amex':
			if (!validationAmex(numero))
				estValide = false;
			break;

		default:
			estValide = false;
			break;
	}

	//Si la date d'expiration n'est pas valide
	//L'année actuelle dépasse l'année d'expiration OU si l'année est la même et le mois actuel dépasse le mois d'expiration 
	//(-1 car de 1-12 dans le formulaire, mais 0-11 pour l'objet date).
	if (date.getFullYear() > expAnnee || (date.getFullYear() == expAnnee && date.getMonth() > expMois - 1) )
	{
		estValide = false;
	}

	if (!estValide)
		$('#messageErreur').show();
	return estValide;
}



//------------------------------
// Fonction pour valider une carte Visa
//------------------------------
function validationVisa(num)
{
	var regexVisa = /^4[0-9]{12}(?:[0-9]{3})?$/;

	if (regexVisa.test(num))
	{
		return validationMod(num);
	}
	else
		return false;
}



//------------------------------
// Fonction pour valider une carte MasterCard
//------------------------------
function validationMasterCard(num)
{
	var regexMasterCard = /^5[1-5][0-9]{14}$/;

	if (regexMasterCard.test(num))
	{
		return validationMod(num);
	}
	else
		return false;
}

//------------------------------
// Fonction pour valider une carte American Express
//------------------------------
function validationAmex(num)
{
	var regexAmex = /^3[47][0-9]{13}$/;

	if (regexAmex.test(num))
	{
		return validationMod(num);
	}
	else
		return false;
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



//------------------------------
// Fonction validation modulo 10
//------------------------------
function validationMod(data)
{
	var somme = 0;

	for (var i = data.length-1; i >= 0; i -= 2)
	{
		somme += data.charAt(i) * 1;		
		car2 = data.charAt(i-1) * 2;
		if (car2 >= 10)
		{
			somme += car2 - 9;				
		}
		else
		{
			somme += car2;				
		}				
	}

	if (somme % 10 == 0)
	{
		return true;
	}	
	return false;
}