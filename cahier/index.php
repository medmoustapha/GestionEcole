<?php
  if ($_SESSION['type_util']=="E")
  {
    include "cahier/index_E.php";
  }
  else
  {
    include "cahier/index_D_P.php";
  }
?>