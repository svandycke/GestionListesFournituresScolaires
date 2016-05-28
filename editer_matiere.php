<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Administration')?>

<!-- Pramètres de la page   -->

<title>Éditer la matière - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gestion-des-matieres')</script>

<!-- Style de la page -->

<style type="text/css">

table{
	border-collapse:collapse;	
}

tr{
	height:30px;
}

th{
	font-weight:bold;	
	background-color:#D3D3D3;
	text-align:left;
}

#img-ajouter-classe{
	position:absolute;
	right:0;	
	top:0;
}

</style>

<?php $id = $_GET['id']; ?>

<!-- Traitement POST   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['modifier_matiere'])){
			modifier_matiere($_POST['idMatiereEnseignee'], $_POST['idUtilisateur'], $_POST['matiere']);
		}
	}
?>

<!-- Contenu de la page   -->

<div class="titre">
	<a href="gestion_fournitures_scolaires.php?profil=&page=gestion_des_matieres"  class=" titre_back">
	<span class="pointeur_dessus">Liste des matières</span></a> : Éditer la matière
</div>
<div id="bloc_ajouter" style="display:block">

<?php $idMatiereEnseignee = $_GET['id']; ?>
<?php include'./inc/db.php'; ?>

<?php 

	$matiere_enseignee = $bdd->prepare('SELECT matieres_enseignees.idMatiereEnseignee, matieres_enseignees.designationMatiere, utilisateurs.nom AS nom, utilisateurs.prenom AS prenom, utilisateurs.date_naissance AS date_naissance, utilisateurs.id AS idUtilisateur FROM matieres_enseignees INNER JOIN utilisateurs ON (matieres_enseignees.idUtilisateur = utilisateurs.id) WHERE idMatiereEnseignee= ?');
	$matiere_enseignee->execute(array($id));

?>

<?php $donnees_matiere_enseignee = $matiere_enseignee->fetch(); ?>

	<form method="post" action="gestion_fournitures_scolaires.php?page=editer_matiere&id=<?php echo $id; ?>">
	<div>
  		<?php echo"<input type=\"hidden\" name=\"idMatiereEnseignee\" value=\"".
		$donnees_matiere_enseignee['idMatiereEnseignee']."\">"?>   
        
		<?php echo"<input type=\"hidden\" name=\"idUtilisateur\" value=\"".
		$donnees_matiere_enseignee['idUtilisateur']."\">"?>  
    
         <label for="nom">Nom :</label>
		<?php echo"<input type=\"text\" name=\"nom\" value=\"".$donnees_matiere_enseignee['nom']."\" 
		readonly=\"readonly\" class=\"fond_grise\">"?> 
        
         <label for="prenom">Prénom :</label>
		<?php echo"<input type=\"text\" name=\"prenom\" value=\"".$donnees_matiere_enseignee['prenom'].
		"\" readonly=\"readonly\" class=\"fond_grise\">"?>  
        
         <label for="date_naissance">Date de naissance :</label>
          <?php $date_naissance =  date("d/m/Y", strtotime($donnees_matiere_enseignee[
		'date_naissance'])) ?>
		<?php echo"<input type=\"text\" name=\"date_naissance\" value=\"".$date_naissance."\" readonly=\"readonly\" class=\"fond_grise\">"?>   
    
        <label for="matiere">Matière :</label>
		<?php echo"<input type=\"text\" name=\"matiere\" value=\"".$donnees_matiere_enseignee['designationMatiere']."\">"?>
       
  	</div>
    <div id="bouton">
        <input type="submit" value="Modifier la matière" name="modifier_matiere" />
    </div>
    </form>
</div>

<!-- Affichage et remise à zéro des alertes -->

<div class="bloc_succes"><?php if(isset($_SESSION['flash']['succes'])){ ?>
	<?php echo $_SESSION['flash']['succes'];} ?> </div>

<div class="bloc_erreur"><?php if(isset($_SESSION['flash']['erreur'])){ ?>
	<?php echo $_SESSION['flash']['erreur'];} ?> </div>

<div><?php if(isset($_SESSION['flash']['succes'])){ unset($_SESSION['flash']['succes']);} ?> </div>
<div><?php if(isset($_SESSION['flash']['erreur'])){ unset($_SESSION['flash']['erreur']);} ?> </div>


