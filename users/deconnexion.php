<?php
  session_start();
  
  session_destroy();
	
	if (!isset($_GET['erreur']))
	{
	  $erreur=0;
	}
	else
	{
	  $erreur=$_GET['erreur'];
	}

  Header("Location:../index_principal.php?erreur_connexion=".$erreur);
?>