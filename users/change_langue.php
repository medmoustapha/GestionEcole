<?php
  session_start();

  $_SESSION['language_application']=$_GET['langue_choisi'];

  Header("Location:../index_principal.php?tab_en_cours=".$_GET['tab_en_cours']);
?>