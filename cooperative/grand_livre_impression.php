<?php
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_repartition'");
  $gestclasse_config_plus['cooperative_repartition']=mysql_result($req_coop,0,'valeur');

  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  $clos=mysql_result($req_bilan,0,'clos');

  $tableau_categorie=Array();
  $tableau_libelle=Array();
  $tableau_piece=Array();
  $tableau_date=Array();
  $tableau_debit=Array();
  $tableau_credit=Array();
  $req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable<>'I'");
  $place=0;
  $grand_total_debit=0;
  $grand_total_credit=0;
  $cumul_total_debit=0;	
  $cumul_total_credit=0;
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
	{
		// Ligne comptable
		$tableau_categorie[$place]=mysql_result($req,$i-1,'ligne_comptable');
		$tableau_libelle[$place]=mysql_result($req,$i-1,'libelle');
		$tableau_piece[$place]=mysql_result($req,$i-1,'piece');
		$tableau_date[$place]=mysql_result($req,$i-1,'date');
		$tableau_debit[$place]=0;
		$tableau_credit[$place]=0;
		if (mysql_result($req,$i-1,'montant')<0)
		{
		  $tableau_debit[$place]=Abs(mysql_result($req,$i-1,'montant'));
		}
		else
		{
		  $tableau_credit[$place]=Abs(mysql_result($req,$i-1,'montant'));
		}
		$place++;
		
		// Si tiers défini
		if (mysql_result($req,$i-1,'tiers')!="")
		{
		  $tableau_categorie[$place]=mysql_result($req,$i-1,'tiers');
		  $tableau_libelle[$place]=mysql_result($req,$i-1,'libelle');
		  $tableau_piece[$place]=mysql_result($req,$i-1,'piece');
		  $tableau_date[$place]=mysql_result($req,$i-1,'date');
		  $tableau_debit[$place]=0;
		  $tableau_credit[$place]=0;
		  if (mysql_result($req,$i-1,'montant')<0)
		  {
			$tableau_credit[$place]=Abs(mysql_result($req,$i-1,'montant'));
		  }
		  else
		  {
			$tableau_debit[$place]=Abs(mysql_result($req,$i-1,'montant'));
		  }
		  if (mysql_result($req,$i-1,'banque')!="")
		  {
			  if (mysql_result($req,$i-1,'montant')<0)
			  {
				$tableau_debit[$place]=Abs(mysql_result($req,$i-1,'montant'));
			  }
			  else
			  {
				$tableau_credit[$place]=Abs(mysql_result($req,$i-1,'montant'));
			  }
		  }
		  $place++;
		}
		
		// Si banque définie
		if (mysql_result($req,$i-1,'banque')!="")
		{
			$tableau_categorie[$place]=mysql_result($req,$i-1,'banque');
			$tableau_libelle[$place]=mysql_result($req,$i-1,'libelle');
			$tableau_piece[$place]=mysql_result($req,$i-1,'reference_bancaire');
			$tableau_date[$place]=mysql_result($req,$i-1,'date');
			$tableau_debit[$place]=0;
			$tableau_credit[$place]=0;
			if (mysql_result($req,$i-1,'montant')<0)
			{
			  $tableau_credit[$place]=Abs(mysql_result($req,$i-1,'montant'));
			}
			else
			{
			  $tableau_debit[$place]=Abs(mysql_result($req,$i-1,'montant'));
			}
			$place++;
		}
	}
	else
	{
		$tableau_categorie[$place]=mysql_result($req,$i-1,'ligne_comptable');
		$tableau_libelle[$place]=mysql_result($req,$i-1,'libelle');
		$tableau_piece[$place]=mysql_result($req,$i-1,'piece');
		$tableau_date[$place]=mysql_result($req,$i-1,'date');
		$tableau_debit[$place]=0;
  	    $tableau_credit[$place]=Abs(mysql_result($req,$i-1,'montant'));
		$place++;
		$tableau_categorie[$place]=mysql_result($req,$i-1,'banque');
		$tableau_libelle[$place]=mysql_result($req,$i-1,'libelle');
		$tableau_piece[$place]=mysql_result($req,$i-1,'piece');
		$tableau_date[$place]=mysql_result($req,$i-1,'date');
		$tableau_credit[$place]=0;
  	    $tableau_debit[$place]=Abs(mysql_result($req,$i-1,'montant'));
		$place++;
	}	
  }
  
  array_multisort($tableau_categorie,SORT_ASC, SORT_STRING,$tableau_date,SORT_ASC, SORT_STRING,$tableau_libelle,$tableau_piece,$tableau_debit,$tableau_credit);
  
  $tiers="";
  $total_debit=0;
  $total_credit=0;
  $annee_a=$_SESSION['cooperative_scolaire']+1;
  $msg='<div class="titre_page">'.$Langue['LBL_GL_IMPRESSION_TITRE'].' '.$_SESSION['cooperative_scolaire'].'/'.$annee_a.'</div><br /><br />';
  $msg=$msg.'<table cellspacing=0 cellpadding=0 style="width:100%;border-bottom:2px solid #000000;border-left:2px solid #000000;">';
  $msg=$msg.'<thead><tr><td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.$Langue['LBL_SAISIE_DATE'].'</td>';
  $msg=$msg.'<td class="centre" style="padding:3px;width:40%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.$Langue['LBL_SAISIE_LIBELLE'].'</td>';
  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.$Langue['LBL_GL_PIECE'].'</td>';
  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.$Langue['LBL_GL_MONTANT_DEBIT'].'</td>';
  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.$Langue['LBL_GL_MONTANT_CREDIT'].'</td>';
  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.$Langue['LBL_GL_CUMUL_DEBIT'].'</td>';
  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.$Langue['LBL_GL_CUMUL_CREDIT'].'</td></tr></thead><tbody>';
  for ($i=0;$i<$place;$i++)
  {
    if ($tiers!=$tableau_categorie[$i])
	{
	  if ($i!=0)
	  {
		$msg=$msg.'<tr><td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">&nbsp;</td>';
		$msg=$msg.'<td colspan=2 class="droite" style="padding:3px;width:50%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold"><b>'.$Langue['LBL_GL_TOTAL_COMPTE'].'</b></td>';
		$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_debit),2,',',' ').' EURO</td>';
		$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_credit),2,',',' ').' EURO</td>';
		if ($total_debit<$total_credit)
		{
		  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">&nbsp;</td>';
		  $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_debit-$total_credit),2,',',' ').' EURO</td></tr>';
		  $grand_total_credit=$grand_total_credit+Abs($total_debit-$total_credit);
		}
		else
		{
		  $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_debit-$total_credit),2,',',' ').' EURO</td>';
		  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">&nbsp;</td></tr>';
		  $grand_total_debit=$grand_total_debit+Abs($total_debit-$total_credit);
		}
	  }

	  $msg=$msg.'<tr><td colspan=7 class="centre" style="padding:3px;width:100%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold;background:#c0c0c0;font-size:16px;">';
	  
