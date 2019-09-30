<?php
  session_start();

  include "../langues/fr-FR/config.php";
  include "../langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
	$Langue[$cle]=$Langue_Application[$cle];
  }
  if ($_SESSION['language_application']!="fr-FR")
  {
	if (file_exists("../langues/".$_SESSION['language_application']."/config.php"))
	{
	  include "../langues/".$_SESSION['language_application']."/config.php";
	}
	if (file_exists("../langues/".$_SESSION['language_application']."/commun.php"))
	{
	  include "../langues/".$_SESSION['language_application']."/commun.php";
	  foreach ($Langue_Application AS $cle => $value)
	  {
		$Langue[$cle]=$Langue_Application[$cle];
	  }
	}
  }
  
  include "../commun/fonctions.php";

  $_SESSION['date_en_cours']=Date_Convertir($_POST['date_choisie'],$Format_Date_PHP,"Y-m-d");
?>