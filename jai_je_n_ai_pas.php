<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Élève')?>

<!-- Pramètres de la page   -->

<title>J'ai / Je n'ai pas - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-j-ai-je-n-ai-pas')</script>

<!-- Style de la page -->

<style type="text/css">

#img-telecharger-pdf{
	position:absolute;
	right:0;	
	top:0;
}

.souris_dessus:hover{
	cursor:move;
	background-color:#e6e6e6;
}

</style>

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
    	J'ai / Je n'ai pas
       	<?php if($donnees_eleve_classe){
			
			$idClasse = $donnees_eleve_classe['idClasse'];
			
			$details_classe = $bdd->prepare('SELECT niveau, groupe FROM classes WHERE 
			idClasse= ?');
			$details_classe->execute(array($idClasse));			
			$donnees_details_classe = $details_classe->fetch();
	
			$niveau = $donnees_details_classe['niveau']; 
			$groupe = $donnees_details_classe['groupe'];
			
			 
		}?>
         
	</div>
    <div class="paragraphe">
    <div class="sous_titre">Fournitures scolaires</div>
    	
       
    <?php
		
		if($donnees_eleve_classe)
		{
			$matieres_enseignees = $bdd->prepare('SELECT matieres_enseignees.designationMatiere AS 
			designationMatiere, matieres_enseignees.idMatiereEnseignee AS idMatiereEnseignee FROM 
			classes_matieres_enseignees INNER JOIN matieres_enseignees ON 
			(classes_matieres_enseignees.idMatiereEnseignee = matieres_enseignees.idMatiereEnseignee)
			WHERE idClasse= ?');
			$matieres_enseignees->execute(array($idClasse));	 ?>
			
			<div style="border:1px solid #CBCBCB; padding-bottom:25px" ondrop="drop(event)" ondragover="allowDrop(event)">
		
			<?php while ($donnees_matieres_enseignees= $matieres_enseignees->fetch()){?>
            <?php $idMatiereEnseignee = $donnees_matieres_enseignees['idMatiereEnseignee']; ?>
		

                <?php 
				
				$liste   = $bdd->prepare('SELECT idListeFournituresScolaires FROM 
				listes_fournitures_scolaires WHERE idMatiereEnseignee = ? AND niveau = ?');
				$liste ->execute(array($idMatiereEnseignee, $niveau));
				
				
				$donnees_liste = $liste->fetch();
				
				$idListeFournituresScolaires = $donnees_liste['idListeFournituresScolaires'];
				
				$fournitures_scolaires  = $bdd->prepare('SELECT quantite, designation, idFournitureScolaire FROM 
				fournitures_scolaires WHERE idListeFournituresScolaires= ?');
				$fournitures_scolaires  ->execute(array($idListeFournituresScolaires));
				
				?>
				

				<?php 
				while ($donnees_fournitures_scolaires = $fournitures_scolaires->fetch()){?>
                
                	<div id="<?php echo $donnees_fournitures_scolaires['idFournitureScolaire'];?>" draggable="true" ondragstart="drag(event)" class="souris_dessus">   
                    	
						<?php echo $donnees_fournitures_scolaires['quantite'];?> 

						<?php echo $donnees_fournitures_scolaires['designation']; ?>
					</div>
                
                
                <?php } $fournitures_scolaires ->closeCursor(); ?> 

                   	
			<?php } $matieres_enseignees->closeCursor(); ?>
            </div>
            <?php 
			
		}
		else
		{
			echo "Aucune classe ne vous a été attribué pour le moment.";		
		}
		

		if($donnees_eleve_classe)
		{?>
        
            <div class="paragraphe">
    			<div class="sous_titre">J'ai</div>
            	<div style="border:1px solid #CBCBCB; padding-bottom:25px" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
    		</div>
        
            <div class="paragraphe">
    			<div class="sous_titre">Je n'ai pas</div>
            	<div style="border:1px solid #CBCBCB; padding-bottom:25px" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
    		</div>
			
		<?php }
	
	
	
	 ?>
	</div>
    
    <script>
	function allowDrop(ev) {
		ev.preventDefault();
	}
	
	function drag(ev) {
		ev.dataTransfer.setData("text", ev.target.id);
	}
	
	function drop(ev) {
		ev.preventDefault();
		var data = ev.dataTransfer.getData("text");
		ev.target.appendChild(document.getElementById(data));
	}
	</script>