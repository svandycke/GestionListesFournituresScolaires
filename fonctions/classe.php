<?php

// Fonction ajouter une classe
function ajouter_classe($niveau, $groupe)
{
	utilisateur_profil('Administration');
	
	if(!empty($niveau) && !empty($groupe)){
		
		$niveau = trim($niveau); 
		$groupe = trim($groupe); 
	
		include'./inc/db.php';
		
		if(!classe_existante($niveau, $groupe))
		{
			$req = $bdd->prepare('INSERT INTO classes(niveau, groupe) VALUES(:niveau, :groupe)');
		
			$req->execute(array(
			'niveau' => ucwords($niveau),
			'groupe' => mb_strtoupper($groupe, 'UTF-8')));
			
			$_SESSION['flash']['succes'] = "- La classe à été ajoutée avec succès.";		
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Cette classe existe déjà.";	
		}
		
		
	}
	else{
		$_SESSION['flash']['erreur'] = "- Veuillez renseigner tous les champs.";	
	}
}

// Fonction qui teste si la classe est existante
function classe_existante($niveau, $groupe)
{
	include'./inc/db.php';
	$classe_existe = false;
	
	$classes = $bdd->prepare('SELECT niveau, groupe FROM classes WHERE niveau= ? AND groupe = ?');
	$classes->execute(array($niveau, $groupe));
	$donnees_classes = $classes ->fetch();
	
	if($donnees_classes){
		$classe_existe = true;
	}
	
	return $classe_existe;	
}

// Fonction qui supprime une classe
function supprimer_classe($idClasse){
	include'./inc/db.php';
	$req = $bdd->prepare('DELETE FROM classes WHERE idClasse = :idClasse');
	$req->execute(array('idClasse' => $idClasse));
	
	$req = $bdd->prepare('DELETE FROM composition_classes WHERE idClasse = :idClasse');
	$req->execute(array('idClasse' => $idClasse));
	
	$req = $bdd->prepare('DELETE FROM classes_matieres_enseignees WHERE idClasse = :idClasse');
	$req->execute(array('idClasse' => $idClasse));
	
	$_SESSION['flash']['succes'] = "- La classe a été supprimée avec succès.";
		
}

