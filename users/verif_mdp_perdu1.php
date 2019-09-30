<?php
  session_start();

  include "../config.php";

  if (!isset($_SESSION['language_application']))
  {
    mysql_connect($gestclasse_config['param_connexion']['serveur'],$gestclasse_config['param_connexion']['user'],$gestclasse_config['param_connexion']['passe']);
    @mysql_select_db($gestclasse_config['param_connexion']['base']);
		$req=mysql_query("SELECT * FROM `config` WHERE parametre='langue_defaut'");
		if (mysql_num_rows($req)=="")
		{
				$_SESSION['language_application']="fr-FR";
		}
		else
		{
				$_SESSION['language_application']=mysql_result($req,0,'valeur');
		}
  }

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

  include "../config_parametre.php";
  
  include "../commun/fonctions.php";
  include "../commun/phplib/php/template.inc";

  Connexion_DB();

	if ($_POST['mdp_type']=="P")
	{
	  $req=mysql_query("SELECT * FROM `profs` WHERE identifiant='".$_POST['mdp_identifiant']."' AND (date_sortie='0000-00-00' OR date_sortie>='".date('Y-m-d')."')");
	}
	else
	{
	  $req=mysql_query("SELECT * FROM `eleves` WHERE identifiant='".$_POST['mdp_identifiant']."' AND (date_sortie='0000-00-00' OR date_sortie>='".date('Y-m-d')."')");
	}
	if (mysql_num_rows($req)!="")
	{
	  $ok="I";
	  if ($_POST['mdp_type']=="P")
		{
		  if (mysql_result($req,0,'questionsecrete')!="" && mysql_result($req,0,'reponse')!="" && mysql_result($req,0,'email')!="")
			{
			  $ok="O";
			}
			else
			{
				if (mysql_result($req,0,'questionsecrete')=="" || mysql_result($req,0,'reponse')=="")
				{
					$ok="Q";
				}
				else
				{
					$ok="M";
				}
			}
		}
		else
		{
		  if (mysql_result($req,0,'questionsecrete')!="" && mysql_result($req,0,'reponse')!="" && (mysql_result($req,0,'email_pere')!="" || mysql_result($req,0,'email_mere')!=""))
			{
			  $ok="O";
			}
			else
			{
				if (mysql_result($req,0,'questionsecrete')=="" || mysql_result($req,0,'reponse')=="")
				{
					$ok="Q";
				}
				else
				{
					$ok="M";
				}
			}
		}
    if ($ok=="O")
		{
	    echo $_POST['mdp_type'].mysql_result($req,0,'id');
		}
		else
		{
		  echo $ok;
		}
	}
	else
	{
	  echo $_POST['mdp_type'];
	}
?>