<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Administration')?>

<!-- Pramètres de la page   -->

<title>Gestion des classes - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gestion-des-classes')</script>

<!-- Style de la page -->

<style type="text/css">

.zebre thead td:nth-child(2) {text-align:center}
.zebre tbody td:nth-child(2) {text-align:center}
.zebre tbody td:nth-child(3) {text-align:center}
.zebre tbody td:nth-child(4) {text-align:center; line-height:10px}

#img-ajouter-classe{
	position:absolute;
	right:0;	
	top:0;
}


</style>

<!-- Traitement POST / GET   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['ajouter_classe'])){
			ajouter_classe($_POST['niveau'], $_POST['groupe']);
		}
	}
	if(!empty($_GET)){
		if(!empty($_GET['supprimer_classe'])){
			supprimer_classe($_GET['supprimer_classe']);	
		}
	}
?>

<!-- Contenu de la page   -->

<div class="titre">
	Liste des classes
    <div id="img-ajouter-classe">
    	<img src="images/ajouter.png" height="20" class="pointeur_dessus" onClick="afficher_masquer_bloc_ajouter('bloc_ajouter')">
    </div>
</div>

<div id="bloc_ajouter">
	<form method="post" action="gestion_fournitures_scolaires.php?page=gestion_des_classes">
	<div>
        <label for="niveau">Niveau :</label>
        <input type="text" name="niveau" id="niveau" autocomplete="off" />
        <label for="groupe">Groupe :</label>
        <input type="text" name="groupe" id="groupe" autocomplete="off" />
  	</div>
    <div id="bouton">
    	<input type="submit" value="Ajouter une classe" name="ajouter_classe"/>
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
	<table style="width:100%" id="tableau_classes" class="avectri zebre">
       	<thead>     
            <tr>
                <th style="width:26%">Niveau</th>
                <th style="width:30%; text-align:center">Groupe</th>
                <th style="width:25%; text-align:center">Nombre d'élèves</th>
                <th style="width:14%; text-align:center">Action(s)</th>
            </tr>  
        </thead>
        <tbody>
        <?php include'./inc/db.php'; ?>
        <?php $liste_classes = $bdd->query('SELECT idClasse, niveau, groupe FROM classes ORDER BY niveau ASC, groupe ASC');?>
        <?php while ($donnees_liste_classes = $liste_classes->fetch()){ ?>
         
            <tr>
                <td><?php echo $donnees_liste_classes['niveau']; ?></td>
                <td style="text-align:center"><?php echo $donnees_liste_classes['groupe']; ?></td>
                <td style="text-align:center"><?php 
                
                $id = $donnees_liste_classes['idClasse'];
                include'./inc/db.php';

				
				$nb_eleves = $bdd->prepare("SELECT COUNT(utilisateurs.id) FROM composition_classes INNER JOIN 
                utilisateurs ON (composition_classes.idUtilisateur = utilisateurs.id) WHERE 
                composition_classes.idClasse = ? AND utilisateurs.profil = 'Eleve'");
				$nb_eleves ->execute(array($id));
				
				
                $donnees_nb_eleves= $nb_eleves->fetch();	echo $donnees_nb_eleves[0];
                ?></td>
                <td style="line-height:10px; text-align:center">               
               <a href="gestion_fournitures_scolaires.php?page=editer_classe&id=<?php echo $donnees_liste_classes['idClasse']?>">
                    <img src="images/modifier_1_15px.png" class="pointeur_dessus"></a> 
                    
                    <a href="gestion_fournitures_scolaires.php?page=gestion_des_classes&supprimer_classe=<?php echo $donnees_liste_classes['idClasse']?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette classe ?'));">
                    <img src="images/supprimer_15px.png" class="pointeur_dessus"></a>
                    
                </td>
            </tr>
        <?php } ?>
        <?php $liste_classes->closeCursor();?>
        </tbody>
	</table>  
    <script >AlternerCouleur(document.getElementById('tableau_classes'))</script>       
</div>
    
    


