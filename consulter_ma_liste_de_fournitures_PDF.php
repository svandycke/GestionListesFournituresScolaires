<?php ob_start();?>
<?php session_start(); ?>

<?php include("fonctions.php") ?>

<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>

<link href="style.css" rel="stylesheet" type="text/css" />

<!-- Style de la page -->

<style type="text/css">


#img-telecharger-pdf{
	position:absolute;
	right:0;	
	top:0;
}

</style>

<page backtop="1%" backbottom="1%" backleft="2%" backright="2%" style="font-size: 11pt")>

<!-- Contenu de la page   -->

<?php 
	include'./inc/db.php';
	$idUtilisateur = $_SESSION['idUtilisateur'];
	$infos_utilisateur = $bdd->prepare('SELECT profil FROM utilisateurs WHERE 
	id= ?');
	$infos_utilisateur->execute(array($idUtilisateur));
	$donnees_infos_utilisateur = $infos_utilisateur->fetch();
	
	if($donnees_infos_utilisateur['profil'] == 'Élève'){

	$eleve_classe = $bdd->prepare('SELECT idClasse FROM composition_classes WHERE 
	idUtilisateur= ?');
	$eleve_classe->execute(array($idUtilisateur));
	
	$donnees_eleve_classe = $eleve_classe->fetch();	
	$idClasse = $donnees_eleve_classe['idClasse'];
	}
	elseif($donnees_infos_utilisateur['profil'] == 'Administration'){
		$idClasse = $_GET['id'];
	}
	else{
		 utilisateur_profil('Administration');
	}
?>

	<div class="titre">
    	Liste de fournitures scolaires
       	<?php if($idClasse){
			
			$details_classe = $bdd->prepare('SELECT niveau, groupe FROM classes WHERE 
			idClasse= ?');
			$details_classe->execute(array($idClasse));			
			$donnees_details_classe = $details_classe->fetch();
				
			echo " (".$donnees_details_classe['niveau']." - ".$donnees_details_classe['groupe'].")";
			
			$niveau = $donnees_details_classe['niveau']; 
			$groupe = $donnees_details_classe['groupe'];
			
			 
		}?>
        
        
	</div>
    <div class="paragraphe">
       
    <?php
		
		if($idClasse)
		{	
			$matieres_enseignees = $bdd->prepare('SELECT matieres_enseignees.designationMatiere AS 
			designationMatiere, matieres_enseignees.idMatiereEnseignee AS idMatiereEnseignee FROM 
			classes_matieres_enseignees INNER JOIN matieres_enseignees ON 
			(classes_matieres_enseignees.idMatiereEnseignee = matieres_enseignees.idMatiereEnseignee)
			WHERE idClasse= ?');
			$matieres_enseignees->execute(array($idClasse));	?>
            
            <div style="background-color:#083d5d; color:#FFFFFF">
                <table>
                    <tr>
                        <td style="width:5%"></td>
                        <td style="width:5%">Qté</td>
                        <td style="width:90%">Désignation</td>
                    </tr>
                </table>
            </div>
	
			<?php while ($donnees_matieres_enseignees= $matieres_enseignees->fetch()){?>
            <?php $idMatiereEnseignee = $donnees_matieres_enseignees['idMatiereEnseignee']; ?>
				<div class="sous_titre">
					<?php echo "<b>".$donnees_matieres_enseignees['designationMatiere']."</b>";
					
					$details_professeur  = $bdd->prepare('SELECT nom, prenom FROM professeur_matiere 
					WHERE idMatiereEnseignee = ?');
					$details_professeur ->execute(array($idMatiereEnseignee));		
					$donnees_details_professeur = $details_professeur->fetch();
					
					echo " - ".$donnees_details_professeur['nom']. " ".$donnees_details_professeur['prenom'];
					
					?>
                    
                    
                    
                </div>
                
                <?php 
				
				$liste   = $bdd->prepare('SELECT idListeFournituresScolaires FROM 
				listes_fournitures_scolaires WHERE idMatiereEnseignee = ? AND niveau = ?');
				$liste  ->execute(array($idMatiereEnseignee, $niveau));
				
				
				$donnees_liste = $liste->fetch();
				
				$idListeFournituresScolaires = $donnees_liste['idListeFournituresScolaires'];
				
				$fournitures_scolaires  = $bdd->prepare('SELECT quantite, designation FROM 
				fournitures_scolaires WHERE idListeFournituresScolaires= ?');
				$fournitures_scolaires  ->execute(array($idListeFournituresScolaires));
				
				?>

				<table style="width:100%">
				<?php 
				while ($donnees_fournitures_scolaires = $fournitures_scolaires->fetch()){?>
                
                <tr>
                	<td style="height:25px; line-height:25px; width:5%"><div class="case_a_cocher"></div></td>
                    <td style="height:25px; line-height:25px; width:5%">
                    	<?php echo $donnees_fournitures_scolaires['quantite'];?> 
                    </td>
                    <td style="height:25px; line-height:25px; width:90%">
						<?php echo $donnees_fournitures_scolaires['designation']; ?>
                  	</td>
          		</tr>
                
                
                <?php } $fournitures_scolaires ->closeCursor(); ?> 
                </table>
                   	
			<?php } $matieres_enseignees->closeCursor();
			
		}
		else
		{
			echo "Aucune classe ne vous a été attribué pour le moment.";		
		}
	
	
	
	 ?>
	</div>




</page>

<?php
$content = ob_get_clean();
require('html2pdf/html2pdf.class.php');
$pdf = new HTML2PDF('P','A4','fr','true','UTF-8');
$pdf->writeHTML($content);
ob_clean();  

$niveau = retirer_accents($niveau, $charset='utf-8');
$groupe = retirer_accents($groupe, $charset='utf-8');
$groupe = mb_strtoupper($groupe, 'UTF-8');

$pdf->Output('Liste_fournitures_scolaires_'.$niveau.'_'.$groupe.'.pdf', 'D');
 
 ?>
	
    