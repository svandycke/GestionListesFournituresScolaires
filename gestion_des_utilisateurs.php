<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Administration')?>

<!-- Pramètres de la page   -->

<title>Gestion des utilisateurs - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gestion-des-utilisateurs')</script>

<!-- Style de la page -->

<style type="text/css">

.zebre tbody td:nth-child(5) {text-align:center; line-height:10px;}

#img-ajouter-utilisateur{
	position:absolute;
	right:0;	
	top:0;
}

#bloc_importer_csv{
	height:auto;
	padding:10px 10px 0 10px;
	display:none;
	background-color:#083d5d; 
	color:#FFFFFF;
}

</style>

<!-- Traitement POST   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['ajouter_utilisateur'])){
			ajouter_utilisateur($_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['email'], $_POST['profil']);
		}
		if(isset($_POST['supprimer_multiple_1'])){
			$liste_utilisateurs = $bdd->query('SELECT id FROM utilisateurs');
			while ($donnees_liste_utilisateurs = $liste_utilisateurs->fetch()){ 
				$id = $donnees_liste_utilisateurs['id'];
				if($_POST[$id] == '1')
				{
					supprimer_utilisateur($id);	
				}
			}
			$liste_utilisateurs->closeCursor();
			
		}
	}
	if(!empty($_GET)){
		if(!empty($_GET['supprimer_utilisateur'])){
			supprimer_utilisateur($_GET['supprimer_utilisateur']);	
		}
	}
?>

<!-- Contenu de la page   -->

<div class="titre">
	Liste des utilisateurs
    <div id="img-ajouter-utilisateur">
    <a href="gestion_fournitures_scolaires.php?page=importer_liste_utilisateurs">
    <img src="images/import_fichier.png" height="20" class="pointeur_dessus" alt="Importer une liste d'uilitsateurs" title="Importer une liste d'uilitsateurs"></a>
    	<img src="images/ajouter.png" height="20" class="pointeur_dessus" onClick="afficher_masquer_bloc_ajouter('bloc_ajouter')" alt="Ajouter_utilisateur" title="Ajouter un utilisateur">
        
    </div>
</div>

<div id="bloc_ajouter">
	<form method="post" action="gestion_fournitures_scolaires.php?page=gestion_des_utilisateurs">
    <div>
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" autocomplete="off" value="<?php if(isset($_SESSION['valeursform'])){ echo $_SESSION['valeursform']['nom'];} ?>" />
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" autocomplete="off" value="<?php if(isset($_SESSION['valeursform'])){ echo $_SESSION['valeursform']['prenom'];} ?>" />
       	<label for="date_naissance">Date de naissance :</label>
        <input type="text" name="date_naissance" id="date_naissance" autocomplete="off" value="<?php if(isset($_SESSION['valeursform'])){ echo $_SESSION['valeursform']['date_naissance'];} ?>"  />
        <label for="email">Email :</label>
        <input type="email" autocomplete="off" name="email" id="email" value="<?php if(isset($_SESSION['valeursform'])){ echo $_SESSION['valeursform']['email'];} ?>"  />
        <label for="profil">Profil :</label>
        <select id="profil" name="profil">
        	<option value="" selected="selected"></option>
            <option value="Professeur">Professeur</option>
            <option value="Élève">Élève</option>
        </select>
  	</div>
    <div id="bouton">
    	<input type="submit" value="Ajouter un utilisateur" name="ajouter_utilisateur"/>
	</div>
    </form>
</div>

<?php unset($_SESSION['valeursform']); ?>

<!-- Affichage et remise à zéro des alertes -->

<div class="bloc_succes"><?php if(isset($_SESSION['flash']['succes'])){ ?>
	<?php echo $_SESSION['flash']['succes'];} ?> </div>

<div class="bloc_erreur"><?php if(isset($_SESSION['flash']['erreur'])){ ?>
	<?php echo $_SESSION['flash']['erreur'];} ?> </div>

<div><?php if(isset($_SESSION['flash']['succes'])){ unset($_SESSION['flash']['succes']);} ?> </div>
<div><?php if(isset($_SESSION['flash']['erreur'])){ ?>
<script>document.onload = afficher_masquer_bloc_ajouter('bloc_ajouter')</script>
<?php unset($_SESSION['flash']['erreur']);} ?> </div>

<?php include'./inc/db.php'; ?>
        
		

<div class="nouveau_bloc">
	<table style="width:100%" id="tableau_utilisateurs" class="avectri zebre">
  		<thead>
            <tr>
            	<th style="width:2%"></th>
                <th style="width:23%">Nom</th>
                <th style="width:23%">Prénom</th>
                <th style="width:23%">Date de naissance</th>
                <th style="width:10%">Profil</th>
                <th style="width:14%; text-align:center">Action(s)</th>
            </tr>
        </thead>
        <tbody>
       

        
        <?php $liste_utilisateurs = $bdd->query('SELECT id, nom, prenom, date_naissance, profil, login FROM utilisateurs WHERE profil != "Administration" ORDER BY profil ASC, nom');?>
        
        <form name="supprimer_multiple" action="gestion_fournitures_scolaires.php?page=gestion_des_utilisateurs" method="post">
        <div style="text-align:right;" >
        <input type="submit" value="Supprimer la sélection" name="supprimer_multiple_1" onclick="return(confirm('Etes-vous sûr de vouloir supprimer la sélection ?'));">
        </div>
        
        <?php while ($donnees_liste_utilisateurs = $liste_utilisateurs->fetch()){ ?>
        <tr>
			<td>
            <?php echo"<input type=\"checkbox\" value=\"1\" name=\"".$donnees_liste_utilisateurs['id']."\" >"?></td>
            <td><?php echo $donnees_liste_utilisateurs['nom']; ?></td>
            <td><?php echo $donnees_liste_utilisateurs['prenom']; ?></td>
            
                    <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_utilisateurs['date_naissance'])) ?>
                    
            <td><?php echo $date_naissance; ?></td>
            <td><?php echo $donnees_liste_utilisateurs['profil']; ?></td>
            <td style="line-height:10px;" class="text_align_center">
                <a href="gestion_fournitures_scolaires.php?page=editer_utilisateur&id=<?php echo $donnees_liste_utilisateurs['id']?>">
                <img src="images/modifier_1_15px.png" class="pointeur_dessus"></a> 
                
                <a href="gestion_fournitures_scolaires.php?page=gestion_des_utilisateurs&supprimer_utilisateur=<?php echo $donnees_liste_utilisateurs['id']?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cet utilisateur ?'));">
                <img src="images/supprimer_15px.png" class="pointeur_dessus"></a>
           	</td>
        </tr>
        <?php } ?>
        <?php $liste_utilisateurs->closeCursor();?>
        </form>
        </tbody>       
    </table>
    <script >AlternerCouleur(document.getElementById('tableau_utilisateurs'))</script> 
</div>
