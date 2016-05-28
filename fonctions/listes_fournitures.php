<?php
// Fonction ajouter une/des fourniture(s) scolaire(s)
function ajouter_fourniture($idListeFournituresScolaires, $designation, $quantite)
{
	utilisateur_profil('Professeur'); 
	
	if(!empty($idListeFournituresScolaires) AND !empty($designation) AND !empty($quantite))
	{
		include'./inc/db.php';
		$idUtilisateur = $_SESSION['idUtilisateur'];
		
		$professeur_liste = $bdd->prepare('SELECT idUtilisateur FROM 
		listes_fournitures_scolaires_utilisateurs WHERE idUtilisateur= ? AND 
		idListeFournituresScolaires = ?');
		$professeur_liste->execute(array($idUtilisateur, $idListeFournituresScolaires));
		$donnees_professeur_liste = $professeur_liste->fetch();	
		
		if($donnees_professeur_liste || $_SESSION['profil'] == 'Administration'){
			
			$fournitures_liste = $bdd->prepare('SELECT idListeFournituresScolaires FROM 
			fournitures_matieres_enseignees WHERE idListeFournituresScolaires= ? AND designation = ?');
			$fournitures_liste->execute(array($idListeFournituresScolaires, $designation));	
			$donnees_fournitures_liste = $fournitures_liste->fetch();
			
			if(!$donnees_fournitures_liste){
				$req = $bdd->prepare('INSERT INTO fournitures_scolaires(idListeFournituresScolaires, 
				quantite, designation) VALUES(:idListeFournituresScolaires, 
				:quantite, :designation)');
		
				$req->execute(array(
				'idListeFournituresScolaires' => $idListeFournituresScolaires,
				'quantite' => $quantite,
				'designation' => ucfirst($designation)));
				
				$_SESSION['flash']['succes'] = "- La fourniture a été ajouté à la liste de fournitures.";	
			
			}
			else
			{
				$_SESSION['flash']['erreur'] = "- Cette fourniture est déjà présente dans cette liste.";
			}
		}
		else{
			$_SESSION['flash']['erreur'] = "- Vous ne pouvez pas ajouter de fournitures sur cette liste.";	
		}
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Veuillez renseigner tous les champs.";		
	}
}

// Fonction supprimer une fourniture scolaire
function supprimer_fourniture($idFournitureScolaire)
{
	utilisateur_profil('Professeur'); 	
	if(!empty($idFournitureScolaire))
	{
		
		include'./inc/db.php';
		$idUtilisateur = $_SESSION['idUtilisateur'];
		
		$fourniture  = $bdd->prepare('SELECT idUtilisateur FROM 
		fournitures_matieres_enseignees WHERE idUtilisateur= ? AND idFournitureScolaire = ?');
		$fourniture ->execute(array($idUtilisateur, $idFournitureScolaire));
		$donnees_fourniture = $fourniture->fetch();	
		
		if($donnees_fourniture){
			$req = $bdd->prepare('DELETE FROM fournitures_scolaires WHERE idFournitureScolaire 
			=:idFournitureScolaire');
			$req->execute(array('idFournitureScolaire' => $idFournitureScolaire));
				
			$_SESSION['flash']['succes'] = "- La fourniture a été supprimé de la liste de 
			fournitures.";
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Vous ne pouvez pas supprimer cette fourniture de la liste.";
		}
		
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Veuillez renseigner la fourniture scolaire à supprimer.";		
	}
}

// Fonction modifier quantité fourniture scolaire
function modifier_quantite_fourniture($quantite, $idFournitureScolaire)
{
	utilisateur_profil('Professeur'); 	
	if(!empty($idFournitureScolaire) AND !empty($quantite) AND is_int((int)$quantite) AND ((int)$quantite >= 1))
	{		
		include'./inc/db.php';
		$idUtilisateur = $_SESSION['idUtilisateur'];
		
		$fourniture  = $bdd->prepare('SELECT idUtilisateur FROM 
		fournitures_matieres_enseignees WHERE idUtilisateur= ? AND idFournitureScolaire = ?');
		$fourniture ->execute(array($idUtilisateur, $idFournitureScolaire));
		
		$donnees_fourniture = $fourniture->fetch();	
		
		if($donnees_fourniture){
			
			$req = $bdd->prepare('UPDATE fournitures_scolaires SET quantite = :quantite WHERE 
			idFournitureScolaire = :idFournitureScolaire');
		
			$req->execute(array(
			'quantite' => $quantite,
			'idFournitureScolaire' => $idFournitureScolaire));
			
			$_SESSION['flash']['succes'] = "- La quantité a été modifié avec succès.";
			
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Vous ne pouvez pas modifier cette fourniture.";
		}
		
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- La quantite minimum est de 1.";		
	}
}

// Fonction supprimer une liste de fournitures scolaires
function supprimer_listes_fournitures_scolaires($idListeFournituresScolaires, $niveau, $idMatiereEnseignee)
{	
	if(!empty($idListeFournituresScolaires) AND !empty($niveau) AND !empty($idMatiereEnseignee))
	{
		include'./inc/db.php';
		
		$nb_classes  = $bdd->prepare('SELECT COUNT(classes_matieres_enseignees.idClasseMatiereEnseignee) 
        FROM classes_matieres_enseignees INNER JOIN classes ON (classes_matieres_enseignees.idClasse = 
		classes.idClasse) WHERE classes.niveau = ? AND 
		classes_matieres_enseignees.idMatiereEnseignee = ?');
		$nb_classes ->execute(array($niveau, $idMatiereEnseignee));
    
       	$donnees_nb_classes= $nb_classes->fetch();
		
		if(empty($donnees_nb_classes[0]))
		{
			$idUtilisateur = $_SESSION['idUtilisateur'];	
	
			$utilisateur_liste  = $bdd->prepare('SELECT idUtilisateur FROM liste_utilisateur WHERE 
			idUtilisateur= ? AND idListeFournituresScolaires = ?');
			$utilisateur_liste->execute(array($idUtilisateur, $idListeFournituresScolaires));
			
			$donnees_utilisateur_liste = $utilisateur_liste->fetch();	
			
			if($donnees_utilisateur_liste)
			{
				
				$req = $bdd->prepare('DELETE FROM fournitures_scolaires WHERE idListeFournituresScolaires 				= :idListeFournituresScolaires');
				$req->execute(array('idListeFournituresScolaires' => $idListeFournituresScolaires));
				
				$req = $bdd->prepare('DELETE FROM listes_fournitures_scolaires WHERE 
				idListeFournituresScolaires = :idListeFournituresScolaires');
				$req->execute(array('idListeFournituresScolaires' => $idListeFournituresScolaires));
				
				$_SESSION['flash']['succes'] = "- La liste de fournitures scolaires a été supprimé avec 
				succès.";	
			}
			else
			{
				$_SESSION['flash']['erreur'] = "- Impossible de supprimer la liste de fournitures 
				scolaires.";
			}
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Impossible de supprimer la liste de fournitures scolaires.";	
		}
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Impossible de supprimer la liste de fournitures scolaires.";	
	}
}
?>