<?php
  $date_debut=$_POST['date_debut'];
  $date_fin=$_POST['date_fin'];
  $req=mysql_query("DELETE FROM `vacances` WHERE date_debut='".$date_debut."' AND date_fin='".$date_fin."' AND zone='".$gestclasse_config_plus['zone']."'");
?>