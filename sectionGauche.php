<div class="row">
	<div class="col-md-2">
		<ul>
			<?php 
			foreach(Categorie::fetchAll() as $value): ?>
			<li><a <?php echo("href='./?listeCategorie=$value->codeCategorie'> $value->nom"); ?></a></li>
		<?php endforeach; ?>


	</ul>
</div>