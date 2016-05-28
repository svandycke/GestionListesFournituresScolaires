<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Élève')?>

<!-- Pramètres de la page   -->

<title>Ma liste de fournitures scolaires - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-consulter-ma-liste-de-fournitures')</script>

<!-- Style de la page -->

<style type="text/css">

#img-telecharger-pdf{
	position:absolute;
	right:0;	
	top:0;
}

</style>

<!-- <script type="text/javascript">
	var corps_de_page = document.getElementById('corps_de_page');
	var menu = document.getElementById('menu')
	corps_de_page.style.marginLeft = '0';
	menu.style.display = 'none';
</script> -->

<!-- Contenu de la page   -->

<?php 

	include'./inc/db.php';
	$idUtilisateur = $_SESSION['idUtilisateur'];

	$eleve_classe = $bdd->prepare('SELECT idClasse FROM composition_classes WHERE 
	idUtilisateur= ?');
	$eleve_classe->execute(array($idUtilisateur));
	
	$donnees_eleve_classe = $eleve_classe->fetch();	
?>

	<div class="titre">
    	Liste de fournitures scolaires
       	<?php if($donnees_eleve_classe){
			
			$idClasse = $donnees_eleve_classe['idClasse'];
			
			$details_classe = $bdd->prepare('SELECT niveau, groupe FROM classes WHERE 
			idClasse= ?');
			$details_classe->execute(array($idClasse));			
			$donnees_details_classe = $details_classe->fetch();
				
			echo " (".$donnees_details_classe['niveau']." - ".$donnees_details_classe['groupe'].")";
			
			$niveau = $donnees_details_classe['niveau']; 
			$groupe = $donnees_details_classe['groupe'];
			
			 
		}?>
        
        <div id="img-telecharger-pdf">
        
        <form method="post" action="consulter_ma_liste_de_fournitures_PDF.php" name="pdf">
    		<img src="images/pdf-icon.png" height="20" class="pointeur_dessus"  title="Télécharger en 
            PDF" alt="Télécharger en PDF" onclick="pdf.submit();">
        </form>
   		</div>   
	</div>
    <div class="paragraphe">
    	<div style="background-color:#083d5d; color:#FFFFFF">
        	<table>
            	<tr style="height:15px">
                	<td style="width:5%;">Qté</td>
                	<td style="width:95%">Désignation</td>
           		</tr>
            </table>
   		</div>
       
    <?php
		
		if($donnees_eleve_classe)
		{	
			$matieres_enseignees = $bdd->prepare('SELECT matieres_enseignees.designationMatiere AS 
			designationMatiere, matieres_enseignees.idMatiereEnseignee AS idMatiereEnseignee FROM 
			classes_matieres_enseignees INNER JOIN matieres_enseignees ON 
			(classes_matieres_enseignees.idMatiereEnseignee = matieres_enseignees.idMatiereEnseignee)
			WHERE idClasse= ?');
			$matieres_enseignees->execute(array($idClasse));	
		
			while ($donnees_matieres_enseignees= $matieres_enseignees->fetch()){?>
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
                    <td style="width:5%">
                    	<?php echo $donnees_fournitures_scolaires['quantite'];?> 
                    </td>
                    <td style="width:95%">
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