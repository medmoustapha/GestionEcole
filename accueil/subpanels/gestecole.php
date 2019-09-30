  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		<?php
      require_once("commun/magpierss/rss_fetch.inc");
      echo FeedParser($liste_choix['flux_rss']["G"], $param[0], $id_panneau, false, true);
		?>
    </div>
	</div>
<script language="Javascript">
function Accueil_Modif_Page_<?php echo $id_panneau; ?>(page)
{
  max=document.getElementById('max_<?php echo $id_panneau; ?>').value;
  page_actuelle=document.getElementById('page_<?php echo $id_panneau; ?>').value;
  nb_par_page=document.getElementById('nb_par_page_<?php echo $id_panneau; ?>').value;
  debut=(page_actuelle-1)*nb_par_page+1;
  fin=page_actuelle*nb_par_page;
  if (fin>max) { fin=max; }
  for(i=debut;i<=fin;i++)
  {
    document.getElementById('ministere_<?php echo $id_panneau; ?>_'+i).style.visibility="hidden";
    document.getElementById('ministere_<?php echo $id_panneau; ?>_'+i).style.display="none";
  }
  $('#lien_<?php echo $id_panneau; ?>_'+page_actuelle).removeClass("ui-state-disabled");
  debut=(page-1)*nb_par_page+1;
  fin=page*nb_par_page;
  if (fin>max) { fin=max; }
  for(i=debut;i<=fin;i++)
  {
    document.getElementById('ministere_<?php echo $id_panneau; ?>_'+i).style.visibility="visible";
    document.getElementById('ministere_<?php echo $id_panneau; ?>_'+i).style.display="block";
  }
  $('#lien_<?php echo $id_panneau; ?>_'+page).addClass("ui-state-disabled");
  document.getElementById('page_<?php echo $id_panneau; ?>').value=page;
}
</script>
