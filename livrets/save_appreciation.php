<?php
  $id=$_POST['id'];
  $trimestre=$_POST['trimestre'];
  $id_util=$_POST['id_util'];
  $type_util=$_POST['type_util'];
  $id_eleve=$_POST['id_eleve'];
  $annee_scolaire_ls=$_POST['annee_scolaire_ls'];
  $appreciation=str_replace("\r\n","<br>",$_POST['appreciation']);

  if ($id=="")
  {
    $id=Construit_Id("livrets_appreciation",20);
	$req=mysql_query("INSERT INTO `livrets_appreciation` (id,id_eleve,id_util,type_util,trimestre,annee,appreciation) VALUES ('$id','$id_eleve','$id_util','$type_util','$trimestre','$annee_scolaire_ls','$appreciation')"); 
  }
  else
  {
	$req=mysql_query("UPDATE `livrets_appreciation` SET appreciation='$appreciation' WHERE id='$id'"); 
  }

  echo $id_eleve.'-'.$annee_scolaire_ls;  
?>