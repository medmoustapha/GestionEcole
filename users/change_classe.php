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

  $req_param=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".$_SESSION['id_util']."'");
  $_SESSION['id_classe_cours']=$_POST['classe_choisie'];
  if ($_POST['classe_choisie']!="")
  {
    $req=mysql_query("SELECT classes_niveaux.*, listes.* FROM `classes_niveaux`,`listes` WHERE classes_niveaux.id_niveau=listes.id AND classes_niveaux.id_classe='".$_POST['classe_choisie']."' ORDER BY listes.ordre ASC");
    $id_niveau=mysql_result($req,0,'classes_niveaux.id_niveau');
  }
  else
  {
    $id_niveau="";
  }

  if (mysql_num_rows($req_param)=="")
  {
    $req=mysql_query("INSERT INTO `param_persos` (id_prof,annee,id_classe_cours,theme,affiche,niveau_en_cours,devoirs) VALUES ('".$_SESSION['id_util']."','".$_SESSION['annee_scolaire']."','".$_POST['classe_choisie']."','".$_SESSION['theme_choisi']."','".$_SESSION['affiche_presents']."','".$id_niveau."','".$_SESSION['affiche_devoirs']."')");
  }
  else
  {
    $req=mysql_query("UPDATE `param_persos` SET id_classe_cours='".$_POST['classe_choisie']."', niveau_en_cours='".$id_niveau."' WHERE id_prof='".$_SESSION['id_util']."'");
  }

  $_SESSION['niveau_en_cours']=$id_niveau;
  $req=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".$_POST['classe_choisie']."' AND type='T'");
  $_SESSION['titulaire_classe_cours']=mysql_result($req,0,'id_prof');
  Param_Utilisateur(mysql_result($req,0,'id_prof'),$_SESSION['annee_scolaire']);
  $_SESSION['trimestre_en_cours']=$gestclasse_config_plus['decoupage_livret'].Trouve_Trimestre($gestclasse_config_plus['decoupage_livret']);
?>