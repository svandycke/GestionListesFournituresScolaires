<title>Gestion des matières - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gestion-des-matieres')</script>

<style type="text/css">

.zebre tbody td:nth-child(5) {text-align:center; line-height:10px;}

#img-ajouter-utilisateur{
	position:absolute;
	right:0;	
	top:0;
}
</style>

<!-- Traitement POST   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['ajouter_matiere'])){	
			ajouter_matiere($_POST['idProfesseur'], $_POST['matiere']);
		}
	}
	if(!empty($_GET)){
		if(!empty($_GET['supprimer_matiere_professeur'])){
			supprimer_matiere_professeur($_GET['supprimer_matiere_professeur']);	
		}
	}
?>

<!-- Contenu de la page   -->

<div class="titre">
	Liste des matières
    <div id="img-ajouter-utilisateur">
    	
    	<img src="images/ajouter.png" height="20" class="pointeur_dessus" onClick="afficher_masquer_bloc_ajouter('bloc_ajouter')">
    </div>
</div>

<div id="bloc_ajouter">
<form method="post" action="gestion_fournitures_scolaires.php?page=gestion_des_matieres">
	<div>
        <label for="idProfesseur">Professeur :</label>
		<select name="idProfesseur" style="min-width:270px">
        	<option value="" selected="selected"></option>
        	<?php $liste_utilisateurs = $bdd->query('SELECT id, nom, prenom, date_naissance FROM 	
			utilisateurs WHERE profil = "Professeur" ORDER BY nom ASC, prenom ASC');?>
        	<?php while ($donnees_liste_utilisateurs = $liste_utilisateurs->fetch()){ ?>
			 <?php echo "<option value='".$donnees_liste_utilisateurs['id']."'>".
			 $donnees_liste_utilisateurs['nom'].' '.$donnees_liste_utilisateurs['prenom'].' ('.
			 $donnees_liste_utilisateurs['date_naissance'].')'."</option>" ?>
			 <?php } ?>
        </select>
        <label for="matiere">Matière :</label>
        <input type="text" name="matiere" id="matiere"/>
  	</div>
    <div id="bouton">
    	<input type="submit" value="Ajouter la matière" name="ajouter_matiere" />
    </div>
</form>
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

<div class="nouveau_bloc">
	<table style="width:100%" id="tableau_matieres_enseignees" class="avectri zebre">
    	<thead>      
        	<tr>
            	<th style="width:19%">Nom</th>
            	<th style="width:19%">Prénom</th>
                <th style="width:22%">Date de naissance</th>
                <th style="width:21%">Matière</th>
                <th style="width:14%; text-align:center">Action(s)</th>
         	</tr> 
     	</thead>
        
        <?php include'./inc/db.php'?>
		<?php $liste_professeurs = $bdd->query("SELECT utilisateurs.id AS idUtilisateur, utilisateurs.nom AS nom, utilisateurs.prenom AS prenom, utilisateurs.date_naissance AS date_naissance, matieres_enseignees.designationMatiere, matieres_enseignees.idMatiereEnseignee FROM matieres_enseignees INNER JOIN utilisateurs ON (matieres_enseignees.idUtilisateur = utilisateurs.id)  ORDER BY utilisateurs.nom ASC, utilisateurs.prenom ASC, utilisateurs.date_naissance ASC");?>
        <tbody>
		<?php while ($donnees_liste_professeurs = $liste_professeurs->fetch()){ ?>
            <tr>
                <td><?php echo $donnees_liste_professeurs['nom'] ?></td>
                <td><?php echo $donnees_liste_professeurs['prenom'] ?></td>
                
                
                 <?php $date_naissance =  date("d/m/Y", strtotime($donnees_liste_professeurs['date_naissance'])) ?>
                
                <td><?php echo $date_naissance ?></td>
                <td><?php echo $donnees_liste_professeurs['designationMatiere'] ?></td>
                  <td style="line-height:10px; text-align:center">
                  <a href="gestion_fournitures_scolaires.php?page=editer_matiere&id=<?php echo $donnees_liste_professeurs['idMatiereEnseignee'] ?>">
                  	<img src="images/modifier_1_15px.png" class="pointeur_dessus"></a>
                    <a href="gestion_fournitures_scolaires.php?page=gestion_des_matieres&supprimer_matiere_professeur=<?php echo $donnees_liste_professeurs['idMatiereEnseignee']?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette matière pour ce professeur?'));">
                    <img src="images/supprimer_15px.png" class="pointeur_dessus"></a>
                </td>
            </tr>  
        <?php } ?>
        </tbody>
	</table>    
    <script >AlternerCouleur(document.getElementById('tableau_matieres_enseignees'))</script>      
</div>
    
    

