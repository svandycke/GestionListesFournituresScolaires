<?php

include "../fonctions.php";

// Fonction qui permet de vérifier si l'utilisateur est connecté
function utilisateur_connecte(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();	
    }
    if(!isset($_SESSION['login'])){
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
		echo "Vous n'avez pas le droit d'accéder à cette page."; ?>
        <script type="text/javascript"> document.location.href = 'index.php'</script>   
        <?php exit();
    }
}

// Fonction qui vérifier le profil
function utilisateur_profil($profil){
    if($_SESSION['profil'] !== $profil && $_SESSION['profil'] != 'Administration'){
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
        ?>
        <script type="text/javascript"> document.location.href = 'gestion_fournitures_scolaires.php'
        </script> 
        <?php  exit();
    }
}

// Fonction ajouter un utilisateur
function ajouter_utilisateur($nom, $prenom, $date_naissance, $email, $profil)
{
	utilisateur_profil('Administration');	
	if(!empty($nom) && !empty($prenom) && !empty($date_naissance) && !empty($email) && !empty($profil)){	
		if(verifier_date($date_naissance)){
			
			preg_match('#^([0-9]{2})([/-])([0-9]{2})\2([0-9]{4})$#', $date_naissance, $m);
			$date_naissance = $m[4].'-'.$m[3].'-'.$m[1]; 
			
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				include'./inc/db.php';
				$login = strtolower(str_replace(' ', '-',$prenom.'.'.$nom));
				$login = retirer_accents($login);
				$nom_minuscule = strtolower($nom);
				$password = password_hash(str_replace(' ','',$nom_minuscule), 
				PASSWORD_BCRYPT);
				
				$utilisateur_existe = login_existant($login);
				$i = 1;
				while($utilisateur_existe == true)
				{
					$login_envoye = $login.$i;
					$utilisateur_existe = login_existant($login_envoye);
					if(!$utilisateur_existe)
					{
						$login = $login_envoye;
					}
					$i += 1;
				}
			
				if($utilisateur_existe == false)
				{
					$req = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, email, profil, login, mot_de_passe, date_naissance, date_creation) 		
					VALUES(:nom, :prenom, :email, :profil, :login, :mot_de_passe, :date_naissance, :date_creation)');
				
					$req->execute(array(
					'nom' => mb_strtoupper($nom, 'UTF-8'),
					'prenom' => ucwords($prenom),
					'email' => $email,
					'profil' => $profil,
					'login' => $login,
					'mot_de_passe' => $password,
					'date_naissance' => $date_naissance,
					'date_creation' => date("Y-m-d")
					));
					
					$_SESSION['flash']['succes'] = "- L'utilisateur à été ajouté avec succès.";	
					unset($login);
				}
			}
			else{
					$_SESSION['flash']['erreur'] = "- L'adresse email est incorrecte. Le bon format est : (example@gsf.fr)";		
					$_SESSION['valeursform']['nom'] = $nom;
					$_SESSION['valeursform']['prenom'] = $prenom;
					$_SESSION['valeursform']['email'] = $email;
					$_SESSION['valeursform']['date_naissance'] = $date_naissance;
				}
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Date de naissance ou format incorrect. Le bon format est : (jj/mm/aaaa)";		
			
			$_SESSION['valeursform']['nom'] = $nom;
			$_SESSION['valeursform']['prenom'] = $prenom;
			$_SESSION['valeursform']['email'] = $email;
			$_SESSION['valeursform']['date_naissance'] = $date_naissance;
		}
	}
	else{
		$_SESSION['flash']['erreur'] = "- Veuillez renseigner tous les champs.";	
		
		$_SESSION['valeursform']['nom'] = $nom;
		$_SESSION['valeursform']['prenom'] = $prenom;
		$_SESSION['valeursform']['email'] = $email;
		$_SESSION['valeursform']['date_naissance'] = $date_naissance;
	}
}

// Fonction qui teste si le login est existant
function login_existant($login)
{
	include'./inc/db.php';
	$utilisateur_existe = false;
	
	//$utilisateurs = $bdd->query("SELECT login FROM utilisateurs WHERE login='$login'");
	
	$utilisateurs = $bdd->prepare('SELECT login FROM utilisateurs WHERE login = ?');
	$utilisateurs->execute(array($login));
	
	$donnees_utilisateurs = $utilisateurs->fetch();
	
	if($donnees_utilisateurs){
		$utilisateur_existe = true;
	}
	
	return $utilisateur_existe;	
}

