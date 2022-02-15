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

  $queryPhotosWhere = (isset($_GET['cat']) && $_GET['cat'] != "all") //si la catégorie est toute les photos("all") on peut directement prendre tous les tuples de la table et affiché ce qui nous intéresse
    ? " WHERE catId = ".$_GET['cat'] . " AND state = 1"
    : " WHERE state = 1";
  $queryPhotos = executeQuery($db, "SELECT * FROM photo".$queryPhotosWhere);
  $photos = $queryPhotos->fetch_all(MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="fr">
  <head>
      <meta charset="utf-8">
      <title>Application Mini-Pinterest</title>
      <link rel="stylesheet" href="style.css">
  </head>

  <body>
      <div>
        <?php if($isConnected){
            echo "Utilisateur : " .  $user['pseudo'] . "</br>Connecté depuis : " . $connectionTime;
          }
        ?> 
      </div>
      <div class="account">
        <?php if($isConnected && getRoleFromId($db, $_SESSION['userId']) == 1){ //administrateur
            echo '<a href="compte_admin.php">Mon compte</a>' . " ";
          }
          if($isConnected && getRoleFromId($db, $_SESSION['userId']) == 2){ //utilisateur
            echo '<a href="compte_utilisateur.php">Mon compte</a>' . " ";
          }
        ?> 
      </div>
      <div class="div_gris">
        <?php echo count($photos); ?> photo(s) sélectionnées
        <div class="pos_right">
                <?php if(!$isConnected){
                    echo '<a href="connexion.php">Connexion</a>' . " ";
                    echo '<a href="inscription.php">Inscription</a>' . " ";
                  } else {
                    echo '<a href="deconnexion.php">Déconnexion</a>' . " ";
                  }
                ?> 
        </div>
      </div>
      <div>
        <div class="pos_right">
            <?php if($isConnected){
                echo '<a href="ajouter_photo.php">Ajouter une photo</a>' . " ";   
              }
            ?> 
        </div>
      </div>

      <div>
        <form action="page_accueil.php" method="get">
          <table class="choix_photo">
            <tr>
            <td>Quelles photos souhaitez-vous afficher?</td>
            <td>
              <?php
               // $categorie = executeQuery($GLOBALS['db'], "SELECT nomCat FROM categorie");
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

    <?php
      if(isset($_GET['cat']) && $_GET['cat'] != "all") {
        $queryCategorie = executeQuery($db, "SELECT * FROM categorie WHERE catId =".$_GET['cat']);
        $cat = $queryCategorie->fetch_assoc();
        $catName = $cat['nomCat'];
      } else {
        $catName = "Toutes les photos";
      }
    ?>

    <h1>
      <?php echo $catName; ?>
    </h1>

    <div class="allphotos">
      <?php

        foreach ($photos as $photo) {
            echo '<a href="affichage.php?photoId=' . $photo['photoId'] . '"><img src="' . $repertoire . $photo['nomFich'] . '" class = "photo" alt="'. $photo['description'] .'"/></a>';
        }
      ?>
    </div>
  </body>
</html>

<?php
  closeConnexion($db);
?>