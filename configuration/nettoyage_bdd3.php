<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
<td width="33%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
<td width="34%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
<td width="33%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
</tr>
</table>
<br />
<p class="explic textgauche"><?php echo $Langue['LBL_NETTOYAGE_EXPL10']; ?></p>
<p class="explic textgauche"><?php echo $Langue['LBL_NETTOYAGE_EXPL11']; ?></p>
<br />
<br />
<div id="button_sauver" class="textcentre"><input type="Button" id="Nettoyer" name="Nettoyer" value="<?php echo $Langue['BTN_NETTOYAGE_NETTOYER']; ?>"></div>
<script language="Javascript">
$(document).ready(function()
{
  $("#Nettoyer").button();
  $("#Nettoyer").click(function()
  {
    $("#button_sauver").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_NETTOYAGE_EN_COURS']; ?></strong>');
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=nettoyage_bdd4"});
    request.done(function(msg)
    {
      $("#button_sauver").html("<?php echo $Langue['LBL_NETTOYAGE_TERMINE']; ?>");
    });
  });

});
</script>
