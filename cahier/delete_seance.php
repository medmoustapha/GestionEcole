<?php
  $req=mysql_query("DELETE FROM `cahierjournal` WHERE id='" . $_POST['id'] . "'");
  $req=mysql_query("DELETE FROM `devoirs` WHERE id_seance='" . $_POST['id'] . "'");
?>