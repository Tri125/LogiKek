//------------------------------
// Script pour rafraichir la page automatiquement à chaque 15 secondes
//------------------------------


//------------------------------
// Appel une fonction après un certain temps
//------------------------------
function Chrono()
{
	setTimeout(function(){
		rafraichis()
	}, 15000);
}

//------------------------------
// Rafraichis la page
//------------------------------
function rafraichis() {
	var url = './historiqueCommande.php';
	window.location.href = url;
}

//------------------------------
// Une fois le DOM chargé, exécute la fonction
//------------------------------
$( document ).ready(function() {
    Chrono();
});