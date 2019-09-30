<?php
// Récupération des informations
  foreach ($tableau_variable['eleves'] AS $cle)
  {
    $tableau_variable['eleves'][$cle['nom']]['value'] = "";
  }

  $req = mysql_query("SELECT * FROM `eleves` WHERE id = '" . $_GET['id'] . "'");
  foreach ($tableau_variable['eleves'] AS $cle)
  {
    if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['eleves'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
  }
  
  if (!file_exists("cache/documents/E".$_GET['id']))
  {
    mkdir("cache/documents/E".$_GET['id']);
		mkdir("cache/documents/E".$_GET['id']."/private");
  }

  // On regarde si on est titulaire de la classe dont fait partie l'élève
  $est_titulaire=false;
  if ($_SESSION['type_util']=="D")
  {
    $est_titulaire=true;
  }
  else
  {
		$req_titulaire=mysql_query("SELECT eleves_classes.*, classes.*, classes_profs.* FROM `classes`,`eleves_classes`,`classes_profs` WHERE classes.annee='".$_SESSION['annee_scolaire']."' AND eleves_classes.id_classe=classes.id AND eleves_classes.id_eleve='".$_GET['id']."' AND classes_profs.id_classe=classes.id AND classes_profs.id_prof='".$_SESSION['id_util']."' AND (classes_profs.type='T' OR classes_profs.type='E')");
		if (mysql_num_rows($req_titulaire)!="") { $est_titulaire=true; }
  }
  
  $tpl = new template("eleves");
  $tpl->set_file("gform","detailview_".$_SESSION['type_util'].".html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['eleves'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
  }

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $debut_annee=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
    $debut_annee=date("Y-m-d",mktime(0,0,0,substr($gestclasse_config_plus['fin_annee_scolaire'],1,2),substr($gestclasse_config_plus['fin_annee_scolaire'],4,2),$_SESSION['annee_scolaire']+1));
  }
  if ($_SESSION['affiche_presents']=="0")
  {
    if ($_SESSION['id_classe_cours']=="")
    {
      $req_el = mysql_query("SELECT * FROM `eleves` WHERE date_entree<='$debut_annee' ORDER BY nom ASC, prenom ASC");
    }
    else
    {
      $req_el = mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$debut_annee' ORDER BY eleves.nom ASC, eleves.prenom ASC");
    }
  }
  else
  {
    if ($_SESSION['id_classe_cours']=="")
    {
      $req_el = mysql_query("SELECT * FROM `eleves` WHERE date_entree<='$debut_annee' AND (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."') ORDER BY nom ASC, prenom ASC");
    }
    else
    {
      $req_el = mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$debut_annee' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".date("Y-m-d")."') ORDER BY eleves.nom ASC, eleves.prenom ASC");
    }
  }
  $msg='<select name="nav_liste_eleve" id="nav_liste_eleve" class="text ui-widget-content ui-corner-all">';
  $msg2='<select name="nav_liste_eleve2" id="nav_liste_eleve2" class="text ui-widget-content ui-corner-all">';
  for ($i=1;$i<=mysql_num_rows($req_el);$i++)
  {
    $msg .='<option value="'.mysql_result($req_el,$i-1,'eleves.id').'"';
    $msg2 .='<option value="'.mysql_result($req_el,$i-1,'eleves.id').'"';
		if (mysql_result($req_el,$i-1,'eleves.id')==$_GET['id']) { $msg .=' SELECTED'; $msg2 .=' SELECTED'; }
		$msg .='>'.mysql_result($req_el,$i-1,'eleves.nom').' '.mysql_result($req_el,$i-1,'eleves.prenom').'</option>';
		$msg2 .='>'.mysql_result($req_el,$i-1,'eleves.nom').' '.mysql_result($req_el,$i-1,'eleves.prenom').'</option>';
  }
  $msg .='</select>';
  $msg2 .='</select>';
  $tpl->set_var("LISTE_ELEVES",$msg);
  $tpl->set_var("LISTE_ELEVES2",$msg2);
  
  if (mysql_result($req,0,"passe")!="") { $tpl->set_var("PASSE",$Langue['LBL_CRYPTE']); }
  if (mysql_result($req,0,"derniere_connexion")=="0000-00-00") { $tpl->set_var("DERNIERE_CONNEXION",$Langue['LBL_JAMAIS_CONNECTE']); } else { $tpl->set_var("DERNIERE_CONNEXION",Date_Convertir(mysql_result($req,0,"derniere_connexion"),"Y-m-d",$Format_Date_PHP)); }
  if (file_exists("cache/photos/".$_GET['id'].".jpg"))
  {
    $tpl->set_var("PHOTO","cache/photos/".$_GET['id'].".jpg?".time());
  }
  else
  {
    if (mysql_result($req,0,'eleves.sexe')=="1")
		{
			$tpl->set_var("PHOTO","images/homme.png?".time());
		}
		else
		{
			$tpl->set_var("PHOTO","images/femme.png?".time());
		}
  }

  if (isset($_GET['id']))
  {
	  $req2=mysql_query("SELECT * FROM `contacts_eleves` WHERE id_eleve='".$_GET['id']."' ORDER BY nom ASC");
	  for ($i=1;$i<=mysql_num_rows($req2);$i++)
	  {
			$tpl->set_var('CONTACT_NOM_'.$i,mysql_result($req2,$i-1,'nom'));
			$tpl->set_var('CONTACT_LIEN_'.$i,mysql_result($req2,$i-1,'lien'));
			$tpl->set_var('CONTACT_ADRESSE_'.$i,mysql_result($req2,$i-1,'adresse'));
			$tpl->set_var('CONTACT_TEL_'.$i,mysql_result($req2,$i-1,'tel'));
			$tpl->set_var('CONTACT_TEL2_'.$i,mysql_result($req2,$i-1,'tel2'));
			$tpl->set_var('CONTACT_PORTABLE_'.$i,mysql_result($req2,$i-1,'portable'));
	  }
	  $debut=mysql_num_rows($req2)+1;
  }
  for ($i=$debut;$i<=5;$i++)
  {
    $tpl->set_var('CONTACT_NOM_'.$i,'&nbsp;');
    $tpl->set_var('CONTACT_LIEN_'.$i,'&nbsp;');
    $tpl->set_var('CONTACT_ADRESSE_'.$i,'&nbsp;');
    $tpl->set_var('CONTACT_TEL_'.$i,'&nbsp;');
    $tpl->set_var('CONTACT_TEL2_'.$i,'&nbsp;');
    $tpl->set_var('CONTACT_PORTABLE_'.$i,'&nbsp;');
  }
  
  $msg='<table id="listing_classes" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr><th class="centre" style="width:5%">&nbsp;</th>';
  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_CLASSES_ANNEES_SCOLAIRES'].'</th>';
  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_CLASSES_CLASSES'].'</th>';
  $msg=$msg.'<th class="centre" style="width:18%">'.$Langue['LBL_CLASSES_NIVEAUX'].'</th>';
  $msg=$msg.'<th class="centre" style="width:2%">'.$Langue['LBL_CLASSES_LIVRET_SCOLAIRE'].'</th>';
  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_CLASSES_ENSEIGNANTS'].'</th>';
  if ($_SESSION['type_util']=="D")
  {  
    $msg=$msg.'<th class="centre" style="width:12%">'.$Langue['LBL_CLASSES_REDOUBLEMENT'].'</th>';
    $msg=$msg.'<th class="centre" style="width:3%">&nbsp;</th>';
  }
  else
  {  
    $msg=$msg.'<th class="centre" style="width:15%">'.$Langue['LBL_CLASSES_REDOUBLEMENT'].'</th>';
  }
  $msg=$msg.'</tr></thead><tbody>';

  $req=mysql_query("SELECT eleves_classes.*, classes.*, listes.* FROM `eleves_classes`, `classes`, `listes` WHERE eleves_classes.id_eleve='".$_GET['id']."' AND eleves_classes.id_classe=classes.id AND eleves_classes.id_niveau=listes.id ORDER BY classes.annee ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
      $anneee=date("Y");
		}
		else
		{
      if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $anneee=date("Y")-1; } else { $anneee=date("Y"); }
		}
    $msg=$msg.'<tr><td class="centre" style="width:5%">';
    if ($anneee==mysql_result($req,$i-1,'classes.annee')) { $msg=$msg.'<img src="images/fleche_'.$Sens_Ecriture.'.png" width=12 height=10 border=0>'; $bold=';font-weight:bold;'; } else { $msg=$msg.'&nbsp;'; $bold=''; }
    $msg=$msg.'</td>';
    $annee2=mysql_result($req,$i-1,'classes.annee')+1;
    $lien="";$lien_fin="";
		if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
      if ($_SESSION['type_util']=="D")
      {
				$annee_actu=date("Y");
				if (mysql_result($req,$i-1,'classes.annee')==$annee_actu || mysql_result($req,$i-1,'classes.annee')==$annee_actu-1 || mysql_result($req,$i-1,'classes.annee')==$annee_actu+1) 
				{ 
					$lien='<a title="'.$Langue['SPAN_VOIR_CLASSE'].'" href=#null onClick="Eleves_D_Charge_Classe(\''.mysql_result($req,$i-1,'classes.id').'\')">';
					$lien_fin='</a>';
				}
			}
      $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'</td>';
		}
		else
		{
      if ($_SESSION['type_util']=="D")
      {
				if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee_actu=date("Y")-1; } else { $annee_actu=date("Y"); }
				if (mysql_result($req,$i-1,'classes.annee')==$annee_actu || mysql_result($req,$i-1,'classes.annee')==$annee_actu-1 || mysql_result($req,$i-1,'classes.annee')==$annee_actu+1) 
				{ 
					$lien='<a title="'.$Langue['SPAN_VOIR_CLASSE'].'" href=#null onClick="Eleves_D_Charge_Classe(\''.mysql_result($req,$i-1,'classes.id').'\')">';
					$lien_fin='</a>';
				}
			}
      $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'-'.$annee2.'</td>';
		}
    $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.$lien.mysql_result($req,$i-1,'classes.nom_classe').$lien_fin.'</td>';
    $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.mysql_result($req,$i-1,'listes.intitule').'</td>';
    $msg=$msg.'<td class="centre" style="width:2%"><a title="'.$Langue['SPAN_CLASSES_VOIR_LS'].'" href="#null" onClick="Eleves_D_Charge_Livret(\''.$_GET['id'].'\',\''.mysql_result($req,$i-1,'classes.annee').'\')"><img src="images/livret_scolaire_petit.gif" width=12 height=12 border=0></a></td>';
    $req2=mysql_query("SELECT classes_profs.*, profs.* FROM `classes_profs`, `profs` WHERE classes_profs.id_classe='".mysql_result($req,$i-1,'classes.id')."' AND classes_profs.type='T' AND classes_profs.id_prof=profs.id");
    $lien="";$lien_fin="";
    if ($_SESSION['type_util']=="D")
    {
			$lien='<a title="'.$Langue['SPAN_CLASSES_VOIR_PERSONNEL'].'" href="#null" onClick="Eleves_D_Charge_Prof(\''.mysql_result($req2,0,'profs.id').'\')">';
			$lien_fin='</a>';
		}
    $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.$lien.$liste_choix['civilite'][mysql_result($req2,0,'profs.civilite')].' '.mysql_result($req2,0,'profs.nom').$lien_fin.'</td>';
    if ($_SESSION['type_util']=="D")
    {  
      $msg=$msg.'<td class="centre" style="width:12%'.$bold.'">'.$liste_choix['ouinon2'][mysql_result($req,$i-1,'eleves_classes.redoublement')].'</td>';
      $msg=$msg.'<td class="centre" style="width:3%" nowrap><a title="'.$Langue['SPAN_CLASSES_MODIFIER_CLASSE'].'" href="#null" onClick="Eleves_D_Modifier_Eleve(\''.mysql_result($req,$i-1,'eleves_classes.id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_CLASSES_SUPPRIMER_CLASSE'].'" href="#null" onClick="Eleves_D_Supprimer_Eleve(\''.mysql_result($req,$i-1,'eleves_classes.id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></td></tr>';
		}
		else
		{
      $msg=$msg.'<td class="centre" style="width:15%'.$bold.'">'.$liste_choix['ouinon2'][mysql_result($req,$i-1,'eleves_classes.redoublement')].'</td></tr>';
		}
  }
  $msg=$msg.'</tbody></table>';
  $tpl->set_var("LISTE_CLASSES",$msg);
  
  // Affichage des livres empruntés dans la bibliothèque
  $msg='<table id="listing_livres_empruntes" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr><th class="gauche" style="width:45%">'.$Langue['LBL_BIBLIO_TITRES'].'</th>';
  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_BIBLIO_BIBLIOTHEQUE'].'</th>';
  $msg=$msg.'<th class="centre" style="width:15%">'.$Langue['LBL_BIBLIO_DATE_EMPRUNT'].'</th>';
  $msg=$msg.'<th class="centre" style="width:15%">'.$Langue['LBL_BIBLIO_DATE_RETOUR'].'</th>';
  $msg=$msg.'<th class="centre" style="width:5%">&nbsp;</th></tr></thead><tbody>';

  $req=mysql_query("SELECT bibliotheque_emprunt.*, bibliotheque.* FROM `bibliotheque_emprunt`, `bibliotheque` WHERE bibliotheque_emprunt.id_util='".$_GET['id']."' AND bibliotheque_emprunt.type_util='E' AND bibliotheque_emprunt.id_livre=bibliotheque.id AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque_emprunt.date_emprunt DESC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		$msg=$msg.'<tr>';
    $msg=$msg.'<td class="gauche" style="width:45%"><a title="'.$Langue['SPAN_BIBLIO_VOIR_LIVRE'].'" href=#null onClick="Eleves_D_Charge_Livre(\''.mysql_result($req,$i-1,'bibliotheque.id').'\')">'.mysql_result($req,$i-1,'bibliotheque.reference').' - '.mysql_result($req,$i-1,'bibliotheque.titre').'</a></td>';
		if (mysql_result($req,$i-1,'bibliotheque.id_prof')=="")
		{
      $msg=$msg.'<td class="centre" style="width:20%">'.$Langue['LBL_BIBLIO_ECOLE'].'</td>';
		}
		else
		{
			$req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'bibliotheque.id_prof')."'");
  	  $msg=$msg.'<td class="centre" style="width:20%">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';
		}
    $msg=$msg.'<td class="centre" style="width:15%">'.Date_Convertir(mysql_result($req,$i-1,'bibliotheque_emprunt.date_emprunt'),'Y-m-d',$Format_Date_PHP).'</td>';
		if (mysql_result($req,$i-1,'bibliotheque_emprunt.date_retour')=="0000-00-00")
		{
      $msg=$msg.'<td class="centre" style="width:15%">&nbsp;</td>';
      $msg=$msg.'<td class="centre" style="width:5%"><a title="'.$Langue['SPAN_BIBLIO_MODIFIER_EMPRUNT'].'" href="#null" onClick="Eleves_D_Charge_Emprunt(\''.mysql_result($req,$i-1,'bibliotheque_emprunt.id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_BIBLIO_CLORE_EMPRUNT'].'" href="#null" onClick="Eleves_D_Valid_Emprunt(\''.mysql_result($req,$i-1,'bibliotheque_emprunt.id').'\')"><img src="images/retour_emprunt.png" width=12 height=12 border=0></a></td>';
		}
		else
		{
      $msg=$msg.'<td class="centre" style="width:15%">'.Date_Convertir(mysql_result($req,$i-1,'bibliotheque_emprunt.date_retour'),'Y-m-d',$Format_Date_PHP).'</td>';
      $msg=$msg.'<td class="centre" style="width:5%">&nbsp;</td>';
		}
		$msg=$msg.'</tr>';
  }
  $msg=$msg.'</tbody></table>';
  $tpl->set_var("LISTE_LIVRES_EMPRUNTES",$msg);
  
  
  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");

  // On recherche si l'élève peut être ajouté dans une classe
  $annee="0000";
  $trouve=false;
  $req=mysql_query("SELECT * FROM `classes` ORDER BY annee ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if ($annee!=mysql_result($req,$i-1,'annee'))
		{
			$annee=mysql_result($req,$i-1,'annee');
			$req2=mysql_query("SELECT classes.*, eleves_classes.* FROM `classes`,`eleves_classes` WHERE classes.annee='$annee' AND classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve='".$_GET['id']."'");
			if (mysql_num_rows($req2)=="")
			{
				$trouve=true;
				$i=mysql_num_rows($req)+1;
			}
		}
  }
