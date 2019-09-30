<?php
// Récupération des informations
  foreach ($tableau_variable['agenda'] AS $cle)
  {
    $tableau_variable['agenda'][$cle['nom']]['value'] = "";
  }

  $req = mysql_query("SELECT * FROM `reunions` WHERE id = '" . $_GET['id'] . "'");
  foreach ($tableau_variable['agenda'] AS $cle)
  {
    if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['agenda'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
  }
  
  $tpl = new template("calendrier");
  $tpl->set_file("gform","detailview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

	$id_util2=$_SESSION['type_util'].$_SESSION['id_util'];
	$req=mysql_query("SELECT * FROM `reunions` WHERE date LIKE '".$_SESSION['annee_en_cours']."-%' AND (id_util LIKE '".$id_util2.",%' OR id_util LIKE '%,".$id_util2."' OR id_util='".$id_util2."' OR id_util LIKE '%,".$id_util2.",%') ORDER BY date ASC, heure_debut ASC, heure_fin ASC");
  $msg='<select name="nav_liste_reunion" id="nav_liste_reunion" class="text ui-widget-content ui-corner-all">';
  $msg2='<select name="nav_liste_reunion2" id="nav_liste_reunion2" class="text ui-widget-content ui-corner-all">';
	for ($i=1;$i<=mysql_num_rows($req);$i++)
	{
		$msg .='<option value="'.mysql_result($req,$i-1,'id').'"';
		$msg2 .='<option value="'.mysql_result($req,$i-1,'id').'"';
		if (mysql_result($req,$i-1,'id')==$_GET['id']) { $msg .=' SELECTED';$msg2 .=' SELECTED'; }
		$msg .='>'.$Langue['LBL_IMPRESSION_LE'].' '.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_IMPRESSION_DE2'].' '.substr(mysql_result($req,$i-1,'heure_debut'),0,5).' '.$Langue['LBL_IMPRESSION_A'].' '.substr(mysql_result($req,$i-1,'heure_fin'),0,5).' - '.$liste_choix['type_reunion'][mysql_result($req,$i-1,'type')].' : '.mysql_result($req,$i-1,'resume').'</option>';
		$msg2 .='>'.$Langue['LBL_IMPRESSION_LE'].' '.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_IMPRESSION_DE2'].' '.substr(mysql_result($req,$i-1,'heure_debut'),0,5).' '.$Langue['LBL_IMPRESSION_A'].' '.substr(mysql_result($req,$i-1,'heure_fin'),0,5).' - '.$liste_choix['type_reunion'][mysql_result($req,$i-1,'type')].' : '.mysql_result($req,$i-1,'resume').'</option>';
	}
	$msg .='</select>';
	$msg2 .='</select>';
	$tpl->set_var("LISTE_REUNIONS",$msg);
	$tpl->set_var("LISTE_REUNIONS2",$msg2);
	
	foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['agenda'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
  }
  
  $id_util=explode(',',$tableau_variable['agenda']['id_util']['value']);
  $msg="";
  for ($i=0;$i<count($id_util);$i++)
  {
    $type=substr($id_util[$i],0,1);
		$id=substr($id_util[$i],1,strlen($id_util[$i]));
		if ($type=="E")
		{
			$req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id'");
		}
		else
		{
			$req2=mysql_query("SELECT * FROM `profs` WHERE id='$id'");
		}
    $msg=$msg.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').', ';
  }
  $tpl->set_var("ID_UTIL",substr($msg,0,strlen($msg)-2));
  
  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
<?php if ($tableau_variable['agenda']['id_saisie']['value']==$_SESSION['type_util'].$_SESSION['id_util']) { ?>
  $("#Modifier").button({ disabled: false });
  $("#Supprimer").button({ disabled: false });
  $("#Modifier2").button({ disabled: false });
  $("#Supprimer2").button({ disabled: false });
<?php } else { ?>
  $("#Modifier").button({ disabled: true });
  $("#Supprimer").button({ disabled: true });
  $("#Modifier2").button({ disabled: true });
  $("#Supprimer2").button({ disabled: true });
<?php } ?>
  $("#Retour").button();
  $("#Retour2").button();
  $("#Retour").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Retour2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  $("#nav_liste_reunion").change(function()
  {
   Charge_Dialog("index2.php?module=calendrier&action=detailview&id="+$("#nav_liste_reunion").val(),"<?php echo $Langue['LBL_FICHE_RDV']; ?>");
  });
  
  $("#nav_liste_reunion2").change(function()
  {
   Charge_Dialog("index2.php?module=calendrier&action=detailview&id="+$("#nav_liste_reunion2").val(),"<?php echo $Langue['LBL_FICHE_RDV']; ?>");
  });

  $("#Modifier").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=calendrier&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_RDV']; ?>");
  });
  $("#Modifier2").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=calendrier&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_RDV']; ?>");
  }); 

  $("#Supprimer").click(function()
  {
    var id=$("#id").val();
    Calendrier_D_Supprimer_Reunion(id);
  });
  $("#Supprimer2").click(function()
  {
    var id=$("#id").val();
    Calendrier_D_Supprimer_Reunion(id);
  });
  $("#Imprimer_Detail").button();
  $("#Imprimer_Detail").click(function()
  {
    Charge_Dialog("index2.php?module=calendrier&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#Imprimer_Detail2").button();
  $("#Imprimer_Detail2").click(function()
  {
    Charge_Dialog("index2.php?module=calendrier&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
});

function Calendrier_D_Supprimer_Reunion(id)
{
	$( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_RDV']; ?></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
  {
    title: "<?php echo $Langue['LBL_SUPPRIMER_RDV']; ?>",
	  resizable: false,
	  draggable: false,
	  height:200,
	  width:450,
	  modal: true,
	  buttons:[
		{
			text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
			click: function()
			{
				Message_Chargement(4,1);
				var request = $.ajax({type: "POST", url: "index2.php", data: "module=calendrier&action=delete_reunion&id="+id});
				request.done(function(msg)
				{
					$( "#dialog-confirm" ).dialog( "close" );
					$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
					$("#dialog-form").dialog( "close" );
					Message_Chargement(1,0);
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
</script>
