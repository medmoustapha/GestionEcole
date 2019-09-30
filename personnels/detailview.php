<?php

// Récupération des informations
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tableau_variable['personnels'][$cle['nom']]['value'] = "";
  }

  $req = mysql_query("SELECT * FROM `profs` WHERE id = '" . $_GET['id'] . "'");
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['personnels'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
  }
  $typage=mysql_result($req,0,'type');

  if (!file_exists("cache/documents/".$typage.$_GET['id']))
  {
    mkdir("cache/documents/".$typage.$_GET['id']);
  }

  $tpl = new template("personnels");
  $tpl->set_file("gform","detailview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
  }
  if (mysql_result($req,0,"passe")!="") { $tpl->set_var("PASSE",$Langue['LBL_CRYPTE']); }
  if (mysql_result($req,0,"derniere_connexion")=="0000-00-00") { $tpl->set_var("DERNIERE_CONNEXION",$Langue['LBL_JAMAIS_CONNECTE']); } else { $tpl->set_var("DERNIERE_CONNEXION",Date_Convertir(mysql_result($req,0,"derniere_connexion"),"Y-m-d",$Format_Date_PHP)); }

  if (file_exists("cache/photos/".$_GET['id'].".jpg"))
  {
    $tpl->set_var("PHOTO","cache/photos/".$_GET['id'].".jpg?".time());
  }
  else
  {
    if (mysql_result($req,0,'civilite')=="" || mysql_result($req,0,'civilite')=="1")
		{
			$tpl->set_var("PHOTO","images/homme.png");
		}
    else
		{
			$tpl->set_var("PHOTO","images/femme.png");
		}
  }
  
  $tpl->set_var("LISTE_PERSONNELS",Liste_Profs("nav_liste_prof","form","",$_GET['id'],"",false,false));
  $tpl->set_var("LISTE_PERSONNELS2",Liste_Profs("nav_liste_prof2","form","",$_GET['id'],"",false,false));
  
  $msg='<table id="listing_classes" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr>';
  $msg=$msg.'<th class="centre" style="width:5%;">&nbsp;</th>';
  $msg=$msg.'<th class="centre" style="width:25%;">'.$Langue['LST_ANNEES_SCOLAIRES'].'</th>';
  $msg=$msg.'<th class="centre" style="width:25%;">'.$Langue['LST_CLASSES'].'</th>';
  $msg=$msg.'<th class="centre" style="width:25%;">'.$Langue['LST_NIVEAUX'].'</th>';
  $msg=$msg.'<th class="centre" style="width:20%;">'.$Langue['LST_QUALITE'].'</th>';
  $msg=$msg.'</tr></thead><tbody>';
  $req=mysql_query("SELECT classes_profs.*, classes.* FROM `classes_profs`,`classes` WHERE  classes_profs.id_prof='".$_GET['id']."' AND classes_profs.id_classe=classes.id AND (classes_profs.type='T' OR classes_profs.type='E') ORDER BY classes.annee DESC, classes_profs.type DESC, classes.nom_classe ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $annee2=mysql_result($req,$i-1,'classes.annee')+1;
		if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
			$anneee=date("Y");
		}
		else
		{
			if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $anneee=date("Y")-1; } else { $anneee=date("Y"); }
		}
		$msg=$msg.'<tr><td class="centre" style="width:5%;">';
		if ($anneee==mysql_result($req,$i-1,'classes.annee')) { $msg=$msg.'<img src="images/fleche_'.$Sens_Ecriture.'.png" width=12 height=10 border=0>'; $bold=';font-weight:bold;'; } else { $msg=$msg.'&nbsp;'; $bold=''; }
		$msg=$msg.'</td>';
		if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
			$msg=$msg.'<td class="centre" style="width:25%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'</td>';
		}
		else
		{
			$msg=$msg.'<td class="centre" style="width:25%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'-'.$annee2.'</td>';
		}
    $msg=$msg.'<td class="centre" style="width:25%'.$bold.'"><a title="'.$Langue['SPAN_VOIR_FICHE_CLASSE'].'" href=#null onClick="Personnnels_D_Charge_Classe(\''.mysql_result($req,$i-1,'classes.id').'\')">'.mysql_result($req,$i-1,'classes.nom_classe').'</a></td>';
    $msg=$msg.'<td class="centre" style="width:25%'.$bold.'">'.str_replace('|',', ',Liste_Niveaux('','value','',mysql_result($req,$i-1,'classes.id'),false)).'</td>';
    $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.$liste_choix['type_user_classe'][mysql_result($req,$i-1,'classes_profs.type')].'</td></tr>';
  }
  $msg=$msg.'</tbody></table>';
  if ($typage=="D" || $typage=="P")
  {
    $tpl->set_var("LISTE_CLASSES",'<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form">'.$Langue['LBL_LISTE_CLASSES'].'</div><div class="textgauche">'.$msg.'</div>');
  }
  else
  {
    $tpl->set_var("LISTE_CLASSES",'');
  }

  // Affichage des livres empruntés dans la bibliothèque
  $msg='<table id="listing_livres_empruntes" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr><th class="gauche" style="width:55%">'.$Langue['LST_BIBLIO_TITRES'].'</th>';
  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LST_BIBLIO_DATE_EMPRUNT'].'</th>';
  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LST_BIBLIO_DATE_RETOUR'].'</th>';
  $msg=$msg.'<th class="centre" style="width:5%">&nbsp;</th></tr></thead><tbody>';

  $req=mysql_query("SELECT bibliotheque_emprunt.*, bibliotheque.* FROM `bibliotheque_emprunt`, `bibliotheque` WHERE bibliotheque_emprunt.id_util='".$_GET['id']."' AND bibliotheque_emprunt.type_util='P' AND bibliotheque_emprunt.id_livre=bibliotheque.id AND bibliotheque.id_prof='' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque_emprunt.date_emprunt DESC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		$msg=$msg.'<tr>';
    $msg=$msg.'<td class="gauche" style="width:55%"><a title="'.$Langue['SPAN_BIBLIO_VOIR_LIVRE'].'" href=#null onClick="Personnnels_D_Charge_Livre(\''.mysql_result($req,$i-1,'bibliotheque.id').'\')">'.mysql_result($req,$i-1,'bibliotheque.reference').' - '.mysql_result($req,$i-1,'bibliotheque.titre').'</a></td>';
    $msg=$msg.'<td class="centre" style="width:20%">'.Date_Convertir(mysql_result($req,$i-1,'bibliotheque_emprunt.date_emprunt'),'Y-m-d',$Format_Date_PHP).'</td>';
		if (mysql_result($req,$i-1,'bibliotheque_emprunt.date_retour')=="0000-00-00")
		{
      $msg=$msg.'<td class="centre" style="width:20%">&nbsp;</td>';
      $msg=$msg.'<td class="centre" style="width:5%"><a title="'.$Langue['SPAN_BIBLIO_MODIFIER_EMPRUNT'].'" href="#null" onClick="Personnnels_D_Charge_Emprunt(\''.mysql_result($req,$i-1,'bibliotheque_emprunt.id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_BIBLIO_CLORE_EMPRUNT'].'" href="#null" onClick="Personnnels_D_Valid_Emprunt(\''.mysql_result($req,$i-1,'bibliotheque_emprunt.id').'\')"><img src="images/retour_emprunt.png" width=12 height=12 border=0></a></td>';
		}
		else
		{
      $msg=$msg.'<td class="centre" style="width:20%">'.Date_Convertir(mysql_result($req,$i-1,'bibliotheque_emprunt.date_retour'),'Y-m-d',$Format_Date_PHP).'</td>';
      $msg=$msg.'<td class="centre" style="width:5%">&nbsp;</td>';
		}
		$msg=$msg.'</tr>';
  }
  $msg=$msg.'</tbody></table>';
  $tpl->set_var("LISTE_LIVRES_EMPRUNTES",$msg);


  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  $('#image_personne').click(function()
  {
    Charge_Dialog3("index2.php?module=personnels&action=saisir_photo1&id_personne=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_PHOTO_TITRE']; ?>");
  });
  
  $('#listing_classes').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "bAutoWidth": false,
      "aaSorting": [[ 1, "desc" ]],
      "aLengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
      "iDisplayLength": 10,
      "aoColumns" : [ {"bSortable":false},null,null,null,null ],
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
    }
  );

  $('#listing_livres_empruntes').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "bSort": false,
			"bFilter": false,
      "aLengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
      "iDisplayLength": 5,
      "bAutoWidth": false,
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_BIBLIO_EMPRUNT_AFFICHER']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_BIBLIO_AUCUN_EMPRUNT']; ?>",
        "sInfo": "<?php echo $Langue['LBL_BIBLIO_ENREGISTREMENT_AFFICHE']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_BIBLIO_AUCUN_EMPRUNT2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_BIBLIO_ENREGISTREMENT_AFFICHE2']; ?>",
        "sSearch": "<?php echo $Langue['LBL_RECHERCHER_DATA']; ?>",
        "oPaginate":
        {
          "sFirst":    "<?php echo $Langue['LBL_DEBUT']; ?>",
          "sPrevious": "<?php echo $Langue['LBL_PRECEDENT']; ?>",
          "sNext":     "<?php echo $Langue['LBL_SUIVANT']; ?>",
          "sLast":     "<?php echo $Langue['LBL_FIN']; ?>"
        }
      },
    }
  );
  
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
    Charge_Dialog("index2.php?module=personnels&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_PERSONNEL']; ?>");
  });
  $("#Modifier2").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=personnels&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_PERSONNEL']; ?>");
  });

  $("#Imprimer_Detail").button();
  $("#Imprimer_Detail").click(function()
  {
    Charge_Dialog("index2.php?module=personnels&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
  
  $("#Imprimer_Detail2").button();
  $("#Imprimer_Detail2").click(function()
  {
    Charge_Dialog("index2.php?module=personnels&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
  
  $("#nav_liste_prof").change(function()
  {
		Charge_Dialog("index2.php?module=personnels&action=detailview&id="+$("#nav_liste_prof").val(),"<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
  });
  
  $("#nav_liste_prof2").change(function()
  {
		Charge_Dialog("index2.php?module=personnels&action=detailview&id="+$("#nav_liste_prof2").val(),"<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
  });
});
  
  function Personnnels_D_Charge_Emprunt(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt&id="+id,"<?php echo $Langue['LBL_BIBLIO_FICHE_EMPRUNT']; ?>");
  }
  
  function Personnnels_D_Charge_Livre(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id="+id,"<?php echo $Langue['LBL_BIBLIO_FICHE_LIVRE']; ?>");
  }
  
  function Personnnels_D_Charge_Classe(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=classes&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
  }

  /* Fonction pour clore l'emprunt */
  function Personnnels_D_Valid_Emprunt(id)
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_BIBLIO_VALIDER_CLOTURE_EMPRUNT']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_BIBLIO_VALIDER_EMPRUNT']; ?>",
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
						Charge_Dialog("index2.php?module=personnels&action=detailview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
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
</script>
<style type='text/css'>
  .elfinder-dialog { left:<?php echo ($_SESSION['largeur_ecran']-250)/2; ?>px; }
  .elfinder-help-plus { margin-top:-550px; }
  .elfinder .elfinder-navbar{width:150px; }
</style>

<?php  
  if (substr($_SESSION['language_application'],0,2)==strtolower(substr($_SESSION['language_application'],3,2)))
  {
    $language_elfinder=substr($_SESSION['language_application'],0,2);
  }
  else
  {
    $language_elfinder=str_replace('-','_',$_SESSION['language_application']);
  }
?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	var elf = $('#elfinder_personnel').elfinder(
	{
		lang: '<?php echo $language_elfinder; ?>',             // language (OPTIONAL)
		dateFormat : '<?php echo $Format_Date_Heure_PHP; ?>',
		fancyDateFormat : '<?php echo $Format_Date_Heure_PHP_2; ?>',
		resizable : false,
		allowShortcuts : false,
		height : 250,
		uiOptions : 
		{
			// toolbar configuration
			toolbar : [['back', 'forward'],['mkdir', 'upload'],['open', 'download', 'info'],['copy', 'cut', 'paste','rm'],['duplicate', 'rename'],['search'],['view']],

			// directories tree options
			tree : 
			{
				openRootOnLoad : true,
				syncTree : true
			},

			// navbar options
			navbar : 
			{
				minWidth : 150,
				maxWidth : 150
			}
		},
		contextmenu : 
		{
			// navbarfolder menu
			navbar : ['open', '|', 'copy', 'cut', 'paste', 'duplicate', 'rm', '|', 'info'],

			// current directory menu
			cwd    : ['reload', 'back', '|', 'upload', 'mkdir', 'paste', '|', 'info'],

			// current directory file menu
			files  : ['getfile', '|','open', 'download', '|', 'copy', 'cut', 'paste', 'duplicate', 'rm', '|', 'rename', '|', 'info']
		},
		url : 'commun/elfinder/php/connector_doc.php?repertoire_perso=<?php echo $typage.$_GET['id']; ?>'  // connector URL (REQUIRED)
	}).elfinder('instance');            
});
</script>

