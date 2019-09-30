<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem2_<?php echo $i_panneau; ?>">
  <div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
    <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
    </div>
  </div>
  <div class="portlet-content" style="padding:0px;margin:0px">
		<iframe src="accueil/subpanels/horoscope_c_frame.php?signe=<?php echo $param[0]; ?>" style="width:300px;height:250px;margin:3px;border:none;margin-bottom:0;display:block" frameborder="0" scrolling="auto"></iframe><noframes>Votre navigateur ne supporte pas le format iframe</noframes>
  </div>
</div>
