<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Gestion des listes de fournitures scolaires</title>
<link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
<link rel="icon" href="./images/favicon.ico" type="image/x-icon">
<link href="style.css" rel="stylesheet" type="text/css" />
<?php session_start(); ?>

</head>

<body class="fond_degrade">

<!-------------------------------->
<!-------------------------------->

	<div id="header">
    	<div id="header_centre">
        	<div id="header_centre_gauche">Gestion des listes de fournitures scolaires</div>
            <div id="header_centre_droite">
            	<form action="connexion.php" method="POST">
                	<?php if(isset($_SESSION['recuperation']['login']) AND !empty($_SESSION['recuperation']['login'])){?>                
                    	 <?php echo"<input type=\"text\" name=\"login\" value=\"".$_SESSION['recuperation']['login']."\" >"?>
                    <?php } else{ ?>
                    	<input type="text" name="login" placeholder="Identifiant">
                    <?php } ?>
                    <input type="password" name="mot_de_passe" placeholder="Mot de passe">
                    <input type="submit" value="Se connecter">
                </form>
            </div>
        </div>
    </div>
    
<!-------------------------------->
<!-------------------------------->


	<div id="image_accueil">
    	<img src="images/fond_bloc_page.jpg" class="bords_ombres" width="100%">
    </div>
    

<!-------------------------------->
<!-------------------------------->

	<?php include './footer.php'?>

<!-------------------------------->
<!-------------------------------->

<?php if(isset($_SESSION['flash']['erreur'])){
	$erreur = $_SESSION['flash']['erreur'];
	
	echo '<script type="text/javascript">alert("'.$erreur.'");</script>';	
} ?>

<?php if(isset($_SESSION['flash']['erreur'])){ unset($_SESSION['flash']['erreur']);} ?>
<?php if(isset($_SESSION['recuperation']['login'])){ unset($_SESSION['recuperation']['login']);} ?>

</body>
</html>
