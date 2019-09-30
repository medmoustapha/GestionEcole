<?php
  $req=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
?>

<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_caisse name=Detail>
<input type="hidden" id="module" name="module" value="cooperative">
<input type="hidden" id="action" name="action" value="save_caisse">
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input tabIndex=17 type="Submit" id="Enregistrer_Caisse" name="Enregistrer_Caisse" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input tabIndex=18 type="Button" id="Annuler_Caisse" name="Annuler_Caisse" value="<?php echo $Langue['BTN_ANNULER']; ?>">
		
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?>&nbsp;&nbsp;<button tabIndex=19 id="aide_caisse" name="aide_caisse"><?php echo $Langue['BTN_AIDE']; ?></button></td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td colspan=3 width=50% class="centre" style="border-right:1px solid #000000;font-weight:bold;"><?php echo $Langue['LBL_BILAN_BILLETS']; ?></td>
  <td colspan=3 width=50% class="centre" style="font-weight:bold;"><?php echo $Langue['LBL_BILAN_PIECES']; ?></td>
</tr>
<tr>
  <td class="centre" width=15% style="font-weight:bold;"><?php echo $Langue['LBL_BILAN_VALEUR']; ?></td>
  <td class="centre" width=20% style="font-weight:bold;"><?php echo $Langue['LBL_BILAN_NOMBRE']; ?></td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000"><?php echo $Langue['LBL_BILAN_TOTAL']; ?></td>
  <td class="centre" width=15% style="font-weight:bold;"><?php echo $Langue['LBL_BILAN_VALEUR']; ?></td>
  <td class="centre" width=20% style="font-weight:bold;"><?php echo $Langue['LBL_BILAN_NOMBRE']; ?></td>
  <td class="centre" width=15% style="font-weight:bold;"><?php echo $Langue['LBL_BILAN_TOTAL']; ?></td>
