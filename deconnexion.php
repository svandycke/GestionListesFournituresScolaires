<?php 

session_start();
unset($_SESSION['login']);
unset($_SESSION['profil']);
unset($_SESSION['idUtilisateur']);
$_SESSION['flash']['success'] = 'Vous êtes maintenant déconnecté';
header('Location: index.php');

?>