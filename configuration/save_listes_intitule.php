<?php
  $id=$_POST['id'];
  $id_liste=$_POST['id_liste'];
  $intitule=$_POST['intitule'];
  if ($_POST['abreviation']!="-1") { $intitule=$_POST['abreviation'].'|'.$intitule; }

  if ($id=="")
  {
    $id=Construit_Id("listes",10);
    if ($_SESSION['type_util']=="D" && $listes_auteurs[$id_liste]=="D")
    {
      $req=mysql_query("SELECT * FROM `listes` WHERE id_prof='' AND nom_liste='$id_liste' ORDER BY ordre DESC");
      if (mysql_num_rows($req)=="") { $ordre=1; } else { $ordre=mysql_result($req,0,'ordre')+1; }
      $req=mysql_query("INSERT INTO `listes` (id,nom_liste,intitule,ordre,id_prof) VALUES ('$id','$id_liste','".$intitule."','$ordre','')");
    }
    else
    {
      $req=mysql_query("SELECT * FROM `listes` WHERE id_prof='".$_SESSION['id_util']."' AND nom_liste='$id_liste' ORDER BY ordre DESC");
      if (mysql_num_rows($req)=="") { $ordre=1; } else { $ordre=mysql_result($req,0,'ordre')+1; }
      $req=mysql_query("INSERT INTO `listes` (id,nom_liste,intitule,ordre,id_prof) VALUES ('$id','$id_liste','".$intitule."','$ordre','".$_SESSION['id_util']."')");
    }
  }
  else
  {
    $req=mysql_query("UPDATE `listes` SET intitule='".$intitule."' WHERE id='$id'");
  }

  echo $id_liste;
?>