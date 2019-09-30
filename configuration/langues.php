<p class="aide2"><input type="Button" id="aide_fenetre" name="aide_fenetre" value="<?php echo $Langue['BTN_AIDE']; ?>"></p>
<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
</tr>
</table>
<br />
<p class="explic textgauche"><?php echo $Langue['LBL_LANGUE_EXPL1']; ?></p>
<ul class="explic textgauche">
<li class="textgauche"><?php echo $Langue['LBL_LANGUE_EXPL2']; ?></li>
<li class="textgauche"><?php echo $Langue['LBL_LANGUE_EXPL3']; ?></li>
<li class="textgauche"><?php echo $Langue['LBL_LANGUE_EXPL4']; ?></li>
<li class="textgauche"><?php echo $Langue['LBL_LANGUE_EXPL5']; ?></li>
</ul>
<br />
<br />
<div class="textcentre"><input type="Button" id="Restaurer" name="Restaurer" value="<?php echo $Langue['BTN_LANGUE_ETAPE2']; ?>"></div>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/directeur-maintenance-de-lapplication/article/246-installer-mettre-a-jour-un-pack-langue.html","Aide");
  });

  $("#Restaurer").button();
  $("#Restaurer").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=langues2","<?php echo $Langue['LBL_LANGUE_TITRE']; ?>");
  });

});
</script>
