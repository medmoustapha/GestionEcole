<?php
  $id_classe=$_POST['id_classe'];
  $id_niveau=$_POST['id_niveau'];
  $redoublement=$_POST['redoublement'];

  $id_eleve=$_POST['id_eleve'];
  for ($i=0;$i<count($id_eleve);$i++)
  {
    $id2=Construit_Id("eleves_classes",20);
    $req2=mysql_query("INSERT INTO `eleves_classes` (id,id_classe,id_eleve,id_niveau,redoublement) VALUES ('$id2','$id_classe','".$id_eleve[$i]."','".$id_niveau[0]."','$redoublement')");
  }
  
  echo $id_classe;
?>