<?php
  session_start();

  if (isset($_POST['hostname']))
  {
	  $_SESSION['largeur_ecran']=$_POST['largeur_ecran'];
	  $_SESSION['largeur_ecran_demi']=$_POST['largeur_ecran']/2;
	  $_SESSION['hostname']=$_POST['hostname'];
	  $_SESSION['database']=$_POST['database'];
	  $_SESSION['user']=$_POST['user'];
	  if (isset($_POST['create_user'])) { $_SESSION['create_user']=$_POST['create_user']; }
	  if (isset($_POST['create_database'])) { $_SESSION['create_database']=$_POST['create_database']; }
	  $_SESSION['password']=$_POST['password'];
	  if (isset($_POST['password_root'])) { $_SESSION['password_root']=$_POST['password_root']; }
  }
  
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
	$Format_Date_Calendar = str_replace('d','dd',str_replace('m','mm',str_replace('Y','yy',$Format_Date_PHP)));  
?>
<!DOCTYPE html>
<head dir="<?php echo $Sens_Ecriture; ?>" lang="<?php echo $Langue_Valeur; ?>">
  <meta charset="utf-8">
  <title>Gest'Ecole - <?php echo $Langue['LBL_ETAPE5_TITRE']; ?></title>
      
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
	  <script src="../commun/jquery/ui/jquery.ui.datepicker.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.mouse.js"></script>
	  <script src="../commun/jquery/ui/jquery.effects.core.js"></script>
	  <script src="../commun/jquery/ui/jquery.effects.blind.js"></script>
	  <script src="../commun/jquery/ui/jquery.effects.fade.js"></script>
<link rel="shortcut icon" href="../images/favicon.ico" />

  <!-- Scripts jQuery UI -->
	  <script src="../commun/jquery/ui/jquery.ui.button.js"></script>

