<?php
  session_start();

  include "../config.php";

  include "../langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
		$Langue[$cle]=$Langue_Application[$cle];
  }
  if ($_SESSION['language_application']!="fr-FR")
  {
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

  Connexion_DB();

  $identifiant=$_POST["identifiant"];
  $motdepasse=md5($_POST["motdepasse"]);
	$erreur=0;

  $_SESSION['largeur_ecran']=$_POST["largeur_ecran"];
  $_SESSION['largeur_ecran_demi']=($_POST["largeur_ecran"]-350)/2;

  $req=mysql_query("SELECT * FROM `profs` WHERE identifiant='$identifiant' AND passe='$motdepasse' AND (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."')");
  if (mysql_num_rows($req)!="")
  // On regarde si c'est un prof ou le directeur
  {
    $_SESSION['id_dossier_mail']="R";
		$_SESSION['livres_a_afficher']="U";
    $_SESSION['id_util']=mysql_result($req,0,'id');
    $_SESSION['nom_util']=mysql_result($req,0,'nom')." ".mysql_result($req,0,'prenom');
    $_SESSION['type_util']=mysql_result($req,0,'type');
    $_SESSION['derniere_connexion']=mysql_result($req,0,'derniere_connexion');
		$req58=mysql_query("UPDATE `profs` SET derniere_connexion='".date("Y-m-d")."' WHERE id='".mysql_result($req,0,'id')."'");
		$dir="../cache/ged/enseignants";
		$dossier=scandir($dir);
		if (!in_array(mysql_result($req,0,'type').mysql_result($req,0,'id'),$dossier))
		{
			if (!mkdir($dir.'/'.mysql_result($req,0,'type').mysql_result($req,0,'id'), 0777)) 
			{
				die("Impossible de créer les dossiers nécessaires pour l'utilisateur");
			}
			else
			{
				if (!mkdir($dir.'/'.mysql_result($req,0,'type').mysql_result($req,0,'id').'/parents', 0777)) 
				{
					die("Impossible de créer les dossiers nécessaires pour l'utilisateur");
				}
				else
				{
					if (!mkdir($dir.'/'.mysql_result($req,0,'type').mysql_result($req,0,'id').'/personnel', 0777)) 
					{
						die("Impossible de créer les dossiers nécessaires pour l'utilisateur");
					}
				}
			}
		}
    $req_param=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".mysql_result($req,0,'id')."'");
		if (mysql_num_rows($req_param)!="" && mysql_result($req,0,'type')=="D")
		{
			$annee_a=mysql_result($req_param,0,'annee');
		}
		else
		{
			// Création de la table etablissement correspondant si elle n'existe pas
			$annee_a=date("Y");
			if (table_ok($gestclasse_config['param_connexion']['base'],"etablissement".$annee_a)==true)
			{
				$req58=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='zone'");
				if (mysql_result($req58,0,'valeur')!="I" && mysql_result($req58,0,'valeur')!="M" && mysql_result($req58,0,'valeur')!="P")
				{
					if (date("n")<=7) { $annee_a=date("Y")-1; } else { $annee_a=date("Y"); }
				}
				else
				{
					if (mysql_result($req58,0,'valeur')!="P")
					{
						$req58=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='etendue_annee_scolaire'");
						if (mysql_result($req58,0,'valeur')=="2")
						{
							$req58=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='fin_annee_scolaire'");
							$variable=substr(mysql_result($req58,0,'valeur'),4,2);
							if (substr($variable,0,1)=="0") { $variable=substr($variable,1,1); }
							if (date("n")<=$variable) { $annee_a=date("Y")-1; } else { $annee_a=date("Y"); }
						}
					}
				}
			}
			else
			{
				$annee_a=$annee_a-1;
			}
		}
    Change_Annee_Session($annee_a);

    if (table_ok($gestclasse_config['param_connexion']['base'],"cooperative".$annee_a)==false)
    {
      $req18=mysql_query("CREATE TABLE `".$gestclasse_config['param_connexion']['base']."`.`cooperative".$annee_a."` 
			(
				`id` varchar(50) NOT NULL,
				`date` date NOT NULL,
				`mode` varchar(1) NOT NULL,
				`ligne_comptable` varchar(5) NOT NULL,
				`piece` varchar(255) NOT NULL,
				`id_classe` varchar(5) NOT NULL,
				`montant` double(10,2) NOT NULL,
				`pointe` int(2) NOT NULL,
				`libelle` varchar(255) NOT NULL,
				`releve` varchar(255) NOT NULL,
				`banque` varchar(4) NOT NULL,
				`tiers` varchar(10) NOT NULL,
				`reference_bancaire` varchar(255) NOT NULL,
				UNIQUE KEY `id` (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }
    $_SESSION['cooperative_scolaire']=$annee_a;
    $_SESSION['annee_scolaire']=$annee_a;
	
    // Récupération de l'année scolaire de travail et de la classe
    if (mysql_num_rows($req_param)=="")
    {
      $_SESSION['affiche_devoirs']="0";
      $_SESSION['affiche_presents']="0";
      if ($_SESSION['type_util']=="D")
      {
        $_SESSION['id_classe_cours']="";
        $_SESSION['titulaire_classe_cours']="";
        $_SESSION['niveau_en_cours']="";
      }
      else
      {
        $req52=mysql_query("SELECT classes.*,classes_profs.* FROM `classes`, `classes_profs` WHERE classes_profs.id_prof='".mysql_result($req,0,'id')."' AND classes.id=classes_profs.id_classe AND classes.annee='".$annee_a."' AND (classes_profs.type='T' OR classes_profs.type='E') ORDER BY classes_profs.type DESC");
        if (mysql_num_rows($req52)!="")
        {
          $_SESSION['id_classe_cours']=mysql_result($req52,0,'classes.id');
          $req53=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req52,0,'classes.id')."' AND type='T'");
          $_SESSION['titulaire_classe_cours']=mysql_result($req53,0,'id_prof');
          $req53=mysql_query("SELECT classes_niveaux.*, listes.* FROM `classes_niveaux`,`listes` WHERE classes_niveaux.id_niveau=listes.id AND classes_niveaux.id_classe='".mysql_result($req52,0,'classes.id')."' ORDER BY listes.ordre ASC");
          $_SESSION['niveau_en_cours']=mysql_result($req53,0,'id_niveau');
        }
        else
        {
					$erreur=1;
        }
      }
    }
    else
    {
      $_SESSION['affiche_devoirs']=mysql_result($req_param,0,'devoirs');
      if ($_SESSION['type_util']=="D")
      {
        $_SESSION['annee_scolaire']=mysql_result($req_param,0,'annee');
        $_SESSION['id_classe_cours']=mysql_result($req_param,0,'id_classe_cours');
        $_SESSION['niveau_en_cours']=mysql_result($req_param,0,'niveau_en_cours');
        if (mysql_result($req_param,0,'id_classe_cours')=="")
        {
          $_SESSION['titulaire_classe_cours']="";
        }
        else
        {
          $req52=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req_param,0,'id_classe_cours')."' AND type='T'");
          $_SESSION['titulaire_classe_cours']=mysql_result($req52,0,'id_prof');
        }
      }
      else
      {
        $req52=mysql_query("SELECT classes.*,classes_profs.* FROM `classes`, `classes_profs` WHERE classes_profs.id_prof='".mysql_result($req,0,'id')."' AND classes.id=classes_profs.id_classe AND classes.annee='".$annee_a."' AND (classes_profs.type='T' OR classes_profs.type='E') ORDER BY classes_profs.type DESC");
        if (mysql_num_rows($req52)!="")
        {
          $_SESSION['id_classe_cours']=mysql_result($req52,0,'classes.id');
          $req53=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req52,0,'classes.id')."' AND type='T'");
          $_SESSION['titulaire_classe_cours']=mysql_result($req53,0,'id_prof');
          $req53=mysql_query("SELECT classes_niveaux.*, listes.* FROM `classes_niveaux`,`listes` WHERE classes_niveaux.id_niveau=listes.id AND classes_niveaux.id_classe='".mysql_result($req52,0,'classes.id')."' ORDER BY listes.ordre ASC");
          $_SESSION['niveau_en_cours']=mysql_result($req53,0,'id_niveau');
        }
        else
        {
				  $erreur=1;
        }
      }
      $_SESSION['theme_choisi']=mysql_result($req_param,0,'theme');
      $_SESSION['affiche_presents']=mysql_result($req_param,0,'affiche');
    }
    $_SESSION['date_en_cours']=date("Y-m-d");
    $_SESSION['mois_en_cours']=date("n");
		$_SESSION['annee_en_cours']=date("Y");
		$_SESSION['date_en_cours_calendrier']=date("Y-m-d");
		$_SESSION['calendrier']="mois";
  }
  else
  // On regarde si c'est un élève
  {
    // On vérifie si c'est l'identifiant du père
    $req=mysql_query("SELECT * FROM `eleves` WHERE identifiant='$identifiant' AND passe='$motdepasse' AND (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."')");
    if (mysql_num_rows($req)!="")
    {
			$_SESSION['id_dossier_mail']="R";
      $_SESSION['id_util']=mysql_result($req,0,'id');
      $_SESSION['nom_util']=mysql_result($req,0,'nom')." ".mysql_result($req,0,'prenom');
      $_SESSION['type_util']="E";
      $_SESSION['derniere_connexion']=mysql_result($req,0,'derniere_connexion');
  	  $req58=mysql_query("UPDATE `eleves` SET derniere_connexion='".date("Y-m-d")."' WHERE id='".mysql_result($req,0,'id')."'");
			$annee_a=date("Y");
			$req58=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='zone'");
			if (mysql_result($req58,0,'valeur')!="I" && mysql_result($req58,0,'valeur')!="M" && mysql_result($req58,0,'valeur')!="P")
			{
					if (date("n")<=7) { $annee_a=date("Y")-1; } else { $annee_a=date("Y"); }
			}
			else
			{
				if (mysql_result($req58,0,'valeur')!="P")
				{
					$req58=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='etendue_annee_scolaire'");
					if (mysql_result($req58,0,'valeur')=="2")
					{
							$req58=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='fin_annee_scolaire'");
					$variable=substr(mysql_result($req58,0,'valeur'),4,2);
					if (substr($variable,0,1)=="0") { $variable=substr($variable,1,1); }
								if (date("n")<=$variable) { $annee_a=date("Y")-1; } else { $annee_a=date("Y"); }
					}
				}
			}
      $_SESSION['annee_scolaire']=$annee_a;
      $req2=mysql_query("SELECT eleves_classes.*, classes.* FROM `eleves_classes`,`classes` WHERE eleves_classes.id_eleve='".mysql_result($req,0,'id')."' AND eleves_classes.id_classe=classes.id AND classes.annee='$annee_a'");
			if (mysql_num_rows($req2)=="")
			{
        $erreur=1;
			}
			else
			{
        $_SESSION['id_classe_cours']=mysql_result($req2,0,'classes.id');
        $_SESSION['niveau_en_cours']=mysql_result($req2,0,'eleves_classes.id_niveau');
				$req52=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req2,0,'classes.id')."' AND type='T'");
				$_SESSION['titulaire_classe_cours']=mysql_result($req52,0,'id_prof');
			}
      $_SESSION['date_en_cours']=date("Y-m-d");
      $_SESSION['mois_en_cours']=date("n");
      $req_param=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".mysql_result($req,0,'id')."'");
      if (mysql_num_rows($req_param)!="")
      {
        $_SESSION['theme_choisi']=mysql_result($req_param,0,'theme');
      }
		}
		else
		{
		  $erreur=2;
		}
  }

	if ($erreur==0)
	{
    Header("Location:../index_principal.php");
	}
	else
	{
    Header("Location:deconnexion.php?erreur=".$erreur);
	}
?>