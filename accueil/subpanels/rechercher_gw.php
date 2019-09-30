  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem2_<?php echo $i_panneau; ?>" style="min-width:300px">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		<form name="form_rechercher<?php echo $id_panneau; ?>" method=GET>
		<div class="textcentre"><input type="text" name="rechercher<?php echo $id_panneau; ?>" style="padding:5px" class="text ui-widget-content ui-corner-all marge5_tout" id="rechercher<?php echo $id_panneau; ?>" size=50 maxlength=255 value=""><br>
		<input type="button" name="google<?php echo $id_panneau; ?>" id="google<?php echo $id_panneau; ?>" value="<?php echo $Langue['BTN_RECHERCHE_GOOGLE']; ?>">&nbsp;
		<input type="button" name="wikipedia<?php echo $id_panneau; ?>" id="wikipedia<?php echo $id_panneau; ?>" value="<?php echo $Langue['BTN_RECHERCHE_WIKIPEDIA']; ?>">&nbsp;
		<input type="button" name="dictionnaire<?php echo $id_panneau; ?>" id="dictionnaire<?php echo $id_panneau; ?>" value="<?php echo $Langue['BTN_RECHERCHE_DICTIONNAIRE']; ?>">
		</form>
		</div>
		</div>
  </div>
<script language="Javascript">
$(document).ready(function()
{
  $("#dictionnaire<?php echo $id_panneau; ?>").button();
  $("#dictionnaire<?php echo $id_panneau; ?>").click(function()
  {
    recherche=$("#rechercher<?php echo $id_panneau; ?>").val();
		recherche=recherche.replace(' ','+');
    window.open("http://www.le-dictionnaire.com/definition.php?mot="+recherche,"Recherche");
  });
  $("#google<?php echo $id_panneau; ?>").button();
  $("#google<?php echo $id_panneau; ?>").click(function()
  {
    recherche=$("#rechercher<?php echo $id_panneau; ?>").val();
		recherche=recherche.replace(' ','+');
    window.open("http://www.google.fr/#q="+recherche,"Recherche");
  });
  $("#wikipedia<?php echo $id_panneau; ?>").button();
  $("#wikipedia<?php echo $id_panneau; ?>").click(function()
  {
    recherche=$("#rechercher<?php echo $id_panneau; ?>").val();
		recherche=recherche.replace(' ','_');
    window.open("http://fr.wikipedia.org/wiki/"+recherche,"Recherche");
  });
});
</script>
