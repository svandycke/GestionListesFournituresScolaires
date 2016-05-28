<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Administration')?>

<!-- Pramètres de la page   -->

<title>Éditer la classe - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gestion-des-classes')</script>



<!-- Style de la page -->

<style type="text/css">

.zebre tbody td:nth-child(5) {text-align:center; line-height:10px;}

#img-ajouter-classe{
	position:absolute;
	right:0;	
	top:0;
}

.select_class{
	padding:4px;
	line-height:20px;
}

</style>

<!-- Traitement POST   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['ajouter_eleve_classe'])){	
			ajouter_eleve_classe($_POST['idEleve'], $_GET['id']);
		}
		elseif(isset($_POST['ajouter_professeur_classe'])){	
			ajouter_professeur_classe($_POST['idProfesseur'], $_GET['id']);
		}
		elseif(isset($_POST['idEleveClasse']) || isset($_POST['idEleve'])){
			include'./inc/db.php';
				
			$idEleveClasse = $_POST['idEleveClasse'];
			foreach($idEleveClasse as $selectValue){	
				$eleve_dans_classe = $bdd->prepare('SELECT idClasse FROM 	
				composition_classes WHERE idUtilisateur=? AND idClasse=?');
				$eleve_dans_classe->execute(array($selectValue, $_GET['id']));	
				$donnees_eleve_dans_classe = $eleve_dans_classe->fetch();
				
				if(!$donnees_eleve_dans_classe && !empty($_GET['id']))
				{
					ajouter_eleve_classe($selectValue, $_GET['id']);
				}
			}
			
			$idEleve =  $_POST['idEleve'];
			foreach($idEleve as $selectValue){	
				$eleve_dans_classe = $bdd->prepare('SELECT idClasse FROM 	
				composition_classes WHERE idUtilisateur=? AND idClasse=?');
				$eleve_dans_classe->execute(array($selectValue, $_GET['id']));	
				$donnees_eleve_dans_classe = $eleve_dans_classe ->fetch();
				
				if($donnees_eleve_dans_classe && !empty($_GET['id']))
				{
					retirer_eleve($selectValue);
				}
			}
			
			$_SESSION['flash']['succes'] = "- Les modifications ont été enregistrées.";
		}
		elseif(isset($_POST['idProfesseurClasse']) || isset($_POST['idProfesseur'])){
			include'./inc/db.php';
			
			$idProfesseurClasse = $_POST['idProfesseurClasse'];
			foreach($idProfesseurClasse as $selectValue){
				$professeur_dans_classe = $bdd->prepare('SELECT idClasse FROM 	
				classes_matieres_enseignees WHERE IdMatiereEnseignee=? AND idClasse=?');
				$professeur_dans_classe->execute(array($selectValue, $_GET['id']));	
				$donnees_professeur_dans_classe = $professeur_dans_classe->fetch();
				
				if(!$donnees_professeur_dans_classe && !empty($_GET['id']))
				{
					ajouter_professeur_classe($selectValue, $_GET['id']);
				}
			}
			
			$idProfesseur = $_POST['idProfesseur'];
			foreach($idProfesseur as $selectValue){
				$professeur_dans_classe = $bdd->prepare('SELECT idClasse FROM 	
				classes_matieres_enseignees WHERE IdMatiereEnseignee=? AND idClasse=?');
				$professeur_dans_classe->execute(array($selectValue, $_GET['id']));	
				$donnees_professeur_dans_classe = $professeur_dans_classe->fetch();
				
				if($donnees_professeur_dans_classe && !empty($_GET['id']))
				{
					retirer_professeur($selectValue, $_GET['id']);
				}
			}
			
			$_SESSION['flash']['succes'] = "- Les modifications ont été enregistrées.";
		}
	}
	if(!empty($_GET)){
		if(!empty($_GET['retirer_eleve'])){
			retirer_eleve($_GET['retirer_eleve']);	
		}
		elseif(!empty($_GET['retirer_professeur'])){
			retirer_professeur($_GET['retirer_professeur']);	
		}
	}
?>

<!-- Contenu de la page   -->

<?php $idClasse = $_GET['id']; ?>

<div class="titre">
	<a href="gestion_fournitures_scolaires.php?page=gestion_des_classes" class="titre_back">Gestion des classes</a> : Éditer la classe (
	<?php if(!empty($_GET['id'])){ 
		$id = $_GET['id'];
		include'./inc/db.php';
		$classe = $bdd->query("SELECT niveau, groupe FROM classes WHERE idClasse ='$id'");
		$donnees_classe = $classe->fetch();	
		
		if($donnees_classe){ 
			echo $donnees_classe['niveau'].' - '.$donnees_classe['groupe']; 
		}
		else{
			echo "La classe saisie n'éxiste pas.";	
		}
	} ?> )
</div>


<!-- Affichage et remise à zéro des alertes -->

<div class="bloc_succes"><?php if(isset($_SESSION['flash']['succes'])){ ?>
	<?php echo $_SESSION['flash']['succes'];} ?> </div>

<div class="bloc_erreur"><?php if(isset($_SESSION['flash']['erreur'])){ ?>
	<?php echo $_SESSION['flash']['erreur'];} ?> </div>

<div><?php if(isset($_SESSION['flash']['succes'])){ unset($_SESSION['flash']['succes']);} ?> </div>
<div><?php if(isset($_SESSION['flash']['erreur'])){ ?>
<script>document.onload = afficher_masquer_bloc_ajouter('bloc_ajouter')</script>
<?php unset($_SESSION['flash']['erreur']);} ?> </div>

<div class="espacement"></div>

<?php if($donnees_classe){  ?>

<div class="titre">
	Liste des élèves
    <div id="img-ajouter-classe">
    	<img src="images/modifier.png" height="20" class="pointeur_dessus" onClick="afficher_masquer_bloc_ajouter('bloc_ajouter')">
    </div>
</div>


<div id="bloc_ajouter">
<form method="post" action="gestion_fournitures_scolaires.php?page=editer_classe&id=<?php echo $idClasse?>" name="modifier_classe_eleves">
	<div> 
    
    	<table style="width:100%">
        	<tr>
            	<td style="width:40%">
                	<select name="idEleve[]" style="width:100%; height:110px; padding:5px;" 
                    size="5" multiple id="idEleve">
        
        			<?php $liste_utilisateurs = $bdd->query('SELECT id, nom, prenom, 
					date_naissance FROM utilisateurs WHERE profil = "Eleve" ORDER BY nom ASC, prenom ASC');?>
        			<?php while ($donnees_liste_utilisateurs = $liste_utilisateurs->fetch()){ 
					?>
                    
                    <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_utilisateurs['date_naissance'])) ?>
        
             		<?php
					$eleve_classe  = $bdd->prepare('SELECT idClasse FROM composition_classes 	
					WHERE idClasse = ? AND idUtilisateur = ?');
					$eleve_classe ->execute(array($idClasse, $donnees_liste_utilisateurs['id']));
					$donnees_eleve_classe  = $eleve_classe ->fetch();
			
					if(!$donnees_eleve_classe){?>
            		<?php echo "<option class=\"select_class\" style=\"\" value='".
					$donnees_liste_utilisateurs['id']."'>".$donnees_liste_utilisateurs['nom'].
					' '.$donnees_liste_utilisateurs['prenom'].' | '.
					$date_naissance."</option>" ?>
            		<?php } ?>
            		<?php } ?>
        			</select>
				</td>
                <td style="width:20%; text-align:center">
                	<input type="Button" value="Ajouter >>" style="width:80px" onClick="SelectMoveRows(document.getElementById('idEleve'),document.getElementById('idEleveClasse'))"><br>
	            <br>
	            <input type="Button" value="<< Retirer" style="width:80px" onClick="SelectMoveRows(document.getElementById('idEleveClasse'),document.getElementById('idEleve'))">
                
                </td>
                <td style="width:40%">
                	<select name="idEleveClasse[]" style="width:100%; height:110px; padding:0 
                    10px;" size="5" multiple id="idEleveClasse">
                
                	<?php $id = $_GET['id']; ?>

          			<?php $liste_eleves = $bdd->prepare("SELECT utilisateurs.id AS idUtilisateur , utilisateurs.nom AS nom, utilisateurs.prenom AS prenom, utilisateurs.date_naissance AS date_naissance, composition_classes.idClasse FROM composition_classes INNER JOIN utilisateurs ON (composition_classes.idUtilisateur = utilisateurs.id) WHERE composition_classes.idClasse = ? AND utilisateurs.profil = 'Eleve' ORDER BY utilisateurs.nom ASC, utilisateurs.prenom ASC, utilisateurs.date_naissance ASC");?>
			<?php $liste_eleves ->execute(array($id));?>
            
            <?php while ($donnees_liste_eleves = $liste_eleves->fetch()){ ?>
            
             <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_eleves['date_naissance'])) ?>
            
            	<?php echo "<option class=\"select_class\" style=\"\" value='".
					$donnees_liste_eleves['idUtilisateur']."'>".$donnees_liste_eleves['nom'].
					' '.$donnees_liste_eleves['prenom'].' | '.
					$date_naissance."</option>" ?>
            <?php } ?>
            <?php $liste_eleves->closeCursor();?>
                	</select>
                </td>
           </tr>
       </table>
    
        
  	</div>
    <div id="bouton">
    	<input type="button" value="Appliquer les modifications" name="appliquer_modifications_classe_eleve" onClick="PostSelect(this.form,'idEleve[]', 'idEleveClasse[]')">
    </div>
 </form>
</div>

<div class="paragraphe" id="bloc_eleves">
    
        <table style="width:100%" id="tableau_eleves" class="avectri zebre">
         	<thead>       
                <tr>
                    <th style="width:35%">Nom</th>
                    <th style="width:35%">Prénom</th>
                    <th style="width:30%">Date de naissance</th>
                </tr> 
            </thead>
            
            <?php $liste_eleves = $bdd->prepare("SELECT utilisateurs.id AS idUtilisateur , utilisateurs.nom AS nom, utilisateurs.prenom AS prenom, utilisateurs.date_naissance AS date_naissance, composition_classes.idClasse FROM composition_classes INNER JOIN utilisateurs ON (composition_classes.idUtilisateur = utilisateurs.id) WHERE composition_classes.idClasse = ? AND utilisateurs.profil = 'Eleve' ORDER BY utilisateurs.nom ASC, utilisateurs.prenom ASC, utilisateurs.date_naissance ASC");?>
			<?php $liste_eleves ->execute(array($id));?>
			<tbody>
			<?php while ($donnees_liste_eleves = $liste_eleves->fetch()){ ?>
            	<tr>
                    <td><?php echo $donnees_liste_eleves['nom']; ?></td>
                    <td><?php echo  $donnees_liste_eleves['prenom']; ?></td>
                    
                    <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_eleves['date_naissance'])) ?>
                    
                    <td><?php echo  $date_naissance; ?></td>
                </tr>
             <?php } ?>
             <?php $liste_eleves->closeCursor();?>
             </tbody>
        </table>
        <script >AlternerCouleur(document.getElementById('tableau_eleves'))</script>  
    </div>
    


