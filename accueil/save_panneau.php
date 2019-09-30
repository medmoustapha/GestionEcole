<?php
  $id=$_POST['id'];
  $titre=$_POST['titre'];
  $nb_param=$_POST['nb_param'];
  $param="";
  for ($i=1;$i<=$nb_param;$i++)
  {
    $param=$param.$_POST['param'.$i]."|";
  }
  $req=mysql_query("UPDATE `accueil_perso` SET titre='".$titre."', parametre='".substr($param,0,strlen($param)-1)."' WHERE id='$id'");
?>