<?php include "../commun/fonctions_js.js"; ?>
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
  <div class="titre_page"><?php echo $Langue['LBL_ETAPE5_TITRE']; ?> : <?php echo $Langue['LBL_ETAPE1_EXPL8B']; ?></div><br /><br /><br /><br />
  <div id="msg_ok"></div>
  <div class="ui-widget ui-widget-header ui-state-default ui-corner-all textgauche" style="float:none;padding:5px;margin-bottom:7px"><?php echo $Langue['LBL_ETAPE5_EXPL1']; ?></div>
  <form id="Edit" name="Edit" action="install6.php" method="POST">
  <table class="tableau_editview" align="center" cellspacing=0 cellpadding=0 width=100%>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL2']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85% colspan=2>
     <input type="text" id="nom_etab" name="nom_etab" value="<?php echo str_replace('\"','"',str_replace("\'","'",$_SESSION['nom_etab'])); ?>" size=40 maxlength=255 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15% valign=top>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL3']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85% colspan=2>
     <textarea id="adresse_etab" name="adresse_etab" cols=100 rows=5 class="text ui-widget-content ui-corner-all"><?php echo str_replace('\"','"',str_replace("\'","'",str_replace("<br>",'\n',$_SESSION['adresse_etab']))); ?></textarea>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL4']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85% colspan=2>
     <input type="radio" id="zone_etab" name="zone_etab" value="A" <?php if ($_SESSION['zone_etab']=="A") { echo "CHECKED"; } ?>> <b>Zone A</b> : Caen, Clermont-Ferrand, Grenoble, Lyon, Montpellier, Nancy-Metz, Nantes, Rennes, Toulouse <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2">&nbsp;</label>
    </td>
    <td class="gauche" width=85% colspan=2>
     <input type="radio" id="zone_etab" name="zone_etab" value="B" <?php if ($_SESSION['zone_etab']=="B") { echo "CHECKED"; } ?>> <b>Zone B</b> : Aix-Marseille, Amiens, Besançon, Dijon, Lille, Limoges, Nice, Orléans-Tours, Poitiers, Reims, Rouen, Strasbourg <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2">&nbsp;</label>
    </td>
    <td class="gauche" width=85% colspan=2>
     <input type="radio" id="zone_etab" name="zone_etab" value="C" <?php if ($_SESSION['zone_etab']=="C") { echo "CHECKED"; } ?>> <b>Zone C</b> : Bordeaux, Créteil, Paris, Versailles <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2">&nbsp;</label>
    </td>
    <td class="gauche" width=42%>
     <input type="radio" id="zone_etab" name="zone_etab" value="F" <?php if ($_SESSION['zone_etab']=="F") { echo "CHECKED"; } ?>> Guyane <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
    <td class="gauche" width=43%>
     <input type="radio" id="zone_etab" name="zone_etab" value="G" <?php if ($_SESSION['zone_etab']=="G") { echo "CHECKED"; } ?>> Martinique <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2">&nbsp;</label>
    </td>
    <td class="gauche" width=42%>
     <input type="radio" id="zone_etab" name="zone_etab" value="H" <?php if ($_SESSION['zone_etab']=="H") { echo "CHECKED"; } ?>> Mayotte <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
    <td class="gauche" width=43%>
     <input type="radio" id="zone_etab" name="zone_etab" value="I" <?php if ($_SESSION['zone_etab']=="I") { echo "CHECKED"; } ?>> Nouvelle-Calédonie <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2">&nbsp;</label>
    </td>
    <td class="gauche" width=42%>
     <input type="radio" id="zone_etab" name="zone_etab" value="J" <?php if ($_SESSION['zone_etab']=="J") { echo "CHECKED"; } ?>> Polynésie Française <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
    <td class="gauche" width=43%>
     <input type="radio" id="zone_etab" name="zone_etab" value="K" <?php if ($_SESSION['zone_etab']=="K") { echo "CHECKED"; } ?>> Réunion <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2">&nbsp;</label>
    </td>
    <td class="gauche" width=42%>
     <input type="radio" id="zone_etab" name="zone_etab" value="L" <?php if ($_SESSION['zone_etab']=="L") { echo "CHECKED"; } ?>> Saint-Pierre-et-Miquelon <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
    <td class="gauche" width=43%>
     <input type="radio" id="zone_etab" name="zone_etab" value="M" <?php if ($_SESSION['zone_etab']=="M") { echo "CHECKED"; } ?>> Wallis-et-Futuna <?php echo $Langue['LBL_ETAPE5_EXPL4B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2">&nbsp;</label>
    </td>
    <td class="gauche" width=85% colspan=2>
     <input type="radio" id="zone_etab" name="zone_etab" value="P" <?php if ($_SESSION['zone_etab']=="P") { echo "CHECKED"; } ?>> <?php echo $Langue['LBL_ETAPE5_EXPL5']; ?>
    </td>
  </tr>
  </table>
<div class="ui-widget ui-state-highlight ui-corner-all" id="Personnaliser" style="visibility:<?php if ($_SESSION['zone_etab']=="P") { echo "visible"; } else { echo "hidden"; } ?>;display:<?php if ($_SESSION['zone_etab']=="P") { echo "block"; } else { echo "none"; } ?>;">
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_ETAPE5_EXPL6']; ?> :</label></td>
  <td class="gauche" width=85% colspan=3><select class="text ui-widget-content ui-corner-all" id="decoupage_etab" name="decoupage_etab">
  <option value="1" <?php if ($_SESSION['decoupage_etab']=="1") echo "SELECTED"; ?>><?php echo $Langue['LBL_ETAPE5_EXPL6B']; ?></option>
  <option value="2" <?php if ($_SESSION['decoupage_etab']=="2") echo "SELECTED"; ?>><?php echo $Langue['LBL_ETAPE5_EXPL6T']; ?></option></select>
  </td>
</tr>
  <td class="droite" width=15%>&nbsp;</td>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_ETAPE5_EXPL7']; ?> :</label></td>
  <td class="gauche" width=35%><input type=text class="text ui-widget-content ui-corner-all" id=debut_etab name=debut_etab value="<?php echo $_SESSION['debut_etab']; ?>" size=10 maxlength=10><script> $(function() { $( "#debut_etab" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "../images/calendar.gif", buttonImageOnly: true }); });</script></td>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_ETAPE5_EXPL8']; ?> :</label></td>
  <td class="gauche" width=35%><input type=text class="text ui-widget-content ui-corner-all" id=fin_etab name=fin_etab value="<?php echo $_SESSION['fin_etab']; ?>" size=10 maxlength=10><script> $(function() { $( "#fin_etab" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "../images/calendar.gif", buttonImageOnly: true }); });</script></td>
</tr>
<tr>
  <td class="gauche" width=85% colspan=3><?php echo $Langue['LBL_ETAPE5_EXPL9']; ?></td>
