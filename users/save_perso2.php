<?php
  $module_session=$_POST['module_session'];
  $colonne=$_POST['colonne'];
  $visible=$_POST['visible'];
  $longueur=$_POST['longueur'];
  $decoupage=explode("|",$_SESSION['colonnes_'.$module_session]);
	if ($visible=="1") { $decoupage[$colonne]=str_replace('"bVisible":false','"bVisible":true',$decoupage[$colonne]); } else { $decoupage[$colonne]=str_replace('"bVisible":true','"bVisible":false',$decoupage[$colonne]); }
	$chaine="";
	for ($i=0;$i<=$longueur;$i++)
	{
	  $chaine .=$decoupage[$i].'|';
	}

	$req=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".$_SESSION['id_util']."'");
	if (mysql_num_rows($req)!="")
	{
		$req=mysql_query("UPDATE `param_persos` SET `".$module_session."`='".substr($chaine,0,strlen($chaine)-1)."' WHERE id_prof='".$_SESSION['id_util']."'");
	}
	else
	{
			$req=mysql_query("INSERT INTO `param_persos` (id_prof,annee,id_classe_cours,theme,affiche,niveau_en_cours,devoirs,".$module_session.") VALUES ('".$_SESSION['id_util']."','".$_SESSION['annee_scolaire']."','','".$_SESSION['theme_choisi']."','".$_SESSION['affiche_presents']."','','".$_SESSION['affiche_devoirs']."','".substr($chaine,0,strlen($chaine)-1)."')");
	}
	
	$_SESSION['colonnes_'.$module_session]=substr($chaine,0,strlen($chaine)-1);
?>