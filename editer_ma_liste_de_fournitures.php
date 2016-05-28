<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Professeur')?>

<title>Éditer ma lister de fournitures scolaires - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gerer-mes-listes-de-fournitures')</script>

<style type="text/css">

th{
	font-weight:bold;	
	background-color:#D3D3D3;
	text-align:left;
}

</style>



<?php $idListeFournituresScolaires = $_GET['idListeFournituresScolaires']; ?>

<!-- Traitement POST / GET   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['ajouter_fourniture'])){
			ajouter_fourniture($idListeFournituresScolaires, $_POST['designation'], $_POST['quantite']);
		}
		elseif(isset($_POST['maj_quantite_fourniture'])){
			modifier_quantite_fourniture($_POST['quantite'], $_POST['idFournitureScolaire']);	
		}
	}
	if(!empty($_GET)){
		if(!empty($_GET['supprimer_fourniture'])){
			supprimer_fourniture($_GET['supprimer_fourniture']);
		}
	}
?>

<!-- Contenu de la page   -->


<?php include'./inc/db.php'; ?>
<?php $idUtilisateur = $_SESSION['idUtilisateur']; ?>
<?php $listes_fournitures_scolaires_utilisateurs  = $bdd->prepare("SELECT idUtilisateur FROM listes_fournitures_scolaires_utilisateurs WHERE idUtilisateur = ? AND idListeFournituresScolaires = ?");
$listes_fournitures_scolaires_utilisateurs->execute(array($idUtilisateur, $idListeFournituresScolaires));

$donnees_listes_fournitures_scolaires_utilisateurs = $listes_fournitures_scolaires_utilisateurs ->fetch(); ?>

