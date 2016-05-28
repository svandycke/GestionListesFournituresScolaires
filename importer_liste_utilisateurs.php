<!-- Vérification des drois d'accès   -->
<?php utilisateur_connecte(); ?>
<?php  utilisateur_profil('Administration')?>

<!-- Pramètres de la page   -->

<title>Importer une liste d'utilisateurs - Gestion des listes de fournitures scolaires</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>document.onload = menu_active('menu-gestion-des-utilisateurs')</script>

<!-- Style de la page -->

<style type="text/css">


</style>

<!-- Traitement POST   -->

<?php   
	if(!empty($_POST)){
		if(isset($_POST['importer_liste'])){
	
			$filePath = $_FILES['liste_utilisateurs']['tmp_name'];
			$nom = $_FILES['liste_utilisateurs']['name'];
			
			if (substr($nom, -3) == 'csv' || substr($nom, -3) == 'CSV')
			{
				if (($handle = fopen($filePath, "r")) !== FALSE) {
					$erreur = false;
					$ligne = 0;
					while ((($data = fgetcsv($handle, 1000, ";")) !== FALSE) && !$erreur) {
					$num = count($data);
						if($num == 5){
							for ($c=0; $c < $num; $c++) {
								$donneesCSV[$ligne][] = utf8_encode($data[$c]);
							}
							
							if(!empty($donneesCSV[$ligne][0]) && !$erreur) // Contrôle du nom
							{
								$regex = "/^([a-z\\sàâçèëéêîôùûü-]+)$/si";
								if(!preg_match($regex,$donneesCSV[$ligne][0])){
									$erreur = true;
								}	
							}
							else
							{
								$erreur = true;

							}
							
							if(!empty($donneesCSV[$ligne][1]) && !$erreur) // Contrôle du prénom
							{
								$regex = "/^([a-z\\sàâçèëéêîôùûü-]+)$/si";
								if(!preg_match($regex,$donneesCSV[$ligne][1])){
									$erreur = true;
								}									
								
							}
							else
							{
								$erreur = true;	
							}
							
							if(!empty($donneesCSV[$ligne][2]) && !$erreur) // Contrôle de l'email
							{
								if(!filter_var($donneesCSV[$ligne][2], FILTER_VALIDATE_EMAIL))
								{
									
									$erreur = true;
								}
							}
							else
							{
								
								$erreur = true;	
							}
							
							if(!empty($donneesCSV[$ligne][3]) && !$erreur) // Contrôle de la date de 				
							//naissance
							{
								if(!verifier_date($donneesCSV[$ligne][3]))
								{
									$erreur = true;	
								}
							}
							else
							{

								$erreur = true;	
							}
							
							if(!empty($donneesCSV[$ligne][4]) && !$erreur) // Contrôle du profil
							{
								if($donneesCSV[$ligne][4] == "Élève" || $donneesCSV[$ligne][4] == "Professeur")
								{
								
								}
								else
								{
									
									$erreur = true;		
								}
							}
							else
							{
								$erreur = true;	
							}
						}
						else{
							$erreur = true;	
						}
						
					$ligne++;	
					}
					
					
					fclose($handle);
				}
				
				if(!$erreur)
				{
					
					for($i=0; $i<$ligne; $i++){
						ajouter_utilisateur($donneesCSV[$i][0], $donneesCSV[$i][1], $donneesCSV[$i][3], $donneesCSV[$i][2], $donneesCSV[$i][4]);
						
					}
					unset($donneesCSV);
					$_SESSION['flash']['succes'] = "- Les utilisateur ont été importés avec succès.";		
				}
				else
				{
					unset($donneesCSV);
					$_SESSION['flash']['erreur'] = "- Une erreur a été détectée dans votre 
					fichier. (Ligne ".$ligne.")";	
				}
				
			}
			else{
				$_SESSION['flash']['erreur'] = "- Le fichier que vous avez importé est incorrect.";		
			}

			
		}	
	}
	
?>

<!-- Contenu de la page   -->

<div class="titre">
	<a href="gestion_fournitures_scolaires.php?page=gestion_des_utilisateurs"  class="titre_back">Gestion des utilisateurs</a> : Importer une liste
</div>
<div id="bloc_ajouter" style="display:block">
	<form method="post" action="gestion_fournitures_scolaires.php?page=importer_liste_utilisateurs" enctype="multipart/form-data">
    	<label for="liste_utilisateurs">Liste d'utilisateurs (.csv) :</label>
        <input type="file" name="liste_utilisateurs" accept=".csv">
        
    <div id="bouton">
    	<input type="submit" value="Importer la liste d'utilisateurs" name="importer_liste"/>
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
<?php unset($_SESSION['flash']['erreur']);} ?> </div>

