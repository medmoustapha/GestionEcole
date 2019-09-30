  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
		<div class="portlet-content">
		<?php
		switch ($param[0])
		{
		  case 'E':
			$date_limite=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt'],date("Y")));
		    $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_emprunt<'".$date_limite."' AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque_emprunt.date_emprunt ASC, bibliotheque.reference ASC");
		    break;
		  case 'C':
			$date_limite=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt_classe'],date("Y")));
		    $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='".$_SESSION['id_util']."' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_emprunt<'".$date_limite."' AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque_emprunt.date_emprunt ASC, bibliotheque.reference ASC");
		    break;
		}
		
	    echo '<table id="tableau_biblio'.$id_panneau.'" class="display" cellpadding=0 cellspacing=0><thead><tr>';
		echo '<th class="textcentre" style="width:43%;">'.$Langue['LBL_BIBLIO_LIVRE'].'</th><th class="textcentre" style="width:25%;">'.$Langue['LBL_BIBLIO_EMPRUNTEUR'].'</th><th class="textcentre" style="width:15%;">'.$Langue['LBL_BIBLIO_DATE_EMPRUNT'].'</th><th class="textcentre" style="width:15%;">'.$Langue['LBL_BIBLIO_DATE_RETOUR'].'</th><th class="textcentre" style="width:2%;">&nbsp;</th></tr></thead><tbody>';
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
		  echo '<tr><td class="textgauche" style="width:43%;">'.mysql_result($req,$i-1,'bibliotheque.reference').' - '.mysql_result($req,$i-1,'bibliotheque.titre').'</td>';	
		  if (mysql_result($req,$i-1,'bibliotheque_emprunt.type_util')=="E")
		  {
		    $req2=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req,$i-1,'bibliotheque_emprunt.id_util')."'");
		  }
		  else
		  {
		    $req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'bibliotheque_emprunt.id_util')."'");
		  }
		  echo '<td class="textcentre" style="width:25%;">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';	
		  echo '<td class="textcentre" style="width:15%;">'.Date_Convertir(mysql_result($req,$i-1,'date_emprunt'),'Y-m-d',$Format_Date_PHP).'</td>';	
	      if ($param[0]=="E") { $ajout=$gestclasse_config_plus['biblio_duree_emprunt']; } else { $ajout=$gestclasse_config_plus['biblio_duree_emprunt_classe']; }
		  $date_retour=date($Format_Date_PHP,mktime(0,0,0,substr(mysql_result($req,$i-1,'date_emprunt'),5,2),substr(mysql_result($req,$i-1,'date_emprunt'),8,2)+$ajout,substr(mysql_result($req,$i-1,'date_emprunt'),0,4)));
		  echo '<td class="textcentre" style="width:15%;">'.$date_retour.'</td>';	
		  echo '<td class="textcentre" style="width:2%;" nowrap><a title="'.$Langue['LBL_BIBLIO_MODIFIER_EMPRUNT'].'" href=#null onClick="Accueil_Charge_Emprunt'.$id_panneau.'(\''.mysql_result($req,$i-1,'bibliotheque_emprunt.id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['LBL_BIBLIO_CLORE_EMPRUNT'].'" href=#null onClick="Accueil_Valid_Emprunt'.$id_panneau.'(\''.mysql_result($req,$i-1,'bibliotheque_emprunt.id').'\')"><img src="images/retour_emprunt.png" width=12 height=12 border=0></a></td></tr>';	
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

  /* Fonction pour clore l'emprunt */
  function Accueil_Valid_Emprunt<?php echo $id_panneau; ?>(id)
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_CLORE_EMPRUNT']; ?></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['MSG_CLORE_EMPRUNT']; ?>",
	  resizable: false,
	  draggable: false,
	  height:200,
	  width:450,
	  modal: true,
	  buttons:[
      {
        text: "<?php echo $Langue['BTN_VALIDER']; ?>",
		click: function()
        {
          Message_Chargement(8,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: "module=bibliotheque&action=save_retour_emprunt&id_emprunt="+id});
          request.done(function(msg)
          {
		    Message_Chargement(1,1);
		    $( "#dialog-confirm" ).dialog( "close" );
			$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
			$("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
		  });
        }
	  },
	  {
        text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
		click: function()
        {
		  $( this ).dialog( "close" );
		}
	  }]
	});
  }

  function Accueil_Charge_Emprunt<?php echo $id_panneau; ?>(id)
  {
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt&id="+id,"<?php echo $Langue['LBL_BIBLIO_SAISIR_EMPRUNT']; ?>");
  }
</script>