<?php
  session_start();
  
  if (isset($_POST['langue_install'])) { $_SESSION['langue_install']=$_POST['langue_install']; }
  if (!isset($_SESSION['accept_licence'])) { $_SESSION['accept_licence']="0"; }
  if (!isset($_SESSION['hostname'])) { $_SESSION['hostname']=""; }
  if (!isset($_SESSION['database'])) { $_SESSION['database']=""; }
  if (!isset($_SESSION['user'])) { $_SESSION['user']=""; }
  if (!isset($_SESSION['create_user'])) { $_SESSION['create_user']="0"; }
  if (!isset($_SESSION['create_database'])) { $_SESSION['create_database']="0"; }
  if (!isset($_SESSION['password'])) { $_SESSION['password']=""; }
  if (!isset($_SESSION['password_root'])) { $_SESSION['password_root']=""; }
  if (!isset($_SESSION['nom_etab'])) { $_SESSION['nom_etab']=""; }
  if (!isset($_SESSION['adresse_etab'])) { $_SESSION['adresse_etab']=""; }
  if (!isset($_SESSION['zone_etab'])) { $_SESSION['zone_etab']="A"; }
  if (!isset($_SESSION['civilite_admin'])) { $_SESSION['civilite_admin']="1"; }
  if (!isset($_SESSION['nom_admin'])) { $_SESSION['nom_admin']=""; }
  if (!isset($_SESSION['prenom_admin'])) { $_SESSION['prenom_admin']=""; }
  if (!isset($_SESSION['identifiant_admin'])) { $_SESSION['identifiant_admin']=""; }
  if (!isset($_SESSION['password_admin'])) { $_SESSION['password_admin']=""; }
  if (!isset($_SESSION['decoupage_etab'])) { $_SESSION['decoupage_etab']="1"; }

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
  if (!isset($_SESSION['debut_etab'])) { $_SESSION['debut_etab']=Date_Convertir("01/01/".date("Y"),"d/m/Y",$Format_Date_PHP); }
  if (!isset($_SESSION['fin_etab'])) { $_SESSION['fin_etab']=Date_Convertir("31/12/".date("Y"),"d/m/Y",$Format_Date_PHP); }
?>
<!DOCTYPE html>
<head dir="<?php echo $Sens_Ecriture; ?>" lang="<?php echo $Langue_Valeur; ?>">
  <meta charset="utf-8">
  <title>Gest'Ecole - <?php echo $Langue['LBL_ETAPE1_TITRE']; ?></title>
      
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

  <!-- Scripts jQuery UI -->
	  <script src="../commun/jquery/ui/jquery.ui.button.js"></script>

		<link rel="shortcut icon" href="../images/favicon.ico" />

<script language="Javascript">
$(document).ready(function()
{
  $("#Suivant").button();
});
</script>	  
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

  <div class="ui-widget ui-widget-content ui-corner-all textgauche" style="min-height:630px;padding:10px">
  <div class="titre_page"><?php echo $Langue['LBL_ETAPE1_TITRE']; ?></div><br /><br /><br /><br />
	<p class=explic><?php echo $Langue['LBL_ETAPE1_EXPL1']; ?> <?php echo $_SESSION['version_install']; ?>.</p>
	<p class=explic>&nbsp;</p>
	<p class=explic><?php echo $Langue['LBL_ETAPE1_EXPL2']; ?></p>
	<p class=explic>&nbsp;</p>
	<p class=explic><?php echo $Langue['LBL_ETAPE1_EXPL3']; ?></p>
	<ul class="explic">
    <li><u><?php echo $Langue['LBL_ETAPE1_EXPL4']; ?></u> : <?php echo $Langue['LBL_ETAPE1_EXPL4B']; ?></li>
    <li><u><?php echo $Langue['LBL_ETAPE1_EXPL5']; ?></u> : <?php echo $Langue['LBL_ETAPE1_EXPL5B']; ?></li>
    <li><u><?php echo $Langue['LBL_ETAPE1_EXPL6']; ?></u> : <?php echo $Langue['LBL_ETAPE1_EXPL6B']; ?></li>
    <li><u><?php echo $Langue['LBL_ETAPE1_EXPL7']; ?></u> : <?php echo $Langue['LBL_ETAPE1_EXPL7B']; ?></li>
    <li><u><?php echo $Langue['LBL_ETAPE1_EXPL8']; ?></u> : <?php echo $Langue['LBL_ETAPE1_EXPL8B']; ?></li>
    <li><u><?php echo $Langue['LBL_ETAPE1_EXPL9']; ?></u> : <?php echo $Langue['LBL_ETAPE1_EXPL9B']; ?></li>
	</ul>
	<p class=explic>&nbsp;</p>
	<p class=explic><?php echo $Langue['LBL_ETAPE1_EXPL10']; ?></p>
	<p class=explic>&nbsp;</p>
	<p class=explic><?php echo $Langue['LBL_ETAPE1_EXPL11']; ?></p>
	<p class=explic>&nbsp;</p>
	<p class=explic><?php echo $Langue['LBL_ETAPE1_EXPL12']; ?></p>
	<div class="textdroite"><input type="button" class="bouton" id="Suivant" name="Suivant" value="<?php echo $Langue['BTN_ETAPE1']; ?>" onClick="document.location.href='install2.php'"></div>
  </div>	
</body>
</html>
  
