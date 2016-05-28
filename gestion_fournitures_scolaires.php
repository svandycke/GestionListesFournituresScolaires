<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
<link rel="icon" href="./images/favicon.ico" type="image/x-icon">
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./fonctions.js"></script>

</head>

<body>

<!-------------------------------->
<!-------------------------------->

<?php include("fonctions.php") ?>

<?php utilisateur_connecte(); ?>

<!-------------------------------->
<!-------------------------------->

	<div id="header">
    	<div id="header_centre">
        	<div id="header_centre_gauche">Gestion des listes de fournitures scolaires</div>
            <div id="header_centre_droite">
            	<form action="deconnexion.php"> 
                	<span id="header_centre_droite_profil" class="pointeur_dessus text_souligne">
						<?php // Nom de la personne connectée ?>                    
                        <?php $login = $_SESSION['login']; ?>
                        <?php include'./inc/db.php'; ?>
                        <?php $info_utilisateur = $bdd->prepare('SELECT nom, prenom FROM utilisateurs WHERE login = ?');?>
						<?php $info_utilisateur ->execute(array($login));?>
                        <?php $donnees_info_utilisateur = $info_utilisateur->fetch(); ?>
                        <a href="gestion_fournitures_scolaires.php?page=profil">
                        
                        <?php echo $donnees_info_utilisateur['nom']." ".$donnees_info_utilisateur['prenom']." (profil)" ?> </a>
                        <?php $info_utilisateur->closeCursor(); ?>
					</span>   
                    <input type="submit" value="Se déconnecter">
                </form>
            </div>
        </div>
    </div>
    
<!-------------------------------->
<!-------------------------------->

<div id="bloc_page">
	<div id="menu">
    	<ul class="bords_blancs_ombres" id="navigation">
        <div id="menu_titre">Navigation</div>
            	<?php if($_SESSION['profil'] == "Élève"){ ?>
					<a href="gestion_fournitures_scolaires.php?page=consulter_ma_liste_de_fournitures">
                    <li id="menu-consulter-ma-liste-de-fournitures">Consulter ma liste de fournitures</li></a>
                    <a href="gestion_fournitures_scolaires.php?page=jai_je_n_ai_pas">
                    <li id="menu-j-ai-je-n-ai-pas">J'ai / Je n'ai pas</li></a>
				<?php } elseif($_SESSION['profil'] == "Professeur"){ ?>
                	<a href="gestion_fournitures_scolaires.php?page=gerer_mes_listes_de_fournitures">
                    <li id="menu-gerer-mes-listes-de-fournitures">Gérer mes listes de fournitures</li> </a>           
                <?php } elseif($_SESSION['profil'] == "Administration"){ ?>
                 	<a href="gestion_fournitures_scolaires.php?page=gestion_des_utilisateurs">
                    	<li id="menu-gestion-des-utilisateurs">Gestion des utilisateurs</li>
                    </a>
                    <a href="gestion_fournitures_scolaires.php?page=gestion_des_classes">
                    <li id="menu-gestion-des-classes">Gestion des classes</li></a>  
                    <a href="gestion_fournitures_scolaires.php?page=gestion_des_matieres">
                    <li id="menu-gestion-des-matieres">Gestion des matières</li>
                   </a>  
                    <a href="gestion_fournitures_scolaires.php?page=graphique_nombre_fournitures_scolaires_par_matière">
                    <li id="menu-graphique">Histogrammes des fournitures</li> </a>   
                <?php } ?>
         </ul>
    </div>	
    
    <div id="corps_de_page">
    	<?php 	if ($_GET['page'] == "gestion_des_utilisateurs") {
					include("gestion_des_utilisateurs.php");}
				   
				elseif($_GET['page'] == "gestion_des_classes"){
					include("gestion_des_classes.php");}
					
				elseif($_GET['page'] == "consulter_ma_liste_de_fournitures"){
					include("consulter_ma_liste_de_fournitures.php");}
					
				elseif($_GET['page'] == "graphique_nombre_fournitures_scolaires_par_matière"){
				include("graphique_nombre_fournitures_scolaires_par_matière.php");}
				
				elseif($_GET['page'] == "gerer_mes_listes_de_fournitures"){
				include("gerer_mes_listes_de_fournitures.php");}
	
				elseif($_GET['page'] == "editer_ma_liste_de_fournitures"){
				include("editer_ma_liste_de_fournitures.php");}
				
				elseif($_GET['page'] == "editer_classe"){
				include("editer_classe.php");}
				
				elseif($_GET['page'] == "editer_utilisateur"){
				include("editer_utilisateur.php");}
				
				elseif($_GET['page'] == "gestion_des_matieres"){
				include("gestion_des_matieres.php");}
				
				elseif($_GET['page'] == "editer_matiere"){
				include("editer_matiere.php");}

				elseif($_GET['page'] == "profil"){
				include("profil.php");}
				
				elseif($_GET['page'] == "importer_liste_utilisateurs"){
				include("importer_liste_utilisateurs.php");}
				
				elseif($_GET['page'] == "jai_je_n_ai_pas"){
				include("jai_je_n_ai_pas.php");}
				
				else {
					if($_SESSION['profil'] == "Professeur"){ 
                		include("gerer_mes_listes_de_fournitures.php");
					}	
						
					elseif($_SESSION['profil'] == "Administration"){
						include("gestion_des_utilisateurs.php");
					}
					
					elseif($_SESSION['profil'] == "Élève"){
						include("consulter_ma_liste_de_fournitures.php");
					}
				}
	?>
    </div>
</div>


<!-------------------------------->
<!-------------------------------->

<?php include 'footer.php' ?>

<!-------------------------------->
<!-------------------------------->

</body>
</html>
