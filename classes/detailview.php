<?php
// Récupération des informations
  foreach ($tableau_variable['classes'] AS $cle)
  {
    $tableau_variable['classes'][$cle['nom']]['value'] = "";
  }

  $req = mysql_query("SELECT * FROM `classes` WHERE id = '" . $_GET['id'] . "'");
  foreach ($tableau_variable['classes'] AS $cle)
  {
    if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['classes'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
  }
  $nom_classe=mysql_result($req,0,'nom_classe');

  $tpl = new template("classes");
  $tpl->set_file("gform","detailview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['classes'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
  }
  $tpl->set_var("ANNEE",Liste_Annee('',$type='value',mysql_result($req,0,'annee')));

  $tpl->set_var("LISTE_CLASSES",Liste_Classes("nav_liste_classe","form",$_SESSION['annee_scolaire'],$_GET['id'],"",false));
  $tpl->set_var("LISTE_CLASSES2",Liste_Classes("nav_liste_classe2","form",$_SESSION['annee_scolaire'],$_GET['id'],"",false));
  
  // Affichage du tableau avec les différents intervenants dans la classe
  $liste_titulaire=Liste_Profs('','value',mysql_result($req,0,'id'),'','T',false);
  $liste_decharge=Liste_Profs('','value',mysql_result($req,0,'id'),'','E',false);
  $liste_decloisonnement=Liste_Profs('','value',mysql_result($req,0,'id'),'','D',false);
  $liste_atsem=Liste_Profs('','value',mysql_result($req,0,'id'),'','S',false);
  $liste_intervenant=Liste_Profs('','value',mysql_result($req,0,'id'),'','I',false);
  $liste_autre=Liste_Profs('','value',mysql_result($req,0,'id'),'','U',false);
  $liste_niveaux=Liste_Niveaux('','value','',mysql_result($req,0,'id'),false);

  $nb_ligne=0;
  $tableau_titulaire=explode("|",$liste_titulaire);
  $tableau_decharge=explode("|",$liste_decharge);
  if (count($tableau_decharge)>$nb_ligne) { $nb_ligne=count($tableau_decharge); }
  $tableau_decloisonnement=explode("|",$liste_decloisonnement);
  if (count($tableau_decloisonnement)>$nb_ligne) { $nb_ligne=count($tableau_decloisonnement); }
  $tableau_atsem=explode("|",$liste_atsem);
  if (count($tableau_atsem)>$nb_ligne) { $nb_ligne=count($tableau_atsem); }
  $tableau_intervenant=explode("|",$liste_intervenant);
  if (count($tableau_intervenant)>$nb_ligne) { $nb_ligne=count($tableau_intervenant); }
  $tableau_autre=explode("|",$liste_autre);
  if (count($tableau_autre)>$nb_ligne) { $nb_ligne=count($tableau_autre); }
  for ($i=1;$i<$nb_ligne;$i++) { $tableau_titulaire[$i]="&nbsp;"; }
  for ($i=count($tableau_decharge);$i<$nb_ligne;$i++) { $tableau_decharge[$i]="&nbsp;"; }
  for ($i=count($tableau_decloisonnement);$i<$nb_ligne;$i++) { $tableau_decloisonnement[$i]="&nbsp;"; }
  for ($i=count($tableau_atsem);$i<$nb_ligne;$i++) { $tableau_atsem[$i]="&nbsp;"; }
  for ($i=count($tableau_intervenant);$i<$nb_ligne;$i++) { $tableau_intervenant[$i]="&nbsp;"; }
  for ($i=count($tableau_autre);$i<$nb_ligne;$i++) { $tableau_autre[$i]="&nbsp;"; }
  
  $msg='<table id="listing_profs" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr>';
  $msg=$msg.'<th style="width:17%" class="centre">'.$Langue['LST_TITULAIRE'].'</th>';
  $msg=$msg.'<th style="width:16%" class="centre">'.$Langue['LST_DECHARGES'].'</th>';
  $msg=$msg.'<th style="width:17%" class="centre">'.$Langue['LST_DECLOISONNEMENTS'].'</th>';
  $msg=$msg.'<th style="width:17%" class="centre">'.$Langue['LST_ATSEM'].'</th>';
  $msg=$msg.'<th style="width:16%" class="centre">'.$Langue['LST_INTERVENANTS_EXTERIEURS'].'</th>';
  $msg=$msg.'<th style="width:17%" class="centre">'.$Langue['LST_AUTRES_INTERVENANTS'].'</th>';
  $msg=$msg.'</tr></thead><tbody>';
  for ($i=0;$i<$nb_ligne;$i++)
  {
    $msg=$msg."<tr>";
    $msg=$msg.'<td style="width:17%" class="centre">'.$tableau_titulaire[$i].'</td>';
    $msg=$msg.'<td style="width:16%" class="centre">'.$tableau_decharge[$i].'</td>';
    $msg=$msg.'<td style="width:17%" class="centre">'.$tableau_decloisonnement[$i].'</td>';
    $msg=$msg.'<td style="width:17%" class="centre">'.$tableau_atsem[$i].'</td>';
    $msg=$msg.'<td style="width:16%" class="centre">'.$tableau_intervenant[$i].'</td>';
    $msg=$msg.'<td style="width:17%" class="centre">'.$tableau_autre[$i].'</td>';
    $msg=$msg."</tr>";
  }
  $msg=$msg."</tbody></table>";
  $tpl->set_var("LISTE_PERSONNEL",$msg);
  $tpl->set_var("NIVEAUX",str_replace("|",", ",$liste_niveaux));

  // Affichage de la liste des élèves
  $msg='<table id="listing_eleves" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr>';
  $msg=$msg.'<th style="width:5%" class="centre">'.$Langue['LST_CLASSE_ELEVES'].'</th>';
  $msg=$msg.'<th style="width:31%" class="centre">'.$Langue['LST_CLASSE_ELEVES'].'</th>';
  $msg=$msg.'<th style="width:16%" class="centre">'.$Langue['LST_CLASSE_SEXE'].'</th>';
  $msg=$msg.'<th style="width:16%" class="centre">'.$Langue['LST_CLASSE_NAISSANCE'].'</th>';
  $msg=$msg.'<th style="width:16%" class="centre">'.$Langue['LST_CLASSE_NIVEAUX'].'</th>';
  $msg=$msg.'<th style="width:11%" class="centre">'.$Langue['LST_CLASSE_REDOUBLEMENT'].'</th>';
  $msg=$msg.'<th style="width:5%" class="centre">&nbsp;</th>';
  $msg=$msg.'</tr></thead><tbody>';
  $req=mysql_query("SELECT eleves_classes.*, eleves.*, listes.* FROM `eleves_classes`,`eleves`,`listes` WHERE  eleves_classes.id_classe='".$_GET['id']."' AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_niveau=listes.id ORDER BY eleves.nom ASC, eleves.prenom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (mysql_result($req,$i-1,"eleves.date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"eleves.date_sortie")>=date("Y-m-d"))
    {
      $msg=$msg.'<tr>';
    }
    else
    {
      $msg=$msg.'<tr class="sorti">';
    }
    $msg=$msg.'<td class="centre" style="width:5%">'.$i.'</td>';
		if ($_SESSION['type_util']=="D")
		{
				$msg=$msg.'<td class="gauche" style="width:31%"><a title="'.$Langue['SPAN_VOIR_ELEVE'].'" href="#null" onClick="Classes_D_Charge_Eleve(\''.mysql_result($req,$i-1,'eleves.id').'\')">'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</a></td>';
		}
		else
		{
				$msg=$msg.'<td class="gauche" style="width:31%">'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</td>';
		}
    $msg=$msg.'<td class="centre" style="width:16%">'.$liste_choix['sexe'][mysql_result($req,$i-1,'eleves.sexe')].'</td>';
    $msg=$msg.'<td class="centre" style="width:16%">'.Date_Convertir(mysql_result($req,$i-1,'eleves.date_naissance'),"Y-m-d",$Format_Date_PHP).'</td>';
    $msg=$msg.'<td class="centre" style="width:16%">'.mysql_result($req,$i-1,'listes.intitule').'</td>';
    $msg=$msg.'<td class="centre" style="width:11%">'.$liste_choix['ouinon2'][mysql_result($req,$i-1,'eleves_classes.redoublement')].'</td>';
    $msg=$msg.'<td class="centre" style="width:5%"><a title="'.$Langue['SPAN_MODIFIER_ELEVE_CLASSE'].'" href="#null" onClick="Classes_D_Modifier_Eleve(\''.mysql_result($req,$i-1,'eleves_classes.id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_SUPPRIMER_ELEVE_CLASSE'].'" href="#null" onClick="Classes_D_Supprimer_Eleve(\''.mysql_result($req,$i-1,'eleves_classes.id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></td></tr>';
  }
  $msg=$msg.'</table>';
  $tpl->set_var("LISTE_ELEVES",$msg);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Modifier").button();
  $("#Retour").button();
  $("#Modifier2").button();
  $("#Retour2").button();
  $("#Retour").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Retour2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  $("#Modifier").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=classes&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_CLASSE']; ?>");
  });
  $("#Modifier2").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=classes&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_CLASSE']; ?>");
  });


	$('#listing_profs').dataTable
  (
    {
      "bJQueryUI": true,
      "aaSorting": [[ 0, "desc" ]],
      "iDisplayLength": 30,
      "bAutoWidth": false,
      "aoColumns" : [ {"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false} ],
      "sDom": 'rt<"clear">',
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sZeroRecords": "<?php echo $Langue['MSG_AUCUN_ENSEIGNANT']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>"
      }
    }
  );

	$('#listing_eleves').dataTable
  (
    {
      "bJQueryUI": true,
      "aaSorting": [[ 1, "asc" ]],
      "iDisplayLength": 50,
      "bAutoWidth": false,
      "aoColumns" : [ {"bSortable":false,"aTargets":[0]},null,null,null,null,null,{"bSortable":false} ],
      "sDom": 'rt<"clear">',
			"fnDrawCallback": function( oSettings ) 
			{
			  var that = this;
				if ( oSettings.bSorted || oSettings.bFiltered )
				{
					this.$('td:first-child', {"filter":"applied"}).each( function (i) 
					{
						that.fnUpdate( i+1, this.parentNode, 0, false, false );
					} );
				}
			},
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sZeroRecords": "<?php echo $Langue['MSG_AUCUN_ELEVE']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>"
      },
    }
  );

  $("#modif_liste").button();
  $("#modif_liste").click(function()
  {
    Charge_Dialog2("index2.php?module=classes&action=modif_liste_eleve&id_classe="+$("#id").val(),"<?php echo $Langue['LBL_MODIFIER_LISTE_ELEVE']; ?>");
  });
  
  $("#modif_liste2").button();
  $("#modif_liste2").click(function()
  {
    $("#modif_liste").click();
  });

  $("#Imprimer_Detail").button();
  $("#Imprimer_Detail").click(function()
  {
    Charge_Dialog("index2.php?module=classes&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#Imprimer_Detail2").button();
  $("#Imprimer_Detail2").click(function()
  {
    Charge_Dialog("index2.php?module=classes&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
  
  $("#nav_liste_classe").change(function()
  {
    Charge_Dialog("index2.php?module=classes&action=detailview&id="+$("#nav_liste_classe").val(),"<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
  });
  
  $("#nav_liste_classe2").change(function()
  {
    Charge_Dialog("index2.php?module=classes&action=detailview&id="+$("#nav_liste_classe2").val(),"<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
  });
});

  function Classes_D_Modifier_Eleve(id)
  {
    Charge_Dialog2("index2.php?module=classes&action=modif_liste_eleve_unique&id="+id,"<?php echo $Langue['LBL_MODIFIER_DONNEES_ELEVE']; ?>");
  }

  function Classes_D_Supprimer_Eleve(id)
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_ELEVE']; ?></div><div class="ui-widget"><div class="ui-state-error ui-corner-all marge10_tout margin10_haut"><strong><?php echo $Langue['MSG_SUPPRIMER_ELEVE2']; ?></strong></div></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_SUPPRIMER_ELEVE_CLASSE']; ?>",
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
					var request = $.ajax({type: "POST", url: "index2.php", data: "module=classes&action=delete_eleves&id="+id});
					request.done(function(msg)
					{
						decoupage=msg.split("|");
						$( "#dialog-confirm" ).dialog( "close" );
						Charge_Dialog("index2.php?module=classes&action=detailview&id="+decoupage[0],"<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
						parent.calcul.location.href='users/calcul_moyenne.php?id_classe='+decoupage[0]+'&id_niveau='+decoupage[1]+'&id_titulaire='+decoupage[2]+'&annee=<?php echo $_SESSION['annee_scolaire']; ?>';
						$("#tabs").tabs("load",2);
						$("#dialog-niveau2").dialog( "close" );
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
   
  function Classes_D_Charge_Eleve(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=eleves&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
  }

</script>
