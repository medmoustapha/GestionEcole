<?php
  $date=$_SESSION['date_en_cours'];

  $datation=Date_Convertir($_SESSION['date_en_cours'],'Y-m-d',$Format_Date_PHP);;
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $jour_max=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $an=$_SESSION['annee_scolaire']+1;
	  $jour_max=$an.$gestclasse_config_plus['fin_annee_scolaire'];
  }

  if ($_SESSION['type_util']=="E")
  {
    $id_classe=$_SESSION['id_classe_cours'];
    $id_niveau=$_SESSION['niveau_en_cours'];
		$prof_titulaire=$_SESSION['titulaire_classe_cours'];
  }
  else
  {
    $req=mysql_query("SELECT classes.*,classes_profs.*,classes_niveaux.*,listes.* FROM `listes`,`classes`,`classes_niveaux`,`classes_profs` WHERE classes_profs.id_prof='".$_SESSION['id_util']."' AND (classes_profs.type='T' OR classes_profs.type='E' OR classes_profs.type='D') AND classes_profs.id_classe=classes.id AND classes.annee='".$_SESSION['annee_scolaire']."' AND classes_profs.id_classe=classes_niveaux.id_classe AND classes_niveaux.id_niveau=listes.id ORDER BY classes_profs.type ASC, listes.ordre ASC");
    if (isset($_GET['id_classe']))
    {
      $id_classe=$_GET['id_classe'];
      $id_niveau=$_GET['id_niveau'];
    }
    else
    {
      $id_classe=mysql_result($req,0,'classes.id');
      $id_niveau=mysql_result($req,0,'listes.id');
    }
		$req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='$id_classe' AND type='T'");
		$prof_titulaire=mysql_result($req2,0,'id_prof');
  }
?>
<div class="titre_page"><?php echo $Langue['LBL_CAHIER_ACTIVITES_DU']; ?> <?php echo $datation; ?></div>
<div class="aide"><button id="aide_d"><?php echo $Langue['BTN_AIDE']; ?></button></div>

<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
  <td class="textdroite" valign=middle nowrap>
    <?php if ($_SESSION['type_util']=="E") { ?>
      <div class="ui-widget ui-state-default ui-corner-all floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_DATE']; ?> : <input type="text" class="text ui-widget-content ui-corner-all" id="date_s2" name="date_s2" value="<?php echo Date_Convertir($date,"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></div>
    <?php } else { ?>
      <div class="ui-widget ui-state-default ui-corner-right floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_CLASSE_NIVEAU']; ?> : <select class="text ui-widget-content ui-corner-all" id="id_classe" name="id_classe">
      <?php
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        echo '<option value="'.mysql_result($req,$i-1,'classes.id').'-'.mysql_result($req,$i-1,'listes.id').'"';
        if ($id_classe.'-'.$id_niveau==mysql_result($req,$i-1,'classes.id').'-'.mysql_result($req,$i-1,'listes.id')) { echo ' SELECTED'; }
        echo '>'.mysql_result($req,$i-1,'classes.nom_classe').' - '.mysql_result($req,$i-1,'listes.intitule').'</option>';
      }
      ?>
      </select>
      </div>
      <div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_DATE']; ?> : <input type="text" class="text ui-widget-content ui-corner-all" id="date_s2" name="date_s2" value="<?php echo Date_Convertir($date,"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></div>
    <?php } ?>
  </td>
