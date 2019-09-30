<?php
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_repartition'");
  $gestclasse_config_plus['cooperative_repartition']=mysql_result($req_coop,0,'valeur');

  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  $clos=mysql_result($req_bilan,0,'clos');


  $annee_a=$_SESSION['cooperative_scolaire']+1;
  $msg='<div class="titre_page">'.$Langue['LBL_JOURNAL2'].' '.$_SESSION['cooperative_scolaire'].'/'.$annee_a.'</div><br /><br />';
  $msg=$msg.'<table cellspacing=0 cellpadding=0 style="width:100%;border-bottom:2px solid #000000;border-left:2px solid #000000;"><thead>';
  $msg=$msg.'<tr><th class="centre" style="padding:3px;width:12%;border-top:2px solid #000000;border-right:2px solid #000000;background:#c0c0c0;">'.$Langue['LBL_SAISIE_DATE'].'</th>';
  $msg=$msg.'<th class="centre" style="padding:3px;width:44%;border-top:2px solid #000000;border-right:2px solid #000000;background:#c0c0c0;">'.$Langue['LBL_SAISIE_LIBELLE'].'</th>';
  $msg=$msg.'<th class="centre" style="padding:3px;width:20%;border-top:2px solid #000000;border-right:2px solid #000000;background:#c0c0c0;">'.$Langue['LBL_SAISIE_BANQUE_MODE'].'</th>';
  $msg=$msg.'<th class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;background:#c0c0c0;">'.$Langue['LBL_SAISIE_ENTREE'].'</th>';
  $msg=$msg.'<th class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;background:#c0c0c0;">'.$Langue['LBL_SAISIE_SORTIE'].'</th>';
  $msg=$msg.'<th class="centre" style="padding:3px;width:4%;border-top:2px solid #000000;border-right:2px solid #000000;background:#c0c0c0;">P</th></tr></thead><tbody>';
  $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable<>'I' ORDER BY date ASC, ligne_comptable ASC");

  $nbr_lignage = mysql_num_rows($req);
  $total_debit=0;
  $total_credit=0;
  $total=0;
  $total_pointe=0;
  $total_nonpointe=0;
  for ($i=1;$i<=$nbr_lignage;$i++)
  {
    $msg=$msg.'<tr><td class="centre" style="padding:3px;width:12%;border-top:2px solid #000000;border-right:2px solid #000000;">'.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).'</td>';
    $msg=$msg.'<td class="gauche" style="padding:3px;width:44%;border-top:2px solid #000000;border-right:2px solid #000000;">';
	if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
	{
		if (mysql_result($req,$i-1,'tiers')!="")
		{
		  $req_liste=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,$i-1,'tiers')."'");
		  $msg=$msg.mysql_result($req,$i-1,'tiers').' - '.mysql_result($req_liste,0,'nom').'<br />';
		}
		$msg=$msg.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].'<br />';
		$msg=$msg.mysql_result($req,$i-1,'libelle');
		$msg=$msg.'</td><td class="centre" style="padding:3px;width:20%;border-top:2px solid #000000;border-right:2px solid #000000;">'.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')].'<br />'.$liste_choix['cooperative_mode'][mysql_result($req,$i-1,'mode')].'</td>';
		if (mysql_result($req,$i-1,'montant')<0)
		{
		  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;">&nbsp;</td>';
		  $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' EURO</td>';
		  $total_debit=$total_debit+Abs(mysql_result($req,$i-1,'montant'));
		  if (mysql_result($req,$i-1,'banque')!="") { $total=$total-Abs(mysql_result($req,$i-1,'montant')); }
		}
		else
		{
		  $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' EURO</td>';
		  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;">&nbsp;</td>';
		  $total_credit=$total_credit+Abs(mysql_result($req,$i-1,'montant'));
		  if (mysql_result($req,$i-1,'banque')!="") { $total=$total+Abs(mysql_result($req,$i-1,'montant')); }
		}
	}
	else
	{
		$msg=$msg.$Langue['LBL_JOURNAL_TRANSFERT'].'<br />'.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].' '.$Langue['LBL_JOURNAL_TRANSFERT_VERS'].' '.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')].'<br />';
		$msg=$msg.mysql_result($req,$i-1,'libelle');
		$msg=$msg.'</td><td class="centre" style="padding:3px;width:20%;border-top:2px solid #000000;border-right:2px solid #000000;">'.$Langue['LBL_JOURNAL_TRANSFERT'].'</td>';
	    $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' EURO</td>';
	    $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' EURO</td>';
	    $total_debit=$total_debit+Abs(mysql_result($req,$i-1,'montant'));
	    $total_credit=$total_credit+Abs(mysql_result($req,$i-1,'montant'));
	}
	if (mysql_result($req,$i-1,'pointe')=="1")
	{
	  if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
	  {
	    $total_pointe=$total_pointe+mysql_result($req,$i-1,'montant');
	  }
 	  $msg=$msg.'<td class="centre" style="padding:3px;width:4%;border-top:2px solid #000000;border-right:2px solid #000000;">P</td></tr>';
	}
	else
	{
 	  $msg=$msg.'<td class="centre" style="padding:3px;width:4%;border-top:2px solid #000000;border-right:2px solid #000000;">&nbsp;</td></tr>';
	}
  }
	$msg=$msg.'</tbody></table><br><br>';

	$msg=$msg.'<table cellspacing=0 cellpadding=0 border=0 style="width:100%;">';
	$msg=$msg.'<tr>';
	$msg=$msg.'<td style="width:10%">&nbsp;</td>';
	$msg=$msg.'<td class="centre" style="width:35%;border:2px solid #000000;padding:5px;font-weight:bold;font-size:16px;">'.$Langue['LBL_JOURNAL_SOLDE_POINTE'].' : '.number_format($total_pointe,2,',',' ').' EURO</td>';
	$msg=$msg.'<td style="width:10%">&nbsp;</td>';
	$msg=$msg.'<td class="centre" style="width:35%;border:2px solid #000000;padding:5px;font-weight:bold;font-size:16px;">'.$Langue['LBL_JOURNAL_SOLDE_COURANT'].' : '.number_format($total,2,',',' ').' EURO</td>';
	$msg=$msg.'<td style="width:10%">&nbsp;</td>';
	$msg=$msg.'</tr>';
	$msg=$msg.'</table>';
	
	$contenu_page=$msg;
?>
