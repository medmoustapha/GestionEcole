  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		<?php
		$date=date("Y-m-d");
		$id_util=$_SESSION['type_util'].$_SESSION['id_util'];
		switch ($param[0])
		{
		  case 'D':
		    $req=mysql_query("SELECT * FROM `reunions` WHERE date='$date' AND (id_util LIKE '".$id_util.",%' OR id_util LIKE '%,".$id_util."' OR id_util='".$id_util."' OR id_util LIKE '%,".$id_util.",%') ORDER BY heure_debut ASC, heure_fin ASC");
		    break;
		  case 'L':
		    $req=mysql_query("SELECT * FROM `reunions` WHERE date>='$date' AND (id_util LIKE '".$id_util.",%' OR id_util LIKE '%,".$id_util."' OR id_util='".$id_util."' OR id_util LIKE '%,".$id_util.",%') ORDER BY date ASC, heure_debut ASC, heure_fin ASC");
		    break;
		}
		
	    echo '<table id="tableau_reunion'.$id_panneau.'" class="display" cellpadding=0 cellspacing=0><thead><tr>';
		echo '<th class="textcentre" style="width:30%;">'.$Langue['LBL_REUNIONS_HORAIRES'].'</th><th class="textcentre" style="width:25%;">'.$Langue['LBL_REUNIONS_TYPE'].'</th><th class="textcentre" style="width:45%;">'.$Langue['LBL_REUNIONS_RESUME'].'</th></tr></thead><tbody>';
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
		  echo '<tr><td class="textcentre" style="width:30%;">';
		  if ($param[0]=="L") { echo $Langue['LBL_REUNIONS_LE'].' '.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).', '.$Langue['LBL_REUNIONS_DE'].' '; } else { echo $Langue['LBL_REUNIONS_DE2'].' '; }
		  echo substr(mysql_result($req,$i-1,'heure_debut'),0,5).' '.$Langue['LBL_REUNIONS_A'].' '.substr(mysql_result($req,$i-1,'heure_fin'),0,5);
		  echo '</td>';	
		  echo '<td class="textcentre" style="width:25%;">'.$liste_choix['type_reunion'][mysql_result($req,$i-1,'type')].'</td>';	
		  echo '<td class="textgauche" style="width:45%;"><a title="'.$Langue['LBL_REUNIONS_VOIR_REUNION'].'" href=#null onclick="Accueil_Charge_Reunion'.$id_panneau.'(\''.mysql_result($req,$i-1,'id').'\')">'.mysql_result($req,$i-1,'resume').'</a></td></tr>';	
		}
		echo '</tbody></table>';
		?>
        </div>
	</div>
<script language="Javascript">
$(document).ready(function()
{
    $('#tableau_reunion<?php echo $id_panneau; ?>').dataTable
    ({
      "bJQueryUI": true,
      "bProcessing": true,
      "iDisplayLength": <?php echo $param[1]; ?>,
      "sPaginationType": "full_numbers",
      "bSort": false,
	  "sDom": 'rt<"clear"><"bottom2"p><"clear">',
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_ELEMENT_AFFICHES']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_NO_DATA']; ?>",
        "sInfo": "<?php echo $Langue['LBL_ELEMENT_AFFICHES2']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_RESULT_RECHERCHE']; ?>",
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

function Accueil_Charge_Reunion<?php echo $id_panneau; ?>(id)
{
  Charge_Dialog("index2.php?module=calendrier&action=detailview&id="+id,"<?php echo $Langue['LBL_REUNIONS_FICHE']; ?>");
}
</script>