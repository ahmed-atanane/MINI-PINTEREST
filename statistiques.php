<?php
  require_once('bd.php');
  require_once('fonctions.php');
?>

<?php
  session_start();
  $connexion=getDB();
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Application Mini-Pinterest</title>
  <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="page_accueil.php"> Page d'accueil </a>

<h1> Onglet de Statistiques </h1>
<?php
    $resultat = executeQuery($connexion,"SELECT pseudo FROM utilisateur ");
    $users = array();
    $index = 0;
    while($row = mysqli_fetch_assoc($resultat))
    {
        $users[$index] = $row["pseudo"];
        $index+= 1;
    }
    $size = sizeof($users);
    $res =executeQuery($connexion,"SELECT U.pseudo FROM utilisateur U JOIN Photo Ph ON U.id = Ph.usrId GROUP BY U.pseudo HAVING COUNT(Ph.usrId)");
    $res1 =executeQuery($connexion,"SELECT COUNT(Ph.usrId) AS Ph FROM utilisateur U JOIN Photo Ph ON U.id = Ph.usrId GROUP BY U.pseudo HAVING COUNT(Ph.usrId) ");
    $nbpseudos= array();
    $utils=array();
    $index = 0;
    $i = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $utils[$index] = $row["pseudo"];
        $index += 1;
    }
    while($row1 = mysqli_fetch_assoc($res1))
    {
        $nbpseudos[$i] = $row1["Ph"];
        $i += 1;
    }
    $result =executeQuery($connexion,"SELECT Cat.nomCat FROM Categorie Cat JOIN Photo P ON Cat.catId = P.catId GROUP BY Cat.nomCat HAVING COUNT(P.CatId)");
    $result1 =executeQuery($connexion,"SELECT COUNT(P.catId) AS P FROM Categorie Cat JOIN Photo P ON Cat.catId = P.catId GROUP BY Cat.nomCat HAVING COUNT(P.catId)");
    $nbcategories= array();
    $util=array();
    $index1 = 0;
    $index2 = 0;
    while($row = mysqli_fetch_assoc($result))
    {
        $util[$index1] = $row["nomCat"];
        $index1 += 1;
    }
    while($row1 = mysqli_fetch_assoc($result1)) 
    {
        $nbcategories[$index2] = $row1["P"];
        $index2 += 1;
    }
    $array=getConnectedUsersUtilisateur($connexion);
?>

<div class="stat">
<table id="stats">
    <tr>
        <th>Le nombre d'inscriptions d'utilisateurs:</th>
        <td>
          <?php
          echo $size;
         ?>
        </td>
    </tr>
</table>
<table id="stats">
    <tr>
        <th>Pseudo de l'utilisateur</th>
        <td>Nombre de photos:</td>
    </tr>
    <?php 
    for($i=0; $i< sizeof($utils); $i++)
    {
        ?>
        <tr>
            <th><?php 
            echo $utils[$i]; 
            ?></th>
            <td>
            <?php 
            echo $nbpseudos[$i];?>
            </td>
        </tr>
<?php }?>
    <tr>
</table>
<table id="stats">
    <th>Catégorie:</th>
    <td>Nombre de photos:</td>
    </tr>
    <?php for($k=0; $k< sizeof($util); $k++){?>
        <tr>
            <th><?php echo $util[$k]; ?> </th>
            <td><?php echo $nbcategories[$k];?></td>
        </tr>
    <?php }?>
</table>
<table id="stats">
    <th>L'utilisateur connecté</th>
    </tr>
    <?php for($l=0; $l< sizeof($array); $l++){?>
        <tr>
            <th><?php echo $array[$l];?> </th>
        </tr>
    <?php }?>
</table>
</div>
</body>
</html>
<?php
closeConnexion($connexion);
?>