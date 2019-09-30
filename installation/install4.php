<?php
  session_start();
  
  $_SESSION['accept_licence']="1";

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
  <title>Gest'Ecole - <?php echo $Langue['LBL_ETAPE4_TITRE']; ?></title>
      
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
  <div class="titre_page"><?php echo $Langue['LBL_ETAPE4_TITRE']; ?> : <?php echo $Langue['LBL_ETAPE1_EXPL7B']; ?></div><br /><br /><br /><br />
  <div id="msg_ok"></div>
  <div class="ui-widget ui-widget-header ui-state-default ui-corner-all" style="float:none;padding:5px;text-align:left;margin-bottom:7px"><?php echo $Langue['LBL_ETAPE4_EXPL1']; ?></div>
  <form id="Edit" name="Edit" action="install5.php" method="POST">
	<script language="Javascript">
		/* On recherche la largeur de la page */
		if (BrowserDetect.browser=="Explorer")
		{
			largeur=document.body.offsetWidth;
		}
		else
		{
			largeur=window.innerWidth;
		}
		document.write("<input type=hidden id=largeur_ecran name=largeur_ecran value="+largeur+">");
	</script>
  <table class="tableau_editview" align="center" cellspacing=0 cellpadding=0 width=100%>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE4_EXPL2']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="text" id="hostname" name="hostname" value="<?php echo $_SESSION['hostname']; ?>" size=40 maxlength=255 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE4_EXPL3']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="text" id="database" name="database" value="<?php echo $_SESSION['database']; ?>" size=40 maxlength=255 class="text ui-widget-content ui-corner-all">&nbsp;<input type="checkbox" id="create_database" name="create_database" value="1" <?php if ($_SESSION['create_database']=="1") { echo "checked"; } ?>> <?php echo $Langue['LBL_ETAPE4_EXPL3B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE4_EXPL4']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="text" id="user" name="user" value="<?php echo $_SESSION['user']; ?>" size=40 maxlength=255 class="text ui-widget-content ui-corner-all">&nbsp;<input type="checkbox" id="create_user" name="create_user" value="1" onClick="Change_User()" <?php if ($_SESSION['create_user']=="1") { echo "checked"; } ?>> <?php echo $Langue['LBL_ETAPE4_EXPL3B']; ?>
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE4_EXPL5']; ?> <font style="color:#FF0000">*</font> :</label>
    </td>
    <td class="gauche" width=85%>
     <input type="password" id="password" name="password" value="<?php echo $_SESSION['password']; ?>" size=20 maxlength=255 class="text ui-widget-content ui-corner-all">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE4_EXPL6']; ?> :</label>
    </td>
    <td class="gauche" width=85%>
     <input disabled type="password" id="password_confirm" name="password_confirm" value="" size=20 maxlength=255 class="text ui-widget-content ui-corner-all input_disabled">
    </td>
  </tr>
  <tr>
    <td class="droite" width=15%>
     <label class="label_class2"><?php echo $Langue['LBL_ETAPE4_EXPL7']; ?> :</label>
    </td>
    <td class="gauche" width=85%>
     <input disabled type="password" id="password_root" name="password_root" name="password_root" value="<?php echo $_SESSION['password_root']; ?>" size=20 maxlength=255 class="text ui-widget-content ui-corner-all input_disabled">
    </td>
  </tr>
  </table>
  <p class=explic>&nbsp;</p>
	<div class="textdroite"><input type="button" class="bouton" id="Retour" name="Retour" value="<?php echo $Langue['BTN_RETOUR_ETAPE']; ?>" onClick="document.location.href='install3.php'">&nbsp;<input type="button" class="bouton" id="Suivant" name="Suivant" value="<?php echo $Langue['LBL_ETAPE4_EXPL8']; ?>"></div>
	</form>
  </div>	
<script language="Javascript">
$(document).ready(function()
{
  $("#Retour").button();
  $("#Suivant").button();
  
  $("#Suivant").click(function(event)
  {
		var bValid = true;
    if (!checkValue($("#hostname"))) { bValid=false; }
    if (!checkValue($("#database"))) { bValid=false; }
    if (!checkValue($("#user"))) { bValid=false; }
    if (!checkValue($("#password"))) { bValid=false; }
		if ($("#create_user").is(':checked'))
		{
      if (!checkValue($("#password_root"))) { bValid=false; }
      if (!checkValue($("#password_confirm"))) 
			{ 
				bValid=false; 
			}
			else
			{
  	    if (checkValue($("#password")))
				{
					if ($("#password").val()!=$("#password_confirm").val())
  	      {
						bValid=false;
						$("#password").addClass( "ui-state-error" );
						$("#password_confirm").addClass( "ui-state-error" );
					}
					else
					{
						$("#password").removeClass( "ui-state-error" );
						$("#password_confirm").removeClass( "ui-state-error" );
					}
				}  
			}	
		}
		else
		{
			$("#password_root").removeClass( "ui-state-error" );
			$("#password").removeClass( "ui-state-error" );
			$("#password_confirm").removeClass( "ui-state-error" );
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

function Change_User()
{
  if (document.Edit.create_user.checked==true)
  {
    $("#password_root").removeAttr("disabled");
    $("#password_root").removeClass("input_disabled");
    $("#password_confirm").removeAttr("disabled");
    $("#password_confirm").removeClass("input_disabled");
  }
  else
  {
    $("#password_root").attr("disabled");
    $("#password_root").addClass("input_disabled");
    $("#password_confirm").attr("disabled");
    $("#password_confirm").addClass("input_disabled");
  }
}

</script>	  
</body>
</html>
  
