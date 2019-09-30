<?php
  $releve=$_POST['releve'];
  $pointage=$_POST['pointage'];
  for ($i=0;$i<count($pointage);$i++)
  {
    $req=mysql_query("UPDATE `cooperative".$_SESSION['cooperative_scolaire']."` SET pointe='1', releve='$releve' WHERE id='".$pointage[$i]."'");
  }	
?>