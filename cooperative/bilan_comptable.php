<?php
  if (isset($_GET['impression'])) { $impression=$_GET['impression']; } else { $impression="0"; }
  $req=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  if (mysql_num_rows($req)=="")
  {
    $req=mysql_query("INSERT INTO `cooperative_bilan` (annee) VALUES ('".$_SESSION['cooperative_scolaire']."')");
  }
  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  
  $contenu_page="";
  if ($impression=="0")
  {
?>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Button" id="Clore" name="Clore" value="<?php echo $Langue['BTN_CLORE_BILAN']; ?>">&nbsp;
    <input type="Button" id="Imprimer" name="Imprimer" value="<?php echo $Langue['BTN_IMPRIMER_BILAN']; ?>">&nbsp;
    <input type="Button" id="Annuler" name="Annuler" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite">
	  <button id="aide_fenetre" name="aide_fenetre"><?php echo $Langue['BTN_AIDE']; ?></button>
	</td>
</tr>
</table>
<?php
  }
  
  $total_charge=0;
  $total_produit=0;
  $annee_a=$_SESSION['cooperative_scolaire']+1;
  $total_actif=0;
  $total_passif=0;
  $total_caisse_actif=0;

// ************************************************ 
// * Affichage du tableau des charges et produits * 
// ************************************************ 

  if ($impression=="0") 
  { 
    $monnaie="&euro;";
    $largeur=34;
	$largeur2=15;
	$plus="";
    $contenu_page=$contenu_page.'<div id="msg_err"></div>
    <table cellspacing=0 cellpadding=0 class="tableau_editview">
	<tr>
	  <td style="text-align:center;width:100%" colspan=5><div class="ui-widget ui-state-default ui-corner-all textcentre" style="padding:5px;">'.$Langue['LBL_BILAN_FONCTIONNEMENT'].' '.$_SESSION['cooperative_scolaire'].' '.$Langue['LBL_BILAN_FONCTIONNEMENT2'].' '.$annee_a.'</div></td>
	</tr>
	<tr>
	  <td colspan=2 class="centre" style="width:49%;font-weight:bold;text-decoration:underline;">'.$Langue['LBL_BILAN_CHARGES'].'</td>
	  <td class="centre" style="width:2%">&nbsp;</td>
	  <td colspan=2 class="centre" style="width:49%;font-weight:bold;text-decoration:underline;">'.$Langue['LBL_BILAN_PRODUITS'].'</td>
	</tr>';
  }
  else
  {
    $monnaie="EURO";
    $largeur=36;
	$largeur2=13;
	$plus="padding:5px;";
    $contenu_page=$contenu_page.'<table cellspacing=0 cellpadding=0 style="width:100%;border:0.5mm solid #000000;background:#c0c0c0;font-family:Arial;font-size:12px">
	<tr>
	  <td class="centre" style="width:100%;padding:5px;">'.$Langue['LBL_BILAN_FONCTIONNEMENT'].' '.$_SESSION['cooperative_scolaire'].' '.$Langue['LBL_BILAN_FONCTIONNEMENT2'].' '.$annee_a.'</td>
	</tr>
	</table>
	<table cellspacing=0 cellpadding=0 style="width:100%;border:0.5mm solid #000000;font-family:Arial;font-size:12px;margin-top:5px">
	<tr>
	  <td colspan=2 class="centre" style="width:49%;font-weight:bold;text-decoration:underline;padding:5px;">'.$Langue['LBL_BILAN_CHARGES'].'</td>
	  <td class="centre" style="width:2%">&nbsp;</td>
	  <td colspan=2 class="centre" style="width:49%;font-weight:bold;text-decoration:underline;padding:5px;">'.$Langue['LBL_BILAN_PRODUITS'].'</td>
	</tr>';
  }
  $contenu_page=$contenu_page.'<tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['6070'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='6070'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format((-1)*mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_charge=$total_charge+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7070'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7070'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['6180'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='6180'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format((-1)*mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_charge=$total_charge+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7080'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7080'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['6281'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='6281'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format((-1)*mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_charge=$total_charge+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7410'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7410'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['6282'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='6282'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format((-1)*mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_charge=$total_charge+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7420'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7420'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['6500'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='6500'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format((-1)*mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_charge=$total_charge+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7500'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7500'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['6700'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='6700'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format((-1)*mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_charge=$total_charge+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7560'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7560'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['6800'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='6800'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format((-1)*mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_charge=$total_charge+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7600'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7600'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">&nbsp;</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">&nbsp;</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['7700'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='7700'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_produit=$total_produit+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="droite marge5_gauche" style="'.$plus.'width:'.$largeur.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.$Langue['LBL_BILAN_TOTAL_CHARGES'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.number_format((-1)*$total_charge,2,","," ").' '.$monnaie.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="droite marge5_gauche" style="'.$plus.'width:'.$largeur.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.$Langue['LBL_BILAN_TOTAL_PRODUITS'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.number_format($total_produit,2,","," ").' '.$monnaie.'</td>
  </tr>
  <tr>
    <td style="'.$plus.'text-align:center;width:100%;font-weight:bold;font-size:12px" colspan=5>'.$Langue['LBL_BILAN_RESULTAT_ANNEE'].' '.$_SESSION['cooperative_scolaire'].'/'.$annee_a.' (B-A) : '.number_format($total_produit+$total_charge,2,","," ").' '.$monnaie.'</td>
  </tr>
  </table>';


// ******************************************** 
// * Affichage du tableau de l'actif / passif * 
// ******************************************** 

  $req_pointage=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE pointe='0'");
  if (mysql_num_rows($req_pointage)=="") { $extension="2"; } else { $extension=""; }

  if ($impression=="0")
  {
    $contenu_page=$contenu_page.'<table cellspacing=0 cellpadding=0 class="tableau_editview'.$extension.'">
		<tr>
		  <td width=100% colspan=5><div class="ui-widget ui-state-default ui-corner-all" style="padding:5px;" align=center>'.$Langue['LBL_BILAN_SIMPLIFIE'].' '.$annee_a.'</div></td>
		</tr>
		<tr>
		  <td width=49% colspan=2 class="centre" style="border-right:5px solid #ffffff;font-weight:bold;text-decoration:underline;">'.$Langue['LBL_BILAN_ACTIF'].'</td>
		  <td style="text-align:center;width:2%">&nbsp;</td>
		  <td width=49% colspan=2 class="centre" style="border-left:5px solid #ffffff;font-weight:bold;text-decoration:underline;">'.$Langue['LBL_BILAN_PASSIF'].'</td>
		</tr>';
  }
  else
  {
    $contenu_page=$contenu_page.'<table cellspacing=0 cellpadding=0 style="width:100%;border:0.5mm solid #000000;background:#c0c0c0;font-family:Arial;font-size:12px;margin-top:10px">
	<tr>
	  <td style="text-align:center;width:100%;padding:5px">'.$Langue['LBL_BILAN_SIMPLIFIE'].' '.$annee_a.'</td>
	</tr>
	</table>
	<table cellspacing=0 cellpadding=0 style="width:100%;border:0.5mm solid #000000;font-family:Arial;font-size:12px;margin-top:5px">
	<tr>
	  <td colspan=2 style="padding:5px;text-align:center;width:49%;font-weight:bold;text-decoration:underline;">'.$Langue['LBL_BILAN_ACTIF'].'</td>
      <td style="text-align:center;width:2%">&nbsp;</td>
	  <td colspan=2 style="padding:5px;text-align:center;width:49%;font-weight:bold;text-decoration:underline;">'.$Langue['LBL_BILAN_PASSIF'].'</td>
	</tr>';
  }
  $contenu_page=$contenu_page.'<tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne_plus']['512'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='512' AND ligne_comptable NOT LIKE '5%'");
  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='512'");
  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='512' AND ligne_comptable LIKE '5%'");
  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="" && mysql_num_rows($req3)=="") 
  { 
    $contenu_page=$contenu_page."0,00 ".$monnaie; 
  } 
  else 
  { 
    $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')),2,","," ")." $monnaie"; 
	$total_actif=$total_actif+mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')); 
  }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne']['110'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='110'");
  if (mysql_num_rows($req)=="") { $contenu_page=$contenu_page."0,00 ".$monnaie; } else { $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total'),2,","," ")." ".$monnaie; $total_passif=$total_passif+mysql_result($req,0,'total'); }

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne_plus']['514'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='514' AND ligne_comptable NOT LIKE '5%'");
  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='514'");
  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='514' AND ligne_comptable LIKE '5%'");
  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="" && mysql_num_rows($req3)=="") 
  { 
    $contenu_page=$contenu_page."0,00 ".$monnaie; 
  } 
  else 
  { 
    $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')),2,","," ")." $monnaie"; 
	$total_actif=$total_actif+mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')); 
  }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$Langue['LBL_BILAN_RESULTAT_ANNEE'].' '.$_SESSION['cooperative_scolaire'].'/'.$annee_a.'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $contenu_page=$contenu_page.number_format($total_produit+$total_charge,2,","," ")." ".$monnaie; $total_passif=$total_passif+$total_produit+$total_charge;

  $contenu_page=$contenu_page.'</td></tr><tr>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">'.$liste_choix['cooperative_ligne_plus']['530'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">';

  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='530' AND ligne_comptable NOT LIKE '5%'");
  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='530'");
  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='530' AND ligne_comptable LIKE '5%'");
  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="" && mysql_num_rows($req3)=="") 
  { 
    $contenu_page=$contenu_page."0,00 ".$monnaie; 
  } 
  else 
  { 
    $contenu_page=$contenu_page.number_format(mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')),2,","," ")." $monnaie"; 
	$total_actif=$total_actif+mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')); 
	$total_caisse_actif=mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total'));
  }

  $contenu_page=$contenu_page.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="gauche marge5_gauche" style="'.$plus.'width:'.$largeur.'%;">&nbsp;</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;font-weight:bold">&nbsp;</td>
  </tr>
  <tr>
    <td class="droite marge5_gauche" style="'.$plus.'width:'.$largeur.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.$Langue['LBL_BILAN_TOTAL_ACTIF'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.number_format($total_actif,2,","," ").' '.$monnaie.'</td>
    <td style="text-align:center;width:2%">&nbsp;</td>
    <td class="droite marge5_gauche" style="'.$plus.'width:'.$largeur.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.$Langue['LBL_BILAN_TOTAL_PASSIF'].'</td>
    <td class="droite marge5_droite" style="'.$plus.'width:'.$largeur2.'%;border-top:0.5mm solid #000000;font-weight:bold;font-size:12px">'.number_format($total_passif,2,","," ").' '.$monnaie.'</td>
  </tr>
  </table>';

  if (mysql_num_rows($req_pointage)!="")
  {
    if ($impression=='0')
	{
	  $contenu_page=$contenu_page.'<div class="ui-widget ui-state-default ui-corner-all" style="padding:5px;" align=center>'.$Langue['LBL_BILAN_RAPPROCHEMENT_BANCAIRE'].'</div>';
	}
	else
	{
      $contenu_page=$contenu_page.'<div style="width:100%;border:0.5mm solid #000000;font-family:Arial;font-size:12px;margin-top:10px;padding:5px;">';
	}
	
	  // ********************************************
	  // * Affichage des lignes non pointées en 512 *
	  // ********************************************
	  $req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='514' AND pointe='0'");
	  if (mysql_num_rows($req)=="") { $extension="2"; $br="<br>"; } else { $extension=""; $br=""; }
	  $req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE (banque='512' OR ligne_comptable='512') AND pointe='0'");
	  if (mysql_num_rows($req)!="")
	  {
	    if ($impression=="0")
		{
		  $contenu_page=$contenu_page.'<table cellspacing=0 cellpadding=0 class="tableau_editview'.$extension.'">
			<tr>
			  <td width=100% colspan=4 class="gauche">
				<b>'.$Langue['LBL_BILAN_BANQUE_512'].'</b><br>'.$Langue['LBL_BILAN_BANQUE_512_2'].'</td>
			</tr>';
		}
		else
		{
		  $contenu_page=$contenu_page.'<b>'.$Langue['LBL_BILAN_BANQUE_512'].'</b><br>'.$Langue['LBL_BILAN_BANQUE_512_2'].'<br>
				<table cellspacing=0 cellpadding=0 style="width:100%">';
		}
		$contenu_page=$contenu_page.'<tr>
		  <td colspan=3 class="droite marge5_droite" style="'.$plus.'width:85%;">'.$Langue['LBL_BILAN_SOLDE_RELEVE'].'</td>
		  <td class="droite marge5_droite" style="'.$plus.'width:15%;font-weight:bold;border:0.5mm solid #000000;border-bottom:none;">';
		  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='512' AND ligne_comptable NOT LIKE '5%' AND pointe='1'");
		  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='512' AND pointe='1'");
		  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='512' AND ligne_comptable LIKE '5%' AND pointe='1'");
		  $total_compte=0;
		  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="" && mysql_num_rows($req3)=="") 
		  { 
			$contenu_page=$contenu_page."0,00 ".$monnaie; 
		  } 
		  else 
		  { 
			$contenu_page=$contenu_page.number_format(mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')),2,","," ")." ".$monnaie;
			$total_compte=$total_compte+mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total'));
		  }

		  $contenu_page=$contenu_page.'</td></tr><tr>
			  <td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000">'.$Langue['LBL_BILAN_CHEQUE'].'</td>
			  <td class="centre" style="'.$plus.'width:50%;border:0.5mm solid #000000;border-left:none;">'.$Langue['LBL_BILAN_DESTINATAIRES'].'</td>
			  <td class="centre" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-right:none;">'.$Langue['LBL_BILAN_DATE_OPERATIONS'].'</td>
			  <td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;">'.$Langue['LBL_BILAN_MONTANTS'].'</td>
			</tr>';

			$req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE (banque='512' OR ligne_comptable='512') AND pointe='0' ORDER BY date ASC");
			for ($i=1;$i<=mysql_num_rows($req);$i++)
			{
			  $contenu_page=$contenu_page.'<tr>';
			  $contenu_page=$contenu_page.'<td style="'.$plus.'text-align:center;width:15%;border:0.5mm solid #000000;border-top:none">';
			  if (mysql_result($req,$i-1,'reference_bancaire')=="") { $contenu_page=$contenu_page.'&nbsp;'; } else { $contenu_page=$contenu_page.mysql_result($req,$i-1,'reference_bancaire'); }
			  $contenu_page=$contenu_page.'</td>';
			  if (mysql_result($req,$i-1,'tiers')!="")
			  {
  			    $req2=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,$i-1,'tiers')."'");
			    $contenu_page=$contenu_page.'<td class="gauche marge5_gauche" style="'.$plus.'width:50%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req2,0,'nom').' - '.mysql_result($req,$i-1,'libelle').'</td>';
			  }
			  else
			  {
			    $contenu_page=$contenu_page.'<td class="gauche marge5_gauche" style="'.$plus.'width:50%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,$i-1,'libelle').'</td>';
			  }
			  $contenu_page=$contenu_page.'<td style="'.$plus.'width:20%;text-align:center;border-bottom:0.5mm solid #000000">'.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).'</td>';
			  if (mysql_result($req,$i-1,'banque')=="512")
			  {
			    if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)=="5")
				{
			      $contenu_page=$contenu_page.'<td class="droite marge5_droite" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-top:none;">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' '.$monnaie.'</td>';
			      $total_compte=$total_compte+Abs(mysql_result($req,$i-1,'montant'));
				}
				else
				{
			      $contenu_page=$contenu_page.'<td class="droite marge5_droite" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-top:none;">'.number_format(mysql_result($req,$i-1,'montant'),2,',',' ').' '.$monnaie.'</td>';
			      $total_compte=$total_compte+mysql_result($req,$i-1,'montant');
				}
			  }
			  else
			  {
			    $contenu_page=$contenu_page.'<td class="droite marge5_droite" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-top:none;">'.number_format(mysql_result($req,$i-1,'montant'),2,',',' ').' '.$monnaie.'</td>';
			    $total_compte=$total_compte+mysql_result($req,$i-1,'montant');
			  }
			  $contenu_page=$contenu_page.'</tr>';
			}
	 	  $contenu_page=$contenu_page.'<tr>
		    <td colspan=3 class="droite marge5_droite" style="'.$plus.'width:85%;">'.$Langue['LBL_BILAN_SOLDE_512'].'</td>
		    <td class="droite marge5_droite" style="'.$plus.'width:15%;font-weight:bold;border:0.5mm solid #000000;border-top:none;">'.number_format($total_compte,2,',',' ').' '.$monnaie.'</td>
		  </tr></table>'.$br;
	  }
	  
	  // ********************************************
	  // * Affichage des lignes non pointées en 514 *
	  // ********************************************
	  $req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE (banque='514' OR ligne_comptable='514') AND pointe='0'");
	  if (mysql_num_rows($req)!="")
	  {
	    if ($impression=="0")
		{
		  $contenu_page=$contenu_page.'<table cellspacing=0 cellpadding=0 class="tableau_editview'.$extension.'">
			<tr>
			  <td width=100% colspan=4 class="gauche">
				<b>'.$Langue['LBL_BILAN_BANQUE_514'].'</b><br>'.$Langue['LBL_BILAN_BANQUE_514_2'].'</td>
			</tr>';
		}
		else
		{
		  $contenu_page=$contenu_page.'<b>'.$Langue['LBL_BILAN_BANQUE_514'].'</b><br>'.$Langue['LBL_BILAN_BANQUE_514_2'].'<br>
				<table cellspacing=0 cellpadding=0 style="width:100%">';
		}
		$contenu_page=$contenu_page.'<tr>
		  <td colspan=3 class="droite marge5_droite" style="'.$plus.'width:85%;">'.$Langue['LBL_BILAN_SOLDE_RELEVE'].'</td>
		  <td class="droite marge5_droite" style="'.$plus.'width:15%;font-weight:bold;border:0.5mm solid #000000;border-bottom:none;">';
		  $req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='514' AND ligne_comptable NOT LIKE '5%' AND pointe='1'");
		  $req2=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable='514' AND pointe='1'");
		  $req3=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='514' AND ligne_comptable LIKE '5%' AND pointe='1'");
		  $total_compte=0;
		  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="" && mysql_num_rows($req3)=="") 
		  { 
			$contenu_page=$contenu_page."0,00 ".$monnaie; 
		  } 
		  else 
		  { 
			$contenu_page=$contenu_page.number_format(mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total')),2,","," ")." ".$monnaie;
			$total_compte=$total_compte+mysql_result($req,0,'total')+mysql_result($req2,0,'total')+Abs(mysql_result($req3,0,'total'));
		  }

		  $contenu_page=$contenu_page.'</td></tr><tr>
			  <td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000">'.$Langue['LBL_BILAN_CHEQUE'].'</td>
			  <td class="centre" style="'.$plus.'width:50%;border:0.5mm solid #000000;border-left:none;">'.$Langue['LBL_BILAN_DESTINATAIRES'].'</td>
			  <td class="centre" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-right:none;">'.$Langue['LBL_BILAN_DATE_OPERATIONS'].'</td>
			  <td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;">'.$Langue['LBL_BILAN_MONTANTS'].'</td>
			</tr>';

			$req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE (banque='514' OR ligne_comptable='514') AND pointe='0' ORDER BY date ASC");
			for ($i=1;$i<=mysql_num_rows($req);$i++)
			{
			  $contenu_page=$contenu_page.'<tr>';
			  $contenu_page=$contenu_page.'<td style="'.$plus.'text-align:center;width:15%;border:0.5mm solid #000000;border-top:none">';
			  if (mysql_result($req,$i-1,'reference_bancaire')=="") { $contenu_page=$contenu_page.'&nbsp;'; } else { $contenu_page=$contenu_page.mysql_result($req,$i-1,'reference_bancaire'); }
			  $contenu_page=$contenu_page.'</td>';
			  if (mysql_result($req,$i-1,'tiers')!="")
			  {
  			    $req2=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,$i-1,'tiers')."'");
			    $contenu_page=$contenu_page.'<td class="gauche marge5_gauche" style="'.$plus.'width:50%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req2,0,'nom').' - '.mysql_result($req,$i-1,'libelle').'</td>';
			  }
			  else
			  {
			    $contenu_page=$contenu_page.'<td class="gauche marge5_gauche" style="'.$plus.'width:50%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,$i-1,'libelle').'</td>';
			  }
			  $contenu_page=$contenu_page.'<td style="'.$plus.'width:20%;text-align:center;border-bottom:0.5mm solid #000000">'.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).'</td>';
			  if (mysql_result($req,$i-1,'banque')=="514")
			  {
			    if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)=="5")
				{
			      $contenu_page=$contenu_page.'<td class="droite marge5_droite" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-top:none;">'.number_format(Abs(mysql_result($req,$i-1,'montant')),2,',',' ').' '.$monnaie.'</td>';
			      $total_compte=$total_compte+Abs(mysql_result($req,$i-1,'montant'));
				}
				else
				{
			      $contenu_page=$contenu_page.'<td class="droite marge5_droite" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-top:none;">'.number_format(mysql_result($req,$i-1,'montant'),2,',',' ').' '.$monnaie.'</td>';
			      $total_compte=$total_compte+mysql_result($req,$i-1,'montant');
				}
			  }
			  else
			  {
			    $contenu_page=$contenu_page.'<td class="droite marge5_droite" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-top:none;">'.number_format(mysql_result($req,$i-1,'montant'),2,',',' ').' '.$monnaie.'</td>';
			    $total_compte=$total_compte+mysql_result($req,$i-1,'montant');
			  }
			  $contenu_page=$contenu_page.'</tr>';
			}
	 	  $contenu_page=$contenu_page.'<tr>
		    <td colspan=3 class="droite marge5_droite" style="'.$plus.'width:85%;">'.$Langue['LBL_BILAN_SOLDE_514'].'</td>
		    <td class="droite marge5_droite" style="'.$plus.'width:15%;font-weight:bold;border:0.5mm solid #000000;border-top:none;">'.number_format($total_compte,2,',',' ').' '.$monnaie.'</td>
		  </tr></table>'.$br;
	  }
		if ($impression=="1")
		{
			$contenu_page=$contenu_page.'</div>';
		}
  }

  $req_caisse=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE banque='530'");
  $total_caisse=0;
  if (mysql_num_rows($req_caisse)!="")
  {
    $req=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
	if ($impression=="0")
	{
		$contenu_page=$contenu_page. '<div class="ui-widget ui-state-default ui-corner-all" style="padding:5px;margin-top:30px;margin-bottom:5px;" align=center>'.$Langue['LBL_BILAN_ARRETE_CAISSE'].' '.$annee_a.'</div>';
		$contenu_page=$contenu_page. '<p class="textdroite"><input type="button" name="arrete_caisse" id="arrete_caisse" value="'.$Langue['BTN_BILAN_ARRETE_CAISSE'].'"></p>';
		$contenu_page=$contenu_page. '<table cellspacing=0 cellpadding=0 class="tableau_editview2">';
	}
	else
	{
		$contenu_page=$contenu_page. '<div style="width:100%;border:0.5mm solid #000000;font-family:Arial;font-size:12px;margin-top:10px;padding:5px">';
		$contenu_page=$contenu_page. '<b>'.$Langue['LBL_BILAN_ARRETE_CAISSE'].' '.$annee_a.'</b><br>'.$Langue['LBL_BILAN_ARRETE_CAISSE2'];
		$contenu_page=$contenu_page. '<table cellspacing=0 cellpadding=0 style="width:100%;">';
	}
	
	$contenu_page=$contenu_page. '<tr><td class="centre" colspan=3 style="'.$plus.'width:50%;border:0.5mm solid #000000;font-weight:bold">'.$Langue['LBL_BILAN_BILLETS'].'</td>';
	$contenu_page=$contenu_page. '<td class="centre" colspan=3 style="'.$plus.'width:50%;border:0.5mm solid #000000;border-left:none;font-weight:bold">'.$Langue['LBL_BILAN_PIECES'].'</td></tr>';
	$contenu_page=$contenu_page. '<tr><td class="centre" style="width:20%;border:0.5mm solid #000000;border-top:none;font-weight:bold">'.$Langue['LBL_BILAN_VALEUR'].'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;font-weight:bold">'.$Langue['LBL_BILAN_NOMBRE'].'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;font-weight:bold">'.$Langue['LBL_BILAN_SOMME'].'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-top:none;font-weight:bold">'.$Langue['LBL_BILAN_VALEUR'].'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;font-weight:bold">'.$Langue['LBL_BILAN_NOMBRE'].'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;font-weight:bold">'.$Langue['LBL_BILAN_SOMME'].'</td></tr>';

	$contenu_page=$contenu_page. '<tr><td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-top:none">&nbsp;200,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_200').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(200*mysql_result($req,0,'nb_200'),2,',',' ').' '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-top:none">&nbsp;2,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_2').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(2*mysql_result($req,0,'nb_2'),2,',',' ').' '.$monnaie.'</td></tr>';
	$total_caisse=$total_caisse+200*mysql_result($req,0,'nb_200')+2*mysql_result($req,0,'nb_2');
	
	$contenu_page=$contenu_page. '<tr><td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-top:none">&nbsp;100,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_100').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(100*mysql_result($req,0,'nb_100'),2,',',' ').' '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-top:none">&nbsp;1,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_1').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(mysql_result($req,0,'nb_1'),2,',',' ').' '.$monnaie.'</td></tr>';
	$total_caisse=$total_caisse+100*mysql_result($req,0,'nb_100')+mysql_result($req,0,'nb_1');

	$contenu_page=$contenu_page. '<tr><td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-top:none">&nbsp;50,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_50').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(50*mysql_result($req,0,'nb_50'),2,',',' ').' '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-top:none">&nbsp;0,50 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_05').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(0.5*mysql_result($req,0,'nb_05'),2,',',' ').' '.$monnaie.'</td></tr>';
	$total_caisse=$total_caisse+50*mysql_result($req,0,'nb_50')+0.5*mysql_result($req,0,'nb_05');

	$contenu_page=$contenu_page. '<tr><td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-top:none">&nbsp;20,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_20').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(20*mysql_result($req,0,'nb_20'),2,',',' ').' '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-top:none">&nbsp;0,20 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_02').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(0.2*mysql_result($req,0,'nb_02'),2,',',' ').' '.$monnaie.'</td></tr>';
	$total_caisse=$total_caisse+20*mysql_result($req,0,'nb_20')+0.2*mysql_result($req,0,'nb_02');

	$contenu_page=$contenu_page. '<tr><td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-top:none">&nbsp;10,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_10').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(10*mysql_result($req,0,'nb_10'),2,',',' ').' '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-top:none">&nbsp;0,10 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_01').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(0.1*mysql_result($req,0,'nb_01'),2,',',' ').' '.$monnaie.'</td></tr>';
	$total_caisse=$total_caisse+10*mysql_result($req,0,'nb_10')+0.1*mysql_result($req,0,'nb_01');

	$contenu_page=$contenu_page. '<tr><td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-top:none">&nbsp;5,00 '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.mysql_result($req,0,'nb_5').'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format(5*mysql_result($req,0,'nb_5'),2,',',' ').' '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="gauche" style="'.$plus.'width:20%;border:0.5mm solid #000000;border-left:none;border-top:none">&nbsp;'.$Langue['LBL_BILAN_PETITE_MONNAIE'].'</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">xxxxxx</td>';
	$tot=0.05*mysql_result($req,0,'nb_005')+0.02*mysql_result($req,0,'nb_002')+0.01*mysql_result($req,0,'nb_001');
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;">'.number_format($tot,2,',',' ').' '.$monnaie.'</td></tr>';
	$total_caisse=$total_caisse+5*mysql_result($req,0,'nb_5')+$tot;

	$contenu_page=$contenu_page. '<tr><td class="droite" colspan=2 style="'.$plus.'width:35%;border-right:0.5mm solid #000000;">'.$Langue['LBL_BILAN_SOLDE_530'].'&nbsp;</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;font-weight:bold;">'.number_format($total_caisse_actif,2,',',' ').' '.$monnaie.'</td>';
	$contenu_page=$contenu_page. '<td class="droite" colspan=2 style="'.$plus.'width:35%;border-right:0.5mm solid #000000;">'.$Langue['LBL_BILAN_SOLDE_ARRETE'].'&nbsp;</td>';
	$contenu_page=$contenu_page. '<td class="centre" style="'.$plus.'width:15%;border:0.5mm solid #000000;border-left:none;border-top:none;font-weight:bold;">'.number_format($total_caisse,2,',',' ').' '.$monnaie.'</td></tr>';
	$contenu_page=$contenu_page. '</table>';
	if ($impression=="1")
	{
	  $contenu_page=$contenu_page.'</div>';
	}
  }
  
  if ($impression=="0")
  {
	echo $contenu_page;
?>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Clore2" name="Clore2" value="<?php echo $Langue['BTN_CLORE_BILAN']; ?>">&nbsp;
    <input type="Button" id="Imprimer2" name="Imprimer2" value="<?php echo $Langue['BTN_IMPRIMER_BILAN']; ?>">&nbsp;
    <input type="Button" id="Annuler2" name="Annuler2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>

<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/164-le-bilan-comptable.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/164-le-bilan-comptable.html","Aide");
<?php } ?>
  });

