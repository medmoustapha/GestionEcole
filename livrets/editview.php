<?php
// Récupération des informations
  foreach ($tableau_variable['controles'] AS $cle)
  {
    $tableau_variable['controles'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `controles` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['controles'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['controles'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
    $valeur_defaut=mysql_result($req,0,'id_prof');
    $id=$_GET['id'];
  }
  else
  {
    $tableau_variable['controles']['date']['value']=date("Y-m-d");
    $tableau_variable['controles']['trimestre']['value']=$_SESSION['trimestre_en_cours'];
    $valeur_defaut=$_SESSION['id_util'];
    $id="";
  }
  
  $tpl = new template("livrets");
  if ($gestclasse_config_plus['calcul_moyenne']=="C")
  {
    $tpl->set_file("gform","editview_C.html");
  }
  else
  {
    $tpl->set_file("gform","editview.html");
  }
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['controles'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var("ID_CLASSE",$_SESSION['id_classe_cours']);
  $tpl->set_var("ID_NIVEAU",$_SESSION['niveau_en_cours']);
  $tpl->set_var("ID_PROF",Liste_Profs('id_prof2','form',$_SESSION['id_classe_cours'],$valeur_defaut,'A',false,false,'',"4"));

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $a=$_SESSION['annee_scolaire'];
	  $annee_fin=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $a=$_SESSION['annee_scolaire']+1;
	  $annee_fin=$a.$gestclasse_config_plus['fin_annee_scolaire'];
  }

  $msg='<table id="listing_competences" class="display" cellpadding=0 cellspacing=0><thead><tr><th class="centre">'.$Langue['LBL_LISTE_COMPETENCES'].'</th></tr></thead><tbody>';
  $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='' ORDER BY ordre ASC");
  for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
  {
    $msg .='<tr><td class="gauche"><strong>'.Convertir_En_Majuscule(mysql_result($req_categorie,$i-1,'titre')).'</strong></td></tr>';
    $id_parent=mysql_result($req_categorie,$i-1,'id');
    $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_cat='$id_parent' ORDER BY ordre ASC");
    for ($j=1;$j<=mysql_num_rows($req_competence);$j++)
    {
      if ($id!="")
      {
        $req_result=mysql_query("SELECT * FROM `controles_competences` WHERE id_controle='$id' AND id_competence='".mysql_result($req_competence,$j-1,'id')."'");
        if (mysql_num_rows($req_result)=="") { $checked=""; }  else { $checked="CHECKED"; }
      }
      else
      {
        $checked="";
      }
      $msg .='<tr><td class="gauche">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/absence_vide.png" width=9 height=12 border=0>&nbsp;<input type="checkbox" name="competences[]" value="'.mysql_result($req_competence,$j-1,'id').'" '.$checked.'>&nbsp;'.mysql_result($req_competence,$j-1,'code')." - ".mysql_result($req_competence,$j-1,'intitule').'</td></tr>';
    }
    $req_categorie2=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='$id_parent' ORDER BY ordre ASC");
    for ($j=1;$j<=mysql_num_rows($req_categorie2);$j++)
    {
      $msg .='<tr><td class="gauche">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/liaison.gif" width=9 height=12 border=0>&nbsp;<strong>'.mysql_result($req_categorie2,$j-1,'titre').'</strong></td></tr>';
      /* Affichage des compétences de la catégorie */
      $id_parent2=mysql_result($req_categorie2,$j-1,'id');
      $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_cat='".$id_parent2."' ORDER BY ordre ASC");
      for ($k=1;$k<=mysql_num_rows($req_competence);$k++)
      {
        if ($id!="")
        {
          $req_result=mysql_query("SELECT * FROM `controles_competences` WHERE id_controle='$id' AND id_competence='".mysql_result($req_competence,$k-1,'id')."'");
          if (mysql_num_rows($req_result)=="") { $checked=""; }  else { $checked="CHECKED"; }
        }
        else
        {
          $checked="";
        }
        $msg .='<tr><td class="gauche">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/absence_vide.png" width=18 height=12 border=0>&nbsp;<input type="checkbox" name="competences[]" value="'.mysql_result($req_competence,$k-1,'id').'" '.$checked.'>&nbsp;'.mysql_result($req_competence,$k-1,'code')." - ".mysql_result($req_competence,$k-1,'intitule').'</td></tr>';
      }
    }
  }
  $msg .='</tbody></table>';
  $tpl->set_var("LISTE_COMPETENCES",$msg);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#Annuler").button();
  $("#Enregistrer2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
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

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="detail";
  });
  $("#Enregistrer2").click(function()
  {
    action_save="detail";
    $("#form_editview").submit();
  });

  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		var results = $(this).serialize();
    if (!checkValue($("#descriptif"))) { bValid=false; }
    if (!checkValue($("#id_prof2"))) { bValid=false; }
    if (!checkRegexp($("#date"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    event.preventDefault();
    if ( bValid )
    {
      if (results.indexOf("competences",0)==-1)
      {
				$("#dialog-form").scrollTop(0);
				$("#msg_ok").fadeIn( 1000 );
				$("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_SELECTIONNER_COMPETENCE']; ?></strong></div></div>');
        bValid=false;
    		setTimeout(function()
        {
          $("#msg_ok").effect("blind",1000);
        }, 3000 );
      }
      else
      {
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
      }
    }
    else
    {
      updateTips("error","livrets","<?php echo $Langue['LBL_FICHE_EVALUATION']; ?>");
      action_save="rien";
    }
  });
});
</script>
