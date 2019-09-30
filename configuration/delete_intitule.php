<?php
  $id=$_POST['id'];
  $req=mysql_query("SELECT * FROM `listes` WHERE id='$id'");
  $id_liste=mysql_result($req,0,'nom_liste');
  $req=mysql_query("DELETE FROM `listes` WHERE id='$id'");

  // On réordonne la liste
  if ($_SESSION['type_util']=="D" && $listes_auteurs[$id_liste]=="D")
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='$id_liste' AND id_prof='' ORDER BY ordre ASC");
  }
  else
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$id_liste."' AND id_prof='".$_SESSION['id_util']."' ORDER BY ordre ASC");
  }
  $ordre=1;
  if (mysql_num_rows($req)!="")
  {
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      $id=mysql_result($req,$i-1,'id');
      $req2=mysql_query("UPDATE `listes` SET ordre='$ordre' WHERE id='$id'");
      $ordre=$ordre+1;
    }
  }
  
  // On récupère l'id du premier élément de la liste
  if ($_SESSION['type_util']=="D" && $listes_auteurs[$id_liste]=="D")
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='$id_liste' AND id_prof='' ORDER BY ordre ASC");
  }
  else
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$id_liste."' AND id_prof='".$_SESSION['id_util']."' ORDER BY ordre ASC");
  }
  $id_premier=mysql_result($req,0,'id');
  // On met tous les id sur l'id du premier dans toutes les tables où cela a de l'importance
  $table=explode('|',$listes_table[$id_liste]);
  $colonne=explode('|',$listes_colonne_table[$id_liste]);
  for($i=0;$i<count($table);$i++)
  {
    $req=mysql_query("UPDATE `".$table[$i]."` SET ".$colonne[$i]."='".$id_premier."' WHERE ".$colonne[$i]."='".$_POST['id']."'");
  }
?>