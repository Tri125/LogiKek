<div class="row"> <!-- Début ligne du contenu central -->
	<div id="rechercheCat" class="col-md-2"> <!-- Section pour parcourir les produits selon leurs catégories -->
		<h3>Naviguer par catégories</h3>
		<ul>
			<li><a href="./">Toutes</a></li>
		<?php foreach(Categorie::fetchAll() as $value): ?>
			<li><a href="./?listeCategorie=<?php echo $value->getCodeCategorie(); ?>"><?php echo $value->getNom(); ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div> <!-- Fin section pour parcourir selon les catégories -->