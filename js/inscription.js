var nomUtilisateurUnique = false;


$(document).ready(function(){

	$('#utilisateurUnique').hide();
	$('#utilisateurNonUnique').hide();

    $('#nomUtilisateur').blur(function(){

    	$('#utilisateurUnique').hide();
		$('#utilisateurNonUnique').hide();

    	var nomUtilisateur = $('#nomUtilisateur').val();

    	if (!nomUtilisateur || nomUtilisateur.trim().length == 0)
    	{
    		nomUtilisateurUnique = false;
    		return;
    	}

    	$.ajax({
			url : './verifierUniciteUsager.php',
			dataType: "text",
			contentType: "text/html; charset=UTF-8",
			data: {'nomUtilisateur': nomUtilisateur},
			cache: false,
			type: "GET",
			success: function(data) {
				if (data == 1)
				{
					nomUtilisateurUnique = true;
					$('#utilisateurUnique').show();
				}
				else
					if (data == 0)
					{
						nomUtilisateurUnique = false;
						$('#utilisateurNonUnique').show();
					}
					else
					{
						nomUtilisateurUnique = true;
						$('#utilisateurUnique').show();
					}
			},
			failure: function(data){
				nomUtilisateurUnique = true;
				$('#utilisateurUnique').show();
			}
		});
	});


    $('#formInscription').submit(function(){
    	return nomUtilisateurUnique;
    });

});