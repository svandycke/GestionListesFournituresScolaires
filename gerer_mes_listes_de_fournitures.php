<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Professeur')?>

<!-- Pramètres de la page   -->

<title>Gérer mes listes de fournitures - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gerer-mes-listes-de-fournitures')</script>

<!-- Style de la page -->

<style type="text/css">


.zebre tbody td:nth-child(4) {text-align:center;}
.zebre tbody td:nth-child(3) {text-align:center;}
.zebre tbody td:nth-child(5) {text-align:center; line-height:10px;}

</style>

<!-- Traitement POST / GET   -->

<?php   
	if(!empty($_POST)){
		
	}
	if(!empty($_GET)){
		if(!empty($_GET['supprimer_liste_fournitures'])){
			supprimer_listes_fournitures_scolaires($_GET['supprimer_liste_fournitures'], 
			$_GET['niveau'], $_GET['idMatiereEnseignee']);	
		}
	}
?>

<!-- Contenu de la page   -->

<div class="titre">
	Gérer mes listes de fournitures
</div>

<!-- Affichage et remise à zéro des alertes -->

<div class="bloc_succes"><?php if(isset($_SESSION['flash']['succes'])){ ?>
	<?php echo $_SESSION['flash']['succes'];} ?> </div>

<div class="bloc_erreur"><?php if(isset($_SESSION['flash']['erreur'])){ ?>
	<?php echo $_SESSION['flash']['erreur'];} ?> </div>

<div><?php if(isset($_SESSION['flash']['succes'])){ unset($_SESSION['flash']['succes']);} ?> </div>
<div><?php if(isset($_SESSION['flash']['erreur'])){ unset($_SESSION['flash']['erreur']);} ?> </div>


<div class="nouveau_bloc">
	<table style="width:100%" id="listes_fournitures_scolaires" class="avectri zebre">
        <thead>
            <tr>
                <th style="width:23%">Matière enseignée</th>
                <th style="width:23%">Niveau scolaire</th>
                <th style="width:20%; text-align:center">Nb.classes</th>
                <th style="width:20%; text-align:center">Nb. fournitures</th>
                <th style="width:14%; text-align:center">Action(s)</th>
            </tr>   
        </thead>
             <?php include'./inc/db.php'; ?>
             <?php $login = $_SESSION['login']; ?>
             
             <?php $liste_fournitures  = $bdd->prepare('SELECT matieres_enseignees.designationMatiere AS 	
			 designationMatiere, listes_fournitures_scolaires.niveau AS niveau, 
			 listes_fournitures_scolaires.idListeFournituresScolaires AS idListeFournituresScolaires,  	
			 matieres_enseignees.idMatiereEnseignee FROM listes_fournitures_scolaires 
			 INNER JOIN matieres_enseignees ON (listes_fournitures_scolaires.idMatiereEnseignee = 
			 matieres_enseignees.idMatiereEnseignee) INNER JOIN utilisateurs ON 
			 (matieres_enseignees.idUtilisateur = utilisateurs.id) WHERE utilisateurs.login = ? 
			 ORDER BY designationMatiere ASC, niveau ASC');
			 $liste_fournitures ->execute(array($login));
		
		?>
		<tbody>
        <?php while ($donnees_liste_fournitures = $liste_fournitures->fetch()){ ?>        
        <tr>
        	<td><?php echo $donnees_liste_fournitures['designationMatiere']; ?></td>
            <td><?php echo $donnees_liste_fournitures['niveau']; ?></td>

        	<td style="text-align:center">
            
				<?php $niveau = $donnees_liste_fournitures['niveau']; ?>
                <?php $idMatiereEnseignee = $donnees_liste_fournitures['idMatiereEnseignee']; ?>  
                
                <?php
				
				$nb_classes  = $bdd->prepare('SELECT 
                COUNT(classes_matieres_enseignees.idClasseMatiereEnseignee) 
                FROM classes_matieres_enseignees
                INNER JOIN classes ON (classes_matieres_enseignees.idClasse = classes.idClasse) 
                WHERE classes.niveau = ? AND classes_matieres_enseignees.idMatiereEnseignee = ?');
				$nb_classes ->execute(array($niveau, $idMatiereEnseignee));
    
                $donnees_nb_classes= $nb_classes->fetch();	echo $donnees_nb_classes[0];
                ?>
            
            
            </td>
            <td style="text-align:center">
            
            	<?php $idListeFournituresScolaires = $donnees_liste_fournitures[
				'idListeFournituresScolaires']; ?>
				
                <?php $qte_fournitures = $bdd->query("SELECT SUM(quantite) AS total_quantite_fournitures 
				FROM fournitures_scolaires WHERE idListeFournituresScolaires = 
				'$idListeFournituresScolaires'"); 
				
				$qte_fournitures= $bdd->prepare('SELECT SUM(quantite) AS total_quantite_fournitures 
				FROM fournitures_scolaires WHERE idListeFournituresScolaires = ?');
				$qte_fournitures->execute(array($idListeFournituresScolaires));
				
				$donnees_qte_fournitures= $qte_fournitures->fetch();	
				
				if(empty($donnees_qte_fournitures[0])){
					echo '0';	
				}
				else{
					echo $donnees_qte_fournitures[0];	
				}?>                  
            </td>
            <td style="line-height:10px; text-align:center">
            	<a href=				"gestion_fournitures_scolaires.php?page=editer_ma_liste_de_fournitures&idListeFournituresScolaires=<?php echo $donnees_liste_fournitures['idListeFournituresScolaires'] ?>"><img src="images/modifier_1_15px.png" class="pointeur_dessus"></a>
                
                <?php if(empty($donnees_nb_classes[0])){ ?>
                <a href=				"gestion_fournitures_scolaires.php?page=gerer_mes_listes_de_fournitures&supprimer_liste_fournitures=<?php echo $donnees_liste_fournitures['idListeFournituresScolaires'] ?>&niveau=<?php echo $donnees_liste_fournitures['niveau'] ?>&idMatiereEnseignee=<?php echo $donnees_liste_fournitures['idMatiereEnseignee'] ?>">
					<img src="images/supprimer_15px.png" class="pointeur_dessus" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette liste ?'));"></a>
				<?php } ?>
           	</td>
       	</tr>
        
        <?php }  $liste_fournitures->closeCursor(); ?>
        </tbody>
       	
	</table>  
    <script >AlternerCouleur(document.getElementById('listes_fournitures_scolaires'))</script>        
</div>
    
    


