<?php 
require_once("./php/biblio/foncCommunes.php");

$js = array();

$css = array();
$css[] = 'inscription.css';
$js[] = 'changerMotDePasse.js';
$titre = 'LogiKek - Changement de mot de passe';
$description = 'Site de vente de système d\'exploitation';
$motCle = 'OS, Linux, Windows, BSD, Apple, RHEL, Vente, logiciel';

require_once("./header.php");
require_once("./sectionGauche.php");

?>

<!-- Début section central col-md-9 -->
<div class="col-md-7" id="centre">
	<!-- Début des produits -->
	<div class="row">
		<form id="formChangementMotDePasse" onsubmit="return validerChamps(this);" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<table>
				<tbody>
					<tr>
						<td colspan="3">
							<label>Saisissez les données</label>
						</td>
					</tr>
					<tr>
						<td>
							<label for="mdpActuel">Mot de passe actuel:</label>
						</td>
						<td>
							<input type="password" required id="mdpActuel" name="mdpActuel">
						</td>
					</tr>
					<tr>
						<td>
							<label for="mdpNouveau">Nouveau mot de passe:</label>
						</td>
						<td>
							<input type="password" required id="mdpNouveau" name="mdpNouveau">
						</td>
					</tr>
					<tr>
						<td>
							<label for="mdpConfirmer">Confirmation du mot de passe:</label>
						</td>
						<td>
							<input type="password" required id="mdpConfirmer" name="mdpConfirmer">
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3">
							<input type="submit" name="valider" value="Changer le mot de passe">
						</td>
					</tr>
				</tbody>
			</table>

		</form>


<!-- Contenu principal -->


	</div> 	<!-- Fin des produits -->
</div>	<!-- Fin section central col-md-9 -->
<div class="col-md-1"> 	<!-- Début Section de droite central -->
</div>
<!-- Fin section de droite central -->
</div>


<?php require_once('./footer.php'); ?>