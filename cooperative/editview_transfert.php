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
    foreach ($tableau_variable['cooperative'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['cooperative'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
	if ($tableau_variable['cooperative']['montant']['value']<0)
	{
	  $tableau_variable['cooperative']['montant']['value']=-$tableau_variable['cooperative']['montant']['value'];
	}
  }
  else
  {
    $tableau_variable['cooperative']['date']['value'] = date("Y-m-d");
	$tableau_variable['cooperative']['id_classe']['value'] = '';
	$tableau_variable['cooperative']['montant']['value'] = 0;
  }

  $tpl = new template("cooperative");
  $tpl->set_file("gform","editview_transfert.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  
  $msg='<select tabindex=9 name="id_classe" class="text ui-widget-content ui-corner-all" id="id_classe">';
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

  $msg='<select name="banque2" class="text ui-widget-content ui-corner-all" id="banque2">';
  foreach ($liste_choix['cooperative_banque'] AS $cle => $value)
  {
	$msg=$msg.'<option value="'.$cle.'"';
	if ($tableau_variable['cooperative']['ligne_comptable']['value']==$cle) { $msg=$msg.' SELECTED'; }
	$msg=$msg.'>'.$value.'</option>';
  }
  $msg=$msg.'<select>';
  $tpl->set_var("BANQUE2",$msg);

//  $tpl->set_var("RELEVE_COMPTE",);
  
  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>

<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/169-faire-un-transfert-entre-la-banque-et-la-caisse.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/169-faire-un-transfert-entre-la-banque-et-la-caisse.html","Aide");
<?php } ?>
  });

  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer_Transfert").button();
  $("#Annuler_Transfert").button();
  $("#Enregistrer2_Transfert").button();
  $("#Annuler2_Transfert").button();
  $("#Annuler_Transfert").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2_Transfert").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  
  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer_Transfert").click(function()
  {
    action_save="detail";
  });
  $("#form_transfert").submit(function(event)
  {
	var bValid = true;
	var results = $(this).serialize();
    if (!checkValue($("#montant"))) { bValid=false; }
    if (!checkValue($("#libelle"))) { bValid=false; }
    if (!checkValue($("#piece"))) { bValid=false; }
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
      updateTips("save","cooperative","<?php echo $Langue['LBL_SAISIE_FICHE_TRANSFERT']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request2 = $.ajax({type: "POST", url: url, data: data});
      request2.done(function(msg)
      {
        $("#id").val(msg);
				Message_Chargement(2,1);
        Charge_Dialog("index2.php?module=cooperative&action=detailview_transfert&id="+msg,"<?php echo $Langue['LBL_SAISIE_FICHE_TRANSFERT']; ?>");
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","cooperative","<?php echo $Langue['LBL_SAISIE_FICHE_TRANSFERT']; ?>");
      action_save="rien";
    }
  });

  $("#Enregistrer2_Transfert").click(function()
  {
    action_save="detail";
    $("#form_transfert").submit();
  });
});
</script>
