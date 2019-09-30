<?php
  $id=$_POST['id'];
  
  $req=mysql_query("DELETE FROM `reunions` WHERE id='$id'");
?>
