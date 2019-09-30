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

	if ($_POST['type_recherche']=="P")
	{
	  $req=mysql_query("SELECT * FROM `profs` WHERE id='".$_POST['id_recherche']."'");
		$to=mysql_result($req,0,'email');
	}
	else
	{
	  $req=mysql_query("SELECT * FROM `eleves` WHERE id='".$_POST['id_recherche']."'");
		if ($_POST['email_contact']=="1") { $to=mysql_result($req,0,'email_pere'); } else { $to=mysql_result($req,0,'email_mere'); }
	}

	if (mysql_result($req,0,'reponse')==$_POST['reponse'])
	{
		$passe=Construit_Id('eleves',6);
		if ($_POST['type_recherche']=="P")
		{
			$req=mysql_query("UPDATE `profs` SET passe='".md5($passe)."' WHERE id='".$_POST['id_recherche']."'");
		}
		else
		{
			$req=mysql_query("UPDATE `eleves` SET passe='".md5($passe)."' WHERE id='".$_POST['id_recherche']."'");
		}
		
		$message=$Langue['MSG_EMAIL_CORPS'].$passe.$Langue['MSG_EMAIL_CORPS2'];
		$sujet=$Langue['MSG_EMAIL_SUJET'];
		$headers = 'From: doxconception@gmail.com' . "\r\n" .
				 'Content-Type: text/html; charset="utf-8"' ."\r\n" .
				 'Reply-To: doxconception@gmail.com' . "\r\n" .
				 'X-Mailer: PHP/' . phpversion();
		mail($to, $sujet, $message, $headers);
		echo $to;
	}
	else
	{
	  echo "E";
	}
?>