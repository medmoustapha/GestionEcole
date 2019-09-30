<?php
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_repartition'");
  $gestclasse_config_plus['cooperative_repartition']=mysql_result($req_coop,0,'valeur');

  // Récupération des informations
  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tableau_variable['cooperative'][$cle['nom']]['value'] = "";
  }

  $type="E";
  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['cooperative'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['cooperative'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
	if ($tableau_variable['cooperative']['montant']['value']<0)
	{
	  $tableau_variable['cooperative']['montant']['value']=-$tableau_variable['cooperative']['montant']['value'];
	  $type="D";
	}
  }
  else
  {
    $tableau_variable['cooperative']['date']['value'] = date("Y-m-d");
	$tableau_variable['cooperative']['id_classe']['value'] = '';
	$tableau_variable['cooperative']['montant']['value'] = 0;
  }

  $tpl = new template("cooperative");
  $tpl->set_file("gform","editview_ouverture.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  
  $msg='<select name="type" class="text ui-widget-content ui-corner-all" id="type">';
  $msg=$msg.'<option value="E"';
  if ($type=="E") { $msg=$msg.' SELECTED'; }
  $msg=$msg.'>'.$Langue['LBL_SAISIE_ENTREE'].'</option>';
  $msg=$msg.'<option value="D"';
  if ($type=="D") { $msg=$msg.' SELECTED'; }
  $msg=$msg.'>'.$Langue['LBL_SAISIE_DEPENSE'].'</option>';
  $msg=$msg.'<select>';
  $tpl->set_var("TYPE",$msg);
  
  $msg='<select name="id_classe" class="text ui-widget-content ui-corner-all" id="id_classe">';
  $msg=$msg.'<option value=""';
  if ($tableau_variable['cooperative']['id_classe']['value']=="") { $msg=$msg.' SELECTED'; }
  $msg=$msg.'>'.$Langue['LBL_SAISIE_POT_COMMUN'].'</option>';
  if ($gestclasse_config_plus['cooperative_repartition']=="E")
  {
	  $req_classe=mysql_query("SELECT * FROM `classes` WHERE annee='".$_SESSION['cooperative_scolaire']."' ORDER BY nom_classe ASC");
	  for ($i=1;$i<=mysql_num_rows($req_classe);$i++)
	  {
		$msg=$msg.'<option value="'.mysql_result($req_classe,$i-1,'id').'"';
		if ($tableau_variable['cooperative']['id_classe']['value']==mysql_result($req_classe,$i-1,'id')) { $msg=$msg.' SELECTED'; }
		$msg=$msg.'>'.mysql_result($req_classe,$i-1,'nom_classe').'</option>';
	  }
  }
  $msg=$msg.'<select>';
  $tpl->set_var("ID_CLASSE",$msg);

//  $tpl->set_var("RELEVE_COMPTE",);
  
  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>

<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#creer_tiers").button();
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
  
  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="detail";
  });
  $("#form_editview").submit(function(event)
  {
	var bValid = true;
	var results = $(this).serialize();
    if (!checkValue($("#montant"))) { bValid=false; }
    if (!checkValue($("#libelle"))) { bValid=false; }
    if (!checkValue($("#piece"))) { bValid=false; }
	if (parseFloat($("#montant").val().replace(',','.'))==0)
	{
	  bValid=false;
	  $("#montant").addClass( "ui-state-error" );
	}
	else
	{
	  $("#montant").removeClass( "ui-state-error" );
	}
	if (($("#banque").val()!="" && $("#mode").val()=="") || ($("#banque").val()=="" && $("#mode").val()!=""))
	{
	  bValid=false;
	  $("#banque").addClass( "ui-state-error" );
	  $("#mode").addClass( "ui-state-error" );
	}
	else
	{
	  $("#banque").removeClass( "ui-state-error" );
	  $("#mode").removeClass( "ui-state-error" );
	}
	if (($("#pointe").val()=="1" && $("#releve").val()=="") || ($("#pointe").val()=="0" && $("#releve").val()!=""))
	{
	  bValid=false;
	  $("#pointe").addClass( "ui-state-error" );
	  $("#releve").addClass( "ui-state-error" );
	}
	else
	{
	  $("#pointe").removeClass( "ui-state-error" );
	  $("#releve").removeClass( "ui-state-error" );
	}
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","cooperative","Fiche d'une ouverture d'exercice");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
		if (action_save=="detail")
		{
          Charge_Dialog("index2.php?module=cooperative&action=detailview_ouverture&id="+msg,"<?php echo $Langue['LBL_SAISIE_FICHE_OUVERTURE']; ?>");
		}
		else
		{
  	      Message_Chargement(1,1);
		  $("#dialog-form").dialog( "close" );
		}
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","cooperative","<?php echo $Langue['LBL_SAISIE_FICHE_OUVERTURE']; ?>");
      action_save="rien";
    }
  });

  $("#Enregistrer2").click(function()
  {
    action_save="detail";
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
