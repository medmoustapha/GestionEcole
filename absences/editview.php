<?php
// Récupération des informations
  foreach ($tableau_variable['justificatifs'] AS $cle)
  {
    $tableau_variable['justificatifs'][$cle['nom']]['value'] = "";
  }

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=$_SESSION['annee_scolaire'];
  }
  else
  {
    if ($_SESSION['mois_en_cours']<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=$_SESSION['annee_scolaire']+1; } else { $annee=$_SESSION['annee_scolaire']; }
  }
  $premier_jour_mois=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours'],1,$annee));
  $premier_jour_mois_suivant=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours']+1,1,$annee));

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `absences_justificatifs` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['justificatifs'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['justificatifs'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
    $valeur_defaut=mysql_result($req,0,'id_eleve');
  }
  else
  {
    if (!isset($_GET['id_a']))
    {
      $tableau_variable['justificatifs']['date_debut']['value'] = date("Y-m-d");
      $tableau_variable['justificatifs']['date_fin']['value'] = date("Y-m-d");
      $valeur_defaut='';
    }
    else
    {
      $id_a=$_GET['id_a'];
      $l=explode("_",$id_a);
      $tableau_variable['justificatifs']['date_debut']['value'] = date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours'],$l[1],$annee));
      $tableau_variable['justificatifs']['date_fin']['value'] = date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours'],$l[1],$annee));
      $valeur_defaut=$l[0];
    }
  }

  $tpl = new template("absences");
  $tpl->set_file("gform","editview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['justificatifs'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var('ID_ELEVE',Liste_Eleve('id_eleve','form',$valeur_defaut,$_SESSION['id_classe_cours'],$premier_jour_mois_suivant,$premier_jour_mois));

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#EnregistrerFermer").button();
  $("#EnregistrerNouveau").button();
  $("#Annuler").button();
  $("#Enregistrer2").button();
  $("#EnregistrerFermer2").button();
  $("#EnregistrerNouveau2").button();
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
    action_save="edit";
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#motif"))) { bValid=false; }
    if (!checkRegexp($("#date_debut"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    if (!checkRegexp($("#date_fin"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    if (checkRegexp($("#date_debut"),<?php echo $date_regexp[$Format_Date_PHP]; ?>) && checkRegexp($("#date_fin"),<?php echo $date_regexp[$Format_Date_PHP]; ?>))
    {
      if (Compare_Date($("#date_debut").val(),$("#date_fin").val(),'<?php echo $Format_Date_PHP; ?>')<0)
      {
        $("#date_debut").addClass( "ui-state-error" );
        $("#date_fin").addClass( "ui-state-error" );
        bValid=false;
      }
      else
      {
        $("#date_debut").removeClass( "ui-state-error" );
        $("#date_fin").removeClass( "ui-state-error" );
      }
      if ($("#date_debut").val()==$("#date_fin").val())
      {
        if ($("#heure_debut").val()=="A" && $("#heure_fin").val()=="D")
        {
          $("#heure_debut").addClass( "ui-state-error" );
          $("#heure_fin").addClass( "ui-state-error" );
          bValid=false;
        }
        else
        {
          $("#heure_debut").removeClass( "ui-state-error" );
          $("#heure_fin").removeClass( "ui-state-error" );
        }
      }
    }
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","absences","<?php echo $Langue['LBL_MODIFIER_JUSTIFICATIF']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","absences","<?php echo $Langue['LBL_CREER_JUSTIFICATIF']; ?>");
        }
        else
        {
          updateTips("success","absences","<?php echo $Langue['LBL_MODIFIER_JUSTIFICATIF']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","absences","<?php echo $Langue['LBL_MODIFIER_JUSTIFICATIF']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="edit";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau2").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer2").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });
});
</script>
