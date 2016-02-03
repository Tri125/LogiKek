<div class="row">
	<div class="col-md-2">
		<ul>
			<?php 
			foreach(Categorie::fetchAll() as $value): ?>
			<li><a href="#"><?php echo $value->nom ?></a></li>
		<?php endforeach; ?>


	</ul>
</div>