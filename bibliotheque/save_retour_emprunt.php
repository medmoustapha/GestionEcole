<?php
  $req=mysql_query("UPDATE `bibliotheque_emprunt` SET date_retour='".date("Y-m-d")."' WHERE id='".$_POST['id_emprunt']."'");
?>