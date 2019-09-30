<?php
if ($_SESSION['type_util']=="D" && $listes_auteurs[$_POST['id_liste']]=="D")
{
  $req=mysql_query("SELECT * FROM `listes` WHERE id_prof='' AND nom_liste='".$_POST['id_liste']."' ORDER BY ordre ASC");
}
else
{
  $req=mysql_query("SELECT * FROM `listes` WHERE id_prof='".$_SESSION['id_util']."' AND nom_liste='".$_POST['id_liste']."' ORDER BY ordre ASC");
}

for ($i=1;$i<=mysql_num_rows($req);$i++)
{
  $id=mysql_result($req,$i-1,'id');
  $ordre=mysql_result($req,$i-1,'ordre');
  $key=array_search($ordre,$_POST['listItem'])+1;
  $req2=mysql_query("UPDATE `listes` SET ordre='$key' WHERE id='$id'");
}
?>