<div class="titre" style="margin-top:20px">
	Liste des professeurs
    <div id="img-ajouter-classe">
    	
    	<img src="images/modifier.png" height="20" class="pointeur_dessus" onClick="afficher_masquer_bloc_ajouter('bloc_ajouter_2')">
    </div>
</div>


<div id="bloc_ajouter_2">
<form method="post" action="gestion_fournitures_scolaires.php?page=editer_classe&id=<?php echo $id; ?>">
	<div>
    <table style="width:100%">
        	<tr>
            	<td style="width:40%">
                	<select name="idProfesseur[]" style="width:100%; height:110px; padding:5px;" 
                    size="5" multiple id="idProfesseur">
        
        			<?php $liste_utilisateurs = $bdd->query('SELECT utilisateurs.id, utilisateurs.nom, utilisateurs.prenom, utilisateurs.date_naissance, matieres_enseignees.designationMatiere AS designationMatiere, matieres_enseignees.idMatiereEnseignee AS idMatiereEnseignee FROM utilisateurs INNER JOIN matieres_enseignees ON (utilisateurs.id = matieres_enseignees.idUtilisateur) WHERE utilisateurs.profil = "Professeur" ORDER BY designationMatiere ASC, nom ASC');?>
            
        	<?php while ($donnees_liste_utilisateurs = $liste_utilisateurs->fetch()){ ?>
            
            <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_utilisateurs['date_naissance'])) ?>
            
            <?php 
			
			$professeur_classe  = $bdd->prepare('SELECT idClasse FROM classes_matieres_enseignees WHERE idClasse = ? AND 
			idMatiereEnseignee = ?');
			$professeur_classe ->execute(array($idClasse, $donnees_liste_utilisateurs['idMatiereEnseignee']));
			$donnees_professeur_classe  = $professeur_classe ->fetch();
			
			if(!$donnees_professeur_classe){?>
			 
			 <?php echo "<option class=\"select_class\" value='".$donnees_liste_utilisateurs['idMatiereEnseignee']."'>".
			 $donnees_liste_utilisateurs['designationMatiere'].' | '.
			 $donnees_liste_utilisateurs['nom'].' '.$donnees_liste_utilisateurs['prenom'].' - '.
			 $date_naissance."</option>" ?>
             
             <?php } ?>
             
             
			 <?php }  $liste_utilisateurs->closeCursor(); ?>
        			</select>
				</td>
                <td style="width:20%; text-align:center">
                	<input type="Button" value="Ajouter >>" style="width:80px" onClick="SelectMoveRows(document.getElementById('idProfesseur'),document.getElementById('idProfesseurClasse'))"><br>
	            <br>
	            <input type="Button" value="<< Retirer" style="width:80px" onClick="SelectMoveRows(document.getElementById('idProfesseurClasse'),document.getElementById('idProfesseur'))">
                
                </td>
                <td style="width:40%">
                	<select name="idProfesseurClasse[]" style="width:100%; height:110px; padding:0 
                    10px;" size="5" multiple id="idProfesseurClasse">
                
                	<?php $id = $_GET['id']; ?>

          			<?php $liste_professeurs  = $bdd->prepare("SELECT utilisateurs.nom AS nom, utilisateurs.prenom AS prenom, utilisateurs.date_naissance AS date_naissance,matieres_enseignees.idMatiereEnseignee AS idMatiereEnseignee, matieres_enseignees.designationMatiere AS designationMatiere, classes_matieres_enseignees.idClasseMatiereEnseignee AS idClasseMatiereEnseignee FROM classes_matieres_enseignees INNER JOIN matieres_enseignees ON (classes_matieres_enseignees.idMatiereEnseignee = matieres_enseignees.idMatiereEnseignee) INNER JOIN utilisateurs ON(matieres_enseignees.idUtilisateur = utilisateurs.id) WHERE classes_matieres_enseignees.idClasse = ? AND utilisateurs.profil = 'Professeur' ORDER BY designationMatiere ASC");?>
			<?php $liste_professeurs->execute(array($id));?>
            
           <?php while ($donnees_liste_professeurs = $liste_professeurs->fetch()){ ?>
           
           <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_professeurs['date_naissance'])) ?>
           
            	<?php echo "<option class=\"select_class\" value='".$donnees_liste_professeurs['idMatiereEnseignee']."'>".$donnees_liste_professeurs['designationMatiere'].' | '.
			 $donnees_liste_professeurs['nom'].' '.$donnees_liste_professeurs['prenom'].' - '.
			 $date_naissance."</option>" ?>
            <?php } ?>
            <?php $liste_professeurs->closeCursor();?>
                	</select>
                </td>
           </tr>
       </table>	
		
  	</div>
    <div id="bouton">
    	<input type="button" value="Appliquer les modifications" name="appliquer_modifications_classe_professeur" onClick="PostSelect(this.form,'idProfesseur[]', 'idProfesseurClasse[]')">
    </div>
