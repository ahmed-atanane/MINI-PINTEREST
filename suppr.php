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

    if(isset($_GET['photoId']) && $_GET['photoId'] != "") {
      $queryPhoto = executeQuery($db, "SELECT * FROM photo WHERE photoId =".$_GET['photoId']);
      $photo = $queryPhoto->fetch_assoc();
      $photoId = $photo['photoId'];
      $photoName = $photo['nomFich'];
      $photoDesc = $photo['description'];
      $queryCat = executeQuery($db, "SELECT * FROM categorie NATURAL JOIN photo WHERE photoId =".$_GET['photoId']);
      $categ = $queryCat->fetch_assoc();
      $categName = $categ['nomCat'];
      $categId = $categ['catId'];
    }
	if($isConnected && getRoleFromId($db, $_SESSION['userId']) == 1){ //administrateur
      	$path = "compte_admin.php";
    } else if($isConnected && getRoleFromId($db, $_SESSION['userId']) == 2){ //utilisateur
      	$path = "compte_utilisateur.php";
    }

    if (isset($_POST['confirmer'])){
    	removePhoto($db, $photo);
    	header('Location:' . $path);
        exit();
    }

    if (isset($_POST['annuler'])){
        header('Location:' . $path);
        exit();
    }
?>

<!doctype html>
<html lang="fr">
	<head>
	    <meta charset="utf-8">
	    <title>Supprimer photo</title>
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
			Etes-vous bien sûr de vouloir supprimer cette photo?
			<form method="post">
			    <input class="button" type="submit" name="confirmer" value ="Oui">
			    <input class="button" type="submit" name="annuler" value ="Non">
			</form>
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
						Fichier
					</th>
					<th class="bordure">
						Catégorie
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
							echo $photoName;
						?>
					</td>
					<td class="bordure">
						<?php
							echo "<a href='page_accueil.php?cat=" . $categId . "'>".$categName."</a>";
						?>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>

<?php
	closeConnexion($db);
?>