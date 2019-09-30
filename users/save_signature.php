<?php
  $id=$_POST['id_signature'];
  $mdp=md5($_POST['mdp']);
  $parametre=$_POST['parametre'];

  if ($_SESSION['type_util']=="E")
  {
    $req=mysql_query("SELECT * FROM `eleves` WHERE id='".$_SESSION['id_util']."' AND passe='$mdp'");
	if (mysql_num_rows($req)!="")
	{
	  $req2=mysql_query("INSERT INTO `signatures` (id,id_util,type_util,date,parametre) VALUES ('$id','".$_SESSION['id_util']."','E','".date("Y-m-d")."','$parametre')");
	  echo "ok";
	}
	else
	{
	  echo "erreur";
	}
  }
  else
  {
    $req=mysql_query("SELECT * FROM `profs` WHERE id='".$_SESSION['id_util']."' AND passe='$mdp'");
	if (mysql_num_rows($req)!="")
	{
	  $req2=mysql_query("INSERT INTO `signatures` (id,id_util,type_util,date,parametre) VALUES ('$id','".$_SESSION['id_util']."','".$_SESSION['type_util']."','".date("Y-m-d")."','$parametre')");
	  echo "ok";
	}
	else
	{
	  echo "erreur";
	}
  }
?>