// Fonction modifier un utilisateur
function modifier_utilisateur($nom, $prenom, $date_naissance, $email, $login, $profil, $id)
{
	utilisateur_profil('Administration');
	
	if( !empty($nom) && !empty($prenom) && !empty($email) && !empty($profil) && !empty($id) && !empty($date_naissance)) {
		
		if(verifier_date($date_naissance)){
			
			preg_match('#^([0-9]{2})([/-])([0-9]{2})\2([0-9]{4})$#', $date_naissance, $m);
			$date_naissance = $m[4].'-'.$m[3].'-'.$m[1]; 
			
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				include'./inc/db.php';
				
				$login = strtolower(str_replace(' ', '-',$prenom.'.'.$nom));
				$login = retirer_accents($login);
				
				$utilisateurs = $bdd->query("SELECT login FROM utilisateurs WHERE id='$id'");
				$donnees_utilisateurs = $utilisateurs->fetch();
				
				if($login == $donnees_utilisateurs['login']){
				
					$req = $bdd->prepare('UPDATE utilisateurs SET email = :email, profil = :profil, date_naissance = :date_naissance WHERE id = :id');
				
					$req->execute(array(
					'email' => $email,
					'profil' => $profil,
					'date_naissance' => $date_naissance,
					'id' => $id
					));
					
					$_SESSION['flash']['succes'] = "- L'utilisateur à été modifié avec succès.";		
				}
				else
				{
					$utilisateur_existe = login_existant($login);
					$i = 1;
					while($utilisateur_existe == true)
					{
						$login_envoye = $login.$i;
						$utilisateur_existe = login_existant($login_envoye);
						if(!$utilisateur_existe)
						{
							$login = $login_envoye;
						}
						$i += 1;	
					}
					
					if($utilisateur_existe == false)
					{
						$req = $bdd->prepare('UPDATE utilisateurs SET email = :email, profil = :profil, nom = :nom, prenom = :prenom, login = :login, date_naissance = :date_naissance WHERE id = :id');
				
						$req->execute(array(
						'nom' => mb_strtoupper($nom, 'UTF-8'),
						'prenom' => ucwords($prenom),
						'date_naissance' => $date_naissance,
						'login' => $login,
						'email' => $email,
						'profil' => $profil,
						'id' => $id));
					
						$_SESSION['flash']['succes'] = "- L'utilisateur à été modifié avec succès.";		
					}			
				}
			}
			else{
				$_SESSION['flash']['erreur'] = "- L'adresse email est incorrecte. Le bon format est : (example@gsf.fr)";	
				
					$_SESSION['valeursform']['nom'] = $nom;
					$_SESSION['valeursform']['prenom'] = $prenom;
					$_SESSION['valeursform']['email'] = $email;
					$_SESSION['valeursform']['date_naissance'] = $date_naissance;
			}
		}
		else
		{
			$_SESSION['flash']['erreur'] = "Date de naissance ou format incorrect. Le bon format est : (jj/mm/aaaa)";	
				$_SESSION['valeursform']['nom'] = $nom;
				$_SESSION['valeursform']['prenom'] = $prenom;
				$_SESSION['valeursform']['email'] = $email;
				$_SESSION['valeursform']['date_naissance'] = $date_naissance;	
		}
	}
	else{
		$_SESSION['flash']['erreur'] = "- Veuillez renseigner tous les champs.";	
			$_SESSION['valeursform']['nom'] = $nom;
			$_SESSION['valeursform']['prenom'] = $prenom;
			$_SESSION['valeursform']['email'] = $email;
			$_SESSION['valeursform']['date_naissance'] = $date_naissance;
	}
}

