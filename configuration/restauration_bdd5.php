<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
<td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
</tr>
</table>
<br />
<p class="explic textgauche"><?php echo $Langue['LBL_RESTAURATION_EXPL11']; ?></strong></p>
<br />
<br />
<br />
<br />
<div id="avancement" class="textcentre"><input type="Button" id="etape5" name="etape5" value="<?php echo $Langue['BTN_RESTAURATION_COMMENCER']; ?>"></div>
<script language="Javascript">
$(document).ready(function()
{
  $("#etape5").button();
  $("#etape5").click(function(event)
  {
    event.preventDefault();
    $("#avancement").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MAJ_DEZIPPAGE']; ?></strong>');
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=restauration_bdd5_unzip&fichier=<?php echo $_GET['fichier']; ?>"});
    request.done(function(msg)
    {
      switch (msg)
      {
	    case "erreur":
          $("#avancement").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_RESTAURATION_ERREUR_DEZIPPAGE']; ?>z</strong></div></div>');
		  break;
	    case "erreur2":
          $("#avancement").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_RESTAURATION_ERREUR_SQL']; ?></strong></div></div>');
		  break;
		default:  
          $("#avancement").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_RESTAURATION_EN_COURS']; ?></strong>');
          var request2 = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=restauration_bdd5_restaure&fichier="+msg});
          request2.done(function(msg)
          {
            if (msg=="erreur")
            {
              $("#avancement").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_RESTAURATION_ERREUR_RECONSTRUCTION']; ?></strong></div></div>');
            }
            else
            {
              $("#avancement").html('<strong><?php echo $Langue['MSG_RESTAURATION_OK']; ?></strong><br /><br /><br /><br /><input type="Button" id="Fermer" name="Fermer" value="<?php echo $Langue['BTN_ANNULER']; ?>">');
              $("#Fermer").button();
              $("#Fermer").click(function()
              {
                $("#dialog-form").dialog( "close" );
              });
            }
          });
      }
    });
  });
});
</script>
