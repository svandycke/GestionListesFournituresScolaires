<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Administration')?>

<!-- Pramètres de la page   -->

<title>Histogramme nombre de fournitures scolaires par matière - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-graphique')</script>

<!-- Style de la page -->

<style type="text/css">
#img-telecharger-pdf{
	position:absolute;
	right:0;	
	top:0;
}

table{
	border-collapse:collapse;	
}

tr{
	height:20px;
	border : none;
}

td{
	border : none;	
}

.graduation{
	border:#FFFFFF 2px solid;
	moz-box-shadow: 0 0 3px #333;
	-webkit-box-shadow: 0 0 3px #333;
	box-shadow: 0 0 3px #333;	
	margin-bottom:10px;
	height:25px;
	cursor:default;
}

.graduation_texte{
	float:left; 
	width:197px;
	height:25px; 
	background-color:#EEEEEE; 
	color:#083d5d; 
    padding-left:3px;	
	line-height:28px;
}

.graduation_barre_variante{
	 float:left;
	 height:25px; 
	 background-color:#d1ebfa	
}

.graduation_nombre{
	 float:left; 
	 width:40px; 
	 height:25px; 
	 background-color:#EEEEEE; 
	 color:#083d5d; 
     text-align:center; 
	 line-height:28px;
	 border-right:#083d5d 0.5px solid;	
}


</style>

<!-- Contenu de la page   -->

<?php 

include'./inc/db.php';


$matieres = $bdd->query("SELECT DISTINCT designationMatiere FROM matieres_quantite_fournitures ORDER BY designationMatiere ASC");

$tableauMatieres = array();
$tableauMatiereNombreFournitures = array();

while ($donnees_matieres = $matieres->fetch()){
	$tableauMatieres[] = $matiere =  $donnees_matieres['designationMatiere'];
	
	$quantite_matiere = $bdd->prepare('SELECT SUM(quantite) FROM matieres_quantite_fournitures WHERE 
	designationMatiere = ?');
	$quantite_matiere->execute(array($matiere));
	
	$donnees_quantite_matiere= $quantite_matiere->fetch();	
	$tableauMatiereNombreFournitures[] = $donnees_quantite_matiere[0];
}
$matieres->closeCursor();
?>

<?php 
$professeurs = $bdd->query("SELECT DISTINCT idUtilisateur FROM professeurs_quantite_fournitures ORDER BY idUtilisateur ASC");

$tableauProfesseurs = array();
$tableauProfesseurNombreFournitures = array();

while ($donnees_professeurs = $professeurs->fetch()){
	$tableauProfesseurs[] = $professeur =  $donnees_professeurs['idUtilisateur'];
	
	$quantite_professeur = $bdd->prepare('SELECT SUM(quantite) FROM professeurs_quantite_fournitures 	
	WHERE idUtilisateur = ?');
	$quantite_professeur->execute(array($professeur));
	
	$donnees_quantite_professeur = $quantite_professeur->fetch();	
	$tableauProfesseurNombreFournitures[] = $donnees_quantite_professeur[0];
}
$professeurs->closeCursor();
?>

<?php $max_valeur_matieres =  max($tableauMatiereNombreFournitures); ?>
<?php $max_valeur_professeurs =  max($tableauProfesseurNombreFournitures); ?>
<?php if($max_valeur_matieres >= $max_valeur_professeurs){
	$max_valeur = $max_valeur_matieres;
}else{
	$max_valeur = $max_valeur_professeurs;
	
}?>


<div class="titre">
    Nombre de fournitures scolaires par matière
    
</div>
    
<div class="nouveau_bloc">

	
    <?php $taille_unite = (635 / $max_valeur); ?>
    <?php $i = 0; ?>
    
    <?php foreach ($tableauMatieres as $valeur){?>
    
    <?php $taille = ($taille_unite * $tableauMatiereNombreFournitures[$i]); ?>
    <?php $taille_graduation = (241 + $taille); ?>

	<div style="width:<?php echo $taille_graduation ?>px;" class="graduation zoom">
    	<div class="graduation_texte">
        	<?php echo $tableauMatieres[$i]; ?>
        </div>
           <div>
        <div class="graduation_nombre">
    	<?php echo $tableauMatiereNombreFournitures[$i]; ?>
    </div>
        <div class="graduation_barre_variante" style="width:<?php echo $taille ?>px;">
        </div>
    </div>
    
    </div>
    
    <?php $i++; } ?> 	
</div>

<?php unset($tableauMatiereNombreFournitures); ?>
<?php unset($tableauMatieres); ?>


<div class="titre" style="margin-top:20px">
    Nombre de fournitures scolaires par professeur
    
</div>

<div class="nouveau_bloc">

	<?php $max_valeur =  max($tableauProfesseurNombreFournitures); ?>
    <?php $taille_unite = (635 / $max_valeur); ?>
    <?php $i = 0; ?>
    
    <?php foreach ($tableauProfesseurs as $valeur){?>
    
    <?php $taille = ($taille_unite * $tableauProfesseurNombreFournitures[$i]); ?>
    <?php $taille_graduation = (241 + $taille); ?>

	<div style="width:<?php echo $taille_graduation ?>px;" class="graduation zoom">
    	<div class="graduation_texte">
        	
		<?php $idUtilisateur = $tableauProfesseurs[$i]; ?>	
		<?php 
		$details_professeur = $bdd->prepare('SELECT nom, prenom FROM utilisateurs WHERE id = ?');
		$details_professeur ->execute(array($idUtilisateur));
		$donnees_details_professeur = $details_professeur->fetch();
		
		echo $donnees_details_professeur['nom']." ".$donnees_details_professeur['prenom'];
		
		?>
    
        </div>
                <div class="graduation_nombre">
        	<?php echo $tableauProfesseurNombreFournitures[$i]; ?>
        </div>
        <div class="graduation_barre_variante" style="width:<?php echo $taille ?>px;">
        </div>

    </div>
    <?php $i++; } ?> 	
</div>
<?php unset($tableauProfesseurNombreFournitures); ?>
<?php unset($tableauProfesseurs); ?>
    
    

