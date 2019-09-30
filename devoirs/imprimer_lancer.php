<?php
  $date=date("Y-m-d");
  $id_classe=$_SESSION['id_classe_cours'];
  $id_niveau=$_SESSION['niveau_en_cours'];
  
  $req=mysql_query("SELECT * FROM `devoirs` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND date_faire>='$date' AND date_donnee<='$date' ORDER BY date_faire ASC, date_donnee ASC");  
  $contenu_html='<div class="titre_page" style="text-align:center">'.$Langue['LBL_DEVOIRS'].'</div><br><br>';
  $date_donnee="0000-00-00";
  $fait=false;
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if ($date_donnee!=mysql_result($req,$i-1,'date_faire'))
	{
	  if ($fait==true) { $contenu_html=$contenu_html.'</table><br><br>'; }
	  $contenu_html=$contenu_html.'<div class="sous_titre_page">'.$Langue['LBL_DEVOIRS_A_FAIRE_POUR'].' '.Date_Convertir(mysql_result($req,$i-1,'date_faire'),"Y-m-d",$Format_Date_PHP).'</div><br>';
	  $contenu_html=$contenu_html.'<table class="heures" cellpadding=0 cellspacing=0 style="width:100%"><tr>';
	  $contenu_html=$contenu_html.'<th style="width:12%;text-align:center;border-left:2px solid #000000">'.$Langue['LBL_DONNES_LE'].'</th><th style="width:20%;text-align:center">'.$Langue['LBL_MATIERE'].'</th><th style="width:68%;text-align:center">'.$Langue['LBL_TRAVAIL_DEMANDE'].'</th></tr>';
	  $date_donnee=mysql_result($req,$i-1,'date_faire');
	  $fait=true;
	  $ligne="even";
    }
    $contenu_html=$contenu_html.'<tr class="'.$ligne.'"><td style="width:12%;text-align:center;vertical-align:top;border-left:2px solid #000000">'.Date_Convertir(mysql_result($req,$i-1,'date_donnee'),"Y-m-d",$Format_Date_PHP).'</td>';
    $req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'id_matiere')."'");
    if (mysql_num_rows($req2)!="")
    {
      $valeur=mysql_result($req2,0,'intitule');
    }
    else
    {
      $valeur=$liste_choix['matieres_cj'][mysql_result($req,$i-1,'devoirs.id_matiere')];
    }
    $contenu_html=$contenu_html.'<td style="width:20%;text-align:center;vertical-align:top">'.$valeur.'</td>';
    $contenu_html=$contenu_html.'<td style="width:68%;text-align:left;vertical-align:top">-&nbsp;'.str_replace("\n","<br>-&nbsp;",mysql_result($req,$i-1,'contenu')).'</td></tr>';
	if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
  }
  if (mysql_num_rows($req)!="")
  {
    $contenu_html=$contenu_html.'</table>';
  }

  $_GET['orientation']="P";
  $_GET['couleur']="G";
	$_GET['numerotation']="T";
  include "commun/impression.php";
?>
