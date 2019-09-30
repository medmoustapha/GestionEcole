<?php
  $req=mysql_query("UPDATE `bibliotheque_emprunt` SET date_emprunt='".date("Y-m-d")."', reservation='0' WHERE id='".$_POST['id_emprunt']."'");
?>