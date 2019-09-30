  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem2_<?php echo $i_panneau; ?>" style="min-width:300px">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
    <script type="text/javascript">
      function setDirections<?php echo $id_panneau; ?>(fromAddress, toAddress, locale)
        {
		  window.open("accueil/subpanels/itineraire_calcul.php?from="+fromAddress+"&to="+toAddress+"&locale=<?php echo $Langue_Valeur; ?>","Itineraire");
        }
    </script>
    <form id="monFormulaire<?php echo $id_panneau; ?>" method="post" action="#" onSubmit="setDirections<?php echo $id_panneau; ?>(this.from<?php echo $id_panneau; ?>.value, this.to<?php echo $id_panneau; ?>.value, 'fr'); return false">
      <table border="0" class="iti" align="center">
        <tr>
          <td class="textdroite"><strong><?php echo $Langue['LBL_ITINERAIRE_DEPART']; ?> :</strong></td>
		  <td class="textgauche"><input class="text ui-widget-content ui-corner-all" type="text" id="fromAddress<?php echo $id_panneau; ?>" name="from<?php echo $id_panneau; ?>" size="40"></td>
		</tr>
        <tr>
		  <td class="textdroite"><strong><?php echo $Langue['LBL_ITINERAIRE_ARRIVEE']; ?> :</strong></td>
		  <td class="textgauche"><input class="text ui-widget-content ui-corner-all" type="text" id="toAddress<?php echo $id_panneau; ?>" name="to<?php echo $id_panneau; ?>" size="40"></td>
		</tr>
		<tr>
          <td class="textcentre" colspan=2><input id="gogogo<?php echo $id_panneau; ?>" name="gogogo<?php echo $id_panneau; ?>" type="submit" value="<?php echo $Langue['BTN_ITINERAIRE_CALCUL']; ?>" /></td>
        </tr>
	  </table>
	</form>
		</div>
  </div>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#gogogo<?php echo $id_panneau; ?>").button();
});
</script>
