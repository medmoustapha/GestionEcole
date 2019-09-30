<?php
// Récupération des informations
  foreach ($tableau_variable['bibliotheque'] AS $cle)
  {
    $tableau_variable['bibliotheque'][$cle['nom']]['value'] = "";
  }

  $tableau_variable['bibliotheque']['id_cat']['nom_prof']=$_SESSION['id_util'];
  $req = mysql_query("SELECT * FROM `bibliotheque` WHERE id = '" . $_GET['id'] . "'");
  foreach ($tableau_variable['bibliotheque'] AS $cle)
  {
    if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['bibliotheque'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
  }
  
  $msg='<select name="nav_liste_biblio" id="nav_liste_biblio" class="text ui-widget-content ui-corner-all">';
  $msg2='<select name="nav_liste_biblio2" id="nav_liste_biblio2" class="text ui-widget-content ui-corner-all">';
  if ($tableau_variable['bibliotheque']['id_prof']['value']=="")
  {
		$tableau_variable['bibliotheque']['id_cat']['type']='liste_bdd';
		$tableau_variable['bibliotheque']['id_cat']['nomliste']='categ_biblio_ecole';
		$msg .='<option value="" class="option_gras">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</option>';
		$msg2 .='<option value="" class="option_gras">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</option>';
		$date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt'],date("Y")));
		$id_prof_cours="";
  }
	else
	{
		$tableau_variable['bibliotheque']['id_cat']['type']='liste_prof';
		$tableau_variable['bibliotheque']['id_cat']['nomliste']='categ_biblio_classe';
		$msg .='<option value="" class="option_gras">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</option>';
		$msg2 .='<option value="" class="option_gras">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</option>';
		$date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt_classe'],date("Y")));
		$id_prof_cours=mysql_result($req,0,'id_prof');
	}
	
	switch ($_SESSION['livres_a_afficher'])
	{
		case "T": $req_biblio=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$id_prof_cours."' ORDER BY reference ASC, titre ASC"); break;
		case "U": $req_biblio=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$id_prof_cours."' AND etat<>'O' ORDER BY reference ASC, titre ASC"); break;
		case "N": $req_biblio=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$id_prof_cours."' AND etat<>'O' ORDER BY reference ASC, titre ASC"); break;
		case "E": $req_biblio=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='".$id_prof_cours."' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' ORDER BY bibliotheque.reference ASC, bibliotheque.titre ASC"); break;
		case "R": 
			$req_biblio=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='".$id_prof_cours."' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.date_emprunt<='".$date_retard."' ORDER BY bibliotheque.reference ASC, bibliotheque.titre ASC"); 
			break;
		case "S": $req_biblio=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$id_prof_cours."' AND etat='S' ORDER BY reference ASC, titre ASC"); break;
		case "O": $req_biblio=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$id_prof_cours."' AND etat='O' ORDER BY reference ASC, titre ASC"); break;
	}
	for ($i=1;$i<=mysql_num_rows($req_biblio);$i++)
	{
		$aafficherdansliste=true;
		if ($_SESSION['livres_a_afficher']=="N")
		{
			$req2=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req_biblio,$i-1,'bibliotheque.id')."' AND date_retour='0000-00-00'");
			if (mysql_num_rows($req2)!="") { $aafficherdansliste=false; }
		}
		if ($aafficherdansliste==true)
		{
			$msg .='<option value="'.mysql_result($req_biblio,$i-1,'bibliotheque.id').'"';
			$msg2 .='<option value="'.mysql_result($req_biblio,$i-1,'bibliotheque.id').'"';
			if (mysql_result($req_biblio,$i-1,'id')==$_GET['id']) { $msg .=' SELECTED'; $msg2 .=' SELECTED'; }
			if (strlen(mysql_result($req_biblio,$i-1,'bibliotheque.titre'))>25) { $titre=substr(mysql_result($req_biblio,$i-1,'bibliotheque.titre'),0,24).'...'; } else { $titre=mysql_result($req_biblio,$i-1,'bibliotheque.titre'); }
			$msg .='>'.mysql_result($req_biblio,$i-1,'bibliotheque.reference').' - '.$titre.'</option>';
			$msg2 .='>'.mysql_result($req_biblio,$i-1,'bibliotheque.reference').' - '.$titre.'</option>';
		}
	}
  $msg .='</select>';
  $msg2 .='</select>';
  
  $tpl = new template("bibliotheque");
  $tpl->set_file("gform","detailview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  $tpl->set_var("LISTE_BIBLIOTHEQUE",$msg);
  $tpl->set_var("LISTE_BIBLIOTHEQUE2",$msg2);

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['bibliotheque'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
  }
  if ($tableau_variable['bibliotheque']['id_prof']['value']!="")
  {
    $req2=mysql_query("SELECT * FROM `profs` WHERE id='".$tableau_variable['bibliotheque']['id_prof']['value']."'");
	$tpl->set_var("BIBLIOTHEQUE",$Langue['LBL_BIBLIOTHEQUE_CLASSE']." ".$liste_choix['civilite_long'][mysql_result($req2,0,'civilite')].' '.mysql_result($req2,0,'nom'));
  }
  else
  {
	$tpl->set_var("BIBLIOTHEQUE",$Langue['LBL_BIBLIOTHEQUE_ECOLE']);
  }

  if ($tableau_variable['bibliotheque']['couverture']['value']!="")
  {
    list($width, $height, $type, $attr) = getimagesize($tableau_variable['bibliotheque']['couverture']['value']);
	if ($width>175)
	{
	  $height2=175*$height/$width;
	  if ($height2>250)
	  {
	    $width2=250*$width/$height;
		$height2=250;
	  }
	  else
	  {
	    $width2=175;
	  }
	}
	else
	{
	  if ($height>250)
	  {
	    $width2=250*$width/$height;
		$height2=250;
	  }
	  else
	  {
	    $height2=$height;
		$width2=$width;
	  }
	}
	$tpl->set_var("COUVERTURE",'<img src="'.$tableau_variable['bibliotheque']['couverture']['value'].'" height='.$height2.' width='.$width2.' border=0>');
  }
  else
  {
	$tpl->set_var("COUVERTURE",'<img src="images/pas_couverture.png" width=175 height=250 border=0>');
  }
   
  // Liste des emprunts
  $msg='<table id="listing_emprunts" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr>';
  $msg=$msg.'<th style="width:55%" class="centre">'.$Langue['LBL_EMPRUNT_EMPRUNTEUR'].'</th>';
  $msg=$msg.'<th style="width:20%" class="centre">'.$Langue['LBL_EMPRUNT_DATE_EMPRUNT'].'</th>';
  $msg=$msg.'<th style="width:20%" class="centre">'.$Langue['LBL_EMPRUNT_DATE_RETOUR'].'</th>';
  $msg=$msg.'<th style="width:5%" class="centre">&nbsp;</th>';
  $msg=$msg.'</tr></thead><tbody>';
  $req=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".$_GET['id']."' AND reservation='0' ORDER BY date_emprunt DESC");
	$actif_reserve=true;
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (mysql_result($req,$i-1,'type_util')=="P")
		{
			$req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'id_util')."'");
		}
		else
		{
			$req2=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req,$i-1,'id_util')."'");
		}
		if ($_SESSION['type_util']=="D")
		{
				$msg=$msg.'<tr><td class="gauche" style="width:55%"><a title="'.$Langue['SPAN_VOIR_FICHE_PERSONNE'].'" href=#null onClick="Biblio_Charge_Emprunteur(\''.mysql_result($req2,0,'id').'\',\''.mysql_result($req,$i-1,'type_util').'\')">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</a></td>';
		}
		else
		{
			if (mysql_result($req,$i-1,'type_util')=="P")
			{
					$msg=$msg.'<tr><td class="gauche" style="width:55%">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';
			}
			else
			{
					$msg=$msg.'<tr><td class="gauche" style="width:55%"><a title="'.$Langue['SPAN_VOIR_FICHE_PERSONNE'].'" href=#null onClick="Biblio_Charge_Emprunteur(\''.mysql_result($req2,0,'id').'\',\''.mysql_result($req,$i-1,'type_util').'\')">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</a></td>';
			}
		}
		$msg=$msg.'<td class="centre" style="width:20%">'.Date_Convertir(mysql_result($req,$i-1,'date_emprunt'),'Y-m-d',$Format_Date_PHP).'</td>'; 
		if (mysql_result($req,$i-1,'date_retour')!="0000-00-00")
		{
			$msg=$msg.'<td class="centre" style="width:20%">'.Date_Convertir(mysql_result($req,$i-1,'date_retour'),'Y-m-d',$Format_Date_PHP).'</td>'; 
			$msg=$msg.'<td class="centre" style="width:5%">&nbsp;</td></tr>'; 
		}
		else
		{
			$msg=$msg.'<td class="centre" style="width:20%">&nbsp;</td>'; 
			$msg=$msg.'<td class="centre" style="width:5%"><a title="'.$Langue['SPAN_MODIFIER_EMPRUNT'].'" href=#null onClick="Biblio_Charge_Emprunt(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_CLORE_EMPRUNT'].'" href="#null" onClick="Biblio_Valid_Emprunt_F(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/retour_emprunt.png" width=12 height=12 border=0></a></td></tr>';
			$actif_reserve=false;
		}
  }
  $msg=$msg.'</tbody></table>';
  $tpl->set_var("LISTE_EMPRUNT",$msg);
  
  // Liste des réservations
  $msg='<table id="listing_reservations" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
  $msg=$msg.'<thead><tr>';
  $msg=$msg.'<th style="width:65%" class="centre">'.$Langue['LBL_RESERVATION_PERSONNE'].'</th>';
  $msg=$msg.'<th style="width:25%" class="centre">'.$Langue['LBL_RESERVATION_DATE'].'</th>';
  $msg=$msg.'<th style="width:10%" class="centre">&nbsp;</th>';
  $msg=$msg.'</tr></thead><tbody>';
  $req=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".$_GET['id']."' AND reservation='1' ORDER BY date_emprunt ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (mysql_result($req,$i-1,'type_util')=="P")
		{
			$req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'id_util')."'");
		}
		else
		{
			$req2=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req,$i-1,'id_util')."'");
		}
		if ($_SESSION['type_util']=="D")
		{
				$msg=$msg.'<tr><td class="gauche" style="width:55%"><a title="'.$Langue['SPAN_VOIR_FICHE_PERSONNE'].'" href=#null onClick="Biblio_Charge_Emprunteur(\''.mysql_result($req2,0,'id').'\',\''.mysql_result($req,$i-1,'type_util').'\')">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</a></td>';
		}
		else
		{
			if (mysql_result($req,$i-1,'type_util')=="P")
			{
					$msg=$msg.'<tr><td class="gauche" style="width:65%">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';
			}
			else
			{
					$msg=$msg.'<tr><td class="gauche" style="width:55%"><a title="'.$Langue['SPAN_VOIR_FICHE_PERSONNE'].'" href=#null onClick="Biblio_Charge_Emprunteur(\''.mysql_result($req2,0,'id').'\',\''.mysql_result($req,$i-1,'type_util').'\')">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</a></td>';
			}
		}
		$msg=$msg.'<td class="centre" style="width:25%">'.Date_Convertir(mysql_result($req,$i-1,'date_emprunt'),'Y-m-d',$Format_Date_PHP).'</td>'; 
		$msg=$msg.'<td class="droite" style="width:10%"><a title="'.$Langue['SPAN_RESERVATION_MODIFIER'].'" href=#null onClick="Biblio_Charge_Reservation(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>';
		if ($actif_reserve==true)
		{
		  $msg=$msg.'&nbsp;<a title="'.$Langue['SPAN_RESERVATION_EMPRUNT'].'" href=#null onClick="Biblio_Valid_Reservation_F(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/reservation_emprunt.png" width=12 height=12 border=0></a>';
		}
		$msg=$msg.'&nbsp;<a title="'.$Langue['SPAN_RESERVATION_SUPPRIMER'].'" href=#null onClick="Biblio_Supprimer_Reservation_F(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></td></tr>'; 
  }
  $msg=$msg.'</tbody></table>';
  $tpl->set_var("LISTE_RESERVATIONS",$msg);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");

  // Le livre est-il empruntable
  $req=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".$_GET['id']."' AND date_retour='0000-00-00' AND reservation='0'");
  if (mysql_num_rows($req)=="")
  {
	if ($tableau_variable['bibliotheque']['id_prof']['value']=="" || $tableau_variable['bibliotheque']['id_prof']['value']==$_SESSION['id_util'])
	{
      $est_empruntable=true;
	}
	else
	{
      $est_empruntable=false;
	}
  }
  else
  {
    $est_empruntable=false;
  }