</tr>
<tr>
  <td class="centre" width=15%>200,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=1 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_200" name="nb_200" value="<?php echo mysql_result($req,0,'nb_200'); ?>"></td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000"><div id="total_200"><?php echo number_format(200*mysql_result($req,0,'nb_200'),2,',',' '); ?> &euro;</td>
  <td class="centre" width=15%>2,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=7 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_2" name="nb_2" value="<?php echo mysql_result($req,0,'nb_2'); ?>"></td>
  <td class="centre" width=15%><div id="total_2"><?php echo number_format(2*mysql_result($req,0,'nb_2'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
  <td class="centre" width=15%>100,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=2 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_100" name="nb_100" value="<?php echo mysql_result($req,0,'nb_100'); ?>"></td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000"><div id="total_100"><?php echo number_format(100*mysql_result($req,0,'nb_100'),2,',',' '); ?> &euro;</td>
  <td class="centre" width=15%>1,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=8 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_1" name="nb_1" value="<?php echo mysql_result($req,0,'nb_1'); ?>"></td>
  <td class="centre" width=15%><div id="total_1"><?php echo number_format(1*mysql_result($req,0,'nb_1'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
  <td class="centre" width=15%>50,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=3 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_50" name="nb_50" value="<?php echo mysql_result($req,0,'nb_50'); ?>"></td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000"><div id="total_50"><?php echo number_format(50*mysql_result($req,0,'nb_50'),2,',',' '); ?> &euro;</td>
  <td class="centre" width=15%>0,50 &euro;</td>
  <td class="centre" width=20%><input tabIndex=9 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_05" name="nb_05" value="<?php echo mysql_result($req,0,'nb_05'); ?>"></td>
  <td class="centre" width=15%><div id="total_05"><?php echo number_format(0.5*mysql_result($req,0,'nb_05'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
  <td class="centre" width=15%>20,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=4 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_20" name="nb_20" value="<?php echo mysql_result($req,0,'nb_20'); ?>"></td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000"><div id="total_20"><?php echo number_format(20*mysql_result($req,0,'nb_20'),2,',',' '); ?> &euro;</td>
  <td class="centre" width=15%>0,20 &euro;</td>
  <td class="centre" width=20%><input tabIndex=10 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_02" name="nb_02" value="<?php echo mysql_result($req,0,'nb_02'); ?>"></td>
  <td class="centre" width=15%><div id="total_02"><?php echo number_format(0.2*mysql_result($req,0,'nb_02'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
  <td class="centre" width=15%>10,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=5 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_10" name="nb_10" value="<?php echo mysql_result($req,0,'nb_10'); ?>"></td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000"><div id="total_10"><?php echo number_format(10*mysql_result($req,0,'nb_10'),2,',',' '); ?> &euro;</td>
  <td class="centre" width=15%>0,10 &euro;</td>
  <td class="centre" width=20%><input tabIndex=11 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_01" name="nb_01" value="<?php echo mysql_result($req,0,'nb_01'); ?>"></td>
  <td class="centre" width=15%><div id="total_01"><?php echo number_format(0.1*mysql_result($req,0,'nb_01'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
  <td class="centre" width=15%>5,00 &euro;</td>
  <td class="centre" width=20%><input tabIndex=6 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_5" name="nb_5" value="<?php echo mysql_result($req,0,'nb_5'); ?>"></td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000"><div id="total_5"><?php echo number_format(5*mysql_result($req,0,'nb_5'),2,',',' '); ?> &euro;</td>
  <td class="centre" width=15%>0,05 &euro;</td>
  <td class="centre" width=20%><input tabIndex=12 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_005" name="nb_005" value="<?php echo mysql_result($req,0,'nb_005'); ?>"></td>
  <td class="centre" width=15%><div id="total_005"><?php echo number_format(0.05*mysql_result($req,0,'nb_005'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
  <td class="centre" width=15%>&nbsp;</td>
  <td class="centre" width=20%>&nbsp;</td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000">&nbsp;</td>
  <td class="centre" width=15%>0,02 &euro;</td>
  <td class="centre" width=20%><input tabIndex=13 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_002" name="nb_002" value="<?php echo mysql_result($req,0,'nb_002'); ?>"></td>
  <td class="centre" width=15%><div id="total_002"><?php echo number_format(0.02*mysql_result($req,0,'nb_002'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
  <td class="centre" width=15%>&nbsp;</td>
  <td class="centre" width=20%>&nbsp;</td>
  <td class="centre" width=15% style="font-weight:bold;border-right:1px solid #000000">&nbsp;</td>
  <td class="centre" width=15%>0,01 &euro;</td>
  <td class="centre" width=20%><input tabIndex=14 type="text" size=7 maxlength=7 class="text ui-widget-content ui-corner-all" id="nb_001" name="nb_001" value="<?php echo mysql_result($req,0,'nb_001'); ?>"></td>
  <td class="centre" width=15%><div id="total_001"><?php echo number_format(0.01*mysql_result($req,0,'nb_001'),2,',',' '); ?> &euro;</td>
</tr>
<tr>
<?php
  $req_530=mysql_query("SELECT SUM(montant) as total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='530' AND ligne_comptable NOT LIKE '5%'");
  $req_531=mysql_query("SELECT SUM(montant) as total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='530'");
  $req_532=mysql_query("SELECT SUM(montant) as total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='530' AND ligne_comptable LIKE '5%'");
  $total=200*mysql_result($req,0,'nb_200')+100*mysql_result($req,0,'nb_100')+50*mysql_result($req,0,'nb_50')+20*mysql_result($req,0,'nb_20')+10*mysql_result($req,0,'nb_10')+5*mysql_result($req,0,'nb_5')+2*mysql_result($req,0,'nb_2')+1*mysql_result($req,0,'nb_1')+0.5*mysql_result($req,0,'nb_05')+0.2*mysql_result($req,0,'nb_02')+0.1*mysql_result($req,0,'nb_01')+0.05*mysql_result($req,0,'nb_005')+0.02*mysql_result($req,0,'nb_002')+0.01*mysql_result($req,0,'nb_001');
?>
  <td colspan=3 width=50% class="centre" style="font-weight:bold;"><?php echo $Langue['LBL_BILAN_SOLDE_530']; ?> : <?php if (mysql_num_rows($req_530)=="" && mysql_num_rows($req_531)=="" && mysql_num_rows($req_532)=="") { echo "0,00 &euro;"; } else { echo number_format(mysql_result($req_530,0,'total')+mysql_result($req_531,0,'total')+Abs(mysql_result($req_532,0,'total')),2,","," ")." &euro;"; } ?></td>
  <td colspan=3 width=50% class="centre" style="font-weight:bold;"><div id="total_arrete"><?php echo $Langue['LBL_BILAN_SOLDE_ARRETE']; ?> : <?php echo number_format($total,2,","," ")." &euro;"; ?></div></td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input tabIndex=15 type="Button" id="Enregistrer2_Caisse" name="Enrigistrer2_Caisse" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input tabIndex=16 type="Button" id="Annuler2_Caisse" name="Annuler2_Caisse" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide_caisse").button();
  $("#aide_caisse").click(function(event)
  {
		event.preventDefault();
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/167-saisir-le-contenu-de-la-caisse-en-especes.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/167-saisir-le-contenu-de-la-caisse-en-especes.html","Aide");
<?php } ?>
  });

  $("#Enregistrer_Caisse").button();
  $("#Enregistrer2_Caisse").button();
  $("#Annuler_Caisse").button();
  $("#Annuler2_Caisse").button();
  $("#Annuler_Caisse").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Annuler2_Caisse").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  
  $('#nb_200').change(function()
  {
    t=200*$('#nb_200').val();
		$('#total_200').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_100').change(function()
  {
    t=100*$('#nb_100').val();
		$('#total_100').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_50').change(function()
  {
    t=50*$('#nb_50').val();
		$('#total_50').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_20').change(function()
  {
    t=20*$('#nb_20').val();
		$('#total_20').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_10').change(function()
  {
    t=10*$('#nb_10').val();
		$('#total_10').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_5').change(function()
  {
    t=5*$('#nb_5').val();
		$('#total_5').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_2').change(function()
  {
    t=2*$('#nb_2').val();
		$('#total_2').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_1').change(function()
  {
    t=$('#nb_1').val();
		$('#total_1').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_05').change(function()
  {
    t=Math.round(0.5*$('#nb_05').val()*100)/100;
		$('#total_05').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_02').change(function()
  {
    t=Math.round(0.2*$('#nb_02').val()*100)/100;
		$('#total_02').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_01').change(function()
  {
    t=Math.round(0.1*$('#nb_01').val()*100)/100;
		$('#total_01').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_005').change(function()
  {
    t=Math.round(0.05*$('#nb_005').val()*100)/100;
		$('#total_005').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_002').change(function()
  {
    t=Math.round(0.02*$('#nb_002').val()*100)/100;
		$('#total_002').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  $('#nb_001').change(function()
  {
    t=Math.round(0.01*$('#nb_001').val()*100)/100;
		$('#total_001').html(number_format(t,2,","," ")+' &euro;');
		Coop_Caisse_Calcul_Total();
  });
  
  $("#Enregistrer2_Caisse").click(function()
  {
    $("#form_caisse").submit();
  });
  
  $("#form_caisse").submit(function(event)
  {
    Message_Chargement(2,1);
    event.preventDefault();
    var $form = $( this );
    url = $form.attr( 'action' );
    data = $form.serialize();
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
      Message_Chargement(1,1);
			$("#dialog-niveau2").dialog( "close" );
      Charge_Dialog("index2.php?module=cooperative&action=bilan_comptable","<?php echo $Langue['LBL_BILAN_COMPTABLE']; ?>");
		});
  });
});

function Coop_Caisse_Calcul_Total()
{
  t=200*$('#nb_200').val()+100*$('#nb_100').val()+50*$('#nb_50').val();
  t=t+20*$('#nb_20').val()+10*$('#nb_10').val()+5*$('#nb_5').val();
  t=t+2*$('#nb_2').val()+1*$('#nb_1').val()+0.5*$('#nb_05').val();
  t=t+0.2*$('#nb_02').val()+0.1*$('#nb_01').val()+0.05*$('#nb_005').val();
  t=t+0.02*$('#nb_002').val()+0.01*$('#nb_001').val();
  t=Math.round(t*100)/100;
  $('#total_arrete').html("<?php echo $Langue['LBL_BILAN_SOLDE_ARRETE']; ?> : "+number_format(t,2,","," ")+" &euro;");
}
</script>
