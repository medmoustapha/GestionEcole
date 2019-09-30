<?php
  session_start();

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
?>
<!DOCTYPE html>
<head dir="<?php echo $Sens_Ecriture; ?>" lang="<?php echo $Langue_Valeur; ?>">
  <meta charset="utf-8">
  <title>Gest'Ecole - <?php echo $Langue['LBL_ETAPE2_TITRE']; ?></title>
      
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
  $("#Retour").button();
  $("#Suivant").button({ disabled:true });
});

function Accepte_Licence()
{
    if (document.getElementById("accepted").checked==true)
    {
      $("#Suivant").button({ disabled:false });
    }
    else
    {
      $("#Suivant").button({ disabled:true });
    }
}
</script>	  
</head>


<body dir="<?php echo $Sens_Ecriture; ?>" onLoad="Accepte_Licence()">
<a name="haut_page"></a>
<!-- Entête de la page -->
<div align=center id="message" class="message_chargement ui-corner-all" style="visibility:hidden;z-index:5000;"></div>
<div class="ui-widget ui-widget-content ui-corner-all espacement_bas">
  <div class="ui-widget ui-widget-header ui-corner-all entete" style="height:40px;">
    <div class="floatgauche"><img src="../themes/images/logo_petit.png"> Gest'&Eacute;cole <font style="font-size:10px;font-weight:normal;">Version <?php echo $_SESSION['version_install']; ?></font></div>
  </div>
</div>

  <div class="ui-widget ui-widget-content ui-corner-all" style="min-height:630px;padding:10px" align=left>
  <div class="titre_page"><?php echo $Langue['LBL_ETAPE2_TITRE']; ?> : <?php echo $Langue['LBL_ETAPE1_EXPL5B']; ?></div><br /><br /><br /><br />
<?php
	$fh = fopen( "../licence_".$Langue_Licence.".txt", 'r' ) or die( "License file not found!" );
	$licence = fread( $fh, filesize( "../licence_".$Langue_Licence.".txt" ) );
	fclose( $fh );
?>
	<p class=explic><?php echo $Langue['LBL_ETAPE2_EXPL1']; ?></p>
	<p class=explic>&nbsp;</p>
	<center><div class="ui-corner-all" style="padding:10px; border: 1px solid #a6c9e2; line-height:24px; text-align:left; height:450px; overflow:auto; scrollbars:auto; direction:ltr"><?php echo str_replace("\n","<br>",$licence); ?></div></center><br>
	<p class=explic><?php echo $Langue['LBL_ETAPE2_EXPL2']; ?></p>
	<p class=explic>&nbsp;</p>
	<p class=explic style="text-align:center"><input id="accepted" type="checkbox" name="accepted" value="1" onClick="Accepte_Licence()" <?php if ($_SESSION['accept_licence']=="1") { echo "checked"; } ?>> <?php echo $Langue['LBL_ETAPE2_EXPL3']; ?></p>
	<p class=explic>&nbsp;</p>
	<div class="textdroite"><input type="button" class="bouton" id="Retour" name="Retour" value="<?php echo $Langue['BTN_RETOUR_ETAPE']; ?>" onClick="document.location.href='install1.php'">&nbsp;<input type="button" class="bouton" id="Suivant" name="Suivant" value="<?php echo $Langue['LBL_ETAPE2_EXPL4']; ?>" onClick="document.location.href='install3.php'"></div>
  </div>	
</body>
</html>
  
