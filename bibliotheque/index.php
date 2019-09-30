<?php
  if ($_SESSION['type_util']=="E")
  {
    include "bibliotheque/index_E.php";
  }
  else
  {
    include "bibliotheque/index_D_P.php";
  }
?>