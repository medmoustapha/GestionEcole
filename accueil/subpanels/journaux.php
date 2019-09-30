  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem2_<?php echo $i_panneau; ?>" style="min-width:300px">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		<?php
		  require_once("commun/magpierss/rss_fetch.inc");
		  $rss = fetch_rss($liste_choix['journaux'][$param[1]][$param[2]]);
		  if (is_array($rss->items))
		  {
		   // on ne recupere que les elements les + recents
		   $items = array_slice($rss->items,0);
		   $i=1;
		   echo '<ul class="marge15_gauche">';
		   foreach ($items as $item)
		   {
			 if($i<=$param[0])
			 {
			   echo '<li><a href='.$item['link'].' target=_new>'.utf8_encode($item['title']).'</a></li>';
			   $i++;
			 }
		   }
		   echo "</ul>";
		  } 
		?>
    </div>
  </div>
