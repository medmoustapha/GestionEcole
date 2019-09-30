<?php
  $id_liste=$_POST['id_liste'];
  $ordre=1;
  foreach($liste_choix[$id_liste] AS $cle => $value)
  {
    $id=Construit_Id("listes",10);
    $req=mysql_query("INSERT INTO `listes` (id,nom_liste,intitule,ordre,id_prof) VALUES ('$id','$id_liste','".$value."','$ordre','".$_SESSION['id_util']."')");
    $ordre=$ordre+1;
  }
  echo $id_liste;
?>
