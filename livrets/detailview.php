<?php
// Récupération des informations
  $plus_modifiable=false;

  foreach ($tableau_variable['controles'] AS $cle)
  {
    $tableau_variable['controles'][$cle['nom']]['value'] = "";
  }

  $req = mysql_query("SELECT * FROM `controles` WHERE id = '" . $_GET['id'] . "'");
  foreach ($tableau_variable['controles'] AS $cle)
  {
    if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['controles'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
  }
  $id=$_GET['id'];
  $date_controle=mysql_result($req,0,'date');

  $tpl = new template("livrets");
  if ($gestclasse_config_plus['calcul_moyenne']=="C")
  {
    $tpl->set_file("gform","detailview_C.html");
  }
  else
  {
    $tpl->set_file("gform","detailview.html");
  }
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['controles'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
  }
  $tpl->set_var("ID_PROF",Liste_Profs('id_prof','value','',mysql_result($req,0,'id_prof'),'A',false));

  // *************************************************
  // * Affichage de la liste des compétences testées *
  // *************************************************
  $msg='<table id="listing_competences" class="display" cellpadding=0 cellspacing=0><thead><tr><th align=center>'.$Langue['LBL_LISTE_COMPETENCES'].'</th></tr></thead><tbody>';
  $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND id_parent='' ORDER BY ordre ASC");
  for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
  {
    $id_parent=mysql_result($req_categorie,$i-1,'id');
    $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND id_cat='$id_parent' ORDER BY ordre ASC");
    for ($j=1;$j<=mysql_num_rows($req_competence);$j++)
    {
      $req_result=mysql_query("SELECT * FROM `controles_competences` WHERE id_controle='$id' AND id_competence='".mysql_result($req_competence,$j-1,'id')."'");
      if (mysql_num_rows($req_result)!="")
      {
        $msg .='<tr><td class="gauche">'.mysql_result($req_competence,$j-1,'code')." - ".mysql_result($req_competence,$j-1,'intitule').'</td></tr>';
        $liste_competence[mysql_result($req_competence,$j-1,'id')]=mysql_result($req_competence,$j-1,'code');
      }
    }
    $req_categorie2=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND id_parent='$id_parent' ORDER BY ordre ASC");
    for ($j=1;$j<=mysql_num_rows($req_categorie2);$j++)
    {
      $id_parent2=mysql_result($req_categorie2,$j-1,'id');
      $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND id_cat='".$id_parent2."' ORDER BY ordre ASC");
      for ($k=1;$k<=mysql_num_rows($req_competence);$k++)
      {
        $req_result=mysql_query("SELECT * FROM `controles_competences` WHERE id_controle='$id' AND id_competence='".mysql_result($req_competence,$k-1,'id')."'");
        if (mysql_num_rows($req_result)!="")
        {
          $msg .='<tr><td class="gauche">'.mysql_result($req_competence,$k-1,'code')." - ".mysql_result($req_competence,$k-1,'intitule').'</td></tr>';
          $liste_competence[mysql_result($req_competence,$k-1,'id')]=mysql_result($req_competence,$k-1,'code');
        }
      }
    }
  }
  $msg .='</tbody></table>';
  $tpl->set_var("LISTE_COMPETENCES",$msg);

  // ************************************
  // * Affichage de la liste des élèves *
  // ************************************
  
  // Affichage des entêtes
  $msg='<table id="listing_eleves" class="display" cellpadding=0 cellspacing=0><thead><tr>';
  $msg .='<th class="centre">'.$Langue['LBL_ELEVES'].'</th>';
  $width2='{ "sWidth": "250px", "bSortable": false },';
  foreach($liste_competence AS $cle => $value)
  {
    $total_tout[$cle]=0;
    $msg .='<th class="centre">'.$value.'</th>';
    $width2=$width2.'{ "sWidth": "150px", "bSortable": false },';
    for ($i=9;$i>=0;$i--)
    {
      if ($gestclasse_config_plus['c'.$i]!="")
      {
        $total[$cle][$i]=0;
      }
    }
  }
  $msg .='</tr></thead><tbody>';

  // Affichage des élèves
  $req_eleve=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_eleve=eleves.id AND eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_niveau='".$_SESSION['niveau_en_cours']."' AND eleves.date_entree<='".$date_controle."' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".$date_controle."') ORDER BY eleves.nom ASC, eleves.prenom ASC");
  // On recherche s'il n'y a pas déjà une signature
  for ($i=1;$i<=mysql_num_rows($req_eleve);$i++)
  {
	  $signature="livrets|".$_SESSION['trimestre_en_cours']."|".$_SESSION['annee_scolaire']."|P|".mysql_result($req_eleve,$i-1,'eleves.id');
	  $req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre LIKE '$signature' AND type_util='P'");
	  if (mysql_num_rows($req_signature)!="") { $plus_modifiable=true; }
  }
  
  // On affiche les élèves
  for ($i=1;$i<=mysql_num_rows($req_eleve);$i++)
  {
    $msg .='<tr><td class="gauche">'.mysql_result($req_eleve,$i-1,'eleves.nom').' '.mysql_result($req_eleve,$i-1,'eleves.prenom').'</td>';
    foreach($liste_competence AS $cle => $value)
    {
      $req_result=mysql_query("SELECT * FROM `controles_resultats` WHERE id_eleve='".mysql_result($req_eleve,$i-1,'eleves.id')."' AND id_controle='$id' AND id_competence='$cle'");
      if (mysql_num_rows($req_result)=="") { $valeurdefaut="-1"; } else { $valeurdefaut=mysql_result($req_result,0,'resultat'); $total[$cle][mysql_result($req_result,0,'resultat')]=$total[$cle][mysql_result($req_result,0,'resultat')]+1; }
	  if ($plus_modifiable==true)
	  {
	    if ($valeurdefaut=="-1") { $msg .='<td class="centre"><font color=#FF0000><strong>'.$Langue['LBL_ABSENT'].'</strong></td>'; } else { $msg .='<td class="centre" style="background:'.$gestclasse_config_plus['couleur_fond'.$valeurdefaut].';color:'.$gestclasse_config_plus['couleur'.$valeurdefaut].'">'.$gestclasse_config_plus['c'.$valeurdefaut].'</td>'; }
	  }
	  else
	  {
	    $msg .='<td class="centre">'.Liste_Resultats('req'.mysql_result($req_eleve,$i-1,'eleves.id').'-'.$cle,$valeurdefaut).'</td>';
	  }
    }
    $msg .='</tr>';
  }
  
  // Affichage des statistiques
  foreach($liste_competence AS $cle => $value)
  {
    for ($i=9;$i>=0;$i--)
    {
      if ($gestclasse_config_plus['c'.$i]!="")
      {
        $total_tout[$cle]=$total_tout[$cle]+$total[$cle][$i];
      }
    }
  }
  for ($i=9;$i>=0;$i--)
  {
    if ($gestclasse_config_plus['c'.$i]!="")
    {
      $msg .='<tr><td class="gauche"><strong>'.$gestclasse_config_plus['c'.$i].'</strong></td>';
      foreach($liste_competence AS $cle => $value)
      {
        if ($total_tout[$cle]!=0)
        {
          $msg .='<td class="centre"><strong>'.number_format(100*$total[$cle][$i]/$total_tout[$cle],2,","," ").' %</strong></td>';
        }
        else
        {
          $msg .='<td class="centre"><strong>0,00 %</strong></td>';
        }
      }
      $msg .='</tr>';
    }
  }

  $msg .='</tbody></table>';
  $tpl->set_var("LISTE_ELEVES",$msg);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  <?php if ($plus_modifiable==true) { ?>
  $("#Modifier").button({disabled:true});
  $("#Modifier2").button({disabled:true});
  $("#Enregistrer_Result").button({disabled:true});
  $("#Enregistrer_Result2").button({disabled:true});
  $("#Supprimer_Result").button({disabled:true});
  $("#Supprimer_Result2").button({disabled:true});
  <?php } else { ?>
  $("#Modifier").button({disabled:false});
  $("#Modifier2").button({disabled:false});
  $("#Enregistrer_Result").button({disabled:false});
  $("#Enregistrer_Result2").button({disabled:false});
  $("#Supprimer_Result").button({disabled:false});
  $("#Supprimer_Result2").button({disabled:false});
  <?php } ?>
  $("#Retour").button();
  $("#Retour2").button();

  /* Action sur les boutons */
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
    Charge_Dialog("index2.php?module=livrets&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_EVALUATION']; ?>");
  });
  $("#Modifier2").click(function()
  {
    var id=$("#id").val();
    Charge_Dialog("index2.php?module=livrets&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_EVALUATION']; ?>");
  });
  
  /* Suppression d'un contrôle */
  
  $("#Supprimer_Result").click(function()
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_EVALUATION']; ?></div><div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_SUPPRIMER_EVALUATION2']; ?></strong></div></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_SUPPRIMER_EVALUATION']; ?>",
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
            var request = $.ajax({type: "POST", url: "index2.php", data: "module=livrets&action=delete_controle&id=<?php echo $id; ?>"});
            request.done(function(msg)
            {
					    $( "#dialog-confirm" ).dialog( "close" );
					    $( "#dialog-form" ).dialog( "close" );
              parent.calcul.location.href='users/calcul_moyenne.php?id_classe=<?php echo $_SESSION['id_classe_cours']; ?>&id_niveau=<?php echo $_SESSION['niveau_en_cours']; ?>&id_titulaire=<?php echo $_SESSION['titulaire_classe_cours']; ?>&annee=<?php echo $_SESSION['annee_scolaire']; ?>';
              $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
//              document.location.href="#haut_page";
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
	});
	
  $("#Supprimer_Result2").click(function()
  {
    $("#Supprimer_Result").click();
  });

  /* Sauvegarde des résultats */
  $("#Enregistrer_Result2").click(function()
  {
    $("#form_detailview").submit();
  });

  $("#form_detailview").submit(function(event)
  {
    action_save="detail";
    event.preventDefault();
    updateTips("save","livrets","<?php echo $Langue['LBL_FICHE_EVALUATION']; ?>");
    var $form = $( this );
    url = $form.attr( 'action' );
    data = $form.serialize();
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
      $("#id").val(msg);
      parent.calcul.location.href='users/calcul_moyenne.php?id_classe=<?php echo $_SESSION['id_classe_cours']; ?>&id_niveau=<?php echo $_SESSION['niveau_en_cours']; ?>&id_titulaire=<?php echo $_SESSION['titulaire_classe_cours']; ?>&annee=<?php echo $_SESSION['annee_scolaire']; ?>';
      updateTips("success","livrets","<?php echo $Langue['LBL_FICHE_EVALUATION']; ?>");
      $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
    });
  });

  /* Tableau des listes de compétences */
	$('#listing_competences').dataTable
  (
    {
      "bJQueryUI": true,
      "bAutoWidth": false,
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bInfo": false,
      "bSort": false,
      "sDom": 'rt<"clear">',
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sZeroRecords": "<?php echo $Langue['MSG_AUCUNE_COMPETENCE']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>"
      },
    }
  );
  /* Création du tableau de données */
	$('#listing_eleves').dataTable
  (
    {
      "bJQueryUI": true,
      "bProcessing": true,
      "aaSorting": [[ 0, "asc" ]],
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bInfo": false,
      "bSort": false,
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
</script>
