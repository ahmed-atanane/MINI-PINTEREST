<?php
  session_start();
  require_once('bd.php');
  require_once('fonctions.php');
  $db=getDB();
  $repertoire="data/";

  $isConnected = isConnected();

  if ($isConnected){
    $user = getUserFromSession($db, $_SESSION['userId']);
  } else {
    $user = null;
  }
  if(!is_null($user)){
    $connectionTime = connectionTime($user['connectedOn']);
  } else {
    $connectionTime = null;
  }

    if(isset($_GET['photoId']) && $_GET['photoId'] != "") { //Récupère les informations nécessaires à l'affichage d'une photo
      $queryPhoto = executeQuery($db, "SELECT * FROM photo WHERE photoId =".$_GET['photoId']);
      $photo = $queryPhoto->fetch_assoc();
      $photoId = $photo['photoId'];
      $photoName = $photo['nomFich'];
      $photoDesc = $photo['description'];
      $queryCat = executeQuery($db, "SELECT * FROM categorie NATURAL JOIN photo WHERE photoId =".$_GET['photoId']);
      $categ = $queryCat->fetch_assoc();
      $categName = $categ['nomCat'];
      $categId = $categ['catId'];
    } else {
    	$photo = null;
    }
		

	if (isset($_POST['valider'])) { //Si on envoir la modif (tous les champs n'ont pas à être remplis, on peut ne modifier qu'une seule donnée)
		$error = false;
		$file = $_FILES['file'];
        $description = $_POST["description"];
        $cat = $_POST["cat"];


	    if($file['error'] != UPLOAD_ERR_NO_FILE && $file['size'] != 0){
			$wrongfile = checkFile($file);
		    if($wrongfile != ""){
		    	$error = true;
		    }
		}

	    if(!$error){
	    	modifyPicture($db, $file, $photo, $description, $cat); //Fonction définie dans fonctions.php, modifie le tuple dans la base de données
	    	header('Location:modif_photo.php?photoId="' . $photo['photoId'].'"');
			exit();
	    }
	}
?>

<!doctype html>
<html lang="fr">
	<head>
	    <meta charset="utf-8">
	    <title>Modifier photo</title>
	    <link rel="stylesheet" href="style.css">
	</head>

	<body>
		<div>
	        <?php if($isConnected){
	            echo "Utilisateur : " .  $user['pseudo'] . "</br>Connecté depuis : " . $connectionTime;
	          }
	        ?> 
      	</div>

		<h1>
			Modifier l'image
		</h1>

		<div>
			<a href="page_accueil.php"> Retour à la page d'accueil </a>
		</div>

		<div>
			<table class="tab">
				<tr class="ligne">
					<th class="bordure">
						Image
					</th>
					<th class="bordure">
						Description
					</th>
					<th class="bordure">
						Catégorie
					</th>
					<th class="bordure">
						
					</th>
				</tr>
				<tr class="ligne">
					<td class="bordure">
						<?php
							echo "<img src='" . $repertoire . $photoName . "' class = 'photo_detail'/>";
						?>
					</td>
					<td class="bordure">
						<?php
							echo $photoDesc;
						?>
					</td>
					<td class="bordure">
						<?php
							echo "<a href='page_accueil.php?cat=" . $categId . "'>".$categName."</a>";
						?>
					</td>
					<td class="bordure">
					</td>
				</tr>
				<tr class="ligne">
					<form method="post" enctype="multipart/form-data">
						<td class="bordure">
							<input type="file" name="file" accept=".png, .jpg, .jpeg, .gif">                        
							<?php
	                            if(isset($wrongfile) && $wrongfile != ""){
	                                echo '<p class="error">' . $wrongfile . '<p>';
                            	}
                        	?>
						</td>
						<td class="bordure">
							<input type="text" name="description" value="<?php echo $photoDesc; ?>">                                          
						</td>
						<td class="bordure">
					       	<?php
				                $queryCategories = executeQuery($db, "SELECT * FROM categorie");
				                $categories = $queryCategories->fetch_all(MYSQLI_ASSOC);
				            ?>
				            <select name="cat" >
				            	<option value=""></option>
				                  <?php
				                      foreach ($categories as $categorie) {
				                          $selected = (isset($categId) && $categId == $categorie['catId'])
				                            ? " selected"
				                            : "";
				                          echo "<option value=".$categorie['catId'].$selected.">".$categorie['nomCat']."</option>";
				                      }
				                  ?>
             				</select>
						</td>

						<td class="bordure">
							<input class="button" type="submit" name="valider" value ="Valider">
						</td>
					</form>
				</tr>
			</table>
		</div>
	</body>
</html>

<?php
	closeConnexion($db);
?>