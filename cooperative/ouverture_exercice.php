<div class="titre_page"><?php echo $Langue['LBL_SAISIE_OUVERTURE']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>

<?php
  $an=$_SESSION['cooperative_scolaire']-1;
  $req=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='$an'");
  $faire=true;
  if (mysql_num_rows($req)!="")
  {
    if (mysql_result($req,0,'clos')=="0") { $faire=false; }
  }
?>
	<table cellpadding=0 cellspacing=0 width=100% border=0>
	<tr>
<?php
  if ($faire==true)
  {
?>
		<td align=left width=100%>
		<input tabIndex=5 type="button" name="sauvegarder" id="sauvegarder" value="<?php echo $Langue['BTN_OUVRIR_EXERCICE']; ?>">
	  </td>
<?php
  }
  else
  {
?>
		<td align=left width=100%>&nbsp;</td>
<?php
  }
?>
	<td align=right valign=middle nowrap>
	<div class="ui-widget ui-state-default ui-corner-all floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_SAISIE_EXERCICE']; ?> : <select class="text ui-widget-content ui-corner-all" id="cooperative_scolaire" name="cooperative_scolaire">
	<?php
		for ($i=2010;$i<=$_SESSION['cooperative_scolaire']+2;$i++)
		{
		  if (table_ok($gestclasse_config['param_connexion']['base'],"cooperative".$i)==true)
		  {
				echo '<option value="'.$i.'"';
				if ($i==$_SESSION['cooperative_scolaire']) { echo ' SELECTED'; }
				$j=$i+1;
				echo '>'.$i.'-'.$j.'</option>';
		  }
		}
		
		$annee_avant=$_SESSION['cooperative_scolaire']-1;
		$banque=0;
		$banque_p=0;
		$caisse=0;
		$report=0;
		if (table_ok($gestclasse_config['param_connexion']['base'],"cooperative".$annee_avant)==true)
		{
		  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE banque='512' AND ligne_comptable NOT LIKE '5%'");
		  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE ligne_comptable='512'");
		  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE banque='512' AND ligne_comptable LIKE '5%'");
		  $banque=mysql_result($req,0,"total")+mysql_result($req2,0,"total")+Abs(mysql_result($req3,0,"total"));
		  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE banque='514' AND ligne_comptable NOT LIKE '5%'");
		  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE ligne_comptable='514'");
		  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE banque='514' AND ligne_comptable LIKE '5%'");
		  $banque_p=mysql_result($req,0,"total")+mysql_result($req2,0,"total")+Abs(mysql_result($req3,0,"total"));
		  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE banque='530' AND ligne_comptable NOT LIKE '5%'");
		  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE ligne_comptable='530'");
		  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE banque='530' AND ligne_comptable LIKE '5%'");
		  $caisse=mysql_result($req,0,"total")+mysql_result($req2,0,"total")+Abs(mysql_result($req3,0,"total"));
		  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_avant."` WHERE ligne_comptable NOT LIKE '5%' AND ligne_comptable<>'I'");
		  $report=mysql_result($req,0,"total");
		}	
	?>
		</select>
		</div>
	  </td>
	</tr>
	</table>
	<?php
  if ($faire==true)
  {
	  if ($mandataire=="M")
	  {
	?>
			<div id="msg_erreur"></div>
			<p class="explic"><?php echo $Langue['EXPL_OUVERTURE1']; ?></p>
			<p class="explic"><?php echo $Langue['EXPL_OUVERTURE2']; ?></p> 
			<form name="form_edit" id="form_edit" action="index2.php" method="POST">
			<input type="hidden" id="module" name="module" value="cooperative">
			<input type="hidden" id="action" name="action" value="save_ouverture">
			<table cellspacing=0 cellpadding=0 class="tableau_editview">
			<tr>
				<td class="centre" width=50% colspan=2><b><?php echo $Langue['LBL_BILAN_ACTIF']; ?></b></td>
				<td class="centre" width=50% colspan=2><b><?php echo $Langue['LBL_BILAN_PASSIF']; ?></b></td>
			</tr>
			<tr>
				<td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_512']; ?> :</label></td>
				<td class="gauche" width=35%><input tabIndex=1 type="text" class="text ui-widget-content ui-corner-all" name="banque" id="banque" style="text-align:right" size=15 maxlength=15 value="<?php echo number_format($banque,2,',',' '); ?>"> &euro;</td>
				<td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_110']; ?> <font color="#FF0000">*</font> :</label></td>
				<td class="gauche" width=35%><input type="text" tabIndex=4 class="text ui-widget-content ui-corner-all" name="report" id="report" style="text-align:right" size=15 maxlength=15 value="<?php echo number_format($report,2,',',' '); ?>"> &euro;</td>
			</tr>
			<tr>
				<td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_514']; ?> :</label></td>
				<td class="gauche" width=35%><input tabIndex=2 type="text" class="text ui-widget-content ui-corner-all" name="banque_p" id="banque_p" style="text-align:right" size=15 maxlength=15 value="<?php echo number_format($banque_p,2,',',' '); ?>"> &euro;</td>
				<td class="droite" width=15%>&nbsp;</td>
				<td class="gauche" width=35%>&nbsp;</td>
			</tr>
			<tr>
				<td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_530']; ?> :</label></td>
				<td class="gauche" width=35%><input tabIndex=3 type="text" class="text ui-widget-content ui-corner-all" name="caisse" id="caisse" style="text-align:right" size=15 maxlength=15 value="<?php echo number_format($caisse,2,',',' '); ?>"> &euro;</td>
				<td class="droite" width=15%>&nbsp;</td>
				<td class="gauche" width=35%>&nbsp;</td>
			</tr>
			<tr>
				<td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_TOTAL_ACTIF']; ?> :</label></td>
				<td class="gauche" width=35%><label class="label_detail" id="total">0,00 €</label></td>
				<td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_SAISIE_TOTAL_PASSIF']; ?> :</label></td>
				<td class="gauche" width=35%><label class="label_detail" id="total2">0,00 €</label></td>
			</tr>
			</table>
			</form>
<?php
	  }
	  else
	  {
?>
			<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong><?php echo $Langue['ERR_SAISIE_OUVERTURE']; ?></strong></div></div>
<?php
	  }
  }
  else
  {
?>
		<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong><?php echo $Langue['ERR_SAISIE_OUVERTURE2']; ?></strong></div></div>
<?php
  }
