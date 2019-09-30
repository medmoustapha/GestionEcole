<?php
  session_start();
  
  include "../langues/fr-FR/commun.php";
  if ($_SESSION['language_application']!="fr-FR")
  {
	if (file_exists("../langues/".$_SESSION['language_application']."/commun.php"))
	{
	  include "../langues/".$_SESSION['language_application']."/commun.php";
	}
  }
  include "../commun/fonctions.php";

  $date_modif=Date_Convertir($_POST['date_en_cours_calendrier'],$Format_Date_PHP,'Y-m-d');;
  $_SESSION['date_en_cours_calendrier']=$date_modif;
  $_SESSION['annee_en_cours']=substr($date_modif,0,4);
  $_SESSION['mois_en_cours']=date('n',mktime(0,0,0,substr($date_modif,5,2),substr($date_modif,8,2),substr($date_modif,0,4)));
  $_SESSION['calendrier']="jour";
?>