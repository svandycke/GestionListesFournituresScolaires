<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Administration')?>

<!-- Pramètres de la page   -->

<title>Éditer l'utilisateur - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gestion-des-utilisateurs')</script>

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
		if(isset($_POST['modifier_utilisateur'])){
			modifier_utilisateur($_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['email'], $_POST['login'], $_POST['profil'], $id);
		}
		elseif(isset($_POST['reinitialiser_mot_de_passe'])){
			reinitialiser_mot_de_passe($id);
		}
	}
?>

<!-- Contenu de la page   -->

<div class="titre">
	<a href="gestion_fournitures_scolaires.php?profil=&page=gestion_des_utilisateurs"  class=" titre_back">
	<span class="pointeur_dessus">Liste des utilisateurs</span></a> : Éditer l'utilisateur
</div>
<div id="bloc_ajouter" style="display:block">


<?php include'./inc/db.php'; ?>

<?php $info_utilisateur = $bdd->prepare("SELECT nom, prenom, date_naissance, profil, login, email FROM utilisateurs WHERE id= ?");?>
<?php $info_utilisateur->execute(array($id));?>
<?php $donnees_info_utilisateur = $info_utilisateur->fetch(); ?>


	<form method="post" action="gestion_fournitures_scolaires.php?page=editer_utilisateur&id=<?php echo $id; ?>">
	<div>
        <?php if(isset($_SESSION['valeursform']['nom'])){
		$donnees_info_utilisateur['nom'] = 	$_SESSION['valeursform'][
		'nom'];}?>
        <label for="nom">Nom :</label>
		<?php echo"<input type=\"text\" name=\"nom\" value=\"".$donnees_info_utilisateur['nom']."\">"?>
        
        <?php if(isset($_SESSION['valeursform']['prenom'])){
		$donnees_info_utilisateur['prenom'] = 	$_SESSION['valeursform'][
		'prenom'];}?>
        
        <label for="prenom">Prénom :</label>
        <?php echo"<input type=\"text\" name=\"prenom\" value=\"".$donnees_info_utilisateur['prenom']."\" >"?>
        
        <?php if(isset($_SESSION['valeursform']['date_naissance'])){
		$donnees_info_utilisateur['date_naissance'] = 	$_SESSION['valeursform'][
		'date_naissance'];}?>
        
        <?php $date_naissance =  date("d/m/Y", strtotime($donnees_info_utilisateur['date_naissance'])) ?>
        
        <label for="date_naissance">Date de naissance :</label>
	<?php echo"<input type=\"text\" id=\"date_naissance\" name=\"date_naissance\" value=\"".$date_naissance."\">"?>
        
        <label for="login">Login :</label>	
		<?php echo"<input type=\"text\" name=\"login\" value=\"".$donnees_info_utilisateur['login']."\" readonly=\"readonly\" class=\"fond_grise\">"?>
        
        <?php if(isset($_SESSION['valeursform']['email'])){
		$donnees_info_utilisateur['email'] = 	$_SESSION['valeursform'][
		'email'];}?>
        
        <label for="email">Email :</label>
        <?php echo"<input type=\"email\" name=\"email\" value=\"".$donnees_info_utilisateur['email']."\">"?>
        <label for="profil">Profil :</label>
	<?php echo"<input type=\"text\" name=\"profil\" value=\"".$donnees_info_utilisateur['profil']."\" readonly=\"readonly\" class=\"fond_grise\">"?>

  	</div>
    <div id="bouton">
        <input type="submit" value="Modifier l'utilisateur" name="modifier_utilisateur" />
        <input type="submit" value="Réinitialiser le mot de passe" name="reinitialiser_mot_de_passe" onclick="return(confirm('Etes-vous sûr de vouloir réinitialiser le mot de passe ?'));" />
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

<?php if(isset($_SESSION['valeursform'])){ unset($_SESSION['valeursform']);} ?> 
