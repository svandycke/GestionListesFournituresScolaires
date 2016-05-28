<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>

<!-- Pramètres de la page   -->

<title>Profil - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />


<!-- Style de la page -->

<style type="text/css">



#img-ajouter-classe{
	position:absolute;
	right:0;	
	top:0;
}



</style>

<?php $id = $_SESSION['idUtilisateur']; ?>

<!-- Traitement POST   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['modifier_profil'])){
			modifier_profil_informations($id, $_POST['email'], $_POST['date_naissance']);	
		}
		elseif($_POST['modifier_mot_de_passe']){
			 modifier_profil_mot_de_passe($id, $_POST['ancien_mot_de_passe'], $_POST['nouveau_1_mot_de_passe'], $_POST['nouveau_2_mot_de_passe']);
		}
	
	}
?>

<!-- Contenu de la page   -->

<div class="titre">
	Profil : Modification des informations personelles
</div>

<div id="bloc_ajouter" style="display:block">


<?php include'./inc/db.php'; ?>

<?php $info_utilisateur = $bdd->prepare("SELECT nom, prenom, date_naissance, profil, login, email FROM utilisateurs WHERE id= ?");?>
<?php $info_utilisateur->execute(array($id));?>
<?php $donnees_info_utilisateur = $info_utilisateur->fetch(); ?>


	<form method="post" action="gestion_fournitures_scolaires.php?page=profil">
	<div>
        <label for="nom">Nom :</label>
		<?php echo"<input type=\"text\" name=\"nom\" value=\"".$donnees_info_utilisateur['nom']."\" readonly=\"readonly\" class=\"fond_grise\">"?>
        <label for="prenom">Prénom :</label>
        <?php echo"<input type=\"text\" name=\"prenom\" value=\"".$donnees_info_utilisateur['prenom']."\" readonly=\"readonly\" class=\"fond_grise\">"?>
        
        <?php $date_naissance =  date("d/m/Y", strtotime($donnees_info_utilisateur['date_naissance'])) ?>
        
        <label for="date_naissance">Date de naissance :</label>
	<?php echo"<input type=\"text\" id=\"date_naissance\" name=\"date_naissance\" value=\"".$date_naissance."\">"?>
        
        <label for="login">Login :</label>	
		<?php echo"<input type=\"text\" name=\"login\" value=\"".$donnees_info_utilisateur['login']."\" readonly=\"readonly\" class=\"fond_grise\">"?>
        <label for="email">Email :</label>
        <?php echo"<input type=\"email\" name=\"email\" value=\"".$donnees_info_utilisateur['email']."\">"?>
        <label for="profil">Profil :</label>
        <?php echo"<input type=\"text\" name=\"profil\" value=\"".$donnees_info_utilisateur['profil']."\" readonly=\"readonly\" class=\"fond_grise\">"?>
  	</div>
    <div id="bouton">
        <input type="submit" value="Appliquer les modifications" name="modifier_profil" />
    </div>
    </form>
</div>
<?php if(isset($_POST['modifier_profil'])){ ?>
<!-- Affichage et remise à zéro des alertes -->

<div class="bloc_succes"><?php if(isset($_SESSION['flash']['succes'])){ ?>
	<?php echo $_SESSION['flash']['succes'];} ?> </div>

<div class="bloc_erreur"><?php if(isset($_SESSION['flash']['erreur'])){ ?>
	<?php echo $_SESSION['flash']['erreur'];} ?> </div>

<div><?php if(isset($_SESSION['flash']['succes'])){ unset($_SESSION['flash']['succes']);} ?> </div>
<div><?php if(isset($_SESSION['flash']['erreur'])){ ?>
<?php unset($_SESSION['flash']['erreur']);} ?> </div>

<?php } ?>

<div class="espacement"></div>

<div class="titre">
	Profil : Modification du mot de passe
</div>
<div id="bloc_ajouter" style="display:block">


	<form method="post" action="gestion_fournitures_scolaires.php?page=profil">
	<div>
    	<div>
        	<label for="ancien_mot_de_passe">Ancien mot de passe :</label>
        	<input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe">
       	</div>
        <div>
        	<label for="nouveau_1_mot_de_passe">Nouveau mot de passe :</label>
        	<input type="password" id="nouveau_1_mot_de_passe" name="nouveau_1_mot_de_passe">
            
            <label for="nouveau_2_mot_de_passe">Confirmer mot de passe :</label>
        	<input type="password" id="nouveau_2_mot_de_passe" name="nouveau_2_mot_de_passe">
        </div>
        
  	</div>
    <div id="bouton">
        <input type="submit" value="Appliquer le nouveau mot de passe" name="modifier_mot_de_passe" />
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


