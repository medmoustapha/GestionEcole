<?php
  if ($_SESSION['type_util']=="E")
  {
    include "ged/index_E.php";
  }
  else
  {
    include "ged/index_D_P.php";
  }
?>