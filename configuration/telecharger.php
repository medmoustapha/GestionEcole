<?php
//  ob_clean();

  header("content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=".$_GET["fichier"]);

  flush();

  readfile("../cache/upload/".$_GET["fichier"]);
?>