</form>
</div>

<div class="paragraphe" id="bloc_professeurs">
    
        <table style="width:100%" id="tableau_professeurs" class="avectri zebre">
          	<thead>      
                <tr>
                    <th style="width:23%">Nom</th>
                    <th style="width:23%">Prénom</th>
                    <th style="width:22%">Date de naissance</th>
                    <th style="width:27%">Matière</th>
                </tr> 
         	</thead>
            <?php include'./inc/db.php'; ?>

            <?php $liste_professeurs  = $bdd->prepare("SELECT utilisateurs.nom AS nom, utilisateurs.prenom AS prenom, utilisateurs.date_naissance AS date_naissance, matieres_enseignees.designationMatiere AS designationMatiere, classes_matieres_enseignees.idClasseMatiereEnseignee AS idClasseMatiereEnseignee  FROM classes_matieres_enseignees INNER JOIN matieres_enseignees ON (classes_matieres_enseignees.idMatiereEnseignee = matieres_enseignees.idMatiereEnseignee) INNER JOIN utilisateurs ON(matieres_enseignees.idUtilisateur = utilisateurs.id) WHERE classes_matieres_enseignees.idClasse = ? AND utilisateurs.profil = 'Professeur' ORDER BY designationMatiere ASC");?>
			<?php $liste_professeurs->execute(array($id));?>
            
            
            <tbody>
			<?php while ($donnees_liste_professeurs = $liste_professeurs->fetch()){ ?>
            
        	<tr>
            	<td><?php echo $donnees_liste_professeurs['nom'] ?></td>
                <td><?php echo $donnees_liste_professeurs['prenom'] ?></td>
                
                <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_professeurs['date_naissance'])) ?>
                
                <td><?php echo $date_naissance ?></td>
                <td><?php echo $donnees_liste_professeurs['designationMatiere'] ?></td>
          	</tr>
            <?php }  $liste_professeurs->closeCursor(); ?>
            </tbody>
	 </table>
     <script >AlternerCouleur(document.getElementById('tableau_professeurs'))</script> 
