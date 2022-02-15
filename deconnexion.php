<?php
	session_start();
 	require_once('bd.php');
 	require('fonctions.php');
	$db = getDB();
	if (isConnected()){
		setDisconnectedUtilisateur($db, $_SESSION['userId']); //Fonction définie dans fonctions.php => déconnecte l'utilisateur connecté        
		header('Location:page_accueil.php');
	    exit();
	}
?>
