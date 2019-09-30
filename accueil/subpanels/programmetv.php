<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem2_<?php echo $i_panneau; ?>">
  <div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
    <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
    </div>
  </div>
  <div class="portlet-content" style="padding:0px;margin:0px">
		<iframe src="http://www.programme-tv.net/widget-tv/programme-tv.html?size=300x250&bouquet=<?php echo $param[0]; ?>&title=000000&title_rollover=c00000&bg=f4f4f4" style="width:300px;height:250px;margin:3px;border:none;margin-bottom:0;display:block" frameborder="0" scrolling="no"></iframe><noframes>Votre navigateur ne supporte pas le format iframe</noframes><div style="display:block;height:13px;width:300px;background-color:#1c1c1c;text-align:center;font-family:Arial;font-size:9px;color:#b6b6b6;line-height:12px;border:0 none;padding:0;margin-top:0;margin-right:3px;margin-left:3px;background-position:0 0">Votre <a href="http://www.programme-tv.net/" title="Programme TV" style="font-family:Arial;font-size:9px;color:#b6b6b6">programme TV</a> avec Télé-Loisirs</div>
  </div>
</div>
