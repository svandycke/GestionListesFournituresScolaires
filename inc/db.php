<?php 
try
{    
	$bdd = new PDO('mysql:host=localhost;dbname=gsf', 'root', 'root');
	$bdd->exec("SET CHARACTER SET utf8");
}
catch (Exception $e)
{        
	die('Erreur : ' . $e->getMessage());
}

?>
