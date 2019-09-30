<?php
  $id=$_POST['id'];
  $contenu=$_POST['contenu_e'];

  // Création ou modification de la classe
  if ($id=="")
  {
    $id=Construit_Id("accueil_news",10);
    $req = mysql_query("INSERT INTO `accueil_news` (id,date,id_prof,cible,titre,contenu) VALUES ('$id','".Date_Convertir($_POST['date'],$Format_Date_PHP,"Y-m-d")."','".$_SESSION['id_util']."','".$_POST['cible']."','".$_POST['titre']."','".$contenu."')");
  }
  else
  {
    $req = mysql_query("UPDATE `accueil_news` SET date='".Date_Convertir($_POST['date'],$Format_Date_PHP,"Y-m-d")."',cible='".$_POST['cible']."',titre='".$_POST['titre']."',contenu='".$contenu."' WHERE id='$id'");
  }
  
  echo $id;
?>