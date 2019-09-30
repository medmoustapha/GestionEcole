<?php
// *******************
// * Page à imprimer *
// *******************

  $id_classe=$_SESSION['id_classe_cours'];
  $id_tit=$_SESSION['titulaire_classe_cours'];
  $annee_scolaire=$_SESSION['annee_scolaire'];
  $dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,date('n'),date('t'),date('Y')));
  if ($dernier_jour_du_mois==date("Y-m-d")) { $mois=date("n"); } else { $mois=date("n")-1; }
  if ($mois==0) { $mois=12; }
  $total_general_absence=0;
  $total_general_presence=0;
  
  $msg='';
  
  // Construction des entêtes
  $annee_plus=$annee_scolaire+1;
  if ($_GET['orientation']=="P") { $largeur="40"; } else { $largeur="25"; }
  $msg .='<div style="width:'.$largeur.'%;text-align:center">';
  if (file_exists("cache/logo_ecole.png") || file_exists("cache/logo_ecole.jpg"))
  {
    if (file_exists("cache/logo_ecole.png")) { $image="cache/logo_ecole.png"; } else { $image="cache/logo_ecole.jpg"; }
    $dimensions=getimagesize($image);
    if ($dimensions[0]<$dimensions[1])
    {
	  if ($dimensions[1]>100) { $largeur=100*$dimensions[0]/$dimensions[1]; $hauteur=100; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
    }
    else
    {
	  if ($dimensions[0]>100) { $hauteur=100*$dimensions[1]/$dimensions[0]; $largeur=100; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
    }
    $msg .= '<img src="'.$image.'" width='.$largeur.' height='.$hauteur.' border=0><br><br>'; 
  }
  $msg .=$gestclasse_config_plus['nom'].'<br />';
  if ($gestclasse_config_plus['circonscription']!="")
  {
    $msg .='-- : : -- : : -- : : --<br />';
    $msg .=$Langue['LBL_IMPRESSION_CIRCONSCRIPTION'].' '.$gestclasse_config_plus['circonscription'].'<br />';
  }
  if ($gestclasse_config_plus['tel']!="")
  {
    $msg .='-- : : -- : : -- : : --<br />';
    $msg .=$Langue['LBL_IMPRESSION_TELEPHONE'].' : '.$gestclasse_config_plus['tel'].'<br />';
  }
  $msg .='</div><br /><br />';
  
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $msg .='<div class="titre_page textcentre" style="font-weight:bold;font-size:24px">'.$Langue['LBL_IMPRESSION_ANNEE_SCOL'].' '.$annee_scolaire.'<br />';
  }
  else
  {
    $msg .='<div class="titre_page textcentre" style="font-weight:bold;font-size:24px">'.$Langue['LBL_IMPRESSION_ANNEE_SCOL'].' '.$annee_scolaire.'-'.$annee_plus.'<br />';
  }
  $msg .=$Langue['LBL_IMPRESSION_BILAN_MOIS'].'<br />';
  $req=mysql_query("SELECT * FROM `classes` WHERE id='$id_classe'");
  $req2=mysql_query("SELECT * FROM `profs` WHERE id='$id_tit'");
  $msg .=$Langue['LBL_IMPRESSION_CLASSE2'].' : '.mysql_result($req,0,'nom_classe').' - '.$liste_choix['civilite'][mysql_result($req2,0,'civilite')].' '.mysql_result($req2,0,'nom').'</div><br /><br />';

  // Construction des entêtes du tableau
  $msg .='<table class="livret" cellspacing=0 cellpadding=0 style="width:100%">';
  $msg .='<tr class="even"><td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_MOIS'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_TOTAL_DEMI_JOURNEES'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_TOTAL_ELEVES'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_PRESENCES_MAX'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_TOTAL_ABSENCES'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_TOTAL_PRESENCES'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_POURC_PRESENCES'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_ELEVES_JAMAIS_ABSENTS'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_ELEVES_SOUVENT_ABSENTS'].'</td>';
  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:bold;font-size:10px">'.$Langue['LBL_IMPRESSION_TOTAL_ABSENCES_IMPUTABLES'].'</td></tr>';

  $faire=true;
  // Affichage des résultats
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  for ($i=1;$i<=12;$i++)
	  {
		$msg .='<tr class="even"><td class="bas gauche" style="width:10%;vertical-align:middle;font-weight:bold;font-size:10px">'.$liste_choix['mois'][$i].'</td>';
		if ($faire==true)
		{
		  // Calcul des demi-journées du mois
		  $total=Demi_Journee($i,$annee_scolaire);
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total.'</td>';
		  // Nombre d'élèves dans le mois
		  $dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$i,date('t',mktime(0,0,0,$i,1,$annee_scolaire)),$annee_scolaire));
		  $premier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$i,1,$annee_scolaire));
		  $req_eleve=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_classe='$id_classe' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$dernier_jour_du_mois' AND (eleves.date_sortie='0000-00-00' OR date_sortie>='$premier_jour_du_mois')");
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.mysql_num_rows($req_eleve).'</td>';
		  $nb_eleve=mysql_num_rows($req_eleve);
		  // Calcul des présences possibles
		  $total=$total*mysql_num_rows($req_eleve)-Demi_Journee_en_moins($i,$annee_scolaire,$premier_jour_du_mois,$dernier_jour_du_mois,$id_classe);
		  $total_general_presence=$total_general_presence+$total;
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total.'</td>';
		  // Calcul des absences et donc des présences effectives
		  $retour=Absences_Mois($i,$annee_scolaire,$id_classe);
		  $tableau=explode("-",$retour);
		  $total_p=$total-$tableau[0];
		  $total_general_absence=$total_general_absence+$tableau[0];
		  $total_eleve=$nb_eleve-$tableau[1];
		  if ($total!="") { $moyenne=number_format(100*$total_p/$total,2,',',' '); } else { $moyenne="0,00"; }
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[0].'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total_p.'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$moyenne.'%</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total_eleve.'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[1].'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[2].'</td></tr>';
		}
		else
		{
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td></tr>';
		}
		if ($i==$mois && $annee_scolaire==date('Y')) { $faire=false; }
	  }
  }
  else
  {
	  for ($i=$gestclasse_config_plus['mois_annee_scolaire']+1;$i<=12;$i++)
	  {
		$msg .='<tr class="even"><td class="bas gauche" style="width:10%;vertical-align:middle;font-weight:bold;font-size:10px">'.$liste_choix['mois'][$i].'</td>';
		if ($faire==true)
		{
		  // Calcul des demi-journées du mois
		  $total=Demi_Journee($i,$annee_scolaire);
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total.'</td>';
		  // Nombre d'élèves dans le mois
		  $dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$i,date('t',mktime(0,0,0,$i,1,$annee_scolaire)),$annee_scolaire));
		  $premier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$i,1,$annee_scolaire));
		  $req_eleve=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_classe='$id_classe' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$dernier_jour_du_mois' AND (eleves.date_sortie='0000-00-00' OR date_sortie>='$premier_jour_du_mois')");
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.mysql_num_rows($req_eleve).'</td>';
		  $nb_eleve=mysql_num_rows($req_eleve);
		  // Calcul des présences possibles
		  $total=$total*mysql_num_rows($req_eleve)-Demi_Journee_en_moins($i,$annee_scolaire,$premier_jour_du_mois,$dernier_jour_du_mois,$id_classe);
		  $total_general_presence=$total_general_presence+$total;
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total.'</td>';
		  // Calcul des absences et donc des présences effectives
		  $retour=Absences_Mois($i,$annee_scolaire,$id_classe);
		  $tableau=explode("-",$retour);
		  $total_p=$total-$tableau[0];
		  $total_general_absence=$total_general_absence+$tableau[0];
		  $total_eleve=$nb_eleve-$tableau[1];
		  if ($total!="") { $moyenne=number_format(100*$total_p/$total,2,',',' '); } else { $moyenne="0,00"; }
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[0].'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total_p.'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$moyenne.'%</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total_eleve.'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[1].'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[2].'</td></tr>';
		}
		else
		{
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td></tr>';
		}
		if ($i==$mois && $annee_scolaire==date('Y')) { $faire=false; }
	  }
	  
	  for ($i=1;$i<=$gestclasse_config_plus['mois_annee_scolaire'];$i++)
	  {
		$msg .='<tr class="even"><td class="bas gauche" style="width:10%;vertical-align:middle;font-weight:bold;font-size:10px">'.$liste_choix['mois'][$i].'</td>';
		if ($faire==true)
		{
		  // Calcul des demi-journées du mois
		  $total=Demi_Journee($i,$annee_plus);
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total.'</td>';
		  // Nombre d'élèves dans le mois
		  $dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$i,date('t',mktime(0,0,0,$i,1,$annee_plus)),$annee_plus));
		  $premier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$i,1,$annee_plus));
		  $req_eleve=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_classe='$id_classe' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$dernier_jour_du_mois' AND (eleves.date_sortie='0000-00-00' OR date_sortie>='$premier_jour_du_mois')");
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.mysql_num_rows($req_eleve).'</td>';
		  // Calcul des présences possibles
		  $total=$total*mysql_num_rows($req_eleve)-Demi_Journee_en_moins($i,$annee_plus,$premier_jour_du_mois,$dernier_jour_du_mois,$id_classe);
		  $total_general_presence=$total_general_presence+$total;
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total.'</td>';
		  // Calcul des absences et donc des présences effectives
		  $retour=Absences_Mois($i,$annee_plus,$id_classe);
		  $tableau=explode("-",$retour);
		  $total_p=$total-$tableau[0];
		  $total_general_absence=$total_general_absence+$tableau[0];
		  $total_eleve=$nb_eleve-$tableau[1];
		  if ($total!="") { $moyenne=number_format(100*$total_p/$total,2,',',' '); } else { $moyenne="0,00"; }
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[0].'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total_p.'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$moyenne.'%</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$total_eleve.'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[1].'</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">'.$tableau[2].'</td></tr>';
		}
		else
		{
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td>';
		  $msg .='<td class="bas" style="width:10%;text-align:center;vertical-align:middle;font-weight:normal;font-size:10px">&nbsp;</td></tr>';
		}
		if ($i==$mois && $annee_plus==date('Y')) { $faire=false; }
	  }
  }
  $msg .='</table>';
  $msg .='<br /><br /><div style="text-align:right;font-size:12px;font-weight:bold;">'.$Langue['LBL_IMPRESSION_POURC_ANNUEL_ABSENCES'].' : '.number_format(100*$total_general_absence/$total_general_presence,2,',',' ').'%</div>';
  
  // Impression
  $contenu_html=$msg;
?>
