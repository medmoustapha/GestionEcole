<?php
  $module_session=$_POST['module_session'];
  $longueur=$_POST['longueur'];
  $page=$_POST['page'];
  $colonne_index=$_POST['colonne_index'];
  $colonne_ordre=$_POST['colonne_ordre'];
  
  $_SESSION['tableau_'.$module_session]=$longueur."|".$page."|".$colonne_index."|".$colonne_ordre;
?>