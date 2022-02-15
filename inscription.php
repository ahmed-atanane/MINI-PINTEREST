<?php 
    session_start();
	require_once('bd.php');
	require_once('fonctions.php');
    $link = getDB();

    if (isset($_POST['submit'])) {
        $error = false;
        $role = $_POST["role"];
        $pseudo = $_POST["pseudo"];
        $pwd = $_POST["motdepasse"];
        $confirm_pwd = $_POST["confirm_motdepasse"];

        if(empty($role)) { // Check rôle vide
            $wrongrole = "Il faut choisir un rôle.";
            $error = true;
        }
        else {
            if ($role != 1 && $role != 2){
                $wrongrole = "Rôle incorrect.";
                $error = true;
            } else {
                $wrongrole = "";
            }
        }
        if (empty($pseudo)) { //Check pseudo vide
            $wrongpseudo = "Il faut choisir un pseudo";
            $error = true;
        }
        else {
            $pseudo = tests($pseudo);
            if (checkAvailabilityUtilisateur($link, $pseudo)==0) {
                $wrongpseudo = "Pseudo déjà utilisé.";
                $error = true;
             } else {
                $wrongpseudo = "";
             } 
        }
        if (empty($pwd)) { //Check mot de passe vide
            $wrongpwd = "le mot de passe est requis";
            $error = true;
        }
        else {
            $pwd = tests($pwd);
        }
        if (empty($confirm_pwd)) { //Check confirmation de mot de passe vide
            $wrongpwd2 = " Il faut confirmer le mot de passe";
            $error = true;
        }
        else {
            $confirm_pwd = tests($confirm_pwd);
        }
        if (!empty($pwd) && $pwd != $confirm_pwd){ // Check si les deux mot de passe sont différents
            $wrongpwd = "Le mot de passe est différent.";
            $wrongpwd2 = "La confirmation de mot de passe est différente.";
            $error = true;
        }

        if (!$error) { // Si auccune erreur
            registerUtilisateur($link, $role, $pseudo, $pwd); //Fonction définie dans fonctions.php => enregistre l'utilisateur dans la base de données
            header('Location:connexion.php');
            exit();
        }
}
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
    <div>
        <a href="page_accueil.php"> Retour à la page d'accueil </a>
    </div>
    <div class="bloc">&nbsp;</div>
    <div class="row justify-content-center">
    	<div class="menu container p-4 m-4 border rounded border-lignt">
    		<form action="inscription.php" method="post">
                <div class="row justify-content-start">
                    <div class="col-4">
                        <p>*champs obligatoires</p>
                    </div>
                </div>
                <div  class="row justify-content-start p-2">
                    <!--<div class="col-5" > !-->
                        <p>Selectionnez du profil</p>
                    
                    <!--<div class="col-6">!-->
                        <select name="role" >
                            <option value="2" selected>Utilisateur</option>
                            <option value="1">Administrateur</option>
                        </select>
                            <?php
                                if(isset($wrongrole) && $wrongrole != ""){
                                    echo '<p class="error">' . $wrongrole . '<p>';
                                }
                            ?>
                    
                    <!--<div class="row justify-content-start p-2">
                        <div class="col-5 ">!-->
                            Choisir un pseudo*
                       
                        <!--<div class="col-6 ">!-->
                            <input type="text" name="pseudo"  placeholder="Pseudo">
                       
                        <div class="col-1">
                            <?php
                                if(isset($wrongpseudo) && $wrongpseudo != ""){
                                    echo '<p class="error">' . $wrongpseudo . '<p>';
                                }
                            ?>
                        </div>
                        <div class="row justify-content-start p-2">
                            <!--<div class="col-10 ">!-->
                                Choisissez un Mot de passe*
                            
                            <!--<div class="col-6 ">!-->
                                <input type="password" name="motdepasse" >
                            
                            <div class="col-10">
                                <?php
                                    if(isset($wrongpwd) && $wrongpwd != ""){
                                        echo '<p class="error">' . $wrongpwd . '<p>';
                                    }
                                ?>
                            </div>
                            <div class="row justify-content-start p-2">
                                <!--<div class="col-5">!-->
                                    Confirmez votre Mot de passe *
                                
                               <!-- <div class="col-6 ">!-->
                                    <input type="password" name="confirm_motdepasse" >
                                
                                <div class="col-10">
                                    <?php
                                        if(isset($wrongpwd2) && $wrongpwd2){
                                            echo '<p class="error">' . $wrongpwd2 . '<p>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <div style="display:flex;margin-top:0em; margin-left:9em; padding-right:8em;padding-left:2em;">
                            <input type="submit" name="submit" value="S'inscrire"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding-left:2em;">
                    <a href="connexion.php"> Déja Inscrit ? </a>
                </div>
            </form>
        </div>
    </div>

<?php
/*Cette fonction doit être définie hors d'une condition (if/else), donc on la définie avant de l'utiliser dans une boucle*/
function tests($donnees){
    $donnees = trim($donnees); //trim supprime les espaces (ou d'autres caractères) en début et fin de chaîne
    $donnees = stripslashes($donnees); //stripslashes supprime les antislashs d'une chaîne
    $donnees = htmlspecialchars($donnees); //htmlspecialchars convertit les caractères spéciaux en entités HTML
    return $donnees;
}
?>

</body>
</html>
