<?php
  session_start();

  include "../config.php";

  include "../langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
	$Langue[$cle]=$Langue_Application[$cle];
  }
  if ($_SESSION['language_application']!="fr-FR")
  {
	if (file_exists("../langues/".$_SESSION['language_application']."/commun.php"))
	{
	  include "../langues/".$_SESSION['language_application']."/commun.php";
	  foreach ($Langue_Application AS $cle => $value)
	  {
		$Langue[$cle]=$Langue_Application[$cle];
	  }
	}
  }
  
  include "../commun/fonctions.php";

  Connexion_DB();

  $_SESSION['theme_choisi']=$_GET['theme_choisi'];
  
  if (isset($_SESSION['id_util']))
  {
    $req_param=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".$_SESSION['id_util']."'");
    if (mysql_num_rows($req_param)=="")
    {
      $req=mysql_query("INSERT INTO `param_persos` (id_prof,annee,id_classe_cours,theme,affiche,niveau_en_cours,devoirs) VALUES ('".$_SESSION['id_util']."','".$_SESSION['annee_scolaire']."','".$_SESSION['id_classe_cours']."','".$_GET['theme_choisi']."','".$_SESSION['affiche_presents']."','".$_SESSION['niveau_en_cours']."','".$_SESSION['affiche_devoirs']."')");
    }
    else
    {
      $req=mysql_query("UPDATE `param_persos` SET theme='".$_GET['theme_choisi']."' WHERE id_prof='".$_SESSION['id_util']."'");
    }
  }

  Header("Location:../index_principal.php?tab_en_cours=".$_GET['tab_en_cours']);
?>