// Fonction qui ajoute un elève dans une classe
function ajouter_eleve_classe($idEleve, $idClasse)
{
	include'./inc/db.php';
	$eleve_dans_classe = $bdd->prepare('SELECT idClasse FROM composition_classes WHERE idUtilisateur=? 
	AND idClasse=?');
	$eleve_dans_classe->execute(array($idEleve, $idClasse));	
	$donnees_eleve_dans_classe = $eleve_dans_classe ->fetch();
	
	if(!$donnees_eleve_dans_classe && !empty($idClasse) && !empty($idEleve))
	{
		$eleve_dans_classe = $bdd->prepare('SELECT idClasse FROM composition_classes WHERE 
		idUtilisateur=?');
		$eleve_dans_classe->execute(array($idEleve));	
		$donnees_eleve_dans_classe = $eleve_dans_classe ->fetch();
		
		if($donnees_eleve_dans_classe){
			$req = $bdd->prepare('UPDATE composition_classes SET idClasse = :idClasse WHERE idUtilisateur 			= :idUtilisateur');
			
				$req->execute(array(
				'idClasse' => $idClasse,
				'idUtilisateur' => $idEleve));
				
				$_SESSION['flash']['succes'] = "- L'élève a bien été ajouté à la classe.";		
		}
		else
		{
				$req = $bdd->prepare('INSERT INTO composition_classes(idClasse, idUtilisateur) 		
				VALUES(:idClasse, :idUtilisateur)');
			
				$req->execute(array(
				'idClasse' => $idClasse,
				'idUtilisateur' => $idEleve));
				
				$_SESSION['flash']['succes'] = "- L'élève a bien été ajouté à la classe.";				
		}	
	}
	else
	{
		if(empty($idClasse) || empty($idEleve))
		{
			$_SESSION['flash']['erreur'] = "- Veuillez selectionner un élève à ajouter.";	
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- L'élève est déjà dans cette classe.";	
		}
	}
}

// Fonction qui retirer l'élève de la classe
function retirer_eleve($idEleve){
	
	if(!empty($idEleve)){
		
			include'./inc/db.php';
			$req = $bdd->prepare('DELETE FROM composition_classes WHERE idUtilisateur = :idUtilisateur');
			$req->execute(array('idUtilisateur' => $idEleve));
			$_SESSION['flash']['succes'] = "- L'élève à été retiré avec succès.";
	}
	else{
		$_SESSION['flash']['erreur'] = "- Veuillez séléctionner un élève à retirer.";	
	}
}

// Fonction qui ajoute un professeur à une classe et une liste de fournitures scolaires si innexistante
function ajouter_professeur_classe($idMatiereEnseignee, $idClasse)
{
	if(!empty($idMatiereEnseignee) AND !empty($idClasse))
	{
		include'./inc/db.php';
		
		$professeur_classe = $bdd->prepare('SELECT idClasse FROM classes_matieres_enseignees WHERE 
		idMatiereEnseignee= ? AND idClasse = ?');
		$professeur_classe->execute(array($idMatiereEnseignee, $idClasse));
		
		$donnees_professeur_classe = $professeur_classe ->fetch();
		
		if(!$donnees_professeur_classe){
			$req = $bdd->prepare('INSERT INTO classes_matieres_enseignees(idClasse, idMatiereEnseignee) 		
			VALUES(:idClasse, :idMatiereEnseignee)');
		
			$req->execute(array(
			'idClasse' => $idClasse,
			'idMatiereEnseignee' => $idMatiereEnseignee));
			
			$niveau = $bdd->prepare('SELECT niveau FROM classes WHERE idClasse = ?');
			$niveau->execute(array($idClasse));
			
			
			$donnees_niveau = $niveau ->fetch();			
			$niveauClasse = $donnees_niveau['niveau'];
			
			if(!empty($niveauClasse))
			{			
				$liste_existante = $bdd->prepare('SELECT niveau FROM listes_fournitures_scolaires WHERE 
				niveau = ? AND idMatiereEnseignee = ?');
				$liste_existante->execute(array($niveauClasse, $idMatiereEnseignee));
		
				$donnees_liste_existante= $liste_existante ->fetch();
				
				if(!$donnees_liste_existante)
				{
					$req = $bdd->prepare('INSERT INTO listes_fournitures_scolaires 
					(niveau,idMatiereEnseignee) VALUES(:niveau, :idMatiereEnseignee)');
		
					$req->execute(array(
					'niveau' => $niveauClasse,
					'idMatiereEnseignee' => $idMatiereEnseignee));		
				}
			}
			
			$_SESSION['flash']['succes'] = "- Le professeur a bien été ajouté à la classe.";	
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Le professeur enseigne déjà cette matière à cette classe.";	
		}
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Veuillez selectionner un professeur à ajouter à cette classe.";
	}
}

// Fonction qui retire un professeur d'une classe
function retirer_professeur($idMatiereEnseignee, $idClasse)
{
	include'./inc/db.php';
	
	$professeur_dans_classe = $bdd->prepare('SELECT idMatiereEnseignee FROM 
	classes_matieres_enseignees WHERE idMatiereEnseignee = ? AND idClasse = ?');
	$professeur_dans_classe->execute(array($idMatiereEnseignee, $idClasse));

	$donnees_professeur_dans_classe = $professeur_dans_classe ->fetch();
	
	if($donnees_professeur_dans_classe){
		
		$req = $bdd->prepare('DELETE FROM classes_matieres_enseignees WHERE idMatiereEnseignee = :idMatiereEnseignee AND 
		idClasse = :idClasse');
		$req->execute(array('idMatiereEnseignee' => $idMatiereEnseignee, 'idClasse' => $idClasse));
		$_SESSION['flash']['succes'] = "- Le professeur à été retiré avec succès.";		
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Ce professeur n'enseigne pas dans cette classe";	
	}
}

?>