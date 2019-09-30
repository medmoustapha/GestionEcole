<form action="index2.php" method=POST id=form_tiers name=Detail_Tiers>
<input type="hidden" id="module" name="module" value="cooperative">
<input type="hidden" id="action" name="action" value="save_tiers">
<div id="msg_tiers"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enrigistrer_Tiers" name="Enrigistrer_Tiers" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Annuler_Tiers" name="Annuler_Tiers" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_SAISIE_TIERS_NOM']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=80%>
    <span title="<?php echo $Langue['EXPL_SAISIE_TIERS_NOM']; ?>"><input type="text" class="text ui-widget-content ui-corner-all" name="nom_tiers" id="nom_tiers" value="" size=50 maxlength=100></span>
  </td>
</tr>
<tr>
  <td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_SAISIE_TIERS_TYPE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=80%>
    <span title="<?php echo $Langue['EXPL_SAISIE_TIERS_TYPE']; ?>">
	<select class="text ui-widget-content ui-corner-all" name="type_tiers" id="type_tiers">
	<option value="401"><?php echo $Langue['LBL_SAISIE_TIERS_FOURNISSEUR']; ?></option>
	<option value="411"><?php echo $Langue['LBL_SAISIE_TIERS_CLIENT']; ?></option>
	</select>
    </span>
  </td>
</tr>
</table>
</form>

<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enrigistrer_Tiers").button();
  $("#Annuler_Tiers").button();
  $("#Annuler_Tiers").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });

  $("#form_tiers").submit(function(event)
  {
    event.preventDefault();
    if ($("#nom_tiers").val()=="") 
	{ 
	  $("#nom_tiers").addClass( "ui-state-error" );
	}
    else
    {
	  $("#nom_tiers").removeClass( "ui-state-error" );
	  Message_Chargement(2,1);
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request2 = $.ajax({type: "POST", url: url, data: data});
      request2.done(function(msg)
      {
	    Message_Chargement(2,0);
		$("#liste_tiers").html(msg);
		$("#dialog-niveau2").dialog( "close" );
      });
    }
  });
});
</script>