</tr>
</table>
</div>
  <br /><br />
  <div class="ui-widget ui-widget-header ui-state-default ui-corner-all" style="float:none;padding:5px;text-align:left;margin-bottom:7px"><?php echo $Langue['LBL_ETAPE5_EXPL10']; ?></div>
  <table class="tableau_editview" align="center" cellspacing=0 cellpadding=0 width=100%>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL11']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <select id="civilite_admin" name="civilite_admin" class="text ui-widget-content ui-corner-all">
	   <option value="1" <?php if ($_SESSION['civilite_admin']=="1") { echo "SELECTED"; } ?>><?php echo $Langue['LBL_ETAPE5_EXPL11B']; ?></option>
	   <option value="2" <?php if ($_SESSION['civilite_admin']=="2") { echo "SELECTED"; } ?>><?php echo $Langue['LBL_ETAPE5_EXPL11T']; ?></option>
	   <option value="3" <?php if ($_SESSION['civilite_admin']=="3") { echo "SELECTED"; } ?>><?php echo $Langue['LBL_ETAPE5_EXPL11Q']; ?></option>
	 </select>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL12']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="text" id="nom_admin" name="nom_admin" value="<?php echo $_SESSION['nom_admin']; ?>" size=40 maxlength=255 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL13']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="text" id="prenom_admin" name="prenom_admin" value="<?php echo $_SESSION['prenom_admin']; ?>" size=40 maxlength=255 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL14']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="text" id="identifiant_admin" name="identifiant_admin" value="<?php echo $_SESSION['identifiant_admin']; ?>" size=40 maxlength=255 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL15']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="password" id="password_admin" name="password_admin" value="<?php echo $_SESSION['password_admin']; ?>" size=20 maxlength=20 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE5_EXPL16']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="password" id="password_admin2" name="password_admin2" value="" size=20 maxlength=20 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  </table>  
	<p class=explic>&nbsp;</p>
	<div style="text-align:right"><input type="button" class="bouton" id="Retour" name="Retour" value="<?php echo $Langue['BTN_RETOUR_ETAPE']; ?>" onClick="document.location.href='install4.php'">&nbsp;<input type="button" class="bouton" id="Suivant" name="Suivant" value="<?php echo $Langue['LBL_ETAPE5_EXPL17']; ?>"></div>
	</form>
  </div>	
<script language="Javascript">
$(document).ready(function()
{
  $("#Retour").button();
  $("#Suivant").button();
  
  $("input[name='zone_etab']").change(function()
  {
		if ($('input[type=radio]:checked').attr('value')=="P") 
		{ 
			document.getElementById('Personnaliser').style.visibility="visible"; 
			document.getElementById('Personnaliser').style.display="block"; 
		} 
		else 
		{ 
			document.getElementById('Personnaliser').style.visibility="hidden"; 
			document.getElementById('Personnaliser').style.display="none"; 
		}
  });

  $("#Suivant").click(function(event)
  {
		var bValid = true;
    if (!checkValue($("#nom_etab"))) { bValid=false; }
    if (!checkValue($("#adresse_etab"))) { bValid=false; }
    if (!checkValue($("#nom_admin"))) { bValid=false; }
    if (!checkValue($("#prenom_admin"))) { bValid=false; }
    if (!checkValue($("#identifiant_admin"))) { bValid=false; }
    if (!checkValue($("#password_admin"))) { bValid=false; }
    if (!checkValue($("#password_admin2"))) { bValid=false; }
		if (checkValue($("#password_admin")) && checkValue($("#password_admin2")))
		{
			if ($("#password_admin").val()!=$("#password_admin2").val())
			{
				bValid=false;
				$("#password_admin").addClass( "ui-state-error" );
				$("#password_admin2").addClass( "ui-state-error" );
			}
			else
			{
				$("#password_admin").removeClass( "ui-state-error" );
				$("#password_admin2").removeClass( "ui-state-error" );
			}
		}
    event.preventDefault();
    if ( bValid )
    {
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
			$("#Edit").submit();
    }
		else
    {
			document.location.href="#haut_page";
  	  $("#msg_ok").fadeIn( 1000 );
  	  $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['LBL_ETAPE4_EXPL9']; ?></strong></div></div><br />');
			setTimeout(function()
      {
        $("#msg_ok").effect("blind",1000);
      }, 3000 );
		}
  });
});
</script>	  
</body>
</html>
  