<?php
  if (mysql_result($req_bilan,0,'clos')=="0")
  {
    echo '$("#Clore").button({disabled:false});';
    echo '$("#Clore2").button({disabled:false});';
    echo '$("#arrete_caisse").button({disabled:false});';
  }
  else
  {
    echo '$("#Clore").button({disabled:true});';
    echo '$("#Clore2").button({disabled:true});';
    echo '$("#arrete_caisse").button({disabled:true});';
  }
?>
  $("#Imprimer").button();
  $("#Annuler").button();
  $("#Imprimer2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  $("#arrete_caisse").click(function()
  {
    Charge_Dialog3("index2.php?module=cooperative&action=editview_caisse","<?php echo $Langue['LBL_BILAN_SAISIE_530']; ?>");
  });
  
  $("#Imprimer").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=detailview_imprimer&id_a_imprimer=3","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#Imprimer2").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=detailview_imprimer&id_a_imprimer=3","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#Clore2").click(function()
  {
    $("#Clore").click();
  });
  
  $("#Clore").click(function()
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_BILAN_CLORE']; ?> <?php echo $_SESSION['cooperative_scolaire'].'-'.$annee_a; ?> ?</div><div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_BILAN_CLORE2']; ?> <?php echo $_SESSION['cooperative_scolaire'].'-'.$annee_a; ?>.</strong></div></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_BILAN_CLORE_EXERCICE']; ?> <?php echo $_SESSION['cooperative_scolaire'].'-'.$annee_a; ?>",
	  resizable: false,
	  draggable: false,
	  height:225,
	  width:450,
	  modal: true,
	  buttons:[
      {
        text: "Valider",
		click: function()
        {
          Message_Chargement(8,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: "module=cooperative&action=save_clore_exercice"});
          request.done(function(msg)
          {
		    Message_Chargement(1,1);
			Charge_Dialog("index2.php?module=cooperative&action=bilan_comptable","<?php echo $Langue['LBL_BILAN_COMPTABLE']; ?>");
			$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
		    $( "#dialog-confirm" ).dialog( "close" );
		  });
        }
	  },
	  {
        text: "Annuler",
		click: function()
        {
		  $( this ).dialog( "close" );
		}
	  }]
	});
  });
  
