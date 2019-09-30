<?php
  $id=$_POST['id'];

  // Création ou modification de la classe
  if ($id=="")
  {
    $id=Construit_Id("taches",50);
    $req = mysql_query("INSERT INTO `taches` (id,date_echeance,heure_echeance,id_util,type_util,etat,priorite,titre,tache) VALUES ('$id','".Date_Convertir($_POST['date_echeance'],$Format_Date_PHP,"Y-m-d")."','".$_POST['heure_echeance']."','".$_SESSION['id_util']."','".$_SESSION['type_util']."','".$_POST['etat']."','".$_POST['priorite']."','".$_POST['titre']."','".$_POST['tache']."')");
  }
  else
  {
    $req = mysql_query("UPDATE `taches` SET date_echeance='".Date_Convertir($_POST['date_echeance'],$Format_Date_PHP,"Y-m-d")."',heure_echeance='".$_POST['heure_echeance']."',etat='".$_POST['etat']."',priorite='".$_POST['priorite']."',titre='".$_POST['titre']."',tache='".$_POST['tache']."' WHERE id='$id'");
  }
  
  echo $id;
?>