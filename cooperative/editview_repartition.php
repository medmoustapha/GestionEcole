<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_editview name=Detail>
<input type="hidden" id="module" name="module" value="cooperative">
<input type="hidden" id="action" name="action" value="save_repartition">
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
<div class="ui-widget ui-state-default ui-corner-all marge5_tout margin5_gauche textcentre"><?php echo $Langue['LBL_SAISIE_CREDITS_DISPO_REPARTIS']; ?></div>
<?php
  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='512' AND ligne_comptable NOT LIKE '5%' AND id_classe=''");
  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='512' AND id_classe='' AND id_classe=''");
  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='512' AND ligne_comptable LIKE '5%' AND id_classe=''");
  $total=mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total'));
  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='514' AND ligne_comptable NOT LIKE '5%' AND id_classe=''");
  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='514' AND id_classe=''");
  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='514' AND ligne_comptable LIKE '5%' AND id_classe=''");
  $total=$total+mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total'));
  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='530' AND ligne_comptable NOT LIKE '5%' AND id_classe=''");
  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='530' AND id_classe=''");
  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='530' AND ligne_comptable LIKE '5%' AND id_classe=''");
  $total=$total+mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total'));
  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='I'");
  $total=$total-mysql_result($req,0,'total');
?>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="droite" width=30%><label class="label_class"><?php echo $Langue['LBL_SAISIE_CREDITS_DISPO']; ?> :</label></td>
  <td class="gauche" width=20%><label class="label_detail"><?php echo number_format($total,2,',',' '); ?> &euro;</label></td>
  <td class="droite" width=30%><label class="label_class"><?php echo $Langue['LBL_SAISIE_CREDITS_REPARTIS']; ?> :</label></td>
  <td class="gauche" width=20%><label class="label_detail"><div id="total_repartition">0,00 &euro;</div></label></td>
</tr>
</table>

<div class="ui-widget ui-state-default ui-corner-all marge5_tout margin5_gauche textcentre"><?php echo $Langue['LBL_SAISIE_CREDITS_REPARTITION']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_CREDITS_MOTIF']; ?> <font color=#FF0000>*</font> :</label></td>
  <td class="gauche" width=85% colspan=3><span title="<?php echo $Langue['EXPL_SAISIE_CREDITS_MOTIF']; ?>"><input type="text" id="motif" name="motif" value="" size=100 maxlength=255 class="text ui-widget-content ui-corner-all"></span>
</tr>
<?php
  $req=mysql_query("SELECT * FROM `classes` WHERE annee='".$_SESSION['cooperative_scolaire']."' ORDER BY nom_classe ASC");
  $colonne=0;
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		if ($colonne==0) { echo '<tr>'; }
    echo '<td class="droite" width=15%><label class="label_class">'.mysql_result($req,$i-1,'nom_classe').' :</label></td>';
    echo '<td class="gauche" width=35%><span title="'.$Langue['EXPL_SAISIE_CREDITS_SOMME'].'"><input type="text" id="cl_'.mysql_result($req,$i-1,'id').'" name="'.mysql_result($req,$i-1,'id').'" value="0,00" size=15 maxlength=15 class="text ui-widget-content ui-corner-all"> &euro;</span></td>';
		if ($colonne==1) { echo '</tr>'; $colonne=0; } else { $colonne=1; }
  }
  if ($colonne==1)
  {
    echo '<td class="droite" width=50% colspan=2>&nbsp;</td></tr>';
  }
?>
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
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/168-repartir-les-credits-dans-les-classes.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/168-repartir-les-credits-dans-les-classes.html","Aide");
<?php } ?>
  });

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

  $("#form_editview").submit(function(event)
  {
    var total_dispo=<?php echo $total; ?>;
		var bValid = true;
		var results = $(this).serialize();
    if (!checkValue($("#motif"))) { bValid=false; }
    event.preventDefault();
    if ( bValid )
    {
			if (total_dispo>Coop_Repartition_Calcul_Total(1))
			{
				updateTips("save","cooperative","<?php echo $Langue['LBL_SAISIE_CREDITS_REPARTITION2']; ?>");
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
			else
			{
				$("#dialog-form").scrollTop(0);
				$("#msg_ok").fadeIn( 1000 );
				$("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_SAISIE_CREDITS_REPARTITION']; ?></strong></div></div>');
				setTimeout(function()
				{
					$("#msg_ok").effect("blind",1000);
				}, 3000 );	    
			}
    }
    else
    {
      updateTips("error","cooperative","<?php echo $Langue['LBL_SAISIE_CREDITS_REPARTITION2']; ?>");
      action_save="rien";
    }
  });

  $("#Enregistrer2").click(function()
  {
    $("#form_editview").submit();
  });
  
<?php
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
?>
    $('#cl_<?php echo mysql_result($req,$i-1,'id'); ?>').change(function()
		{
			Coop_Repartition_Calcul_Total(0);
		});
<?php
  }
?>
});

function Coop_Repartition_Calcul_Total(action)
{
  total=0;
<?php
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
?>
		t=$('#cl_<?php echo mysql_result($req,$i-1,'id'); ?>').val();
    total=total+parseFloat(t.replace(",",".").replace(" ",""));
<?php
  }
?>
  total=Math.round(total*100)/100;
  if (action==0)
  {
    total2=total.toString();
    $('#total_repartition').html(number_format(total2,2,',',' ')+" &euro;");
  }
  else
  {
    return total;
  }
}
</script>