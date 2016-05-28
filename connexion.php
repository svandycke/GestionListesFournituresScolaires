<?php 

session_start();

if(!empty($_POST) && !empty($_POST['login']) && !empty($_POST['mot_de_passe'])){
	if(session_status() == PHP_SESSION_NONE){
        session_start();	
    }

    require_once 'inc/db.php';
	
	
    $req = $bdd->prepare('SELECT login, mot_de_passe, profil, id FROM utilisateurs WHERE (login = :login)');
    $req->execute(['login' => $_POST['login']]);
    $reponse = $req->fetch();
	
    if(password_verify($_POST['mot_de_passe'], $reponse['mot_de_passe'])){
        $_SESSION['login'] = $reponse['login'];
		$_SESSION['profil'] = $reponse['profil'];
		$_SESSION['idUtilisateur'] = $reponse['id'];
        $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';
        header('Location: gestion_fournitures_scolaires.php');
        exit();
    }else{
		$_SESSION['recuperation']['login'] = $_POST['login'];
        $_SESSION['flash']['erreur'] = 'Identifiant ou mot de passe incorrect';		
		header('Location: index.php');
        exit();
    }
}
else{
	$_SESSION['flash']['erreur'] = 'Veuillez saisir vos identifiants.';
	$_SESSION['recuperation']['login'] = $_POST['login'];
	header('Location: index.php');
    exit();
}

?>