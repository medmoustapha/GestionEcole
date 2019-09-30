<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=date("Y");
  }
  else
  {
    if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=date("Y")-1; } else { $annee=date("Y"); }
  }
  Param_Utilisateur($_SESSION['id_util'],$annee);

  // Récupération des informations
  foreach ($tableau_variable['bibliotheque'] AS $cle)
  {
    $tableau_variable['bibliotheque'][$cle['nom']]['value'] = "";
  }

  $tableau_variable['bibliotheque']['id_cat']['nom_prof']=$_SESSION['id_util'];
  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `bibliotheque` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['bibliotheque'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['bibliotheque'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
    if ($tableau_variable['bibliotheque']['id_prof']['value']=="")
	{
	  $tableau_variable['bibliotheque']['id_cat']['type']='liste_bdd';
	  $tableau_variable['bibliotheque']['id_cat']['nomliste']='categ_biblio_ecole';
	}
  }
  else
  {
    if ($gestclasse_config_plus['biblio_ecole']=="1")
	{
	  $tableau_variable['bibliotheque']['id_cat']['type']='liste_bdd';
	  $tableau_variable['bibliotheque']['id_cat']['partie']='1';
	  $tableau_variable['bibliotheque']['id_cat']['nomliste']='categ_biblio_ecole';
	}
  }

  $tpl = new template("bibliotheque");
  $tpl->set_file("gform","editview_D.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['bibliotheque'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  
  $msg='<select tabindex=1 class="text ui-widget-content ui-corner-all" name="id_prof" id="id_prof">';
  if ($gestclasse_config_plus['biblio_ecole']=="1" && $gestclasse_config_plus['biblio_classe']=="1")
  {
    $msg .='<option value=""';
	if ($tableau_variable['bibliotheque']['id_prof']['value']=="") { $msg .=' SELECTED'; }
	$msg .='>'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</option>';
    $msg .='<option value="'.$_SESSION['id_util'].'"';
	if ($tableau_variable['bibliotheque']['id_prof']['value']==$_SESSION['id_util']) { $msg .=' SELECTED'; }
	$msg .='>'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</option>';
  }  
  else
  {
    if ($gestclasse_config_plus['biblio_ecole']=="1")
	{
      $msg .='<option value="">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</option>';
	}
	else
	{
      $msg .='<option value="'.$_SESSION['id_util'].'">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</option>';
	}
  }
  $msg .='</select>';
  $tpl->set_var("ID_PROF", $msg);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#EnregistrerFermer").button();
  $("#Annuler").button();
  $("#EnregistrerNouveau").button();
  $("#EnregistrerNouveau2").button();
  $("#Enregistrer2").button();
  $("#EnregistrerFermer2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  /* Quand on change de bibliothèque */
  $("#id_prof").change(function()
  {
    id_prof=$("#id_prof").val();
    $("#liste_cat").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=bibliotheque&action=change_biblio2&valeur_defaut=<?php echo $tableau_variable['bibliotheque']['id_prof']['value']; ?>&id_prof="+id_prof });
    request.done(function(msg)
    {
      $("#liste_cat").html(msg);
    });
  });
  
  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="detail";
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#titre"))) { bValid=false; }
    if (!checkValue($("#reference"))) { bValid=false; }
    if (!checkValue($("#auteur"))) { bValid=false; }
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","bibliotheque","<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","bibliotheque","<?php echo $Langue['LBL_AJOUTER_LIVRE']; ?>");
        }
        else
        {
          updateTips("success","bibliotheque","<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","bibliotheque","<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="detail";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });
  $("#EnregistrerNouveau2").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer2").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });
});
</script>
