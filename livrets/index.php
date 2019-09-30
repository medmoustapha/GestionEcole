<?php
  if ($_SESSION['type_util']=="E")
  {
    include "livrets/detailview_ls.php";
  }
  else
  {
    include "livrets/index_D_P.php";
  }
?>