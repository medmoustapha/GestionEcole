<?php
  session_start();

  if (!isset($_SESSION['largeur_ecran'])) { $_SESSION['largeur_ecran']=0; $_SESSION['largeur_ecran_demi']=0; }
  if (!isset($_SESSION['version_install'])) { $_SESSION['version_install']="2.1"; }
  if (!isset($_SESSION['langue_install'])) { $_SESSION['langue_install']="fr-FR"; }
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Gest'Ecole - Procédure d'installation - Choix de la langue / Installation - Choose the language</title>
      
  <!-- **************** -->
  <!-- * Fichiers CSS * -->
  <!-- **************** -->

  <!-- Fichiers CSS jQuery -->
    <link rel="stylesheet" href="../themes/redmond/jquery.ui.all_ltr.css">
    <link rel="stylesheet" href="../themes/redmond/personnel_ltr.css">
    <link rel="stylesheet" href="../themes/personnel_ltr.css">
	<link rel="stylesheet" href="../themes/jquery.datatables_ltr.css">

  <!-- Scripts jQuery fondamentaux -->
	  <script src="../commun/jquery/jquery.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.core.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.widget.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.mouse.js"></script>

  <!-- Scripts jQuery UI -->
	  <script src="../commun/jquery/ui/jquery.ui.button.js"></script>
		<link rel="shortcut icon" href="../images/favicon.ico" />
</head>


<body>
<a name="haut_page"></a>
<!-- Entête de la page -->
<div align=center id="message" class="message_chargement ui-corner-all" style="visibility:hidden;z-index:5000;"></div>
<div class="ui-widget ui-widget-content ui-corner-all espacement_bas">
  <div class="ui-widget ui-widget-header ui-corner-all entete" style="height:40px;">
    <div style="float:left;"><img src="../themes/images/logo_petit.png"> Gest'&Eacute;cole <font style="font-size:10px;font-weight:normal;">Version <?php echo $_SESSION['version_install']; ?></font></div>
  </div>
</div>

  <div class="ui-widget ui-widget-content ui-corner-all" style="min-height:630px;padding:10px" align=left>
  <div class="titre_page">Procédure d'installation - Choix de la langue / Installation - Choose the language</div><br /><br /><br /><br />
  <form id="Edit" name="Edit" action="install1.php" method="POST">
	<table cellspacing=0 cellpadding=5 border=0 width=100%>
	<tr>
	  <td width=50% class="textdroite" valign=top>
			Choisissez la langue de la procédure d'installation :<br>
			Choose the language of the installation :
		</td>
		<td width=50% class="textgauche">
		  <select id="langue_install" name="langue_install" class="text ui-widget-content ui-corner-all" size=15>
			  <option value="en-GB">English</option>
				<option value="fr-FR" selected>Français</option>
			</select>
		</td>
	</tr>
	</table>
	<br><br><br>
	<div style="text-align:right"><input type="button" class="bouton" id="Suivant" name="Suivant" value="Passer à l'étape 1 / Go to step 1"></div>
	</form>
  </div>	
</body>
</html>
<script language="Javascript">
$(document).ready(function()
{
  $("#Suivant").button();
  
  $("#Suivant").click(function(event)
  {
    event.preventDefault();
		var $form = $( this );
		url = $form.attr( 'action' );
		data = $form.serialize();
	  $("#Edit").submit();
	});
});
</script>
  
