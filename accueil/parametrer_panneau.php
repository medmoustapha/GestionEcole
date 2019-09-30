<?php
  $id=$_GET['id'];
  $req=mysql_query("SELECT * FROM `accueil_perso` WHERE id='".$id."'");
  $subpanel=mysql_result($req,0,'subpanel');
?>
<form action="index2.php" method=POST id=form_editview2 name=form_editview2>
<input type="hidden" id="module" name="module" value="accueil">
<input type="hidden" id="action" name="action" value="save_panneau">
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer_Param" name="Enrigistrer_Param" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Annuler_Param" name="Annuler_Param" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
<div id="msg_ok_p"></div>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_INFOS_PANNEAU']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_TITRE_PANNEAU']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><input type="text" id="titre" name="titre" class="text ui-widget-content ui-corner-all" value="<?php echo mysql_result($req,0,'titre'); ?>" size=50 maxlength=50></td>
</tr>
<?php include "subpanels/parametrer_".$subpanel.".php"; ?>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer2_Param" name="Enrigistrer2_Param" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Annuler2_Param" name="Annuler2_Param" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer_Param").button();
  $("#Annuler_Param").button();
  $("#Enregistrer2_Param").button();
  $("#Annuler2_Param").button();
  $("#Annuler_Param").click(function()
  {
    $("#dialog-niveau2").dialog( "destroy" );
  });
  $("#Annuler2_Param").click(function()
  {
    $("#dialog-niveau2").dialog( "destroy" );
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer_Param").click(function()
  {
    action_save="rien";
  });

  $("#form_editview2").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#titre"))) { bValid=false; }
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","accueil","<?php echo $Langue['LBL_PARAMETRER_PANNEAU']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function()
      {
        Message_Chargement(1,0);
        $("#tabs").tabs("load",0);
        $("#dialog-niveau2").dialog( "close" );
      });
    }
    else
    {
		  $("#dialog-niveau2").scrollTop(0);
			$("#msg_ok_p").fadeIn( 1000 );
			$("#msg_ok_p").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
			setTimeout(function()
			{
				$("#msg_ok_p").effect("blind",1000);
			}, 3000 );
      action_save="rien";
    }
  });

  $("#Enregistrer2_Param").click(function()
  {
    action_save="rien";
    $("#form_editview2").submit();
  });

});
</script>
