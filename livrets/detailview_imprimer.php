<?php
  if ($_SESSION['type_util']=="E")
  {
    include "livrets/detailview_imprimer_E.php";
  }
  else
  {
    include "livrets/detailview_imprimer_P_D.php";
  }
?>