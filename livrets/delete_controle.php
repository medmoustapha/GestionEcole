<?php
  $id=$_POST['id'];
  $req=mysql_query("SELECT * FROM `controles` WHERE id='$id'");
  $trimestre=mysql_result($req,0,'trimestre');
  $req=mysql_query("DELETE FROM `controles_resultats` WHERE id_controle='$id'");
  $req=mysql_query("DELETE FROM `controles_competences` WHERE id_controle='$id'");
  $req=mysql_query("DELETE FROM `controles` WHERE id='$id'");
?>
