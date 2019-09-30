<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $jour_min=str_replace('-','',$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire']);
	  $jour_max=str_replace('-','',$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire']);
  }
  else
  {
	  $jour_min=str_replace('-','',$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire']);
	  $an=$_SESSION['annee_scolaire']+1;
	  $jour_max=str_replace('-','',$an.$gestclasse_config_plus['fin_annee_scolaire']);
  }
// Récupération des informations
  foreach ($tableau_variable['devoirs'] AS $cle)
  {
    $tableau_variable['devoirs'][$cle['nom']]['value'] = "";
  }

  $tableau_variable['devoirs']['date_donnee']['min']=$jour_min;
  $tableau_variable['devoirs']['date_faire']['min']=$jour_min;
  $tableau_variable['devoirs']['date_donnee']['max']=$jour_max;
  $tableau_variable['devoirs']['date_faire']['max']=$jour_max;

  if (isset($_GET['id']))
  // Si on souhaite modifier une séance
  {
    $req = mysql_query("SELECT * FROM `devoirs` WHERE id = '" . $_GET['id'] . "'");
    $tableau_variable['devoirs']['id_matiere']['idclasse']=mysql_result($req,0,'id_classe');
    foreach ($tableau_variable['devoirs'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['devoirs'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
  }
  else
  // Si on crée une nouvelle séance
  {
    if (isset($_GET['id_classe']))
    // Si on transmet la classe (quand on crée une séance après une autre)
    {
      $tableau_variable['devoirs']['id_classe']['value']=$_GET['id_classe'];
      $tableau_variable['devoirs']['id_matiere']['idclasse']=$_GET['id_classe'];
      $tableau_variable['devoirs']['id_niveau']['value']=$_GET['id_niveau'];
    }
    else
    // Sinon, on récupère la classe dont il est titulaire
    {
      $req2=mysql_query("SELECT classes.*,classes_profs.* FROM `classes`,`classes_profs` WHERE classes_profs.id_prof='".$_SESSION['id_util']."' AND classes_profs.id_classe=classes.id AND classes.annee='".$_SESSION['annee_scolaire']."' AND (classes_profs.type='T' OR classes_profs.type='E' OR classes_profs.type='D') ORDER BY classes_profs.type DESC");
      $tableau_variable['devoirs']['id_classe']['value']=mysql_result($req2,0,'classes.id');
      $tableau_variable['devoirs']['id_matiere']['idclasse']=mysql_result($req2,0,'classes.id');
    }
    $tableau_variable['devoirs']['date_faire']['value']=$_SESSION['date_en_cours'];
    $tableau_variable['devoirs']['date_donnee']['value']=$_SESSION['date_en_cours'];
  }

  // Affichage du cahier-journal
  $tpl = new template("cahier");
  $tpl->set_file("gform","editview_devoirs.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['devoirs'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  
  if (isset($_GET['id']))
  {
    $tpl->set_var("ID_CLASSE",'<label class="label_detail">'.Liste_Classes("id_classe","value",$_SESSION['annee_scolaire'],$tableau_variable['devoirs']['id_classe']['value'],$_SESSION['id_util'],false).'<input type="hidden" name=id_classe id=id_classe value="'.$tableau_variable['devoirs']['id_classe']['value'].'"></label>');
    $req3=mysql_query("SELECT * FROM `listes` WHERE id='".$tableau_variable['devoirs']['id_niveau']['value']."'");
    $tpl->set_var("ID_NIVEAU",'<label class="label_detail">'.mysql_result($req3,0,'intitule').'<input type="hidden" name="id_niveau2[]" id="id_niveau2" value="'.$tableau_variable['devoirs']['id_niveau']['value'].'"></label>');
  }
  else
  {
    $tpl->set_var("ID_CLASSE",Liste_Classes("id_classe","form",$_SESSION['annee_scolaire'],$tableau_variable['devoirs']['id_classe']['value'],$_SESSION['id_util'],false));
    $tpl->set_var("ID_NIVEAU",Liste_Niveaux("id_niveau2","form",$tableau_variable['devoirs']['id_niveau']['value'],$tableau_variable['devoirs']['id_classe']['value'],true,'2'));
  }
  $tpl->set_var("ID_PROF_E",$_SESSION['id_util']);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#EnregistrerNouveau").button();
  $("#Annuler").button();
  $("#Enregistrer2").button();
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

  /* Quand on change la classe */
  $("#id_classe").change(function()
  {
    id_classe=$("#id_classe").val();
    if (id_classe!="")
    {
      $("#matiere").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
      $("#niveau").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
      var request = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=change_niveau&valeur_defaut=<?php echo $tableau_variable['devoirs']['id_niveau']['value']; ?>&id_classe="+id_classe });
      var request2 = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=change_matiere&valeur_defaut=<?php echo $tableau_variable['devoirs']['id_matiere']['value']; ?>&id_classe="+id_classe });
      request.done(function(msg)
      {
        $("#niveau").html(msg);
      });
      request2.done(function(msg)
      {
        $("#matiere").html(msg);
      });
    }
  });
  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="edit";
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		var results = $(this).serialize();
    if (results.indexOf("id_niveau2",0)==-1) { $("#id_niveau2").addClass( "ui-state-error" );bValid=false; } else { $("#id_niveau2").removeClass( "ui-state-error" ); }
    if (!checkValue($("#id_classe"))) { bValid=false; }
    if (!checkValue($("#contenu"))) { bValid=false; }
    if (!checkRegexp($("#date_donnee"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    if (!checkRegexp($("#date_faire"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    /* Si une date pour les devoirs est spécifiée alors on regarde s'il y a un contenu et inversement */
    if (checkRegexp($("#date_donnee"),<?php echo $date_regexp[$Format_Date_PHP]; ?>) && checkRegexp($("#date_faire"),<?php echo $date_regexp[$Format_Date_PHP]; ?>))
    {
      if (Compare_Date($("#date_donnee").val(),$("#date_faire").val(),'<?php echo $Format_Date_PHP; ?>')<=0)
      {
        $("#date_donnee").addClass( "ui-state-error" );
        $("#date_faire").addClass( "ui-state-error" );
        bValid=false;
      }
      else
      {
        $("#date_donnee").removeClass( "ui-state-error" );
        $("#date_faire").removeClass( "ui-state-error" );
      }
    }
    
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","cahier","<?php echo $Langue['LBL_MODIFIER_DEVOIRS']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          Message_Chargement(1,1);
          decoupe=msg.split('-');
          Charge_Dialog("index2.php?module=cahier&action=editview_devoirs&id_classe="+decoupe[0]+"&id_niveau="+decoupe[1],"<?php echo $Langue['LBL_CREER_NOUVEAUX_DEVOIRS']; ?>");
        }
        else
        {
          updateTips("success","cahier","<?php echo $Langue['LBL_MODIFIER_DEVOIRS']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","cahier","<?php echo $Langue['LBL_MODIFIER_DEVOIRS']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer").click(function()
  {
    action_save="fermer";
  });

  $("#Enregistrer2").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau").click(function()
  {
    action_save="nouveau";
    $("#afaire").val('nouveau');
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau2").click(function()
  {
    action_save="nouveau";
    $("#afaire").val('nouveau');
    $("#form_editview").submit();
  });
});
</script>
