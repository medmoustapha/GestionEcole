  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		<?php
	    $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.id_util='".$_SESSION['id_util']."' AND bibliotheque_emprunt.type_util='E' AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque_emprunt.date_emprunt ASC, bibliotheque.reference ASC");

	    echo '<table id="tableau_biblio'.$id_panneau.'" class="display" cellpadding=0 cellspacing=0><thead><tr>';
		echo '<th class="textcentre" style="width:45%;">'.$Langue['LBL_BIBLIO_LIVRE'].'</th><th class="textcentre" style="width:25%;">'.$Langue['LBL_BIBLIO_BIBLIOTHEQUE'].'</th><th class="textcentre" style="width:15%;">'.$Langue['LBL_BIBLIO_DATE_EMPRUNT'].'</th><th class="textcentre" style="width:15%;">'.$Langue['LBL_BIBLIO_DATE_RETOUR'].'</th></tr></thead><tbody>';
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
	      if (mysql_result($req,$i-1,'bibliotheque.id_prof')=="") { $ajout=$gestclasse_config_plus['biblio_duree_emprunt']; } else { $ajout=$gestclasse_config_plus['biblio_duree_emprunt_classe']; }
		  $date_retour=date("Y-m-d",mktime(0,0,0,substr(mysql_result($req,$i-1,'date_emprunt'),5,2),substr(mysql_result($req,$i-1,'date_emprunt'),8,2)+$ajout,substr(mysql_result($req,$i-1,'date_emprunt'),0,4)));
		  if ($date_retour<date("Y-m-d")) { $class='class="retard"'; } else { $class=''; }
		  echo '<tr '.$class.'><td class="textgauche" style="width:45%;">'.mysql_result($req,$i-1,'bibliotheque.reference').' - '.mysql_result($req,$i-1,'bibliotheque.titre').'</td>';	
		  echo '<td class="textcentre" style="width:25%;">';
		  if (mysql_result($req,$i-1,'bibliotheque.id_prof')=="")
		  {
		    echo $Langue['LBL_BIBLIO_ECOLE'];
		  }
		  else
		  {
		    $req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'bibliotheque.id_prof')."'");
  		    echo $Langue['LBL_BIBLIO_BIBLIOTHEQUE_DE']." ".$liste_choix['civilite_long'][mysql_result($req2,0,'civilite')].' '.mysql_result($req2,0,'nom').'</td>';	
		  }
		  echo '</td>';
		  echo '<td class="textcentre" style="width:15%;">'.Date_Convertir(mysql_result($req,$i-1,'date_emprunt'),'Y-m-d',$Format_Date_PHP).'</td>';	
		  echo '<td class="textcentre" style="width:15%;">'.Date_Convertir($date_retour,'Y-m-d',$Format_Date_PHP).'</td></tr>';	
		}
		echo '</tbody></table>';
		?>
        </div>
	</div>
<script language="Javascript">
$(document).ready(function()
{
    $('#tableau_biblio<?php echo $id_panneau; ?>').dataTable
    ({
      "bJQueryUI": true,
      "bProcessing": true,
      "iDisplayLength": <?php echo $param[0]; ?>,
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
</script>