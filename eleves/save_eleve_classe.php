<?php
  $id_eleve=$_POST['id_eleve'];
  $id_classe=$_POST['id_classe'];
  $id_niveau=$_POST['id_niveau'];
  $redoublement=$_POST['redoublement'];
  $id=Construit_Id("eleves_classes",20);
  $req=mysql_query("INSERT INTO `eleves_classes` (id,id_eleve,id_classe,id_niveau,redoublement) VALUES ('$id','$id_eleve','$id_classe','".$id_niveau[0]."','$redoublement')");
?>