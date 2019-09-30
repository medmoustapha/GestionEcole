<?php
  $id=$_POST['id'];
  $date=$_POST['nouvelle_date'];
  $req=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".Date_Convertir($date,$Format_Date_PHP,'Y-m-d')."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
  if (mysql_num_rows($req)=="")
  {
	  if ($id=="")
	  {
		$req=mysql_query("INSERT INTO `dates_speciales` (date,type) VALUES ('".Date_Convertir($date,$Format_Date_PHP,'Y-m-d')."','".$gestclasse_config_plus['zone']."')");
	  }
	  else
	  {
		$req=mysql_query("UPDATE `dates_speciales` SET date='".Date_Convertir($date,$Format_Date_PHP,'Y-m-d')."' WHERE date='".Date_Convertir($id,$Format_Date_PHP,'Y-m-d')."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
	  }
  }
?>