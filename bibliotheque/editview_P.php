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

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `bibliotheque` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['bibliotheque'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['bibliotheque'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
  }

  $tpl = new template("bibliotheque");
  $tpl->set_file("gform","editview_P.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['bibliotheque'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var("ID_PROF", $_SESSION['id_util']);

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
