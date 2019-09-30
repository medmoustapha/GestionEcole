<?php
  $req=mysql_query("SELECT * FROM `accueil_perso` WHERE id='".$_POST['id']."'");
  $ordre=mysql_result($req,0,'ordre');
  $colonne=mysql_result($req,0,'colonne');
	$panneau=mysql_result($req,0,'subpanel');
  $req=mysql_query("DELETE FROM `accueil_perso` WHERE id='".$_POST['id']."'");

  $req=mysql_query("SELECT * FROM `accueil_perso` WHERE id_util='".$_SESSION['id_util']."' AND ordre>'$ordre' AND colonne='$colonne' ORDER BY ordre ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $req2=mysql_query("UPDATE `accueil_perso` SET ordre='$ordre' WHERE id='".mysql_result($req,$i-1,'id')."'");
    $ordre=$ordre+1;
  }

  echo $panneau;	
?>