<?php
  session_start();

  include "config.php";

  if (isset($_GET['module'])) { $module_faire=$_GET['module']; } else { $module_faire=$_POST['module']; }
  if (isset($_GET['action'])) { $action_faire=$_GET['action']; } else { $action_faire=$_POST['action']; }

  if ($module_faire!="users")
  {
	  include "langues/fr-FR/config.php";
	  include "langues/fr-FR/commun.php";
	  foreach ($Langue_Application AS $cle => $value)
	  {
	    $Langue[$cle]=$Langue_Application[$cle];
	  }
	  if (file_exists("langues/fr-FR/".$module_faire.".php"))
	  {
	    include "langues/fr-FR/".$module_faire.".php";
  	    foreach ($Langue_Module AS $cle => $value)
	    {
	      $Langue[$cle]=$Langue_Module[$cle];
	    }
	  }

	  if ($_SESSION['language_application']!="fr-FR")
	  {
		if (file_exists("langues/".$_SESSION['language_application']."/config.php"))
		{
		  include "langues/".$_SESSION['language_application']."/config.php";
		}
		if (file_exists("langues/".$_SESSION['language_application']."/commun.php"))
		{
		  include "langues/".$_SESSION['language_application']."/commun.php";
		  foreach ($Langue_Application AS $cle => $value)
		  {
			$Langue[$cle]=$Langue_Application[$cle];
		  }
		}
		if (file_exists("langues/".$_SESSION['language_application']."/".$module_faire.".php"))
		{
		  include "langues/".$_SESSION['language_application']."/".$module_faire.".php";
  	      foreach ($Langue_Module AS $cle => $value)
	      {
	        $Langue[$cle]=$Langue_Module[$cle];
	      }
		}
	  }
	  $Format_Date_Calendar = str_replace('d','dd',str_replace('m','mm',str_replace('Y','yy',$Format_Date_PHP)));  
  } 

  include "config_parametre.php";
  
  include "commun/fonctions.php";
  include "commun/phplib/php/template.inc";

  Connexion_DB();
  Param_Utilisateur($_SESSION['titulaire_classe_cours'],$_SESSION['annee_scolaire']);

  include $module_faire."/parametres_variables.php";

  include $module_faire . "/" . $action_faire . ".php";
?>
