<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
</tr>
</table>
<br />
<p class="explic textgauche"><?php echo $Langue['LBL_LANGUE_EXPL9']; ?></p>
<br />
<br />
<br />
<br />
<div id="avancement" style="text-align:center"><input type="Button" id="etape4" name="etape4" value="<?php echo $Langue['BTN_MAJ_COMMENCER_VERIF']; ?>"></div>
<script language="Javascript">
$(document).ready(function()
{
  $("#etape4").button();
  $("#etape4").click(function(event)
  {
    event.preventDefault();
    $("#avancement").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MAJ_DEZIPPAGE']; ?></strong>');
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=langues3_unzip&fichier=<?php echo $_GET['fichier']; ?>"});
    request.done(function(msg)
    {
	  switch (msg)
	  {
	    case "erreur":
          $("#avancement").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_MAJ_ERREUR_DEZIPPAGE']; ?></strong></div></div>');
		  break;
		case "erreur2":
          $("#avancement").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_LANGUE_ERREUR_INSTALL']; ?></strong></div></div>');
		  break;
		default:
		  $("#avancement").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MAJ_VERIF_FICHIER']; ?></strong>');
          var request2 = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=langues3_controle&fichier="+msg});
          request2.done(function(msg)
          {
			switch(msg)
			{
			  case "erreur":
			    $("#avancement").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_LANGUE_ERREUR_INSTALL_LIRE']; ?></strong></div></div>');
				break;
			  case "erreur2":
			    $("#avancement").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_LANGUE_ERREUR_PAS_MAJ']; ?></strong></div></div>');
				break;
			  default:
				$("#avancement").html('<strong><?php echo $Langue['MSG_MAJ_PRET_INSTALL']; ?></strong><br /><br /><br /><br /><input type="Button" id="Installer" name="Installer" value="<?php echo $Langue['BTN_LANGUE_ETAPE4']; ?>">');
                $("#Installer").button();
                $("#Installer").click(function()
                {
                  Charge_Dialog("index2.php?module=configuration&action=langues4&fichier="+msg,"<?php echo $Langue['LBL_LANGUE_TITRE']; ?>");
                });
            }
          });
		  break;
      }
    });
  });
});
</script>
