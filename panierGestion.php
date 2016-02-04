<?php

if(isset($_GET['quoiFaire']))
{
	$quoiFaire = $_GET['quoiFaire'];
	echo $quoiFaire, "<br>";
}

if(isset($_GET['noproduit']))
{
	$noProduit = $_GET['noproduit'];
	echo $noProduit;
}

?>