// Fonction supprimer un utilisateur
function supprimer_utilisateur($id)
{
	utilisateur_profil('Administration');
	
	if(!empty($id)){
		
		include'./inc/db.php';
		
			include'./inc/db.php';
			
			//$utilisateurs = $bdd->query("SELECT profil FROM utilisateurs WHERE id='$id'");
			
			$utilisateurs = $bdd->prepare('SELECT profil FROM utilisateurs WHERE id= ?');
			$utilisateurs->execute(array($id));	
			$donnees_utilisateurs = $utilisateurs->fetch();
			
			if($donnees_utilisateurs['profil'] == 'Élève'){
				$req = $bdd->prepare('DELETE FROM composition_classes WHERE idUtilisateur = 
				:idUtilisateur');
				$req->execute(array('idUtilisateur' => $id));		
			}
			elseif($donnees_utilisateurs['profil'] == 'Professeur'){
				
				
				$matieres_enseignees = $bdd->prepare('SELECT idMatiereEnseignee FROM matieres_enseignees 
				WHERE idUtilisateur=?');
				$matieres_enseignees->execute(array($id));	
				
				while ($donnees_matieres_enseignees = $matieres_enseignees->fetch()){
					$idMatiereEnseignee = $donnees_matieres_enseignees['idMatiereEnseignee'];
	
					$listes_fournitures_scolaires  = $bdd->prepare('SELECT idListeFournituresScolaires 
					FROM listes_fournitures_scolaires WHERE idMatiereEnseignee= ?');
					$listes_fournitures_scolaires ->execute(array($idMatiereEnseignee));
					
					
					
						while ($donnees_listes_fournitures_scolaires = $listes_fournitures_scolaires->
						fetch()){
							
							$idListeFournituresScolaires = $donnees_listes_fournitures_scolaires[
							'idListeFournituresScolaires'];
							
							$req = $bdd->prepare('DELETE FROM fournitures_scolaires WHERE 
							idListeFournituresScolaires = :idListeFournituresScolaires');
							$req->execute(array('idListeFournituresScolaires' => 
							$idListeFournituresScolaires));		
						}
						
						$req = $bdd->prepare('DELETE FROM listes_fournitures_scolaires WHERE 
							idMatiereEnseignee = :idMatiereEnseignee');
							$req->execute(array('idMatiereEnseignee' => $idMatiereEnseignee));
							
						$req = $bdd->prepare('DELETE FROM classes_matieres_enseignees WHERE 
						idMatiereEnseignee = :idMatiereEnseignee');
						$req->execute(array('idMatiereEnseignee' => $idMatiereEnseignee));										
				}
				
				$req = $bdd->prepare('DELETE FROM matieres_enseignees WHERE idUtilisateur = 
				:idUtilisateur');
				$req->execute(array('idUtilisateur' => $id));	
			}
				
			$req = $bdd->prepare('DELETE FROM utilisateurs WHERE id = :id');
			$req->execute(array('id' => $id));		
			
			$_SESSION['flash']['succes'] = "- L'utilisateur à été supprimé avec succès.";			
	}
	else{
		$_SESSION['flash']['erreur'] = "- Veuillez séléctionner un utilisateur à supprimer.";	
	}
}

// Fonction réinitialiser/modifier mot de passe
function reinitialiser_mot_de_passe($id, $mot_de_passe)
{
	if(!empty($id)){
		
		include'./inc/db.php';
		
		$req = $bdd->prepare('SELECT nom FROM utilisateurs WHERE (id = :id)');
    	$req->execute(['id' => $id]);
    	$reponse = $req->fetch();
		
		$nom = $reponse['nom'];
		$nom_minuscule = str_replace(' ','',strtolower($nom));
		
		if($mot_de_passe != ""){	
			$mot_de_passe = password_hash($mot_de_passe, PASSWORD_BCRYPT);
		}
		else{
			$mot_de_passe = password_hash($nom_minuscule, PASSWORD_BCRYPT);	
		}
		
		$req = $bdd->prepare('UPDATE utilisateurs SET mot_de_passe = :mot_de_passe WHERE id = :id');
		
			$req->execute(array(
			'mot_de_passe' => $mot_de_passe,
			'id' => $id
			));
		
		$_SESSION['flash']['succes'] = "- Le mot de passe a été réinitialisé avec succès.";			
	}
	else{
		$_SESSION['flash']['erreur'] = "- Une erreur s'est produite lors de la réinitialisation du mot de passe.";	
	}
}

// Fonction qui permet de modifier son profil
function modifier_profil_informations($id, $email, $date_naissance){
	if(!empty($id) AND !empty($email) AND!empty($date_naissance))
	{
		if(verifier_date($date_naissance)){
		
				preg_match('#^([0-9]{2})([/-])([0-9]{2})\2([0-9]{4})$#', $date_naissance, $m);
			$date_naissance = $m[4].'-'.$m[3].'-'.$m[1]; 	
			
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				include'./inc/db.php';
				
				$req = $bdd->prepare('UPDATE utilisateurs SET email = :email, date_naissance = 				:date_naissance WHERE id = :id');
				
					$req->execute(array(
					'email' => $email,
					'date_naissance' => $date_naissance,
					'id' => $id
					));
				
				
				$_SESSION['flash']['succes'] = "- Votre profil a été mis à jour.";	
			}
			else
			{
				$_SESSION['flash']['erreur'] = "- L'adresse email est incorrecte. Le bon format est : (example@gsf.fr)";	
			}
				
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Date de naissance incorrecte. Le bon format est : (jj/mm/aaaa)";
		}
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Tous les champs doivent être renseignés.";
	}
	
}

// Fonction qui permet de modifier son mot de passe
function modifier_profil_mot_de_passe($id, $ancien_mot_de_passe, $nouveau1_mot_de_passe, $nouveau2_mot_de_passe){
	if(!empty($id) AND !empty($ancien_mot_de_passe) AND !empty($nouveau1_mot_de_passe) AND !empty($nouveau2_mot_de_passe))
	{
		include'./inc/db.php';
		
		$req = $bdd->prepare('SELECT mot_de_passe FROM utilisateurs WHERE 	
		(id = :id)');
    	$req->execute(['id' => $id]);
    	$reponse = $req->fetch();
	
		 if(password_verify($ancien_mot_de_passe, $reponse['mot_de_passe'])){
			 if($nouveau1_mot_de_passe == $nouveau2_mot_de_passe){
				$req = $bdd->prepare('UPDATE utilisateurs SET mot_de_passe = :mot_de_passe 
				WHERE id = :id');
				
				$req->execute(array(
				'mot_de_passe' => password_hash($nouveau1_mot_de_passe, PASSWORD_BCRYPT),
				'id' => $id
				)); 
				
				$_SESSION['flash']['succes'] = "- Votre mot de passe a été mis à jour.";
			 }
			 else
			 {
				$_SESSION['flash']['erreur'] = "- Les nouveaux mots de passe saisis ne sont 
				pas correct"; 
			 }
		 }
		 else
		 {
			 $_SESSION['flash']['erreur'] = "- Votre ancien mot de passe n'est pas correct";
		 }
			
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Tous les champs doivent être renseignés.";
	}
	
}

?>