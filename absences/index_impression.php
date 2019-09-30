<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=$_SESSION['annee_scolaire'];
  }
  else
  {
    if ($id_mois<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=$_SESSION['annee_scolaire']+1; } else { $annee=$_SESSION['annee_scolaire']; }
  }
  $msg='<div class="titre_page_droite" style="width:100%;">'.$Langue['LBL_IMPRESSION_ABSENCES'].'</div><br /><br />';
  $msg2='<div class="titre_page_gauche" style="width:100%;">'.$liste_choix['mois'][$id_mois].' '.$annee.'</div><br /><br />';
  $msg=$msg.'<table class="display" cellpadding=0 cellspacing=0><tr><th class="centre" style="width:25%;">'.$Langue['LBL_IMPRESSION_ELEVES'].'</th>';
  $msg2=$msg2.'<table class="display" cellpadding=0 cellspacing=0><tr>';
  $nb_jour=date("t",mktime(0,0,0,$id_mois,1,$annee));
  for ($i=1;$i<=$nb_jour;$i++)
  {
    $jour=date("w",mktime(0,0,0,$id_mois,$i,$annee));
    if ($i<=15)
    {
      $msg=$msg.'<th class="centre" style="width:5%;">'.$liste_choix['jour_court'][$jour].'<br />'.$i.'</th>';
    }
    else
    {
      $msg2=$msg2.'<th class="centre" style="width:5%;">'.$liste_choix['jour_court'][$jour].'<br />'.$i.'</th>';
    }
    $nb_eleves_matin[$i]=0;
    $nb_eleves_am[$i]=0;
    $nb_presents_matin[$i]=0;
    $nb_presents_am[$i]=0;
  }
  $largeur=100-($nb_jour-15)*5;
  $msg2=$msg2.'<th class="centre" style="width:'.$largeur.'%;">'.$Langue['LBL_IMPRESSION_JUSTIFICATIFS'].'</th>';
  $msg=$msg.'</tr>';
  $msg2=$msg2.'</tr>';

  $premier_jour_mois=date("Y-m-d",mktime(0,0,0,$id_mois,1,$annee));
  $premier_jour_mois_suivant=date("Y-m-d",mktime(0,0,0,$id_mois+1,1,$annee));
  if ($_SESSION['id_classe_cours']=="")
  {
    $req=mysql_query("SELECT eleves.* FROM `eleves` WHERE (eleves.date_entree<'$premier_jour_mois_suivant' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='$premier_jour_mois')) ORDER BY eleves.nom ASC, eleves.prenom ASC");
  }
  else
  {
    $req=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE (eleves.date_entree<'$premier_jour_mois_suivant' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='$premier_jour_mois')) AND eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_eleve=eleves.id ORDER BY eleves.nom ASC, eleves.prenom ASC");
  }

  $index_justificatif=Array();
  $ligne="even";
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $liste_justificatif="";
    $msg=$msg.'<tr class="'.$ligne.'">';
    $msg2=$msg2.'<tr class="'.$ligne.'">';
    $msg=$msg.'<td class="gauche" style="width:25%;">'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom');
    $msg=$msg.'</td>';
    for ($j=1;$j<=$nb_jour;$j++)
    {
//      unset($index_justificatif);
      $param_plus="";
      // Récupération des vacances scolaires et jours fériés
      $date_en_cours=date("Y-m-d",mktime(0,0,0,$id_mois,$j,$annee));
      $req_vacances=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".$date_en_cours."' AND date_fin>='".$date_en_cours."' AND zone='".$gestclasse_config_plus['zone']."'");
      $req_feries=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".$date_en_cours."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");

      $jour=date("w",mktime(0,0,0,$id_mois,$j,$annee));
      if ($jour!=0 && mysql_num_rows($req_vacances)=="" && mysql_num_rows($req_feries)=="")
      // Si le jour n'est pas dimanche ou un jour de vacances
      {
        // On regarde si l'élève est arrivé durant le mois
        if (mysql_result($req,$i-1,'date_entree')>date("Y-m-d",mktime(0,0,0,$id_mois,$j,$annee)))
        {
          if ($j<=15) { $msg=$msg.'<td class="centre sorti">&nbsp;</td>'; } else { $msg2=$msg2.'<td align=center class=sorti>&nbsp;</td>'; }
        }
        else
        {
          // On regarde si l'élève est sorti durant le mois
          if (mysql_result($req,$i-1,'date_sortie')!="0000-00-00" && mysql_result($req,$i-1,'date_sortie')<=date("Y-m-d",mktime(0,0,0,$id_mois,$j,$annee)))
          {
            if ($j<=15) { $msg=$msg.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; } else { $msg2=$msg2.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; }
          }
          else
          {
            // On vérifie que c'est un jour travaillé soit le matin ou l'après-midi
            if ($gestclasse_config_plus['matin'.$jour]=="1" || $gestclasse_config_plus['am'.$jour]=="1")
            {
              if ($gestclasse_config_plus['matin'.$jour]=="1") { $nb_eleves_matin[$j]=$nb_eleves_matin[$j]+1; }
              if ($gestclasse_config_plus['am'.$jour]=="1") { $nb_eleves_am[$j]=$nb_eleves_am[$j]+1; }
              if ($gestclasse_config_plus['matin'.$jour]=="0") { $nb_eleves_matin[$j]=-1; }
              if ($gestclasse_config_plus['am'.$jour]=="0") { $nb_eleves_am[$j]=-1; }
              // On recherche si l'élève est absent
              $req_absence=mysql_query("SELECT * FROM `absences` WHERE date='".$date_en_cours."' AND id_eleve='".mysql_result($req,$i-1,'eleves.id')."'");
              // Elève non absent
              if (mysql_num_rows($req_absence)=="")
              {
                if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/absence_vide.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/absence_vide.png" width=15 height=15 border=0></td>'; }
                if ($gestclasse_config_plus['matin'.$jour]=="1") { $nb_presents_matin[$j]=$nb_presents_matin[$j]+1; }
                if ($gestclasse_config_plus['am'.$jour]=="1") { $nb_presents_am[$j]=$nb_presents_am[$j]+1; }
              }
              else
              // Elève absent à un moment dans la journée
              {
                $req1=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='".mysql_result($req,$i-1,'eleves.id')."' AND date_debut<'$date_en_cours' AND date_fin>'$date_en_cours'"); // Valide une absence si la date est comprise entre les deux dates d'un justificatif
                $req2=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='".mysql_result($req,$i-1,'eleves.id')."' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin quand le justificatif est sur une journée
                $req3=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='".mysql_result($req,$i-1,'eleves.id')."' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi quand le justificatif est sur une journée
                $req4=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='".mysql_result($req,$i-1,'eleves.id')."' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin ou la journée quand le justificatif commence le jour de l'absence
                $req5=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='".mysql_result($req,$i-1,'eleves.id')."' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours'"); // Valide une absence l'après-midi quand le justificatif commence le jour de l'absence
                $req6=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='".mysql_result($req,$i-1,'eleves.id')."' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours'"); // Valide une absence le matin quand le justificatif se termine le jour de l'absence
                $req7=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='".mysql_result($req,$i-1,'eleves.id')."' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi ou la journée quand le justificatif se termine le jour de l'absence
                if (mysql_num_rows($req1)!="" || mysql_num_rows($req2)!="" || mysql_num_rows($req4)!="" || mysql_num_rows($req6)!="")
                {
                  $matin=true;
                  if (mysql_num_rows($req1)!="")
                  {
                    if (!in_array(mysql_result($req1,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req1,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req1,0,'type')].', '; }
                  }
                  if (mysql_num_rows($req2)!="")
                  {
                    if (!in_array(mysql_result($req2,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req2,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req2,0,'type')].', '; }
                  }
                  if (mysql_num_rows($req4)!="")
                  {
                    if (!in_array(mysql_result($req4,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req4,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req4,0,'type')].', '; }
                  }
                  if (mysql_num_rows($req6)!="")
                  {
                    if (!in_array(mysql_result($req6,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req6,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req6,0,'type')].', '; }
                  }
                } else { $matin=false; }
                if (mysql_num_rows($req1)!="" || mysql_num_rows($req3)!="" || mysql_num_rows($req5)!="" || mysql_num_rows($req7)!="")
                {
                  $am=true;
                  if (mysql_num_rows($req1)!="")
                  {
                    if (!in_array(mysql_result($req1,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req1,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req1,0,'type')].', '; }
                  }
                  if (mysql_num_rows($req3)!="")
                  {
                    if (!in_array(mysql_result($req3,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req3,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req3,0,'type')].', '; }
                  }
                  if (mysql_num_rows($req5)!="")
                  {
                    if (!in_array(mysql_result($req5,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req5,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req5,0,'type')].', '; }
                  }
                  if (mysql_num_rows($req7)!="")
                  {
                    if (!in_array(mysql_result($req7,0,'id'),$index_justificatif)) { $index_justificatif[]=mysql_result($req7,0,'id'); $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req7,0,'type')].', '; }
                  }
                } else { $am=false; }
                // Absence le matin mais pas l'après-midi
                if (mysql_result($req_absence,0,'matin')=="1" && mysql_result($req_absence,0,'apres_midi')=="0")
                {
                  if ($gestclasse_config_plus['matin'.$jour]=="1")
                  {
                    if ($gestclasse_config_plus['am'.$jour]=="1") { $nb_presents_am[$j]=$nb_presents_am[$j]+1; }
                    if ($matin==true)
                    {
                      if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/impression_excuse_matin.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/impression_excuse_matin.png" width=15 height=15 border=0></td>'; }
                    }
                    else
                    {
                      if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/impression_non_excuse_matin.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/impression_non_excuse_matin.png" width=15 height=15 border=0></td>'; }
                    }
                  }
                  else
                  {
                    if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/absence_vide.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/absence_vide.png" width=15 height=15 border=0></td>'; }
                  }
                }
                // Absence l'après-midi mais pas le matin
                if (mysql_result($req_absence,0,'matin')=="0" && mysql_result($req_absence,0,'apres_midi')=="1")
                {
                  if ($gestclasse_config_plus['am'.$jour]=="1")
                  {
                    if ($gestclasse_config_plus['matin'.$jour]=="1") { $nb_presents_matin[$j]=$nb_presents_matin[$j]+1; }
                    if ($am==true)
                    {
                      if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/impression_excuse_am.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/impression_excuse_am.png" width=15 height=15 border=0></td>'; }
                    }
                    else
                    {
                      if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/impression_non_excuse_am.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/impression_non_excuse_am.png" width=15 height=15 border=0></td>'; }
                    }
                  }
                  else
                  {
                    if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/absence_vide.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/absence_vide.png" width=15 height=15 border=0></td>'; }
                  }
                }
                // Absence toute la journée
                if (mysql_result($req_absence,0,'matin')=="1" && mysql_result($req_absence,0,'apres_midi')=="1")
                {
                  if ($gestclasse_config_plus['matin'.$jour]=="1" && $gestclasse_config_plus['am'.$jour]=="1")
                  {
                    $image="impression_excuse_journee";
                    if ($matin==false && $am==false) { $image="impression_non_excuse_journee"; }
                    // Justificatif pour le matin mais pas l'après-midi
                    if ($matin==true && $am==false) { $image="impression_excuse_journee_matin"; }
                    // Justificatif pour l'après-midi mais pas le matin
                    if ($matin==false && $am==true) { $image="impression_excuse_journee_am"; }
                  }
                  if ($gestclasse_config_plus['matin'.$jour]=="1" && ($gestclasse_config_plus['am'.$jour]=="0" || $gestclasse_config_plus['am'.$jour]==""))
                  {
                    $image="impression_excuse_matin";
                    if ($matin==false) { $image="impression_non_excuse_matin"; }
                  }
                  if (($gestclasse_config_plus['matin'.$jour]=="" || $gestclasse_config_plus['matin'.$jour]=="0") && $gestclasse_config_plus['am'.$jour]=="1")
                  {
                    $image="impression_excuse_am";
                    if ($am==false) { $image="impression_non_excuse_am"; }
                  }
                  if ($j<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><img src="images/'.$image.'.png" width=15 height=15 border=0></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><img src="images/'.$image.'.png" width=15 height=15 border=0></td>'; }
                }
              }
            }
            else
            {
              $nb_eleves_matin[$j]=-1;
              $nb_eleves_am[$j]=-1;
              if ($j<=15) { $msg=$msg.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; } else { $msg2=$msg2.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; }
            }
          }
        }
      }
      else
      // Le jour est un dimanche, un jour férié ou un jour de vacances
      {
        if ($j<=15) { $msg=$msg.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; } else { $msg2=$msg2.'<td style="width:5%;" class="centre sorti">&nbsp;</td>'; }
        $nb_eleves_matin[$j]=-1;
        $nb_eleves_am[$j]=-1;
      }
    }
    $msg=$msg.'</tr>';
    $msg2=$msg2.'<td class="gauche" style="width:'.$largeur.'%;">'.substr($liste_justificatif,0,strlen($liste_justificatif)-2).'</td></tr>';
    if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
  }
  
  // Affichage du tableau de statistique
  $total_absence=0;
  $total_eleve=0;

  $msg=$msg.'<tr class="'.$ligne.'"><td class="gauche" style="width:25%;"><strong>'.$Langue['LBL_IMPRESSION_MATIN_ABSENTS'].'</strong></td>';
  $msg2=$msg2.'<tr class="'.$ligne.'">';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_matin[$i]!=-1)
    {
      $nb=$nb_eleves_matin[$i]-$nb_presents_matin[$i];
      $total_absence=$total_absence+$nb;
      $total_eleve=$total_eleve+$nb_eleves_matin[$i];
      if ($i<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><strong>'.$nb.'</strong></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><strong>'.$nb.'</strong></td>'; }
    }
    else
    {
      if ($i<=15) { $msg=$msg.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; } else { $msg2=$msg2.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; }
    }
  }
  $msg=$msg.'</tr>';
  $msg2=$msg2.'<td class="gauche" style="width:'.$largeur.'%;">&nbsp;</td></tr>';

  $msg=$msg.'<tr class="'.$ligne.'"><td class="gauche" style="width:25%;"><strong>'.$Langue['LBL_IMPRESSION_MATIN_PRESENTS'].'</strong></td>';
  $msg2=$msg2.'<tr class="'.$ligne.'">';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_matin[$i]!=-1)
    {
      if ($i<=15) { $msg=$msg.'<td  class="centre" style="width:5%;"><strong>'.$nb_presents_matin[$i].'</strong></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><strong>'.$nb_presents_matin[$i].'</strong></td>'; }
    }
    else
    {
      if ($i<=15) { $msg=$msg.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; } else { $msg2=$msg2.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; }
    }
  }
  $msg=$msg.'</tr>';
  $msg2=$msg2.'<td class="gauche" style="width:'.$largeur.'%;">&nbsp;</td></tr>';

  $msg=$msg.'<tr class="'.$ligne.'"><td class="gauche" style="width:25%;"><strong>'.$Langue['LBL_IMPRESSION_AM_ABSENTS'].'</strong></td>';
  $msg2=$msg2.'<tr class="'.$ligne.'">';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_am[$i]!=-1)
    {
      $nb=$nb_eleves_am[$i]-$nb_presents_am[$i];
      $total_absence=$total_absence+$nb;
      $total_eleve=$total_eleve+$nb_eleves_am[$i];
      if ($i<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><strong>'.$nb.'</strong></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><strong>'.$nb.'</strong></td>'; }
    }
    else
    {
      if ($i<=15) { $msg=$msg.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; } else { $msg2=$msg2.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; }
    }
  }
  $msg=$msg.'</tr>';
  $msg2=$msg2.'<td class="gauche" style="width:'.$largeur.'%;">&nbsp;</td></tr>';

  $msg=$msg.'<tr class="'.$ligne.'"><td class="gauche" style="width:25%;"><strong>'.$Langue['LBL_IMPRESSION_AM_PRESENTS'].'</strong></td>';
  $msg2=$msg2.'<tr class="'.$ligne.'">';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_am[$i]!=-1)
    {
      if ($i<=15) { $msg=$msg.'<td class="centre" style="width:5%;"><strong>'.$nb_presents_am[$i].'</strong></td>'; } else { $msg2=$msg2.'<td class="centre" style="width:5%;"><strong>'.$nb_presents_am[$i].'</strong></td>'; }
    }
    else
    {
      if ($i<=15) { $msg=$msg.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; } else { $msg2=$msg2.'<td class="centre sorti" style="width:5%;">&nbsp;</td>'; }
    }
  }
  $msg=$msg.'</tr>';
  $msg2=$msg2.'<td class="gauche" style="width:'.$largeur.'%;">&nbsp;</td></tr>';

  $msg=$msg.'</table>';
  $msg2=$msg2.'</table>';
  if ($total_eleve!=0)
  {
    $msg2=$msg2.'<br /><br /><br /><div class="textcentre font16"><strong><u>'.$Langue['LBL_TAUX_ABSENCE'].'</u> : '.number_format(100*$total_absence/$total_eleve,2,","," ").' %</strong></div>';
  }
  else
  {
    $msg2=$msg2.'<br /><br /><br /><div class="textcentre font16"><strong><u>'.$Langue['LBL_TAUX_ABSENCE'].'</u> : 0,00 %</strong></div>';
  }
  
  if ($gestclasse_config_plus['signature_registre']=="1")
  {
	  $parametre="absences|".$id_mois."|".$annee."|".$_SESSION['id_classe_cours'];
	  $req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
	  if (mysql_num_rows($req_signature)!="")
	  {
		$req_p=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req_signature,0,'id_util')."'");
		$par=mysql_result($req_p,0,'nom').' '.mysql_result($req_p,0,'prenom');
		$msg2=$msg2.'<br /><br /><div class="textcentre">'.$Langue['MSG_SIGNATURE_SIGNE_PAR'].' <strong>'.$par.'</strong> '.$Langue['MSG_SIGNATURE_LE'].' <strong>'.Date_Convertir(mysql_result($req_signature,0,'date'),'Y-m-d',$Format_Date_PHP).'</strong><br />'.$Langue['MSG_SIGNATURE_CLE'].' : <strong>'.mysql_result($req_signature,0,'id').'</strong></div>';
	  }
  }
  
  if (file_exists("commun/mpdf/mpdf.php"))
  {
    $contenu_html=$msg."<pagebreak />".$msg2;
  }
  else
  {
    $contenu_html=$msg.'</page><page pageset="old">'.$msg2;
  }
?>