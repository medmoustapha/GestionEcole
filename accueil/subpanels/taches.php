  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_CREER_TACHE']; ?>" onClick="Ajouter_Tache()"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		<?php
		    $req=mysql_query("SELECT * FROM `taches` WHERE id_util='".$_SESSION['id_util']."' AND type_util='".$_SESSION['type_util']."' AND etat!='T' AND etat!='A' ORDER BY priorite DESC");
	    echo '<table id="tableau_tache'.$id_panneau.'" class="display" cellpadding=0 cellspacing=0><thead><tr>';
		echo '<th class="textcentre" style="width:55%;">'.$Langue['LBL_TACHE_TACHE'].'</th><th class="textcentre" style="width:15%;">'.$Langue['LBL_TACHE_PRIORITE'].'</th><th class="textcentre" style="width:15%;">'.$Langue['LBL_TACHE_ECHEANCE'].'</th><th class="textcentre" style="width:12%;">'.$Langue['LBL_TACHE_ETAT'].'</th><th class="textcentre" style="width:2%;">&nbsp;</th></tr></thead><tbody>';
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
		  echo '<tr><td class="textgauche" style="width:55%;color:'.$liste_choix['tache_priorite_couleur'][mysql_result($req,$i-1,'priorite')].'">'.mysql_result($req,$i-1,'titre').'</td>';	
		  echo '<td class="textcentre" style="width:15%;color:'.$liste_choix['tache_priorite_couleur'][mysql_result($req,$i-1,'priorite')].'">'.$liste_choix['tache_priorite'][mysql_result($req,$i-1,'priorite')].'</td>';	
		  echo '<td class="textcentre" style="width:15%;color:'.$liste_choix['tache_priorite_couleur'][mysql_result($req,$i-1,'priorite')].'">';
		  if (mysql_result($req,$i-1,'date_echeance')!="0000-00-00") { echo Date_Convertir(mysql_result($req,$i-1,'date_echeance'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_TACHE_A'].' '.substr(mysql_result($req,$i-1,'heure_echeance'),0,5); }
		  echo '<td class="textcentre" style="width:15%;color:'.$liste_choix['tache_priorite_couleur'][mysql_result($req,$i-1,'priorite')].'">'.$liste_choix['tache_etat'][mysql_result($req,$i-1,'etat')].'</td>';	
		  echo '<td class="textcentre" style="width:15%;">';
		  echo '<a href="#null" onClick="Accueil_Charge_Tache'.$id_panneau.'(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/editer.png" height=12 width=12 border=0></a>';
		  echo '</td>';	
		  echo '</tr>';	
		}
		echo '</tbody></table>';
		?>
    </div>
	</div>
<script language="Javascript">
$(document).ready(function()
{
    $('#tableau_tache<?php echo $id_panneau; ?>').dataTable
    ({
      "bJQueryUI": true,
      "bProcessing": true,
      "iDisplayLength": <?php echo $param[0]; ?>,
      "sPaginationType": "full_numbers",
      "aoColumns" : [ {"bSortable":false},null,null,null,{"bSortable":false} ],
	  "sDom": 'rt<"clear"><"bottom2"p><"clear">',
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_TACHE_ELEMENT_AFFICHES']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_TACHE_NO_TACHE']; ?>",
        "sInfo": "<?php echo $Langue['LBL_TACHE_TOTAL']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_TACHE_NO_TACHE2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_TACHE_RESULT_RECHERCHE']; ?>",
        "sSearch": "<?php echo $Langue['LBL_RECHERCHER_DATA']; ?>",
        "oPaginate":
        {
          "sFirst":    "<?php echo $Langue['LBL_DEBUT']; ?>",
          "sPrevious": "<?php echo $Langue['LBL_PRECEDENT']; ?>",
          "sNext":     "<?php echo $Langue['LBL_SUIVANT']; ?>",
          "sLast":     "<?php echo $Langue['LBL_FIN']; ?>"
        }
      },
    });
});

function Accueil_Charge_Tache<?php echo $id_panneau; ?>(id)
{
  Charge_Dialog("index2.php?module=accueil&action=editview_tache&id="+id,"<?php echo $Langue['LBL_MODIFIER_TACHE']; ?>");
}
</script>
