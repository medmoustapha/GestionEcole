<?php
  $req_bilan=mysql_query("UPDATE `cooperative_bilan` SET clos='1' WHERE annee='".$_SESSION['cooperative_scolaire']."'");
?>