?>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/166-ouverture-dun-exercice-comptable.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/166-ouverture-dun-exercice-comptable.html","Aide");
<?php } ?>
  });

  $("#sauvegarder").button({<?php if ($mandataire=="M") { echo "disabled:false"; } else { echo "disabled:true"; } ?>});
  $("#sauvegarder").click(function()
  {
    if (Coop_Ouverture_Calcul_Total(2)==Coop_Ouverture_Calcul_Total2(2))
		{
			Message_Chargement(2,1);
  	  var $form = $('#form_edit');
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
				Message_Chargement(1,1);
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
		}
		else
		{
			$('#msg_erreur').html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_SAISIE_OUVERTURE3']; ?></strong></div></div>');
		}
  });

  $('#banque').change(function()
  {
		banque=$('#banque').val();
		banque_f=parseFloat(banque.replace(",",".").replace(" ",""));
	  $('#banque').val(number_format(banque_f,2,","," "));
    Coop_Ouverture_Calcul_Total(1);
  });
  $('#banque_p').change(function()
  {
		banque=$('#banque_p').val();
		banque_f=parseFloat(banque.replace(",",".").replace(" ",""));
	  $('#banque_p').val(number_format(banque_f,2,","," "));
    Coop_Ouverture_Calcul_Total(1);
  });
  $('#caisse').change(function()
  {
		banque=$('#caisse').val();
		banque_f=parseFloat(banque.replace(",",".").replace(" ",""));
	  $('#caisse').val(number_format(banque_f,2,","," "));
    Coop_Ouverture_Calcul_Total(1);
  });
  $('#report').change(function()
  {
		banque=$('#report').val();
		banque_f=parseFloat(banque.replace(",",".").replace(" ",""));
	  $('#report').val(number_format(banque_f,2,","," "));
    Coop_Ouverture_Calcul_Total2(1);
  });
  
  $('#cooperative_scolaire').change(function()
  {
     Message_Chargement(1,1);
     var url="cooperative/change_annee.php";
     var data="annee_choisi="+$("#cooperative_scolaire").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function()
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
     });
  });
});

function Coop_Ouverture_Calcul_Total(action)
{
  banque=$('#banque').val();
  banque_p=$('#banque_p').val();
  caisse=$('#caisse').val();
  banque_f=parseFloat(banque.replace(",",".").replace(" ",""));
  banque_p_f=parseFloat(banque_p.replace(",",".").replace(" ",""));
  caisse_f=parseFloat(caisse.replace(",",".").replace(" ",""));
  total=banque_f+banque_p_f+caisse_f;
  total=Math.round(total*100)/100;
  if (action==1)
  {
    $('#total').html(number_format(total,2,","," ")+" &euro;");
  }
  else
  {
    return total;
  }
}

function Coop_Ouverture_Calcul_Total2(action)
{
  report=$('#report').val();
  report_f=parseFloat(report.replace(",",".").replace(" ",""));
  report_f=Math.round(report_f*100)/100;
  if (action==1)
  {
    $('#total2').html(number_format(report_f,2,","," ")+" &euro;");
  }
  else
  {
    return report_f;
  }
}

<?php 
if ($faire==true) 
{ 
  if ($mandataire=="M") {
?>
  Coop_Ouverture_Calcul_Total(1);
  Coop_Ouverture_Calcul_Total2(1);
<?php } } ?>
</script>


