<?php
	session_start();
 	require_once('bd.php');
 	require_once('fonctions.php');
  $repertoire="data/";
  $db = getDB();

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

/** On regarde quelle photo on souhaite afficher en fonction de la catégorie et de l'user id*/
  if (isset($_GET['cat']) && $_GET['cat'] != "all" && isset($_GET['usr']) && $_GET['usr'] != "allUsers"){
    $querySelectionWhere = " WHERE catId =".$_GET['cat'] . " AND usrId =". $_GET['usr'];
  } else if (isset($_GET['cat']) && $_GET['cat'] != "all") {
    $querySelectionWhere = " WHERE catId =".$_GET['cat'];
  } else if (isset($_GET['usr']) && $_GET['usr'] != "allUsers") {
    $querySelectionWhere = " WHERE usrId =".$_GET['usr'];
  } else {
    $querySelectionWhere = "";
  }
  $queryPhotos = executeQuery($db, "SELECT * FROM photo".$querySelectionWhere);
  $photos = $queryPhotos->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['photoId'])){ // si on change la valeur
      $status = $_POST["status"]; 
      changePhotoStatus($db, $_POST['photoId'], $status); //Fonction cahngePhotoStatus définie dans fonctions.php
      header('Location:compte_admin.php'); //on recharge la page
      exit();
  }
?>

<!doctype html>
<html lang="fr">
  <head>
      <meta charset="utf-8">
      <title>Compte aministrateur</title>
      <link rel="stylesheet" href="style.css">
  </head>

  <body>
  	 <div>
        <?php if($isConnected){
            echo "Utilisateur : " .  $user['pseudo'] . "</br>Connecté depuis : " . $connectionTime;
          }
        ?> 
     </div>
     <h1>Mon compte admin</h1>
      
     <div>
        <a href="page_accueil.php"> Page d'accueil </a>
     </div>

      <div>
        <a href="statistiques.php"> Statistiques </a>
     </div>

     <div>
        <form action="compte_admin.php" method="get">
          <table class="choix_photo">
            <tr>
              <td>Quel utilisateur?</td>
              <td>
                <?php
                  $queryUsers = executeQuery($db, "SELECT * FROM utilisateur");
                  $users = $queryUsers->fetch_all(MYSQLI_ASSOC);               
                ?>
                <select name="usr" >
                    <option value="allUsers">Toutes les utilisateurs</option>
                    <?php
                        foreach ($users as $user) {
                            $selected = (isset($_GET['usr']) && $_GET['usr'] == $user['id'])
                              ? " selected"
                              : "";
                            echo "<option value=".$user['id'].$selected.">".$user['pseudo']."</option>";
                        }
                    ?>
                </select>
              </td>
              <td>Quelles photos souhaitez-vous afficher?</td>
              <td>
                <?php
                  $queryCategories = executeQuery($db, "SELECT * FROM categorie");
                  $categories = $queryCategories->fetch_all(MYSQLI_ASSOC);
                ?>
                <select name="cat" >
                    <option value="all">Toutes les photos</option>
                    <?php
                        foreach ($categories as $categorie) {
                            $selected = (isset($_GET['cat']) && $_GET['cat'] == $categorie['catId'])
                              ? " selected"
                              : "";
                            echo "<option value=".$categorie['catId'].$selected.">".$categorie['nomCat']."</option>";
                        }
                    ?>
                </select>
                <input type="submit" value="Valider" class="button"/>
              </td>
            </tr>
          </table>
        </form>
     </div>

    <div>
      <table class="tab">
        <tr>
          <th class="bordure">
            Photo
          </th>
          <th class="bordure">
            Utilisateur
          </th>
          <th class="bordure">
            Catégorie
          </th>
          <th class="bordure">
            Description
          </th>
          <th class="bordure">
            Nom du fichier
          </th>
          <th class="bordure">
            Action
          </th>
        </tr>
      <?php

        foreach ($photos as $photo) {
          echo "<tr>";
            echo '<td class="bordure">';
              echo '<img src="' . $repertoire . $photo['nomFich'] . '" class = "photo" alt="'. $photo['description'] .'"/>';
            echo "</td>";
            echo '<td class="bordure">';
              echo getPseudoFromId($db, $photo['usrId']);
            echo "</td>";
            echo '<td class="bordure">';
              echo getCategorieFromCatId($db, $photo['catId']);
            echo "</td>";
            echo '<td class="bordure">';
              echo $photo['description'];
            echo "</td>";
            echo '<td class="bordure">';
              echo $photo['nomFich'];
            echo "</td>";
            echo '<td class="bordure">';
              echo '<p><a href="modif_photo.php?photoId=' . $photo['photoId'] . '">Modifier</a></p>';
              echo '<p><a href="suppr.php?photoId=' . $photo['photoId'] . '">Suppimer</a></p>';
            echo "</td>";
          echo "</tr>";
          echo "<tr>";
            echo '<td class="bordure">'; 
            ?>
              <form method="post" name="<?php echo $photo['photoId']; ?>">
                    <select name="status" >
                        <?php
                          if ($photo['state'] == 0) { //si le state est à zéro la photo est cachée
                        ?>
                        <option value="1">Actif</option>
                        <option value="0" selected="">Caché</option>
                      <?php } else { ?>
                        <option value="1" selected="">Actif</option>
                        <option value="0">Caché</option>
                      <?php } ?>
                    </select> 
                    <input type="hidden" name="photoId" value="<?php echo $photo['photoId']; ?>"> 
                    <input type="submit" name="valider" value="Valider" class="button"/> 
              </form>         
            </td>
          </tr>
        
        
        <?php            

          }
        ?>
      </table>
    </div>
  </body>
</html>