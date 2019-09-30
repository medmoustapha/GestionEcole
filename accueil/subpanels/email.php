  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
	<div class="portlet-content">
    <?php
  	$decaler=explode(':',$gestclasse_config_plus['decalage_horaire']);
	  if (substr($decaler[0],0,1)=="-") { $decaler[1]=-$decaler[1]; }
	  $req=mysql_query("SELECT * FROM `email` WHERE (id_destinataire LIKE '".$_SESSION['type_util'].$_SESSION['id_util']."' OR id_destinataire LIKE '".$_SESSION['type_util'].$_SESSION['id_util'].";%' OR id_destinataire LIKE '%;".$_SESSION['type_util'].$_SESSION['id_util']."' OR id_destinataire LIKE '%;".$_SESSION['type_util'].$_SESSION['id_util'].";%') ORDER BY date DESC");
	  echo '<table id="tableau_email'.$id_panneau.'" class="display" cellpadding=0 cellspacing=0><thead><tr>';
		echo '<th class="textcentre" style="width:15%;">'.$Langue['LBL_EMAIL_DATE'].'</th><th class="textcentre" style="width:25%;">'.$Langue['LBL_EMAIL_EXPEDITEUR'].'</th><th class="textcentre" style="width:60%;">'.$Langue['LBL_EMAIL_SUJET'].'</th></tr></thead><tbody>';
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
		  $destinataire=explode(';',mysql_result($req,$i-1,'id_destinataire'));
		  $key=array_search($_SESSION['type_util'].$_SESSION['id_util'],$destinataire);
		  $etat=explode(";",mysql_result($req,$i-1,'etat'));
		  if ($etat[$key]=="N") 
		  {
			  echo '<tr><td class="textcentre" style="width:15%;">'.$Langue['LBL_EMAIL_LE'].' '.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_EMAIL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req,$i-1,'date'),11,2)-$decaler[0],substr(mysql_result($req,$i-1,'date'),14,2)-$decaler[1],substr(mysql_result($req,$i-1,'date'),17,2),01,01,2010)).'</td>';	
			  if (mysql_result($req,$i-1,'type_expediteur')=="E")
			  {
				$req2=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req,$i-1,'id_expediteur')."'");
			  }
			  else
			  {
				$req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'id_expediteur')."'");
			  }
			  echo '<td class="textcentre" style="width:25%;">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';	
			  echo '<td class="textgauche" style="width:60%;"><a href=#null onClick="Accueil_Charge_Email'.$id_panneau.'(\''.mysql_result($req,$i-1,'id').'\')">'.mysql_result($req,$i-1,'titre').'</a></td></tr>';	
		  }
		}
		echo '</tbody></table>';
	?>
    </div>
  </div>
<script language="Javascript">
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "date-euro<?php echo $id_panneau; ?>-pre": function ( a ) {
		    a = a.replace("<b>","");
		    a = a.replace("</b>","");
		    a = a.replace("<?php echo $Langue['LBL_EMAIL_LE']; ?> ","");
		    a = a.replace("<?php echo $Langue['LBL_EMAIL_A']; ?> ","");
        if ($.trim(a) != '') {
            var frDatea = $.trim(a).split(' ');
            var frTimea = frDatea[1].split(':');
<?php		switch ($Format_Date_PHP)
				{
					case "d/m/Y":
					case "d/Y/m":
					case "Y/m/d":
					case "Y/d/m":
					case "m/d/Y":
					case "m/Y/d":
?>
            var frDatea2 = frDatea[0].split('/');
<?php
						break;
					case "d-m-Y":
					case "d-Y-m":
					case "Y-m-d":
					case "Y-d-m":
					case "m-d-Y":
					case "m-Y-d":
?>
            var frDatea2 = frDatea[0].split('-');
<?php
						break;
					case "d.m.Y":
					case "d.Y.m":
					case "Y.m.d":
					case "Y.d.m":
					case "m.d.Y":
					case "m.Y.d":
?>
            var frDatea2 = frDatea[0].split('.');
<?php
						break;
				}
				switch ($Format_Date_PHP)
				{
					case "d/m/Y":
					case "d-m-Y":
					case "d.m.Y":
?>
            var x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "d/Y/m":
					case "d-Y-m":
					case "d.Y.m":
?>
            var x = (frDatea2[1] + frDatea2[2] + frDatea2[0] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "Y/m/d":
					case "Y-m-d":
					case "Y.m.d":
?>
            var x = (frDatea2[0] + frDatea2[1] + frDatea2[2] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "Y/d/m":
					case "Y-d-m":
					case "Y.d.m":
?>
            var x = (frDatea2[0] + frDatea2[2] + frDatea2[1] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "m/d/Y":
					case "m-d-Y":
					case "m.d.Y":
?>
            var x = (frDatea2[2] + frDatea2[0] + frDatea2[1] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "m/Y/d":
					case "m-Y-d":
					case "m.Y.d":
?>
            var x = (frDatea2[1] + frDatea2[0] + frDatea2[2] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					}
?>
				} else {
            var x = 10000000000000; // = l'an 1000 ...
        }
        return x;
    },
 
    "date-euro<?php echo $id_panneau; ?>-asc": function ( a, b ) {
        return a - b;
    },
 
    "date-euro<?php echo $id_panneau; ?>-desc": function ( a, b ) {
        return b - a;
    }
} );

$(document).ready(function()
{
    $('#tableau_email<?php echo $id_panneau; ?>').dataTable
    ({
      "bJQueryUI": true,
      "bProcessing": true,
      "iDisplayLength": <?php echo $param[0]; ?>,
      "sPaginationType": "full_numbers",
			"aaSorting": [[ 0, "desc" ]],
			"aoColumns" : [ { "sType": "date-euro<?php echo $id_panneau; ?>" },null,null ],
			"sDom": 'rt<"clear"><"bottom2"p><"clear">',
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_EMAIL_AFFICHE_PAGE']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_EMAIL_NO_MSG']; ?>",
        "sInfo": "<?php echo $Langue['LBL_EMAIL_TOTAL']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_EMAIL_NO_MSG2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_EMAIL_RECHERCHE']; ?>",
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

function Accueil_Charge_Email<?php echo $id_panneau; ?>(id)
{
  Charge_Dialog("index2.php?module=email&action=detailview&id="+id,"<?php echo $Langue['LBL_EMAIL_LECTURE_MESSAGE']; ?>");
}
</script>