<?php
  if (Abs($total_caisse-$total_caisse_actif)>=0.001)
  {
?>
    $("#msg_err").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_BILAN_CAISSE']; ?> (<?php echo number_format($total_caisse_actif,2,',',' ').' '.$monnaie; ?>) <?php echo $Langue['MSG_BILAN_CAISSE2']; ?> (<?php echo number_format($total_caisse,2,',',' ').' '.$monnaie; ?>).<br><?php echo $Langue['MSG_BILAN_CAISSE3']; ?></strong></div></div>');
<?php
    echo '$("#Clore").button({disabled:true});';
    echo '$("#Clore2").button({disabled:true});';
  }
  if (Abs($total_passif-$total_actif)>=0.001)
  {
?>
    $("#msg_err").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_BILAN_PAS_EQUILIBRE']; ?> (<?php echo number_format($total_actif,2,',',' ').' '.$monnaie; ?>) <?php echo $Langue['MSG_BILAN_PAS_EQUILIBRE2']; ?> (<?php echo number_format($total_passif,2,',',' ').' '.$monnaie; ?>).<br><?php echo $Langue['MSG_BILAN_PAS_EQUILIBRE3']; ?></strong></div></div>');
<?php
    echo '$("#Clore").button({disabled:true});';
    echo '$("#Clore2").button({disabled:true});';
  }
?>
});
</script>

<?php
  }
?>
