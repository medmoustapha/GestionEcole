<?php
  $date=$_POST['date'];
  $req=mysql_query("DELETE FROM `dates_speciales` WHERE date='".$date."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
?>