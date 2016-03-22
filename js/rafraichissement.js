//------------------------------
// Script pour rafraichir la page automatiquement à chaque 15 secondes
//------------------------------

function Chrono()
{
	setTimeout(function(){
		rafraichis()
	}, 15000);
}

function rafraichis() {
	var url = './historiqueCommande.php';
	window.location.href = url;
}


$( document ).ready(function() {
    Chrono();
});