?>
<script language="Javascript">
$(document).ready(function()
{
  $("#Reserver_Livre").button({disabled:false});
  $("#Reserver_Livre2").button({disabled:false});
  $("#Imprimer_Detail").button({disabled:false});
  $("#Imprimer_Detail2").button({disabled:false});
  $("#Imprimer_Detail").click(function()
  {
    Charge_Dialog("index2.php?module=bibliotheque&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
  $("#Imprimer_Detail2").click(function()
  {
    Charge_Dialog("index2.php?module=bibliotheque&action=detailview_imprimer&id_a_imprimer=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#nav_liste_biblio").change(function()
  {
    if ($("#nav_liste_biblio").val()!="")
	{
      Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id="+$("#nav_liste_biblio").val(),"<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
	}
  });
  
  $("#nav_liste_biblio2").change(function()
  {
    if ($("#nav_liste_biblio2").val()!="")
	{
      Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id="+$("#nav_liste_biblio2").val(),"<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
	}
  });

  /* Initialisation de la page, notamment des boutons */
<?php if ($est_empruntable==true) { ?>
  $("#Saisir_Emprunt").button({disabled:false});
  $("#Saisir_Emprunt2").button({disabled:false});
  $("#Saisir_Emprunt").click(function()
  {
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt&id_livre=<?php echo $_GET['id']; ?>","<?php echo $Langue['BTN_SAISIR_EMPRUNT']; ?>");
  });
  $("#Saisir_Emprunt2").click(function()
  {
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt&id_livre=<?php echo $_GET['id']; ?>","<?php echo $Langue['BTN_SAISIR_EMPRUNT']; ?>");
  });
<?php } else { ?>
  $("#Saisir_Emprunt").button({disabled:true});
  $("#Saisir_Emprunt2").button({disabled:true});
<?php } ?>

  $("#Reserver_Livre").click(function()
  {
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_reservation&id_livre=<?php echo $_GET['id']; ?>","<?php echo $Langue['BTN_RESERVATION_SAISIR']; ?>");
  });
  $("#Reserver_Livre2").click(function()
  {
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_reservation&id_livre=<?php echo $_GET['id']; ?>","<?php echo $Langue['BTN_RESERVATION_SAISIR']; ?>");
  });

<?php if (($_SESSION['type_util']=="D" && $tableau_variable['bibliotheque']['id_prof']['value']=="") || $_SESSION['id_util']==$tableau_variable['bibliotheque']['id_prof']['value']) { ?>
  $("#Modifier").button({disabled:false});
  $("#Modifier2").button({disabled:false});
<?php } else { ?>
  $("#Modifier").button({disabled:true});
  $("#Modifier2").button({disabled:true});
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

  $("#Modifier").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=bibliotheque&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_LIVRE']; ?>");
  });
  $("#Modifier2").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=bibliotheque&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_LIVRE']; ?>");
  });

  $('#listing_emprunts').dataTable
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

  $('#listing_reservations').dataTable
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
});

function Biblio_Charge_Emprunt(id)
{
  Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt&id="+id,"<?php echo $Langue['BTN_SAISIR_EMPRUNT']; ?>");
}

function Biblio_Charge_Reservation(id,id_livre)
{
  Charge_Dialog3("index2.php?module=bibliotheque&action=editview_reservation&id="+id+"&id_livre="+id_livre,"<?php echo $Langue['BTN_RESERVATION_SAISIR']; ?>");
}

function Biblio_Charge_Emprunteur(id,type)
{
  if (type=="P")
  {
    Charge_Dialog("index2.php?module=personnels&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
  }
  else
  {
    Charge_Dialog("index2.php?module=eleves&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
  }
}

  /* Fonction pour clore l'emprunt */
  function Biblio_Valid_Emprunt_F(id)
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_RETOUR_LIVRE']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_EMPRUNT_VALIDER_EMPRUNT']; ?>",
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
					request.done(function()
					{
						Message_Chargement(1,1);
						$( "#dialog-confirm" ).dialog( "close" );
						Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
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

  /* Fonction pour valider une réservation */
  function Biblio_Valid_Reservation_F(id)
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_RESERVATION_EMPRUNT']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_RESERVATION_VALIDER']; ?>",
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
					var request = $.ajax({type: "POST", url: "index2.php", data: "module=bibliotheque&action=save_valider_reservation&id_emprunt="+id});
					request.done(function()
					{
						Message_Chargement(1,1);
						$( "#dialog-confirm" ).dialog( "close" );
						Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
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

  /* Fonction pour supprimer une réservation */
  function Biblio_Supprimer_Reservation_F(id)
  {
    $( "#dialog-confirm" ).html('<div style="text-align:left;line-height:24px;"><?php echo $Langue['MSG_RESERVATION_SUPPRIMER']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_RESERVATION_SUPPRIMER']; ?>",
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
          var request = $.ajax({type: "POST", url: "index2.php", data: "module=bibliotheque&action=delete_reservation&id_emprunt="+id});
          request.done(function(msg)
          {
						Message_Chargement(1,1);
						$( "#dialog-confirm" ).dialog( "close" );
						Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
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