<?php if($donnees_listes_fournitures_scolaires_utilisateurs ){ ?>

	 
	<?php $niveau_matiere = $bdd->prepare("SELECT niveau, designationMatiere FROM listes_fournitures_scolaires_utilisateurs WHERE idListeFournituresScolaires = ?");
	$niveau_matiere->execute(array($idListeFournituresScolaires));
	$donnees_niveau_matiere  = $niveau_matiere  ->fetch(); ?>


    <div class="titre">
    <a href="gestion_fournitures_scolaires.php?profil=&page=gere_mes_listes_de_fournitures"  class=" titre_back">
	<span class="pointeur_dessus">Liste des fournitures</span></a> : Éditer la liste (<?php echo $donnees_niveau_matiere['designationMatiere'].' - '.$donnees_niveau_matiere['niveau']; ?>)
       
    </div>
     
    
    
    <!-- Affichage et remise à zéro des alertes -->

<div class="bloc_succes"><?php if(isset($_SESSION['flash']['succes'])){ ?>
	<?php echo $_SESSION['flash']['succes'];} ?> </div>

<div class="bloc_erreur"><?php if(isset($_SESSION['flash']['erreur'])){ ?>
	<?php echo $_SESSION['flash']['erreur'];} ?> </div>

<div><?php if(isset($_SESSION['flash']['succes'])){ unset($_SESSION['flash']['succes']);} ?> </div>
<div><?php if(isset($_SESSION['flash']['erreur'])){ ?>
<?php unset($_SESSION['flash']['erreur']);} ?> </div>
    
    <?php $professeur_enseigne_matiere = $bdd->query("SELECT quantite FROM fournitures_matieres_enseignees WHERE idUtilisateur ='$idUtilisateur' AND idListeFournituresScolaires ='$idListeFournituresScolaires'");
    $donnees_professeur_enseigne_matiere = $professeur_enseigne_matiere ->fetch(); ?>
    
    <?php if($donnees_professeur_enseigne_matiere){ ?>
        
    <div class="paragraphe">
        <table style="width:100%" id="tableau_fournitures_scolaires">
            <tr>
                <th style="width:10%; text-align:center">Quantité</td>
                <th style="width:80%">Désignation</td>
                <th style="width:10%; text-align:center">Action(s)</td>
            </tr>
                
            <?php $professeur_enseigne_matiere = $bdd->prepare("SELECT quantite, designation, idFournitureScolaire FROM fournitures_matieres_enseignees WHERE idUtilisateur = ? AND 
			idListeFournituresScolaires = ?");
			$professeur_enseigne_matiere->execute(array($idUtilisateur, $idListeFournituresScolaires));?>
                
                
                <?php while ($donnees_professeur_enseigne_matiere = $professeur_enseigne_matiere->fetch()){ ?>
            
            <tr>
                <td style="text-align:center;">
                <form action="gestion_fournitures_scolaires.php?page=editer_ma_liste_de_fournitures&idListeFournituresScolaires=<?php echo $idListeFournituresScolaires ?>" method="post"  id="<?php echo $donnees_professeur_enseigne_matiere[
				'idFournitureScolaire'] ?>">
                <?php echo"<input type=\"number\" name=\"quantite\" min=\"1\" value=\"".
				$donnees_professeur_enseigne_matiere['quantite']."\" style=\"width:50px; margin-top:10px\" onChange=\"maj_quantite(".
				$donnees_professeur_enseigne_matiere[
				'idFournitureScolaire'].")\">"?>
           		<input type="hidden" name="maj_quantite_fourniture"  value="1">
                
				 <?php echo"<input type=\"hidden\" name=\"idFournitureScolaire\" value=\"".
				$donnees_professeur_enseigne_matiere[
				'idFournitureScolaire']."\">"?>
                
                </form>
                
                </td>
                <td><?php echo $donnees_professeur_enseigne_matiere['designation'] ?></td>
                <td style="line-height:10px; text-align:center">
                <a href="gestion_fournitures_scolaires.php?page=editer_ma_liste_de_fournitures&idListeFournituresScolaires=<?php echo $idListeFournituresScolaires?>&supprimer_fourniture=<?php echo $donnees_professeur_enseigne_matiere['idFournitureScolaire'] ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette fourniture ?'));">
                    <img src="images/supprimer_15px.png" class="pointeur_dessus"></td></a>
            </tr>
            
            <?php } $professeur_enseigne_matiere->closeCursor();?>
            <form method="post" action="gestion_fournitures_scolaires.php?page=editer_ma_liste_de_fournitures&idListeFournituresScolaires=<?php echo $idListeFournituresScolaires?>" name="ajouter_fournitures_scolaires">
            <tr>
            	<td style="text-align:center">
                	<input type="number" name="quantite" id="quantite" autocomplete="off" min="1"  value=
                    "1" style="width:50px; margin-top:10px;"/></td>
                <td>
                	<input type="text" name="designation" id="designation" autocomplete="off" style="
                    margin-top:10px; width:100%" />
                </td>
                <td style="text-align:center">
                	<img src="images/ajouter.png" height="14" class="pointeur_dessus" onclick=
                    "ajouter_fournitures_scolaires.submit();" style="margin-top:8px">
                </td>
            </tr>
            
            <input type="hidden" value="a" name="ajouter_fourniture" />
            
            </form>
        </table>
        <script >AlternerCouleur(document.getElementById('tableau_fournitures_scolaires'))</script> 
    </div>
    <?php } else{ ?>
    <div class="paragraphe">
      <table style="width:100%" id="tableau_fournitures_scolaires">
            <tr>
                <th style="width:10%; text-align:center">Quantité</td>
                <th style="width:80%">Désignation</td>
                <th style="width:10%; text-align:center">Action(s)</td>
            </tr>
            
            <form method="post" action="gestion_fournitures_scolaires.php?page=editer_ma_liste_de_fournitures&idListeFournituresScolaires=<?php echo $idListeFournituresScolaires?>" name="ajouter_fournitures_scolaires">
        <tr>
            	<td style="text-align:center">
                	<input type="number" name="quantite" id="quantite" autocomplete="off" min="1"  value=
                    "1" style="width:50px; margin-top:10px;"/></td>
                <td>
                	<input type="text" name="designation" id="designation" autocomplete="off" style="
                    margin-top:10px; width:100%" />
                </td>
                <td style="text-align:center">
                	<img src="images/ajouter.png" height="14" class="pointeur_dessus" onclick=
                    "ajouter_fournitures_scolaires.submit();"  style="
                    margin-top:8px">
                </td>
            </tr>
            <input type="hidden" value="a" name="ajouter_fourniture" />
            </form>
        </table>
    </div>
    
    <?php } ?>
<?php } else{ ?>
	<div class="paragraphe">
    	Vous n'avez accès à cette liste de fournitures scolaires.
    
    </div>
 <?php } ?>
    


