  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem2_<?php echo $i_panneau; ?>" style="min-width:300px">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		  <?php 
		    $fp=fopen('http://fetedujour.fr/api/text','r');
			$ligne=fgets($fp);
			$pos=strpos($ligne,"nous");
			$ligne=$Langue['LBL_FETE_JOUR_AUJOURDHUI'].", ".substr($ligne,$pos,strlen($ligne));
			echo '<p class="marge5_tout">'.$ligne.'</p>'; 
		  ?>
		</div>
  </div>
