<?php
// Fonction ajouter une matière pour un professeur
function ajouter_matiere($idProfesseur, $matiere)
{
	utilisateur_profil('Administration');
	
	$idProfesseur = trim($idProfesseur); 
	$matiere = trim($matiere); 
	
	$_SESSION['idProfesseur'] = $idProfesseur;
	$_SESSION['matiere'] = $matiere;
	
	if(!empty($idProfesseur) || !empty($matiere)){
		include'./inc/db.php';

		$matiere_professeur = $bdd->prepare('SELECT idMatiereEnseignee FROM matieres_enseignees WHERE 
		idUtilisateur = ? AND designationMatiere = ?');
		$matiere_professeur->execute(array($idProfesseur, $matiere));
		$donnees_matiere_professeur = $matiere_professeur->fetch();
		
		if(!$donnees_matiere_professeur)
		{
			$req = $bdd->prepare('INSERT INTO matieres_enseignees(idUtilisateur, designationMatiere) 		
			VALUES(:idUtilisateur, :designationMatiere)');
		
			$req->execute(array(
			'idUtilisateur' => $idProfesseur,
			'designationMatiere' => ucfirst($matiere)));
			$_SESSION['flash']['succes'] = "- La matière a été ajouté au professeur.";	
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Le professeur enseigne déjà cette matière.";	
		}
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Veuillez renseigner tous les champs.";
	}
}

// Fonction modifier une matière pour un professeur
function modifier_matiere($idMatiereEnseignee, $idUtilisateur, $matiere)
{
	utilisateur_profil('Administration');
	if(!empty($idMatiereEnseignee) AND !empty($idUtilisateur) AND !empty($matiere))
	{
		include'./inc/db.php';	
	
		$matiere_professeur = $bdd->prepare('SELECT idMatiereEnseignee FROM matieres_enseignees WHERE 
		idUtilisateur = ? AND designationMatiere = ?');
		$matiere_professeur->execute(array($idMatiereEnseignee, $matiere));		
		$donnees_matiere_professeur = $matiere_professeur ->fetch();
	
		if(!$donnees_matiere_professeur)
		{
			$req = $bdd->prepare('UPDATE matieres_enseignees SET designationMatiere = :designationMatiere 			WHERE idMatiereEnseignee = :idMatiereEnseignee');
		
			$req->execute(array(
			'designationMatiere' => ucfirst($matiere),
			'idMatiereEnseignee' => $idMatiereEnseignee
			));	
			
			$_SESSION['flash']['succes'] = "- La matière a été modifiée avec succès.";
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Aucune modification effectuée.";	
		}
	}
	else
	{
		$_SESSION['flash']['erreur'] = "- Tous les champs doivent être remplis.";	
	}
	
}

// Fonction supprimer une matière pour un professeur
function supprimer_matiere_professeur($idMatiereEnseignee){
	utilisateur_profil('Administration');
	include'./inc/db.php';
		
		$matiere_professeur = $bdd->prepare('SELECT idMatiereEnseignee FROM matieres_enseignees WHERE 
		idMatiereEnseignee = ?');
		$matiere_professeur->execute(array($idMatiereEnseignee));	
		$donnees_matiere_professeur = $matiere_professeur ->fetch();
		
		if($donnees_matiere_professeur)
		{
	
			$listes_fournitures_scolaires = $bdd->prepare('SELECT idListeFournituresScolaires FROM 
			listes_fournitures_scolaires WHERE idMatiereEnseignee= ?');
			$listes_fournitures_scolaires->execute(array($idMatiereEnseigneee));			
			
					
			while ($donnees_listes_fournitures_scolaires = $listes_fournitures_scolaires->fetch()){
							
				$idListeFournituresScolaires = $donnees_listes_fournitures_scolaires[
				'idListeFournituresScolaires'];
							
				$req = $bdd->prepare('DELETE FROM fournitures_scolaires WHERE idListeFournituresScolaires = :idListeFournituresScolaires');
				$req->execute(array('idListeFournituresScolaires' => $idListeFournituresScolaires));		
			}
						
			$req = $bdd->prepare('DELETE FROM listes_fournitures_scolaires WHERE idMatiereEnseignee = 
			:idMatiereEnseignee');
			$req->execute(array('idMatiereEnseignee' => $idMatiereEnseignee));
							
			$req = $bdd->prepare('DELETE FROM classes_matieres_enseignees WHERE 
			idMatiereEnseignee = :idMatiereEnseignee');
			$req->execute(array('idMatiereEnseignee' => $idMatiereEnseignee));			
			
			$req = $bdd->prepare('DELETE FROM matieres_enseignees WHERE idMatiereEnseignee = 
			:idMatiereEnseignee');
			$req->execute(array('idMatiereEnseignee' => $idMatiereEnseignee));		
			
			$_SESSION['flash']['succes'] = "- La matière enseignée par le professeur a été supprimée.";
		}
		else
		{
			$_SESSION['flash']['erreur'] = "- Le professeur n'enseigne pas cette matière.";
		}
	
}

?>