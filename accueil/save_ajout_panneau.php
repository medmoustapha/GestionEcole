<?php
  $parametre=$_POST['param'];
  $subpanel=$_POST['panneau'];
  $colonne=$_POST['colonne'];
  $titre=$_POST['titre'];
  $req=mysql_query("SELECT * FROM `accueil_perso` WHERE id_util='".$_SESSION['id_util']."' AND colonne='$colonne' ORDER BY ordre DESC");
  $ordre=mysql_result($req,0,'ordre')+1;
  $id=Construit_Id("accueil_perso",10);
  $req=mysql_query("INSERT INTO `accueil_perso` (id,id_util,subpanel,titre,ordre,parametre,colonne) VALUES ('$id','".$_SESSION['id_util']."','$subpanel','$titre','$ordre','$parametre','$colonne')");
?>