</tr>
</table>
<table id="listing_cahierjournal" class="display" cellpadding=0 cellspacing=0 style="width:100%;margin-top:5px">
<thead>
<tr>
<th style="width:14%" align=center><?php echo $Langue['LBL_HORAIRES']; ?></th>
<th style="width:14%" align=center><?php echo $Langue['LBL_MATIERE']; ?></th>
<th style="width:72%" align=center><?php echo $Langue['LBL_TRAVAIL_PREVU']; ?></th>
</tr>
</thead>
<tbody>
<?php
  $req=mysql_query("SELECT * FROM `cahierjournal` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND date='$date' ORDER BY heure_debut ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (mysql_result($req,$i-1,'id_matiere')!="RECRE")
    {
      echo '<tr><td align=center valign=top style="width:14%">'.$Langue['LBL_DE2'].' '.substr(mysql_result($req,$i-1,'heure_debut'),0,5).' '.$Langue['LBL_A'].' '.substr(mysql_result($req,$i-1,'heure_fin'),0,5).'</td>';
      $req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'id_matiere')."' AND nom_liste='matieres_cj'");
      if (mysql_num_rows($req2)!="")
      {
        echo '<td align=center valign=top style="width:14%">'.mysql_result($req2,0,'intitule').'</td>';
      }
      else
      {
        echo '<td align=center valign=top style="width:14%">'.$liste_choix['matieres_cj'][mysql_result($req,$i-1,'id_matiere')].'</td>';
      }
      $plus="";
      if (mysql_result($req,$i-1,'id_prof')!=$prof_titulaire)
      {
        $id_prof=mysql_result($req,$i-1,'id_prof');
        $req3=mysql_query("SELECT profs.*,classes_profs.* FROM `profs`,`classes_profs` WHERE profs.id='$id_prof' AND classes_profs.id_prof=profs.id AND classes_profs.id_classe='$id_classe'");
        if (mysql_result($req3,0,'classes_profs.type')=="E")
        {
          $plus='<strong>'.$Langue['LBL_PAR'].' '.$liste_choix['civilite_long'][mysql_result($req3,0,'profs.civilite')].' '.mysql_result($req3,0,'profs.nom').'</strong></p><br /><p class="explic">';
        }
        else
        {
          $plus='<strong>'.$Langue['LBL_DECLOISONNEMENT'].' '.$liste_choix['civilite_long'][mysql_result($req3,0,'profs.civilite')].' '.mysql_result($req3,0,'profs.nom').'</strong></p><br /><p class="explic">';
        }
      }
      if (mysql_result($req,$i-1,'parent')!="")
      {
        echo '<td class="textgauche" valign=top style="width:72%"><p class="explic">'.$plus.mysql_result($req,$i-1,'parent').'</p></td>';
      }
      else
      {
        echo '<td class="textgauche" valign=top style="width:72%"><p class="explic">'.$plus.mysql_result($req,$i-1,'contenu').'</p></td>';
      }
    }
    else
    {
      echo '<tr class="sorti"><td align=center style="width:14%;color:#000000">'.$Langue['LBL_DE2'].' '.substr(mysql_result($req,$i-1,'heure_debut'),0,5).' '.$Langue['LBL_A'].' '.substr(mysql_result($req,$i-1,'heure_fin'),0,5).'</td>';
      echo '<td align=center style="width:14%">&nbsp;</td>';
      echo '<td class="textgauche" style="width:72%;color:#000000"><strong>'.$Langue['LBL_RECREATION'].'</strong></td>';
    }
    echo '</tr>';
    
    // Si on arrive à l'heure du midi et qu'on travaille l'après-midi
    $jour_date=date("w",mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4)));
    if ($gestclasse_config_plus['am'.$jour_date]=="1" && substr(mysql_result($req,$i-1,'heure_fin'),0,5)==$gestclasse_config_plus['jour_matin_fin'])
    {
      echo '<tr class="sorti"><td align=center style="width:14%">&nbsp;</td>';
      echo '<td align=center style="width:14%">&nbsp;</td>';
      echo '<td class="textgauche" style="width:72%;color:#000000"><strong><br />'.$Langue['LBL_PAUSE_MIDI'].'<br /><br /></strong></td></tr>';
    }

  }
?>
</tbody>
</table>

<script language="Javascript">
$(document).ready(function()
{
  $("#aide_d").button();
  $("#aide_d").click(function(event)
  {
		event.preventDefault();
<?php
  switch ($_SESSION['type_util'])
	{
	  case "D":
?>
			window.open("http://www.doxconception.com/site/index.php/directeur-cahier-journal/article/147-voir-le-cahier-dactivites-tel-que-le-verront-les-parents.html","Aide");
<?php
			break;
		case "P":
?>
			window.open("http://www.doxconception.com/site/index.php/prof-cahier-journal/article/147-voir-le-cahier-dactivites-tel-que-le-verront-les-parents.html","Aide");
<?php
			break;
		case "E":
?>
			window.open("http://www.doxconception.com/site/index.php/parent-cahier-dactivites/article/149-voir-le-cahier-dactivites.html","Aide");
<?php
			break;
		}
?>
  });

  $("#id_classe").change(function()
  {
    Message_Chargement(1,1);
    id_c=$("#id_classe").val();
    decoupe=id_c.split('-');
    Charge_Dialog("index2.php?module=cahier&action=index_E&id_classe="+decoupe[0]+"&id_niveau="+decoupe[1],"<?php echo $Langue['LBL_VUE_PARENTS']; ?>");
  });

  // Création du champ de choix de la date et de l'action associée en cas de changement
  $("#date_s2").datepicker(
  {
    dateFormat: "<?php echo $Format_Date_Calendar; ?>",
    showOn: "button",
    buttonImage: "images/calendar.gif",
    buttonImageOnly: true,
    minDate:new Date(<?php echo substr($jour_min,0,4); ?>,<?php echo substr($jour_min,5,2); ?>-1,<?php echo substr($jour_min,8,2); ?>),
    maxDate:new Date(<?php echo substr($jour_max,0,4); ?>,<?php echo substr($jour_max,5,2); ?>-1,<?php echo substr($jour_max,8,2); ?>)
  });

  $("#date_s2").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_date.php";
     var data="date_choisie="+$("#date_s2").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function()
     {
       <?php   if ($_SESSION['type_util']!="E") { ?>
         Charge_Dialog("index2.php?module=cahier&action=index_E","<?php echo $Langue['LBL_VUE_PARENTS']; ?>");
       <?php  } ?>
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
  });

  /* Création du tableau de données */
	$('#listing_cahierjournal').dataTable
  ({
    "bJQueryUI": true,
    "bProcessing": true,
    "aaSorting": [[ 0, "asc" ]],
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": false,
    "bSort": false,
    "sDom": 'rt<"clear">',
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

  $( "#dialog:ui-dialog" ).dialog( "destroy" );
});
</script>
