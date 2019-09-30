<form target="calcul" enctype="multipart/form-data" action="index2.php" method="POST" id="form_restaure" name="form_restaure">
<input type="hidden" id="module" name="module" value="configuration">
<input type="hidden" id="action" name="action" value="save_logo">
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer" name="Enrigistrer" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Rafraichir" name="Rafraichir" value="<?php echo $Langue['BTN_RAFRAICHIR']; ?>">&nbsp;
    <input type="Button" id="Supprimer" name="Supprimer" value="<?php echo $Langue['BTN_SUPPRIMER']; ?>">&nbsp;
    <input type="Button" id="Annuler" name="Annuler" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite">
    <input type="Button" id="aide_fenetre" name="aide_fenetre" value="<?php echo $Langue['BTN_AIDE']; ?>">
  </td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_LOGO']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15% valign=top><label class="label_class"><?php echo $Langue['LBL_LOGO2']; ?> :</label></td>
  <td class="gauche" width=85%><input type="file" name="fichier" id="fichier" size=75><br><?php echo $Langue['EXPL_LOGO']; ?></td>
</tr>
  <td class="droite" width=15% valign=top><label class="label_class"><?php echo $Langue['LBL_LOGO_ACTUEL']; ?> :</label></td>
  <td class="gauche" width=50%>
<?php
  $image_existe=false;
  if (file_exists("cache/logo_ecole.png") || file_exists("cache/logo_ecole.jpg"))
  {
    $image_existe=true;
    if (file_exists("cache/logo_ecole.png")) 
	{ 
	  $dimensions=getimagesize('cache/logo_ecole.png');
	  if ($dimensions[0]<$dimensions[1])
	  {
	    if ($dimensions[1]>100) { $largeur=100*$dimensions[0]/$dimensions[1]; $hauteur=100; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
	  }
	  else
	  {
	    if ($dimensions[0]>100) { $hauteur=100*$dimensions[1]/$dimensions[0]; $largeur=100; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
	  }
	  echo '<img src="cache/logo_ecole.png?'.time().'" width='.$largeur.' height='.$hauteur.' border=0>'; 
	}
    if (file_exists("cache/logo_ecole.jpg")) 
	{ 
	  $dimensions=getimagesize('cache/logo_ecole.jpg');
	  if ($dimensions[0]<$dimensions[1])
	  {
	    if ($dimensions[1]>100) { $largeur=100*$dimensions[0]/$dimensions[1]; $hauteur=100; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
	  }
	  else
	  {
	    if ($dimensions[0]>100) { $hauteur=100*$dimensions[1]/$dimensions[0]; $largeur=100; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
	  }
	  echo '<img src="cache/logo_ecole.jpg?'.time().'" width='.$largeur.' height='.$hauteur.' border=0>'; 
	}
  }
  else
  {
    echo '<img src="images/no_logo.png" width=100 height=100 border=0>';
  }
?>
  </td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer2" name="Enrigistrer2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Rafraichir2" name="Rafraichir2" value="<?php echo $Langue['BTN_RAFRAICHIR']; ?>">&nbsp;
    <input type="Button" id="Supprimer2" name="Supprimer2" value="<?php echo $Langue['BTN_SUPPRIMER']; ?>">&nbsp;
    <input type="Button" id="Annuler2" name="Annuler2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/directeur-configuration/article/242-uploadermodifiersupprimer-le-logo-de-votre-etablissement.html","Aide");
  });

<?php if ($image_existe==true) { ?>
    $("#Supprimer").button({disabled:false});
    $("#Supprimer2").button({disabled:false});
<?php } else { ?>
    $("#Supprimer").button({disabled:true});
    $("#Supprimer2").button({disabled:true});
<?php } ?>
    $("#Enregistrer").button();
    $("#Enregistrer2").button();
    $("#Rafraichir").button();
    $("#Rafraichir2").button();
    $("#Annuler").button();
    $("#Annuler2").button();
	
	$("#Supprimer").click(function()
	{
      $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_LOGO_SUPPRIMER']; ?></div>');
	  $( "#dialog:ui-dialog" ).dialog( "destroy" );

	  $( "#dialog-confirm" ).dialog(
      {
        title: "<?php echo $Langue['LBL_LOGO_SUPPRIMER']; ?>",
		resizable: false,
		draggable: false,
		height:200,
		width:450,
		modal: true,
		buttons:[
        {
          text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
		  click: function()
          {
            Message_Chargement(4,1);
            var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=delete_logo"});
            request.done(function()
            {
			  $( "#dialog-confirm" ).dialog( "close" );
              Charge_Dialog("index2.php?module=configuration&action=config_logo","<?php echo $Langue['LBL_LOGO']; ?>");
              Message_Chargement(1,0);
			});
          }
		},
		{
          text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
		  click: function()
          {
			$( this ).dialog( "close" );
		  }
		}]
	  });
	});

	$("#Supprimer2").click(function()
	{
	  $("#Supprimer").click();
	});

	$("#Rafraichir").click(function()
	{
	  Charge_Dialog("index2.php?module=configuration&action=config_logo","<?php echo $Langue['LBL_LOGO']; ?>");
	});
	$("#Rafraichir2").click(function()
	{
	  Charge_Dialog("index2.php?module=configuration&action=config_logo","<?php echo $Langue['LBL_LOGO']; ?>");
	});
	$("#Annuler").click(function()
	{
	  $("#dialog-form").dialog( "close" );
	});
	$("#Annuler2").click(function()
	{
	  $("#dialog-form").dialog( "close" );
	});
    $("#form_restaure").submit(function(event)
    {
      if ($("#fichier").val()=="")
      {
        event.preventDefault();
	      $("#msg_ok").fadeIn( 1000 );
 		  $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_MAJ_FICHIER']; ?></strong></div></div>');
   		  setTimeout(function()
          {
            $("#msg_ok").effect("blind",1000);
          }, 3000 );
      }
      else
      {
        fichier=$("#fichier").val();
        extension=fichier.substr(fichier.length-4,4);
        if (extension.toLowerCase()==".jpg" || extension.toLowerCase()==".png")
        {
          $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout"><strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MAJ_CHARGEMENT_FICHIER']; ?></strong></div></div>');
        }
        else
        {
          event.preventDefault();
  	      $("#msg_ok").fadeIn( 1000 );
 	  	  $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_LOGO_ERREUR_FICHIER']; ?></strong></div></div>');
   	      setTimeout(function()
          {
            $("#msg_ok").effect("blind",1000);
          }, 3000 );
        }
      }
    });
	$("#Enregistrer2").click(function()
	{
	  $("#form_restaure").submit();
	});
});

function formUploadCallback (result) {
  switch (result)
  {
    case "move":
      $("#msg_ok").fadeIn( 1000 );
	  $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_LOGO_DEPLACEMENT']; ?></strong></div></div>');
      break;
    case "upload":
      $("#msg_ok").fadeIn( 1000 );
	  $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_LOGO_UPLOAD']; ?></strong></div></div>');
      break;
    default:
      $("#msg_ok").fadeIn( 1000 );
      $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_LOGO_UPLOAD_OK']; ?></strong></div></div>');
      break;
  }
  setTimeout(function()
  {
    $("#msg_ok").effect("blind",1000);
  }, 6000 );
}
</script>
