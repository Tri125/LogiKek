<div class="row">
	<div class="col-md-2">
		<ul>
			<?php 
			foreach(Categorie::fetchAll() as $value): ?>
			<li><a href="./?listeCategorie=<?php echo $value->getCodeCategorie(); ?>"><?php echo $value->getNom(); ?></a></li>
		<?php endforeach; ?>


	</ul>
</div>