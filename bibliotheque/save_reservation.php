<?php
  $id=$_POST['id'];
  $id_livre_emprunt=$_POST['id_livre_emprunt'];
  $id_util_emprunt=$_POST['id_util_emprunt'];
  $decoupe=explode("|",$id_util_emprunt);
  $date_emprunt=Date_Convertir($_POST['date_emprunt'],$Format_Date_PHP,"Y-m-d");
  $date_retour="0000-00-00";
  
  if ($id=="")
  {
    $id=Construit_Id("bibliotheque_emprunt",20);
		$req=mysql_query("INSERT INTO `bibliotheque_emprunt` (id,id_livre,id_util,type_util,date_emprunt,date_retour,reservation) VALUES ('$id','$id_livre_emprunt','".$decoupe[1]."','".$decoupe[0]."','$date_emprunt','$date_retour','1')");
  }
  else
  {
		$req=mysql_query("UPDATE `bibliotheque_emprunt` SET id_util='".$decoupe[1]."', type_util='".$decoupe[0]."', date_emprunt='$date_emprunt', id_livre='$id_livre_emprunt', date_retour='$date_retour' WHERE id='$id'");
  }
  
  echo $id.'|'.$id_livre_emprunt;
?>