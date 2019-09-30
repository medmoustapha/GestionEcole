<?php
$req=mysql_query("SELECT * FROM `accueil_perso` WHERE id_util='".$_SESSION['id_util']."' AND colonne='2' ORDER BY ordre ASC");
for ($i=1;$i<=mysql_num_rows($req);$i++)
{
  $id=mysql_result($req,$i-1,'id');
  $ordre=mysql_result($req,$i-1,'ordre');
  $key=array_search($ordre,$_POST['listItem2'])+1;
  $req2=mysql_query("UPDATE `accueil_perso` SET ordre='$key' WHERE id='$id'");
}
?>