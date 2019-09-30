<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_editview name=Detail>
<input type="hidden" id="module" name="module" value="cooperative">
<input type="hidden" id="action" name="action" value="save_rapprochement">
<input type="hidden" id="id" name="id" value="{ID}">
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer" name="Enrigistrer" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Annuler" name="Annuler" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?>&nbsp;&nbsp;&nbsp;<button id="aide_fenetre" name="aide_fenetre"><?php echo $Langue['BTN_AIDE']; ?></button></td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_SAISIE_RELEVE_COMPTE']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_RELEVE_COMPTE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><span title="<?php echo $Langue['EXPL_SAISIE_RELEVE_COMPTE2']; ?>"><input type="text" class="text ui-widget-content ui-corner-all" id="releve" name="releve" value="" size=50 maxlength=255></span></td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_SAISIE_POINTAGE']; ?></div>
<table class="display" cellspacing=0 cellpadding=0 width=100% border=0>
<thead>
<tr>
  <th width=10% class="centre" class="ui-state-default"><?php echo $Langue['LBL_SAISIE_DATE']; ?></th>
  <th width=45% class="centre" class="ui-state-default"><?php echo $Langue['LBL_SAISIE_LIBELLE']; ?></th>
  <th width=10% class="centre" class="ui-state-default"><?php echo $Langue['LBL_SAISIE_MODE_PAIEMENT2']; ?></th>
  <th width=14% class="centre" class="ui-state-default"><?php echo $Langue['LBL_SAISIE_DEBIT']; ?></th>
  <th width=14% class="centre" class="ui-state-default"><?php echo $Langue['LBL_SAISIE_CREDIT']; ?></th>
  <th width=7% class="centre" class="ui-state-default"><?php echo $Langue['LBL_SAISIE_POINTE']; ?></th>
</tr>
</thead>
<tbody>
<?php
  $req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE pointe='0' AND ((banque='512' OR banque='514') OR (ligne_comptable='512' OR ligne_comptable='514')) ORDER BY date ASC");
  $ligne="even";
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    echo '<tr class="'.$ligne.'">';
	echo '<td class="centre">'.Date_Convertir(mysql_result($req,$i-1,'date'),"Y-m-d",$Format_Date_PHP).'</td>';
	if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
	{
		if (mysql_result($req,$i-1,'tiers')!='')
		{
		  $req_liste=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,$i-1,'tiers')."'");
		  $msg=mysql_result($req_liste,0,'nom').'<br />';
		}
		else
		{
		  $msg='';
		}
		$msg=$msg.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].'<br />';
		$msg=$msg.mysql_result($req,$i-1,'libelle');
	}
	else
	{
	  $msg=$Langue['LBL_JOURNAL_TRANSFERT']."<br />".$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')]." ".$Langue['LBL_JOURNAL_TRANSFERT_VERS']." ".$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')]."<br>".mysql_result($req,$i-1,'libelle');
	}
	echo '<td class="gauche">'.$msg.'</td>';
	echo '<td class="centre">'.$liste_choix['cooperative_mode'][mysql_result($req,$i-1,'mode')];
	if (mysql_result($req,$i-1,'reference_bancaire')!="") { echo '<br>'.mysql_result($req,$i-1,'reference_bancaire'); }
	echo '</td>';
	if (mysql_result($req,$i-1,'banque')=="512" || mysql_result($req,$i-1,'banque')=="514")
	{
	  if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
	  {
		if (mysql_result($req,$i-1,'montant')<0)
		{
		  echo '<td class="droite">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' &euro;</td>';
		  echo '<td class="droite">&nbsp;</td>';
		}
		else
		{
		  echo '<td class="droite">&nbsp;</td>';
		  echo '<td class="droite">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' &euro;</td>';
		}
	  }
	  else
	  {
		  echo '<td class="droite">&nbsp;</td>';
		  echo '<td class="droite">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' &euro;</td>';
	  }
	}
	else
	{
		  echo '<td class="droite">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' &euro;</td>';
		  echo '<td class="droite">&nbsp;</td>';
	}
	echo '<td class="centre"><input type="checkbox" id="req_'.mysql_result($req,$i-1,'id').'" name="pointage[]" value="'.mysql_result($req,$i-1,'id').'"></td></tr>';
	if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
  }
?>
</tbody>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer2" name="Enrigistrer2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Annuler2" name="Annuler2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/170-faire-un-rapprochement-bancaire.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/170-faire-un-rapprochement-bancaire.html","Aide");
<?php } ?>
  });

  $("#Enregistrer").button();
  $("#Enregistrer2").button();
  $("#Annuler").button();
  $("#Annuler2").button();

  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		var results = $(this).serialize();
    event.preventDefault();
    if (!checkValue($("#releve"))) { bValid=false; }
    if ( bValid )
    {
			if (results.indexOf("pointage",0)==-1)
			{
				$("#dialog-form").scrollTop(0);
				$("#msg_ok").fadeIn( 1000 );
				$("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_RAPPROCHEMENT']; ?></strong></div></div>');
				setTimeout(function()
				{
					$("#msg_ok").effect("blind",1000);
				}, 3000 );
			}
			else
			{
				updateTips("save","cooperative","<?php echo $Langue['LBL_SAISIE_RAPPROCHEMENT_BANCAIRE']; ?>");
				var $form = $( this );
				url = $form.attr( 'action' );
				data = $form.serialize();
				var request = $.ajax({type: "POST", url: url, data: data});
				request.done(function(msg)
				{
					$("#dialog-form").dialog( "close" );
					$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
				});
	    }
    }
    else
    {
      updateTips("error","cooperative","<?php echo $Langue['LBL_SAISIE_RAPPROCHEMENT_BANCAIRE']; ?>");
      action_save="rien";
    }
  });

  $("#Enregistrer2").click(function()
  {
    $("#form_editview").submit();
  });
});
</script>
  