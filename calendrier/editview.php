<?php
// Récupération des informations
  foreach ($tableau_variable['agenda'] AS $cle)
  {
    $tableau_variable['agenda'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `reunions` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['agenda'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['agenda'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
  }
  else
  {
    $tableau_variable['agenda']['date']['value'] = date("Y-m-d");
		$tableau_variable['agenda']['id_util']['value'] = $_SESSION['type_util'].$_SESSION['id_util'];
		$tableau_variable['agenda']['id_saisie']['value'] = $_SESSION['type_util'].$_SESSION['id_util'];
  }

  $tpl = new template("calendrier");
  $tpl->set_file("gform","editview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['agenda'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }

  $parametre=explode(',',$tableau_variable['agenda']['id_util']['value']);
  $msg='<select tabindex=6 name="id_util2[]" id="id_util2" multiple="multiple" size="10" style="width:500px">';
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=date("Y");
  }
  else
  {
    if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=date("Y")-1; } else { $annee=date("Y"); }
  }
	$msg .='<optgroup label="'.$Langue['LBL_ENSEIGNANTS'].'">'; 
	$req=mysql_query("SELECT * FROM `profs` WHERE (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."') AND (type='D' OR type='P') ORDER BY nom ASC, prenom ASC");
	for ($i=1;$i<=mysql_num_rows($req);$i++)
	{
		$msg=$msg.'<option value="'.mysql_result($req,$i-1,'type').mysql_result($req,$i-1,'id').'"';
	for ($j=0;$j<count($parametre);$j++)
	{
		if (mysql_result($req,$i-1,'type').mysql_result($req,$i-1,'id')==$parametre[$j]) { $msg .=' SELECTED'; }
	}
		$msg=$msg.'>'.mysql_result($req,$i-1,'profs.nom').' '.mysql_result($req,$i-1,'profs.prenom').'</option>';
	}
	$msg .='</optgroup>';
	$req=mysql_query("SELECT * FROM `classes` WHERE annee='".$annee."' ORDER BY nom_classe ASC");
	for ($i=1;$i<=mysql_num_rows($req);$i++)
	{
	  $msg .='<optgroup label="'.mysql_result($req,$i-1,'nom_classe').'">';
	  $id_classe=mysql_result($req,$i-1,'id');
		$req2=mysql_query("SELECT eleves.*,eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves.id=eleves_classes.id_eleve AND eleves_classes.id_classe='".$id_classe."' ORDER BY eleves.nom ASC, eleves.prenom ASC");
	  for ($j=1;$j<=mysql_num_rows($req2);$j++)
	  {
			$msg=$msg.'<option value="E'.mysql_result($req2,$j-1,'eleves.id').'"';
	    for ($k=0;$k<count($parametre);$k++)
	    {
				if ("E".mysql_result($req2,$j-1,'id')==$parametre[$k]) { $msg .=' SELECTED'; }
			}
			$msg=$msg.'>'.mysql_result($req2,$j-1,'eleves.nom').' '.mysql_result($req2,$j-1,'eleves.prenom').'</option>';
	  }
		$msg .='</optgroup>';
	}  
	$msg .='</select><br /><label class="petit">'.$Langue['MSG_SELECT_CLASSE'].'</label>';
  $tpl->set_var("ID_UTIL",$msg);
  
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

  $("#id_util2").multiselect(
  {
    header:true,
    selectAll: true,
    noneSelectedText: "<?php echo $Langue['MSG_SELECT_PERSONNES_CONCERNEES']; ?>",
    selectedText: "# <?php echo $Langue['MSG_SELECTED_PERSONNES_CONCERNEES']; ?>",
    checkAllText: "<?php echo $Langue['MSG_COCHER_TOUT']; ?>",
		uncheckAllText: "<?php echo $Langue['MSG_DECOCHER_TOUT']; ?>"
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="detail";
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#resume"))) { bValid=false; }
    if (!checkRegexp($("#date"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    if (Compare_Heure("01/01/2000 "+$("#heure_debut").val(),"01/01/2000 "+$("#heure_fin").val())<=0)
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
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","calendrier","<?php echo $Langue['LBL_FICHE_RDV']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
			ed = tinyMCE.get('detail');
			$('#detail_e').val(ed.getContent());
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","calendrier","<?php echo $Langue['LBL_FICHE_RDV']; ?>");
        }
        else
        {
          updateTips("success","calendrier","<?php echo $Langue['LBL_FICHE_RDV']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","calendrier","<?php echo $Langue['LBL_FICHE_RDV']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="detail";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau").button();
  $("#EnregistrerNouveau2").button();
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
