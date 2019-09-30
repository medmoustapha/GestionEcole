<?php
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_repartition'");
  $gestclasse_config_plus['cooperative_repartition']=mysql_result($req_coop,0,'valeur');

  // Récupération des informations
  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tableau_variable['cooperative'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE id = '" . $_GET['id'] . "'");
	$tableau_variable['cooperative']['montant']['value']=mysql_result($req,0,'montant');
	$tableau_variable['cooperative']['libelle']['value']=mysql_result($req,0,'libelle');
	$tableau_variable['cooperative']['date']['value']=mysql_result($req,0,'date');
	$tableau_variable['cooperative']['id_classe']['value']=mysql_result($req,0,'id_classe');
  }

  $tpl = new template("cooperative");
  $tpl->set_file("gform","editview_interne.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  $tpl->set_var("MONTANT", Variables_Form($tableau_variable['cooperative']['montant']));
  $tpl->set_var("LIBELLE", Variables_Form($tableau_variable['cooperative']['libelle']));
  $tpl->set_var("DATE", Variables_Form($tableau_variable['cooperative']['date']));
  $tpl->set_var("ID", $_GET['id']);
  
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
  $("#Enregistrer_Interne").button();
  $("#EnregistrerFermer_Interne").button();
  $("#Annuler_Interne").button();
  $("#Enregistrer2_Interne").button();
  $("#EnregistrerFermer2_Interne").button();
  $("#Annuler2_Interne").button();
  $("#Annuler_Interne").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Annuler2_Interne").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  
  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer_Interne").click(function()
  {
    action_save="detail";
  });
  $("#form_interne").submit(function(event)
  {
	var bValid = true;
	var results = $(this).serialize();
    if (!checkValue($("#montant"))) { bValid=false; }
    if (!checkValue($("#libelle"))) { bValid=false; }
    if (!checkRegexp($("#date"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
	if (parseFloat($("#montant").val().replace(',','.'))==0)
	{
	  bValid=false;
	  $("#montant").addClass( "ui-state-error" );
	}
	else
	{
	  $("#montant").removeClass( "ui-state-error" );
	}
    event.preventDefault();
    if ( bValid )
    {
	  Message_Chargement(2,1);
	  var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
	    Message_Chargement(1,1);
		if (action_save=="detail")
		{
		  Charge_Dialog2("index2.php?module=cooperative&action=detailview_interne&id="+msg,"<?php echo $Langue['LBL_SAISIE_FICHE_FONCTIONNEMENT_INTERNE']; ?>");
		}
		else
		{
		  $("#dialog-niveau2").dialog( "close" );
		}
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
  	  $("#msg_interne").fadeIn( 1000 );
  	  $("#msg_interne").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
	  setTimeout(function()
	  {
		$("#msg_interne").effect("blind",1000);
	  }, 3000 );
      action_save="rien";
    }
  });

  $("#Enregistrer2_Interne").click(function()
  {
    action_save="detail";
    $("#form_interne").submit();
  });

  $("#EnregistrerFermer_Interne").click(function()
  {
    action_save="fermer";
    $("#form_interne").submit();
  });

  $("#EnregistrerFermer2_Interne").click(function()
  {
    action_save="fermer";
    $("#form_interne").submit();
  });
});
</script>