//	  if ($tiers=='') { $tiers=$tableau_categorie[0]; }
	  $tiers=$tableau_categorie[$i];
	  if (substr($tiers,0,1)=="4")
	  {
		$req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".$tiers."'");
		$msg=$msg.$tiers." - ".mysql_result($req,0,'nom').'</td></tr>';
	  }
	  else
	  {
		$msg=$msg.$liste_choix['cooperative_ligne_plus'][$tiers].'</td></tr>';
	  }
	  
	  $total_debit=0;
	  $total_credit=0;
	}
	$msg=$msg.'<tr><td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:normal">'.Date_Convertir($tableau_date[$i],'Y-m-d',$Format_Date_PHP).'</td>';
	$msg=$msg.'<td class="gauche" style="padding:3px;width:40%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:normal">'.$tableau_libelle[$i].'</td>';
	$msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:normal">'.$tableau_piece[$i].'</td>';
	$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:normal">'.number_format(Abs($tableau_debit[$i]),2,',',' ').' EURO</td>';
	$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:normal">'.number_format(Abs($tableau_credit[$i]),2,',',' ').' EURO</td>';
	$cumul_total_debit=$cumul_total_debit+Abs($tableau_debit[$i]);
	$cumul_total_credit=$cumul_total_credit+Abs($tableau_credit[$i]);
	$total_debit=$total_debit+Abs($tableau_debit[$i]);
	$total_credit=$total_credit+Abs($tableau_credit[$i]);
	$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:normal">'.number_format(Abs($cumul_total_debit),2,',',' ').' EURO</td>';
	$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:normal">'.number_format(Abs($cumul_total_credit),2,',',' ').' EURO</td></tr>';
  }
  
	$msg=$msg.'<tr><td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">&nbsp;</td>';
	$msg=$msg.'<td colspan=2 class="centre" style="padding:3px;width:50%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold"><b>'.$Langue['LBL_GL_TOTAL_COMPTE'].'</b></td>';
	$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_debit),2,',',' ').' EURO</td>';
	$msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_credit),2,',',' ').' EURO</td>';
	if ($total_debit<$total_credit)
	{
	  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">&nbsp;</td>';
	  $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_debit-$total_credit),2,',',' ').' EURO</td></tr>';
	  $grand_total_credit=$grand_total_credit+Abs($total_debit-$total_credit);
	}
	else
	{
	  $msg=$msg.'<td class="droite" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">'.number_format(Abs($total_debit-$total_credit),2,',',' ').' EURO</td>';
	  $msg=$msg.'<td class="centre" style="padding:3px;width:10%;border-top:2px solid #000000;border-right:2px solid #000000;font-weight:bold">&nbsp;</td></tr>';
	  $grand_total_debit=$grand_total_debit+Abs($total_debit-$total_credit);
	}
	
	$msg=$msg.'</tbody></table><br><br>';

	$msg=$msg.'<table cellspacing=0 cellpadding=0 border=0 style="width:100%;">';
	$msg=$msg.'<tr>';
	$msg=$msg.'<td style="width:10%">&nbsp;</td>';
	$msg=$msg.'<td class="centre" style="width:35%;border:2px solid #000000;padding:5px;font-weight:bold;font-size:16px;">'.$Langue['LBL_GL_SOLDE_DEBITEUR'].' : '.number_format($grand_total_debit,2,',',' ').' EURO</td>';
	$msg=$msg.'<td style="width:10%">&nbsp;</td>';
	$msg=$msg.'<td class="centre" style="width:35%;border:2px solid #000000;padding:5px;font-weight:bold;font-size:16px;">'.$Langue['LBL_GL_SOLDE_CREDITEUR'].' : '.number_format($grand_total_credit,2,',',' ').' EURO</td>';
	$msg=$msg.'<td style="width:10%">&nbsp;</td>';
	$msg=$msg.'</tr>';
	$msg=$msg.'</table>';
	
	$contenu_page=$msg;
?>
