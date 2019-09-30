<?php
  session_start();

  ini_set('error_reporting',E_ALL ^ E_DEPRECATED);

  $_SESSION['nom_etab']=$_POST['nom_etab'];
  $_SESSION['adresse_etab']=str_replace('\n','<br>',$_POST['adresse_etab']);
  $_SESSION['zone_etab']=$_POST['zone_etab'];
  $_SESSION['civilite_admin']=$_POST['civilite_admin'];
  $_SESSION['nom_admin']=$_POST['nom_admin'];
  $_SESSION['prenom_admin']=$_POST['prenom_admin'];
  $_SESSION['identifiant_admin']=$_POST['identifiant_admin'];
  $_SESSION['password_admin']=$_POST['password_admin'];
  if (isset($_POST['decoupage_etab'])) { $_SESSION['decoupage_etab']=$_POST['decoupage_etab']; }
  if (isset($_POST['debut_etab'])) { $_SESSION['debut_etab']=$_POST['debut_etab']; }
  if (isset($_POST['fin_etab'])) { $_SESSION['fin_etab']=$_POST['fin_etab']; }

  include "langues/fr-FR/installation.php";
  foreach ($Langue_Installation AS $cle => $value)
  {
		$Langue[$cle]=$value;
		$Langue_JS[$cle]=str_replace('"','&quot;',str_replace("'","\'",$value));
  }
  include "../langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
		$Langue[$cle]=$value;
		$Langue_JS[$cle]=str_replace('"','&quot;',str_replace("'","\'",$value));
  }
  if ($_SESSION['langue_install']!="fr-FR")
  {
		include "langues/".$_SESSION['langue_install']."/installation.php";
		foreach ($Langue_Installation AS $cle => $value)
		{
			$Langue[$cle]=$value;
			$Langue_JS[$cle]=str_replace('"','&quot;',str_replace("'","\'",$value));
		}
	}
	
	include "../commun/fonctions.php";
?>
<!DOCTYPE html>
<head dir="<?php echo $Sens_Ecriture; ?>" lang="<?php echo $Langue_Valeur; ?>">
  <meta charset="utf-8">
  <title>Gest'Ecole - <?php echo $Langue['LBL_ETAPE6_TITRE']; ?></title>
      
  <!-- **************** -->
  <!-- * Fichiers CSS * -->
  <!-- **************** -->

  <!-- Fichiers CSS jQuery -->
    <link rel="stylesheet" href="../themes/redmond/jquery.ui.all_<?php echo $Sens_Ecriture; ?>.css">
    <link rel="stylesheet" href="../themes/redmond/personnel_<?php echo $Sens_Ecriture; ?>.css">
    <link rel="stylesheet" href="../themes/personnel_<?php echo $Sens_Ecriture; ?>.css">
		<link rel="stylesheet" href="../themes/jquery.datatables_<?php echo $Sens_Ecriture; ?>.css">

  <!-- Scripts jQuery fondamentaux -->
	  <script src="../commun/jquery/jquery.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.core.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.widget.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.mouse.js"></script>
	  <script src="../commun/jquery/ui/jquery.effects.core.js"></script>
	  <script src="../commun/jquery/ui/jquery.effects.blind.js"></script>
	  <script src="../commun/jquery/ui/jquery.effects.fade.js"></script>
<link rel="shortcut icon" href="../images/favicon.ico" />

  <!-- Scripts jQuery UI -->
	  <script src="../commun/jquery/ui/jquery.ui.button.js"></script>
</head>


<body dir="<?php echo $Sens_Ecriture; ?>">
<a name="haut_page"></a>
<!-- Entête de la page -->
<div align=center id="message" class="message_chargement ui-corner-all" style="visibility:hidden;z-index:5000;"></div>
<div class="ui-widget ui-widget-content ui-corner-all espacement_bas">
  <div class="ui-widget ui-widget-header ui-corner-all entete" style="height:40px;">
    <div class="floatgauche"><img src="../themes/images/logo_petit.png"> Gest'&Eacute;cole <font style="font-size:10px;font-weight:normal;">Version <?php echo $_SESSION['version_install']; ?></font></div>
  </div>
</div>
  <div class="ui-widget ui-widget-content ui-corner-all" style="min-height:630px;padding:10px" align=left>
  <div class="titre_page"><?php echo $Langue['LBL_ETAPE6_TITRE']; ?> : <?php echo $Langue['LBL_ETAPE1_EXPL9B']; ?></div><br /><br /><br /><br />
