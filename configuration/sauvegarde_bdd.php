<?php if (isset($_GET['etape'])) { ?>
  <?php switch ($_GET['decoupe']) {
    case "3": ?>
	<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
	<tr>
	  <td width="33%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
	  <td width="34%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
	  <td width="33%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
	</tr>
	</table>
  <?php break;
    case "4": ?>  
	<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
	<tr>
	  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
	  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
	  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
	  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
	</tr>
	</table>
  <?php break;
    case "5": ?>
	<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
	<tr>
	  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
	  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
	  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
	  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
	  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE5']; ?></div></td>
	</tr>
	</table>
  <?php } ?>	
<br />
<?php } else { ?>
  <p class="aide2"><input type="Button" id="aide_fenetre" name="aide_fenetre" value="<?php echo $Langue['BTN_AIDE']; ?>"></p>
<?php } ?>
<p class="explic textgauche"><?php echo $Langue['LBL_SAUVEGARDE_EXPL1']; ?></p>
<p class="explic textgauche"><?php echo $Langue['LBL_SAUVEGARDE_EXPL2']; ?></p>
<?php if (!isset($_GET['etape'])) { ?>
<p class="explic textgauche"><?php echo $Langue['LBL_SAUVEGARDE_EXPL3']; ?></p>
<ul class="explic textgauche">
  <li class="textgauche"><?php echo $Langue['LBL_SAUVEGARDE_EXPL4']; ?></li>
  <li class="textgauche"><?php echo $Langue['LBL_SAUVEGARDE_EXPL5']; ?></li>
  <li class="textgauche"><?php echo $Langue['LBL_SAUVEGARDE_EXPL6']; ?></li>
  <li class="textgauche"><?php echo $Langue['LBL_SAUVEGARDE_EXPL7']; ?></li>
</ul>
<?php } ?>
<br />
<br />
<br />
<div id="button_sauver" class="textcentre"><input type="Button" id="Sauvegarder" name="Sauvegarder" value="<?php echo $Langue['BTN_SAUVEGARDE_BDD']; ?>"></div>
<script language="Javascript">
$(document).ready(function()
{
<?php if (!isset($_GET['etape'])) { ?>
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/directeur-maintenance-de-lapplication/article/250-sauvegarde-de-la-base-de-donnees.html","Aide");
  });
<?php } ?>  

	$("#Sauvegarder").button();
  $("#Sauvegarder").click(function()
  {
    $("#button_sauver").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_SAUVEGARDE_GENERATION']; ?></strong>');
    <?php if (!isset($_GET['etape'])) { ?>
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=sauvegarde_bdd2"});
    <?php } else { ?>
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=sauvegarde_bdd2&etape=2&decoupe=<?php echo $_GET['decoupe']; ?>"});
    <?php } ?>
    request.done(function(msg)
    {
      $("#button_sauver").html(msg);
    });
  });
});
</script>
