<?php
  $req=mysql_query("DELETE FROM `bibliotheque_emprunt` WHERE id='".$_POST['id_emprunt']."'");
?>