<?php



/*Cette fonction prend en entrée l'identifiant de la machine hôte de la base de données, les identifiants (login, mot de passe) d'un utilisateur autorisé 
sur la base de données contenant les tables pour le chat et renvoie une connexion active sur cette base de donnée. Sinon, un message d'erreur est affiché.*/
function getDB()
{
    $dbHost="localhost"; 
	$dbUser="root"; 
	$dbPwd=""; 
	$dbName="root";
	
	$db=mysqli_connect($dbHost,$dbUser,$dbPwd, $dbName);
    if(mysqli_connect_errno())
    {
        echo "Échec lors de la connexion à la base de données: ".mysqli_connect_errno().")";
    }
    return $db;
}

/*Cette fonction prend en entrée une connexion vers la base de données du chat ainsi 
qu'une requête SQL SELECT et renvoie les résultats de la requête. Si le résultat est faux, un message d'erreur est affiché*/
function executeQuery($db, $query)
{
    $result = mysqli_query($db, $query);
	if($result==FALSE){
		echo "La requête ".$query." n'a pas pu être executée à cause d'une erreur de syntaxe";
	}
	return $result;
}

/*Cette fonction prend en entrée une connexion vers la base de données du chat ainsi 
qu'une requête SQL INSERT/UPDATE/DELETE et ne renvoie rien si la mise à jour a fonctionné, sinon un 
message d'erreur est affiché.*/
function executeUpdate($db, $query)
{
	$result = mysqli_query($db, $query);
	if($result==FALSE){
		echo "Echec de la requête de mise à jour à cause d'une erreur de syntaxe";
	}
}

/*Cette fonction ferme la connexion active $link passée en entrée*/
function closeConnexion($db)
{
	mysqli_close($db);
}

?>