?>
<script language="Javascript">
$(document).ready(function()
{
  $('#image_personne').click(function()
  {
    Charge_Dialog3("index2.php?module=eleves&action=saisir_photo1&id_personne=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_PHOTO_TITRE']; ?>");
  });
  
  $('#listing_classes').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "bAutoWidth": false,
      "aaSorting": [[ 1, "desc" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "iDisplayLength": 10,
	  <?php if ($_SESSION['type_util']=="D") { ?>
      "aoColumns" : [ {"bSortable":false},null,null,null,{"bSortable":false},null,null,{"bSortable":false} ],
	  <?php } else { ?>
      "aoColumns" : [ {"bSortable":false},null,null,null,{"bSortable":false},null,null ],
	  <?php } ?>
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
        "sLengthMenu": "<?php echo $Langue['LBL_BIBLIO_ELEMENT_AFFICHES']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_BIBLIO_NO_DATA']; ?>",
        "sInfo": "<?php echo $Langue['LBL_BIBLIO_ELEMENT_AFFICHES2']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_BIBLIO_NO_DATA2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_BIBLIO_RESULT_RECHERCHE']; ?>",
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
  
  $("#nav_liste_eleve").change(function()
  {
		Charge_Dialog("index2.php?module=eleves&action=detailview&id="+$("#nav_liste_eleve").val(),"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
  });
  
  $("#nav_liste_eleve2").change(function()
  {
		Charge_Dialog("index2.php?module=eleves&action=detailview&id="+$("#nav_liste_eleve2").val(),"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
  });

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

  $("#Imprimer_Detail").button();
  $("#Imprimer_Detail").click(function()
  {
    Charge_Dialog("index2.php?module=eleves&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#Imprimer_Detail2").button();
  $("#Imprimer_Detail2").click(function()
  {
    Charge_Dialog("index2.php?module=eleves&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

<?php if ($est_titulaire==false) { ?>
  $("#Modifier").button({ disabled: true });
  $("#Modifier2").button({ disabled: true });
<?php } else { ?>
  $("#Modifier").button({ disabled: false });
  $("#Modifier2").button({ disabled: false });
  <?php if ($trouve==false) { ?>
  $("#AjouterClasse").button({ disabled: true });
  $("#AjouterClasse2").button({ disabled: true });
  <?php } else { ?>
  $("#AjouterClasse").button({ disabled: false });
  $("#AjouterClasse2").button({ disabled: false });
  <?php } ?>
  $("#Modifier").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=eleves&action=editview&id="+id,"<?php echo $Langue['SPAN_MODIFIER_ELEVE']; ?>");
  });
  $("#Modifier2").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=eleves&action=editview&id="+id,"<?php echo $Langue['SPAN_MODIFIER_ELEVE']; ?>");
  });
  $("#AjouterClasse").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog2("index2.php?module=eleves&action=ajout_eleve_classe&id_eleve="+id,"<?php echo $Langue['LBL_CLASSES_AJOUTER']; ?>");
  });
  $("#AjouterClasse2").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog2("index2.php?module=eleves&action=ajout_eleve_classe&id_eleve="+id,"<?php echo $Langue['LBL_CLASSES_AJOUTER']; ?>");
  });
<?php } ?>
});

function Eleves_D_Modifier_Eleve(id)
{
  Charge_Dialog2("index2.php?module=eleves&action=modif_liste_eleve_unique&id="+id,"<?php echo $Langue['LBL_CLASSES_MODIFIER']; ?>");
}

function Eleves_D_Supprimer_Eleve(id)
{
	$( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_CLASSES_SUPPRIMER']; ?></div><div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_CLASSES_SUPPRIMER2']; ?></strong></div></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
	{
		title: "<?php echo $Langue['LBL_CLASSES_SUPPRIMER']; ?>",
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
				var request = $.ajax({type: "POST", url: "index2.php", data: "module=eleves&action=delete_eleves&id="+id});
				request.done(function(msg)
				{
					decoupage=msg.split("|");
					$( "#dialog-confirm" ).dialog( "close" );
					Charge_Dialog("index2.php?module=eleves&action=detailview&id="+decoupage[0],"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
					parent.calcul.location.href='users/calcul_moyenne.php?id_classe='+decoupage[1]+'&id_niveau='+decoupage[2]+'&id_titulaire='+decoupage[3]+'&annee='+decoupage[4];
					$("#tabs").tabs("load",3);
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

  function Eleves_D_Charge_Prof(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=personnels&action=detailview&id="+id,"<?php echo $Langue['LBL_CLASSES_FICHE_PERSONNEL']; ?>");
  }
  
  function Eleves_D_Charge_Classe(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=classes&action=detailview&id="+id,"<?php echo $Langue['LBL_CLASSES_FICHE_CLASSE']; ?>");
  }
  
  function Eleves_D_Charge_Livret(id_eleve,annee)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve="+id_eleve+"&annee_scolaire_ls="+annee,"<?php echo $Langue['LBL_CLASSES_FICHE_LS']; ?>");
  }
  
  function Eleves_D_Charge_Emprunt(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt&id="+id,"<?php echo $Langue['LBL_BIBLIO_FICHE_EMPRUNT']; ?>");
  }
  
  function Eleves_D_Charge_Livre(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id="+id,"<?php echo $Langue['LBL_BIBLIO_FICHE_LIVRE']; ?>");
  }

  /* Fonction pour clore l'emprunt */
  function Eleves_D_Valid_Emprunt(id)
  {
    $( "#dialog-confirm" ).html('<div class="gauche" style="line-height:24px;"><?php echo $Langue['MSG_BIBLIO_EMPRUNT']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_BIBLIO_FIN_EMPRUNT']; ?>",
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
						Charge_Dialog("index2.php?module=eleves&action=detailview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
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
    $(document).ready(function() 
		{
			var elf = $('#elfinder_eleve').elfinder(
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
				url : 'commun/elfinder/php/connector_doc_E_<?php echo $_SESSION['type_util']; ?>.php?repertoire_perso=<?php echo "E".$_GET['id']; ?>'  // connector URL (REQUIRED)
			}).elfinder('instance');            
    });
</script>

