<div id="msg_ok2"></div>
<form target="calcul" enctype="multipart/form-data" name="form_restaure" id="form_restaure" action="index2.php" method="POST">
<input type="hidden" name="module" id="module" value="email">
<input type="hidden" name="action" id="action" value="save_pj">
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_FICHIER_JOINT']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><font id="texte_pj"><input type="file" id="pj" name="pj" value="" size=50></font></td>
</tr>
</table>
<input type="Submit" name="joindrepj" id="joindrepj" value="<?php echo $Langue['BTN_JOINDRE_FICHIER']; ?>">&nbsp;<input type="button" name="Annuler_PJ" id="Annuler_PJ" value="<?php echo $Langue['BTN_ANNULER']; ?>">
</form>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
	$("#joindrepj").button();
	$("#Annuler_PJ").button();
  $("#Annuler_PJ").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
	
	$("#joindrepj").click(function()
	{
	  $("#msg_ok2").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MAJ_CHARGEMENT_FICHIER']; ?></strong><br><br>');
	});
});

function formUploadCallback (nom_pj,id_pj) {
  switch (nom_pj)
  {
    case "move":
			$("#texte_pj").html('<input type="file" id="pj" name="pj" value="" size=50>');
      $("#msg_ok2").fadeIn( 1000 );
			$("#msg_ok2").html('<div class="ui-widget" style="width:80%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_MAJ_DEPLACEMENT']; ?></strong></div></div>');
			setTimeout(function()
      {
        $("#msg_ok2").effect("blind",1000);
      }, 6000 );
      break;
    case "upload":
			$("#texte_pj").html('<input type="file" id="pj" name="pj" value="" size=50>');
      $("#msg_ok2").fadeIn( 1000 );
			$("#msg_ok2").html('<div class="ui-widget" style="width:80%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_MAJ_UPLOAD']; ?></strong></div></div>');
			setTimeout(function()
      {
        $("#msg_ok2").effect("blind",1000);
      }, 6000 );
      break;
    default:
      $("#pj_id").val($("#pj_id").val()+id_pj+', ');
      $("#pj_name").val($("#pj_name").val()+nom_pj+', ');
			pf=str_replace(', ','<br>',$("#pj_name").val());
			$("#pj_nom").html(pf);
      $("#dialog-niveau2").dialog( "close" );
      break;
  }
}
</script>