<?php
  $install=true;
  $hostname=$_SESSION['hostname'];
  $database=$_SESSION['database'];
  $user=$_SESSION['user'];
  $password=$_SESSION['password'];
  $create_database=$_SESSION['create_database'];
  $create_user=$_SESSION['create_user'];
  $password_root=$_SESSION['password_root'];
  
  $civilite_admin=$_SESSION['civilite_admin'];
  $nom_admin=$_SESSION['nom_admin'];
  $prenom_admin=$_SESSION['prenom_admin'];
  $identifiant_admin=$_SESSION['identifiant_admin'];
  $password_admin=md5($_SESSION['password_admin']);
  
  $avancement=true;

  echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL1'];
  if ($create_user=="1")
  {
    if (@mysql_connect($hostname,'root',$password_root))
    {
      echo " <font color=#008000>".$Langue['LBL_ETAPE6_FAIT']."<strong></strong></font></p>";
      if ($create_database=="1")
      {
        $req = mysql_query("CREATE DATABASE `".$database."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
        echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL2']." '$database'... <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p>";
      }
      $req = mysql_query("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX ON `".$database."`.*	TO '".$user."'@'".$hostname."' IDENTIFIED BY '$password';");
      $req = mysql_query("SET PASSWORD FOR '".$user."'@'".$hostname."' = password('$password')");
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL3']." '$user'... <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p><p class=explic>&nbsp;</p>";

    }
    else
    {
      echo " <p class=explic>".$Langue['LBL_ETAPE6_EXPL4']." <font color=#ff0000><strong>".$Langue['LBL_ETAPE6_STOP']."</strong></font></p><p class=explic>&nbsp;</p>";
      $avancement=false;
    }
  }
  else
  {
    if (@mysql_connect($hostname,$user,$password))
    {
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL5']." '".$user."'.</p>";
      if ($create_database=="1")
      {
        $req = mysql_query("CREATE DATABASE `".$database."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
        $req = mysql_query("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX ON `".$database."`.*	TO '".$user."'@'".$hostname."' IDENTIFIED BY '$password';");
        $req = mysql_query("SET PASSWORD FOR '".$user."'@'".$hostname."' = password('$password')");
        echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL2']." '$database'... <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p><p class=explic>&nbsp;</p>";
      }
    }
    else
    {
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL6']." '".$user."'... <font color=#ff0000><strong>".$Langue['LBL_ETAPE6_STOP']."</strong></font></p><p class=explic>&nbsp;</p>";
      $avancement=false;
    }
  }
  if ($avancement==true)
  {
    if (@mysql_connect($hostname,$user,$password))
    {
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL5']." '".$user."'.</p><p class=explic>&nbsp;</p>";
    }
    else
    {
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL6']." '".$user."'... <font color=#FF0000><strong>".$Langue['LBL_ETAPE6_STOP']."</strong></font></p><p class=explic>&nbsp;</p>";
      $avancement=false;
    }
  }
  if ($avancement==true)
  {
	function split_sql($sql) 
	{
		$sql = trim($sql);
		$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);

		$buffer = array();
		$ret = array();
		$in_string = false;

		for($i=0; $i<strlen($sql)-1; $i++) {
			if($sql[$i] == ";" && !$in_string) {
				$ret[] = substr($sql, 0, $i);
				$sql = substr($sql, $i + 1);
				$i = 0;
			}

			if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
				$in_string = false;
			}
			elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
				$in_string = $sql[$i];
			}
			if(isset($buffer[1])) {
				$buffer[0] = $buffer[1];
			}
			$buffer[1] = $sql[$i];
		}

		if(!empty($sql)) {
			$ret[] = $sql;
		}
		return($ret);
	}

	function Table($fichier)
	{
		$mqr = @get_magic_quotes_runtime();
		@set_magic_quotes_runtime(0);
		$query = fread( fopen( $fichier, 'r' ), filesize( $fichier ) );
		@set_magic_quotes_runtime($mqr);
		$pieces  = split_sql($query);

		for ($i=0; $i<count($pieces); $i++)
	  {
			$pieces[$i] = trim($pieces[$i]);
			if(!empty($pieces[$i]) && $pieces[$i] != "#")
		{
		  $req=mysql_query($pieces[$i]);
			}
		}
	}
    if (@mysql_select_db($database))
    {
			// Création des tables
			mysql_query('SET NAMES utf8');
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL7'];
      Table("mysql_table.sql");
      echo " <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p><p class=explic>&nbsp;</p>";

			// Données par défaut
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL8'];
      Table("mysql_donnees.sql");
      echo " <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p><p class=explic>&nbsp;</p>";

			// Données spécifiques de la langue
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL9'];
      Table("langues/".$_SESSION['langue_install']."/mysql_donnees.sql");
      echo " <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p><p class=explic>&nbsp;</p>";

			// Création des données personnalisées
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL10'];
      $req=mysql_query("INSERT INTO `profs` (id,nom,prenom,civilite,identifiant,passe,date_entree,type) VALUES ('1','".$nom_admin."','".$prenom_admin."','$civilite_admin','$identifiant_admin','$password_admin','".date('Y-m-d')."','D')");
      $req=mysql_query("INSERT INTO `config` (parametre,valeur) VALUES ('sauve_bdd','".date("Y-m-d",mktime(0,0,0,date("m"),date("d")-16,date("Y")))."')");
			$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('nom','".$_SESSION['nom_etab']."')");
			$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('adresse','".$_SESSION['adresse_etab']."')");
			$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('zone','".$_SESSION['zone_etab']."')");
			if ($_SESSION['zone_etab']=="P")
			{
				if ($_SESSION['decoupage_etab']=="1")
				{
					$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('etendue_annee_scolaire','1')");
					$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('debut_annee_scolaire','".date('Y')."-01-01')");
					$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('fin_annee_scolaire','".date('Y')."-12-31')");
				}
				else
				{
					$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('etendue_annee_scolaire','2')");
					$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('debut_annee_scolaire','".Date_Convertir($_SESSION['debut_etab'],$Format_Date_PHP,'Y-m-d')."')");
					$req=mysql_query("INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES ('fin_annee_scolaire','".Date_Convertir($_SESSION['fin_etab'],$Format_Date_PHP,'Y-m-d')."')");
				}
			}
			
			if ($_SESSION['zone_etab']!="I" && $_SESSION['zone_etab']!="M" && $_SESSION['zone_etab']!="P")
			{
				if (date("n")<=7) { $annee=date("Y")-1; } else { $annee=date("Y"); }
			}
			else
			{
				if ($_SESSION['zone_etab']=="P")
				{
					if ($_SESSION['decoupage_etab']=="1") { $annee=date("Y"); } else { $annee=substr(Date_Convertir($_SESSION['debut_etab'],$Format_Date_PHP,'Y-m-d'),0,4); }
				}
				else
				{
					$annee=date("Y");
				}
			}
      $req=mysql_query("CREATE TABLE `$database`.`etablissement".$annee."` (`parametre` varchar( 50 ) NOT NULL ,`valeur` text NOT NULL) ENGINE = MYISAM DEFAULT CHARSET = utf8");
			$req=mysql_query("INSERT INTO `$database`.`etablissement".$annee."` SELECT * FROM `$database`.`etablissement`");
	  
      echo " <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p><p class=explic>&nbsp;</p>";

      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL11']." 'config.php'...";
      $fp = fopen("../config.php","w");
      fwrite($fp,"<"."?php\n");
      fwrite($fp,"$"."gestclasse_config = array(\n");
      fwrite($fp,"  'param_connexion' => Array(\n");
      fwrite($fp,"     'serveur' => '".$hostname."',\n");
      fwrite($fp,"     'user' => '".$user."',\n");
      fwrite($fp,"     'passe' => '".$password."',\n");
      fwrite($fp,"     'base' => '".$database."'\n");
      fwrite($fp,"  ),\n");
      fwrite($fp,");\n");
      fwrite($fp,"?".">\n");
      fclose($fp);
      echo " <font color=#008000><strong>".$Langue['LBL_ETAPE6_FAIT']."</strong></font></p><p class=explic>&nbsp;</p>";

// Envoi des statistiques
      $cle=md5($_SESSION['nom_etab']);
			$req=mysql_query("INSERT INTO `config` (parametre,valeur) VALUES ('cle_stat','$cle')");

      echo "<p class=explic><font color=#008000><strong>".$Langue['LBL_ETAPE6_EXPL12']."</strong></font></p><p class=explic>&nbsp;</p>";
			echo "<p class=expli><font color=#FF0000><strong>".$Langue['LBL_ETAPE6_EXPL13']."</strong></font></p><p class=explic>&nbsp;</p>";
      echo "<p class=explic>".$Langue['LBL_ETAPE6_EXPL14']." <b>".$identifiant_admin."</b> ".$Langue['LBL_ETAPE6_EXPL14B']."</p><p class=explic>&nbsp;</p><p class=explic><font color=#FF0000><strong>".$Langue['LBL_ETAPE6_EXPL15']."</strong></font></p><p class=explic>&nbsp;</p>";
    }
    else
    {
      echo "<p class=explic><font color=#FF0000><strong>".$Langue['LBL_ETAPE6_EXPL16']." '$database'... ".$Langue['LBL_ETAPE6_STOP']."</strong></font></p><p class=explic>&nbsp;</p>";
    }
  }
?>
	<div style="text-align:right"><input type="button" class="bouton" id="Retour" name="Retour" value="<?php echo $Langue['BTN_RETOUR_ETAPE']; ?>" onClick="document.location.href='install5.php'">&nbsp;<input type="button" class="bouton" id="Suivant" name="Suivant" value="<?php echo $Langue['LBL_ETAPE6_EXPL17']; ?>" onClick="document.location.href='../index.php?cle=<?php echo $cle; ?>&type=install&version=<?php echo $_SESSION['version_install']; ?>&zone=<?php echo $_SESSION['zone_etab']; ?>&faire=1'"></div>
	</form>
  </div>	
<script language="Javascript">
$(document).ready(function()
{
  <?php if ($avancement==true) { ?>
  $("#Suivant").button({disabled:false});
  $("#Retour").button({disabled:true});
  <?php } else { ?>
  $("#Retour").button({disabled:false});
  $("#Suivant").button({disabled:true});
  <?php } ?>
});
</script>	  
</body>
</html>
  
