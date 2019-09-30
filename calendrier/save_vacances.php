<?php
  $id_debut=$_POST['id_debut'];
  $id_fin=$_POST['id_fin'];
  $date_debut=$_POST['nouvelle_date_debut'];
  $date_fin=$_POST['nouvelle_date_fin'];
  $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut='".Date_Convertir($date_debut,$Format_Date_PHP,'Y-m-d')."' AND date_fin='".Date_Convertir($date_fin,$Format_Date_PHP,'Y-m-d')."' AND zone='".$gestclasse_config_plus['zone']."'");
  if (mysql_num_rows($req)=="")
  {
	  if ($id_debut=="")
	  {
		$req=mysql_query("INSERT INTO `vacances` (date_debut,date_fin,zone) VALUES ('".Date_Convertir($date_debut,$Format_Date_PHP,'Y-m-d')."','".Date_Convertir($date_fin,$Format_Date_PHP,'Y-m-d')."','".$gestclasse_config_plus['zone']."')");
	  }
	  else
	  {
		$req=mysql_query("UPDATE `vacances` SET date_debut='".Date_Convertir($date_debut,$Format_Date_PHP,'Y-m-d')."', date_fin='".Date_Convertir($date_fin,$Format_Date_PHP,'Y-m-d')."' WHERE date_debut='".Date_Convertir($id_debut,$Format_Date_PHP,'Y-m-d')."' AND date_fin='".Date_Convertir($id_fin,$Format_Date_PHP,'Y-m-d')."' AND zone='".$gestclasse_config_plus['zone']."'");
	  }
  }
?>