</div>

<div class="titre" style="margin-top:20px">
    Visionner la liste de fournitures scolaires
  	<div id="img-ajouter-classe">    
        <form method="GET" action="consulter_ma_liste_de_fournitures_PDF.php" name="pdf">
                 <img src="images/look.png" height="20" class="pointeur_dessus"  title="Afficher/masquer la liste" alt="Afficher/masquer la liste" onclick="afficher_masquer_bloc_ajouter('liste_fournitures_scolaires');">
    		<img src="images/pdf-icon.png" height="20" class="pointeur_dessus"  title="Télécharger en PDF" alt="Télécharger en PDF" onclick="pdf.submit();">
            <?php echo"<input type=\"hidden\" name=\"id\" value=\"".$idClasse."\">"?>
        </form>
	</div>
</div>

<div class="paragraphe" id="liste_fournitures_scolaires" style="display:none">

         	<div style="background-color:#083d5d; color:#FFFFFF">
        	<table>
            	<tr style="height:15px">
                	<td style="width:5%;">Qté</td>
                	<td style="width:95%">Désignation</td>
           		</tr>
            </table>
   		</div>

<?php 

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
				$liste  ->execute(array($idMatiereEnseignee, $donnees_classe['niveau']));
				
				
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

?>


</div>


<?php } ?>

