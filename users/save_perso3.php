<?php
  $recherche=substr($_POST['recherche'],0,strlen($_POST['recherche'])-1);
	$module_session=$_POST['module_session'];
	$_SESSION['recherche_'.$module_session]=$recherche;
?>