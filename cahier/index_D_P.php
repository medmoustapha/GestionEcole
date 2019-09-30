<?php
  Param_Utilisateur($_SESSION['id_util'],$_SESSION['annee_scolaire']);
  if (!isset($_SESSION['date_en_cours'])) { $_SESSION['date_en_cours']=date("Y-m-d"); }
  $datation=Date_Convertir($_SESSION['date_en_cours'],'Y-m-d',$Format_Date_PHP);
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $jour_max=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $an=$_SESSION['annee_scolaire']+1;
	  $jour_max=$an.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  $req_classe_id=mysql_query("SELECT classes.*,classes_profs.* FROM `classes`,`classes_profs` WHERE classes_profs.id_prof='".$_SESSION['id_util']."' AND classes_profs.id_classe=classes.id AND classes.annee='".$_SESSION['annee_scolaire']."' AND (classes_profs.type='T' OR classes_profs.type='E' OR classes_profs.type='D') ORDER BY classes_profs.type DESC");
  if (mysql_num_rows($req_classe_id)=="")
  {
?>
    <div class="titre_page"><?php echo $Langue['LBL_CAHIER_JOURNAL_DU']; ?> <?php echo $datation; ?></div>
    <div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div><br><br><br>
	<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong><?php echo $Langue['MSG_CJ_PAS_UTIL']; ?></strong></div></div>	
<?php
  }
  else
  {
?>
<div class="titre_page"><?php echo $Langue['LBL_CAHIER_JOURNAL_DU']; ?> <?php echo $datation; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>

<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
  <td class="textdroite" valign=middle nowrap>
    <div class="ui-widget ui-state-default ui-corner-right floatdroite ui-div-listview textcentre"><input type="checkbox" id="affiche_devoirs" name="affiche_devoirs" <?php if ($_SESSION['affiche_devoirs']=="1") { echo 'value="0" CHECKED'; } else { echo 'value="1"'; } ?>> <?php echo $Langue['LBL_AFFICHE_DEVOIRS_DANS_CJ']; ?></div>
    <div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_DATE']; ?> : <input type="text" class="text ui-widget-content ui-corner-all" id="date_s" name="date_s" value="<?php echo Date_Convertir($_SESSION['date_en_cours'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></div>
  </td>
</tr>
<tr>
  <td class="textgauche">
    <input type="Button" id="AjouterSequence" name="AjouterSequence" value="<?php echo $Langue['BTN_AJOUTER_SEANCE']; ?>">&nbsp;<input type="Button" id="AjouterDevoirs" name="AjouterDevoirs" value="<?php echo $Langue['BTN_AJOUTER_DEVOIRS_HORS_SEANCE']; ?>">&nbsp;<input type="Button" id="VoirCJ" name="VoirCJ" value="<?php echo $Langue['BTN_VUE_PARENTS']; ?>">&nbsp;<input type="Button" id="Imprimer" name="Imprimer" value="<?php echo $Langue['BTN_IMPRIMER']; ?>">
  </td>
</tr>
</table>
<?php
  // On recherche si la date en cours est un jour de vacances, un jour férié ou un jour non travaillé
  $date=$_SESSION['date_en_cours'];
  $travaille=0;
  // Est-ce un jour de vacances ?
  $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='$date' AND date_fin>='$date' AND zone='".$gestclasse_config_plus['zone']."'");
  if (mysql_num_rows($req)=="")
  // Si ce n'est pas un jour de vacances, on regarde si c'est un jour férié
  {
    $req=mysql_query("SELECT * FROM `dates_speciales` WHERE date='$date' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
    if (mysql_num_rows($req)=="")
    // Si ce n'est pas un jour férié, on regarde quand on travaille
    {
      $date=date("w",mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4)));
      if ($date!="0")
      // Si ce n'est pas un dimanche
      {
        // Travaille-t-on le matin ou l'après-midi
        if ($gestclasse_config_plus['matin'.$date]=="0" && $gestclasse_config_plus['am'.$date]=="0") { $travaille=4; }
      }
      else
      {
        $travaille=3;
      }
    }
    else
    {
      $travaille=2;
    }
  }
  else
  {
    $travaille=1;
  }
  switch ($travaille)
  {
    case '1':
      echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_PAS_CJ_VACANCES'].'</strong></div></div>';
      break;
    case '2':
      echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_PAS_CJ_FERIE'].'</strong></div></div>';
      break;
    case '3':
      echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_PAS_CJ_DIMANCHE'].'</strong></div></div>';
      break;
    case '4':
      echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_PAS_CJ_PAS_TRAVAIL'].'</strong></div></div>';
      break;
    default:
      /********************************/
      /* On affiche le cahier-journal */
      /********************************/

      // On récupère les classes et niveaux dans le cahier-journal
      $req_niveau=mysql_query("SELECT listes.*,cahierjournal.*,classes_profs.* FROM `classes_profs`,`listes`,`cahierjournal` WHERE cahierjournal.id_prof='".$_SESSION['id_util']."' AND cahierjournal.date='".$_SESSION['date_en_cours']."' AND cahierjournal.id_niveau=listes.id AND classes_profs.id_classe=cahierjournal.id_classe AND classes_profs.id_prof=cahierjournal.id_prof ORDER BY classes_profs.type DESC, listes.ordre ASC");
      $nb_niveau=0;
      $niveau=Array();
      $classe=Array();
      for ($i=1;$i<=mysql_num_rows($req_niveau);$i++)
      {
        $trouve=false;
        for ($j=1;$j<=count($niveau);$j++)
        {
          if ($niveau[$j-1]==mysql_result($req_niveau,$i-1,'cahierjournal.id_niveau') && $classe[$j-1]==mysql_result($req_niveau,$i-1,'cahierjournal.id_classe'))
          {
            $trouve=true;
          }
        }
        if ($trouve==false)
        {
          $niveau[]=mysql_result($req_niveau,$i-1,'cahierjournal.id_niveau');
          $classe[]=mysql_result($req_niveau,$i-1,'cahierjournal.id_classe');
          $nb_niveau++;
          $actuel[]=1;
          $premier[]=1;
        }
      }
      // On récupère l'heure de début et l'heure de fin de la journée
      $req=mysql_query("SELECT * FROM `cahierjournal` WHERE id_prof='".$_SESSION['id_util']."' AND date='".$_SESSION['date_en_cours']."' ORDER BY heure_debut ASC");
      $req2=mysql_query("SELECT * FROM `cahierjournal` WHERE id_prof='".$_SESSION['id_util']."' AND date='".$_SESSION['date_en_cours']."' ORDER BY heure_fin DESC");
      if ($gestclasse_config_plus['matin'.$date]=="1") { $heure_debut=$gestclasse_config_plus['jour_matin_debut']; } else { $heure_debut=$gestclasse_config_plus['jour_am_debut']; }
      if ($gestclasse_config_plus['am'.$date]=="1") { $heure_fin=$gestclasse_config_plus['jour_am_fin']; } else { $heure_fin=$gestclasse_config_plus['jour_matin_fin']; }
      if (mysql_num_rows($req)!="")
      {
        if (Compare_Heure(mysql_result($req,0,'heure_debut'),$heure_debut.':00')<0) { $heure_debut=substr(mysql_result($req,0,'heure_debut'),0,5); }
      }
      if (mysql_num_rows($req2)!="")
      {
        if (Compare_Heure(mysql_result($req2,0,'heure_fin'),$heure_fin.':00')>0) { $heure_fin=substr(mysql_result($req2,0,'heure_fin'),0,5); }
      }
      
      // On construit les entêtes de la page
      if ($nb_niveau!=0)
      {
        if (500*$nb_niveau+145>$_SESSION['largeur_ecran'])
        {
          $largeur=500;
          $largeur_div=$_SESSION['largeur_ecran']-75;
          echo '<div style="width:'.$largeur_div.'px;overflow:auto;">';
          $largeur_table=$nb_niveau*$largeur+70;
          $fin_div=true;
        }
        else
        {
          $fin_div=false;
          $largeur=($_SESSION['largeur_ecran']-145)/$nb_niveau;
          $largeur_table=$nb_niveau*$largeur+70;
        }
      }
	  else
	  {
	    $fin_div=false;
	    $largeur_table=$_SESSION['largeur_ecran']-75;
	  }
      echo '<table cellspacing=0 cellpadding=0 border=0 style="width:'.$largeur_table.'px" class="heures">';
      echo '<thead><tr><th class="ui-state-default" style="text-align:center;width:70px;border-bottom:0px">'.$Langue['LBL_HORAIRES'].'</th>';
      if ($nb_niveau!=0)
      {
        for ($i=1;$i<=$nb_niveau;$i++)
        {
          $req_c=mysql_query("SELECT * FROM `classes` WHERE id='".$classe[$i-1]."'");
          $req_n=mysql_query("SELECT * FROM `listes` WHERE id='".$niveau[$i-1]."'");
          echo '<th style="text-align:center;width:'.$largeur.'px;border-left:0px;border-bottom:0px" class="ui-state-default">'.mysql_result($req_c,0,'nom_classe').' - '.mysql_result($req_n,0,'intitule').'</th>';
        }
      }
      else
      // Cas où il n'y a rien dans le cahier-journal
      {
        $largeur=($_SESSION['largeur_ecran']-190);
        echo '<th style="text-align:center;width:'.$largeur.'px" class="ui-state-default">&nbsp;</th>';
      }
      echo '</tr></thead><tbody>';
      $ligne_en_cours=0;
      for ($i=8;$i<=18;$i++)
      {
        for ($j=0;$j<=55;$j=$j+5)
        {
          $heure=date("H:i",mktime($i,$j,0,date("m"),date("d"),date("Y")));
          // On regarde si l'heure est comprise entre les horaires de travail
          if ($heure>=$heure_debut && $heure<=$heure_fin)
          {
            // On regarde si on est à la pause méridienne
            if ($heure<=$gestclasse_config_plus['jour_matin_fin'] || $heure>=$gestclasse_config_plus['jour_am_debut'])
            {
              if ($heure==$gestclasse_config_plus['jour_matin_fin'] && $gestclasse_config_plus['am'.$date]=="1")
              {
                $ligne_en_cours=$ligne_en_cours+1;
                echo '<tr class=sorti><td class="heure" valign=top style="width:50px;color:#000000">'.$heure.'</td>';
                if ($nb_niveau!=0)
                {
                  for ($k=1;$k<=$nb_niveau;$k++)
                  {
                    echo '<td class="contenu sorti" style="text-align:center;width:'.$largeur.'px;color:#000000"><strong><br />'.$Langue['LBL_PAUSE_MIDI'].'<br /><br /></strong></td>';
                    $actuel[$k-1]=$actuel[$k-1]+1;
										$premier[$k-1]="1";
                  }
                }
                else
                {
                  echo '<td class="contenu sorti" style="text-align:center;width:'.$largeur.'px;color:#000000"><strong><br />'.$Langue['LBL_PAUSE_MIDI'].'<br /><br /></strong></td>';
                }
              }
              else
              // Si ce n'est pas la pause méridienne
              {
                $ligne_en_cours=$ligne_en_cours+1;
                $extension="";
                if ($heure==$gestclasse_config_plus['jour_matin_fin'] && $gestclasse_config_plus['am'.$date]==0) { $extension='_fin'; }
                if ($heure==$heure_fin) { $extension='_fin'; }
                if ($j==0 || $j==15 || $j==30 || $j==45 || $heure==$gestclasse_config_plus['jour_am_debut'] || $heure==$heure_fin || $heure==$heure_debut) { echo '<tr><td class="heure'.$extension.'" valign=top style="width:50px">'.$heure.'</td>'; } else { echo '<tr><td class="heure_non_marquee" style="width:50px">&nbsp;</td>'; }
                if ($nb_niveau!=0)
                {
                  for ($k=1;$k<=$nb_niveau;$k++)
                  {
                    // On recherche si on a quelque chose pour la classe et le niveau
                    $req=mysql_query("SELECT * FROM `cahierjournal` WHERE id_prof='".$_SESSION['id_util']."' AND date='".$_SESSION['date_en_cours']."' AND id_classe='".$classe[$k-1]."' AND id_niveau='".$niveau[$k-1]."' AND heure_debut>='$heure' ORDER BY heure_debut ASC");
                    if (mysql_num_rows($req)!="")
                    {
                      // Il y a quelque chose à l'heure où on est arrivé dans le tableau => On affiche le contenu de la séance
                      if ($heure.":00"==mysql_result($req,0,'heure_debut'))
                      {
                        $rowspan=(mktime(substr(mysql_result($req,0,'heure_fin'),0,2),substr(mysql_result($req,0,'heure_fin'),3,2),0,date("m"),date("d"),date("Y"))-mktime($i,$j,0,date("m"),date("d"),date("Y")))/300;
                        if (mysql_result($req,0,'id_matiere')!="RECRE")
                        {
                          echo '<td class="contenu'.$extension.' textgauche" rowspan="'.$rowspan.'" style="width:'.$largeur.'px" valign=top>';
                          $req2=mysql_query("SELECT * FROM `listes` WHERE nom_liste='matieres_cj' AND id='".mysql_result($req,0,'id_matiere')."'");
                          if (mysql_num_rows($req2)=="")
                          {
                            $valeur=$liste_choix['matieres_cj'][mysql_result($req,0,'id_matiere')];
                          }
                          else
                          {
                            $valeur=mysql_result($req2,0,'intitule');
                          }
                          echo '<font class="sous_titre_cj">'.$Langue['LBL_MATIERE_HORAIRES'].' : </font>'.$valeur.' ('.$Langue['LBL_DE'].' '.substr(mysql_result($req,0,'heure_debut'),0,5).' '.$Langue['LBL_A'].' '.substr(mysql_result($req,0,'heure_fin'),0,5).')';
                          echo '<div class="floatdroite"><a title="'.$Langue['MSG_MODIFIER_SEANCE'].'" href="#null" onClick="Cahier_L_Charge_Seance(\''.mysql_result($req,0,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['MSG_SUPPRIMER_SEANCE'].'" href="#null" onClick="Cahier_L_Supprime_Seance(\''.mysql_result($req,0,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></div><br /><br />';
                          if (mysql_result($req,0,'objectifs')!="")
                          {
                            echo '<div class="sous_titre_cj">'.$Langue['LBL_OBJECTIFS'].' :</div><ul class="explic" style="margin-top:0px"><li>'.str_replace("\n","</li><li>",mysql_result($req,0,'objectifs')).'</li></ul><br />';
                          }
                          if (mysql_result($req,0,'materiel')!="")
                          {
                            echo '<div class="sous_titre_cj">'.$Langue['LBL_MATERIEL'].' :</div><ul class="explic" style="margin-top:0px"><li>'.str_replace("\n","</li><li>",mysql_result($req,0,'materiel')).'</li></ul><br />';
                          }
                          echo '<div class="sous_titre_cj">'.$Langue['LBL_DEROULEMENT'].' :</div><div style="padding-left:30px"><p class="explic">'.mysql_result($req,0,'contenu').'</p></div>';
                          if ($_SESSION['affiche_devoirs']=="1")
                          {
                            $req3=mysql_query("SELECT * FROM `devoirs` WHERE id_seance='".mysql_result($req,0,'id')."'");
                            if (mysql_num_rows($req3)!="")
                            {
                              echo '<br /><div class="sous_titre_cj">'.$Langue['LBL_DEVOIRS_A_FAIRE_POUR2'].' '.Date_Convertir(mysql_result($req3,0,'date_faire'),"Y-m-d",$Format_Date_PHP).' :</div><ul class="explic" style="margin-top:0px"><li>'.str_replace("\n","</li><li>",mysql_result($req3,0,'contenu')).'</li></ul>';
                            }
                          }
                        }
                        else
                        {
                          echo '<td class="contenu'.$extension.' sorti" rowspan="'.$rowspan.'" style="text-align:center;width:'.$largeur.'px;color:#000000" valign=middle><div class="floatdroite"><a title="'.$Langue['MSG_MODIFIER_RECREATION'].'" href="#null" onClick="Cahier_L_Charge_Seance(\''.mysql_result($req,0,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['MSG_SUPPRIMER_RECREATION'].'" href="#null" onClick="Cahier_L_Supprime_Seance(\''.mysql_result($req,0,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></div><strong>'.$Langue['LBL_RECREATION'].'</strong>';
                        }
                        echo '</td>';
                        $actuel[$k-1]=$actuel[$k-1]+$rowspan;
                      }
                      else
                      // Il y a quelque chose mais à une heure postérieure à l'heure où on est arrivé => On affiche une cellule vide
                      {
                        if ($actuel[$k-1]==$ligne_en_cours)
                        {
                          if (mysql_result($req,0,'heure_debut')>=$gestclasse_config_plus['jour_am_debut'] && $heure<=$gestclasse_config_plus['jour_matin_fin'])
                          {
                            $rowspan=(mktime(substr($gestclasse_config_plus['jour_matin_fin'],0,2),substr($gestclasse_config_plus['jour_matin_fin'],3,2),0,date("m"),date("d"),date("Y"))-mktime($i,$j,0,date("m"),date("d"),date("Y")))/300;
                          }
                          else
                          {
                            $rowspan=(mktime(substr(mysql_result($req,0,'heure_debut'),0,2),substr(mysql_result($req,0,'heure_debut'),3,2),0,date("m"),date("d"),date("Y"))-mktime($i,$j,0,date("m"),date("d"),date("Y")))/300;
                          }
                          echo '<td rowspan="'.$rowspan.'" class="contenu'.$extension.' sorti textgauche" style="width:'.$largeur.'px" valign=top>&nbsp;</td>';
                          $actuel[$k-1]=$actuel[$k-1]+$rowspan;
                        }
                      }
                    }
                    else
                    // Il n'y a plus rien dans le cahier-journal => On affiche une cellule vide
                    {
                      if ($actuel[$k-1]==$ligne_en_cours)
                      {
                        if ($extension=="")
                        {
						  if ($premier[$k-1]=="1")
						  {
                            echo '<td style="width:'.$largeur.'px" class="contenu sorti textgauche" valign=top>&nbsp;</td>';
							$premier[$k-1]="0";
  						  }
						  else
						  {
                            echo '<td style="width:'.$largeur.'px" class="contenu pas_haut sorti textgauche" valign=top>&nbsp;</td>';
						  }
                        }
                        else
                        {
                          echo '<td style="width:'.$largeur.'px" class="contenu_fin textgauche" valign=top>&nbsp;</td>';
                        }
                        $actuel[$k-1]=$actuel[$k-1]+1;
                      }
                    }
                  }
                }
                else
                // Cas où il n'y a encore rien dans le cahier-journal pour le jour donné
                {
                  if ($extension=="")
                  {
                    echo '<td class="sorti textcentre" style="width:'.$largeur.'px">&nbsp;</td>';
                  }
                  else
                  {
                    echo '<td style="width:'.$largeur.'px" class="contenu_fin textgauche" valign=top>&nbsp;</td>';
                  }
                }
              }
              echo '</tr>';
            }
          }
        }
      }
      echo '</tbody></table>';
      if ($fin_div==true) { echo '</div>'; }
      echo '<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td class="textgauche" width=100%>';
      echo '<input type="Button" id="AjouterSequence2" name="AjouterSequence2" value="'.$Langue['BTN_AJOUTER_SEANCE'].'">&nbsp;<input type="Button" id="AjouterDevoirs2" name="AjouterDevoirs2" value="'.$Langue['BTN_AJOUTER_DEVOIRS_HORS_SEANCE'].'">&nbsp;<input type="Button" id="VoirCJ2" name="VoirCJ2" value="'.$Langue['BTN_VUE_PARENTS'].'">&nbsp;<input type="Button" id="Imprimer2" name="Imprimer2" value="'.$Langue['BTN_IMPRIMER'].'"></td></tr></table>';


      /*******************************************************/
      /* On affiche les devoirs donnés pour le jour en cours */
      /*******************************************************/
      if ($_SESSION['affiche_devoirs']=="1")
      {
        $req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.id_seance='' AND devoirs.date_faire='".$_SESSION['date_en_cours']."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
      }
      else
      {
        $req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.date_faire='".$_SESSION['date_en_cours']."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
      }
      echo '<br /><br /><div class="titre_page">'.$Langue['LBL_DEVOIRS_A_FAIRE_POUR2'].' '.$datation.'</div>';
      echo '<table id="listing_devoirs_donnes" class="display" cellpadding=0 cellspacing=0 style="width:100%"><thead><tr>';
      echo '<th align=center>'.$Langue['LBL_CLASSE'].'</th>';
      echo '<th style="width:8%" align=center>'.$Langue['LBL_DEVOIRS_DONNES_LE2'].'</th>';
      echo '<th style="width:17%" align=center>'.$Langue['LBL_MATIERE'].'</th>';
      echo '<th style="width:75%" align=center>'.$Langue['LBL_DEVOIRS_TRAVAIL'].'</th></tr></thead><tbody>';
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        echo '<tr><td align=center valign=top><strong>'.mysql_result($req,$i-1,'classes.nom_classe').' - '.mysql_result($req,$i-1,'listes.intitule').'</strong></td>';
        echo '<td align=center valign=top>'.Date_Convertir(mysql_result($req,$i-1,'devoirs.date_donnee'),'Y-m-d',$Format_Date_PHP).'</td>';
        $req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'devoirs.id_matiere')."'");
        if (mysql_num_rows($req2)!="")
        {
          $valeur=mysql_result($req2,0,'intitule');
        }
        else
        {
          $valeur=$liste_choix['matieres_cj'][mysql_result($req,$i-1,'devoirs.id_matiere')];
        }
        echo '<td align=center valign=top>'.$valeur.'</td>';
        echo '<td class="text_gauche" valign=top>';
        echo '<div class="floatdroite"><a title="'.$Langue['MSG_MODIFIER_DEVOIRS'].'" href="#null" onClick="Cahier_L_Charge_Devoir(\''.mysql_result($req,$i-1,'devoirs.id').'\',\''.mysql_result($req,$i-1,'devoirs.id_seance').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['MSG_SUPPRIMER_DEVOIRS'].'" href="#null" onClick="Cahier_L_Supprime_Devoir(\''.mysql_result($req,$i-1,'devoirs.id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></div>';
        echo '<ul class="explic marge_rien margin_rien_haut margin_rien_bas marge10_gauche"><li>'.str_replace("\n","</li><li>",mysql_result($req,$i-1,'contenu')).'</li></ul></td></tr>';
      }
      echo '</tbody></table>';


      /**************************************************/
      /* On affiche les devoirs donnés le jour en cours */
      /**************************************************/
      if ($_SESSION['affiche_devoirs']=="1")
      {
        $req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.id_seance='' AND devoirs.date_donnee='".$_SESSION['date_en_cours']."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
      }
      else
      {
        $req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.date_donnee='".$_SESSION['date_en_cours']."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
      }
      echo '<br /><br /><div class="titre_page">'.$Langue['LBL_DEVOIRS_DONNES_LE'].' '.$datation.'</div>';
      echo '<table id="listing_devoirs_faire" class="display" cellpadding=0 cellspacing=0 style="width:100%"><thead><tr>';
      echo '<th align=center>Classe</th>';
      echo '<th style="width:8%" align=center>'.$Langue['LBL_DEVOIRS_A_FAIRE_POUR'].'</th>';
      echo '<th style="width:17%" align=center>'.$Langue['LBL_MATIERE'].'</th>';
      echo '<th style="width:75%" align=center>'.$Langue['LBL_DEVOIRS_TRAVAIL'].'</th></tr></thead><tbody>';
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        echo '<tr><td align=center valign=top><strong>'.mysql_result($req,$i-1,'classes.nom_classe').' - '.mysql_result($req,$i-1,'listes.intitule').'</strong></td>';
        echo '<td align=center valign=top>'.Date_Convertir(mysql_result($req,$i-1,'devoirs.date_faire'),'Y-m-d',$Format_Date_PHP).'</td>';
        $req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'devoirs.id_matiere')."'");
        if (mysql_num_rows($req2)!="")
        {
          $valeur=mysql_result($req2,0,'intitule');
        }
        else
        {
          $valeur=$liste_choix['matieres_cj'][mysql_result($req,$i-1,'devoirs.id_matiere')];
        }
        echo '<td align=center valign=top>'.$valeur.'</td>';
        echo '<td class="textgauche" valign=top>';
        echo '<div class="floatdroite"><a title="'.$Langue['MSG_MODIFIER_DEVOIRS'].'" href="#null" onClick="Cahier_L_Charge_Devoir(\''.mysql_result($req,$i-1,'devoirs.id').'\',\''.mysql_result($req,$i-1,'devoirs.id_seance').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['MSG_SUPPRIMER_DEVOIRS'].'" href="#null" onClick="Cahier_L_Supprime_Devoir(\''.mysql_result($req,$i-1,'devoirs.id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></div>';
        echo '<ul class="explic marge_rien margin_rien_haut margin_rien_bas marge10_gauche"><li>'.str_replace("\n","</li><li>",mysql_result($req,$i-1,'contenu')).'</li></ul></td></tr>';
      }
      echo '</tbody></table>';
      break;
  }
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
		window.open("http://www.doxconception.com/site/index.php/directeur-cahier-journal.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cahier-journal.html","Aide");
<?php } ?>
  });

<?php if (mysql_num_rows($req_classe_id)!="") { ?>

  <?php if ($travaille==0) { ?>
    // Déclaration des boutons
    $("#AjouterSequence").button({disabled:false});
    $("#AjouterDevoirs").button({disabled:false});
    $("#VoirCJ").button({disabled:false});
	<?php if ($nb_niveau!=0) { ?>
    $("#Imprimer").button({disabled:false});
    $("#Imprimer2").button({disabled:false});
	<?php } else { ?>
    $("#Imprimer").button({disabled:true});
    $("#Imprimer2").button({disabled:true});
	<?php } ?>
    $("#AjouterSequence2").button({disabled:false});
    $("#AjouterDevoirs2").button({disabled:false});
    $("#VoirCJ2").button({disabled:false});

    // Action sur les boutons
    $("#VoirCJ").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=index_E","<?php echo $Langue['LBL_VUE_PARENTS']; ?>");
    });
    $("#VoirCJ2").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=index_E","<?php echo $Langue['LBL_VUE_PARENTS']; ?>");
    });
    $("#AjouterSequence").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=editview","<?php echo $Langue['BTN_AJOUTER_SEANCE']; ?>");
    });
    $("#AjouterSequence2").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=editview","<?php echo $Langue['BTN_AJOUTER_SEANCE']; ?>");
    });
    $("#AjouterDevoirs").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=editview_devoirs","<?php echo $Langue['LBL_CREER_NOUVEAUX_DEVOIRS']; ?>");
    });
    $("#AjouterDevoirs2").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=editview_devoirs","<?php echo $Langue['LBL_CREER_NOUVEAUX_DEVOIRS']; ?>");
    });
	$("#Imprimer").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
	});
	$("#Imprimer2").click(function()
    {
      Charge_Dialog("index2.php?module=cahier&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
	});

    /* Création du tableau des devoirs à faire pour le jour sélectionné */
	  $('#listing_devoirs_donnes').dataTable
    ({
      "bJQueryUI": true,
      "bAutoWidth": false,
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bInfo": false,
      "bSort": false,
      "sDom": 'rt<"clear">',
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sZeroRecords": "<?php echo $Langue['MSG_AUCUN_DEVOIR']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>"
      },
      "fnDrawCallback": function ( oSettings )
      {
        if ( oSettings.aiDisplay.length == 0 )
        {
          return;
        }
        var nTrs = $('#listing_devoirs_donnes tbody tr');
        var iColspan = nTrs[0].getElementsByTagName('td').length;
        var sLastGroup = "";
        for ( var i=0 ; i<nTrs.length ; i++ )
        {
          var iDisplayIndex = oSettings._iDisplayStart + i;
          var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
          if ( sGroup != sLastGroup )
          {
            var nGroup = document.createElement( 'tr' );
            var nCell = document.createElement( 'td' );
            nCell.colSpan = iColspan;
            nCell.className = "group";
            nCell.innerHTML = sGroup;
            nGroup.appendChild( nCell );
            nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
            sLastGroup = sGroup;
          }
        }
      },
      "aoColumnDefs": [ { "bVisible": false, "aTargets": [ 0 ] } ],
    });

    /* Création du tableau des devoirs donnés le jour sélectionné */
	  $('#listing_devoirs_faire').dataTable
    ({
      "bJQueryUI": true,
      "bAutoWidth": false,
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bInfo": false,
      "bSort": false,
      "sDom": 'rt<"clear">',
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sZeroRecords": "<?php echo $Langue['MSG_AUCUN_DEVOIR']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>"
      },
      "fnDrawCallback": function ( oSettings )
      {
        if ( oSettings.aiDisplay.length == 0 )
        {
          return;
        }
        var nTrs = $('#listing_devoirs_faire tbody tr');
        var iColspan = nTrs[0].getElementsByTagName('td').length;
        var sLastGroup = "";
        for ( var i=0 ; i<nTrs.length ; i++ )
        {
          var iDisplayIndex = oSettings._iDisplayStart + i;
          var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
          if ( sGroup != sLastGroup )
          {
            var nGroup = document.createElement( 'tr' );
            var nCell = document.createElement( 'td' );
            nCell.colSpan = iColspan;
            nCell.className = "group";
            nCell.innerHTML = sGroup;
            nGroup.appendChild( nCell );
            nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
            sLastGroup = sGroup;
          }
        }
      },
      "aoColumnDefs": [ { "bVisible": false, "aTargets": [ 0 ] } ],
    });
  <?php } else { ?>
    $("#AjouterSequence").button({disabled:true});
    $("#AjouterDevoirs").button({disabled:true});
    $("#VoirCJ").button({disabled:true});
    $("#Imprimer").button({disabled:true});
    $("#AjouterSequence2").button({disabled:true});
    $("#AjouterDevoirs2").button({disabled:true});
    $("#VoirCJ2").button({disabled:true});
    $("#Imprimer2").button({disabled:true});
  <?php } ?>

  /* Choix si on affiche les devoirs dans le cahier-journal ou non */
	$("#affiche_devoirs").click(function()
  {
     Message_Chargement(1,1);
     var url="users/change_devoirs.php";
     var data="affiche_choisi="+$("#affiche_devoirs").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});

  // Création du champ de choix de la date et de l'action associée en cas de changement
  $("#date_s").datepicker(
  {
    dateFormat: "<?php echo $Format_Date_Calendar; ?>",
    showOn: "button",
    buttonImage: "images/calendar.gif",
    buttonImageOnly: true,
    minDate:new Date(<?php echo substr($jour_min,0,4); ?>,<?php echo substr($jour_min,5,2); ?>-1,<?php echo substr($jour_min,8,2); ?>),
    maxDate:new Date(<?php echo substr($jour_max,0,4); ?>,<?php echo substr($jour_max,5,2); ?>-1,<?php echo substr($jour_max,8,2); ?>)
  });
  
  $("#date_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_date.php";
     var data="date_choisie="+$("#date_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function()
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
  });
  
  $( "#dialog:ui-dialog" ).dialog( "destroy" );
});

function Cahier_L_Charge_Seance(id)
{
  Charge_Dialog("index2.php?module=cahier&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_SEANCE']; ?>");
}

function Cahier_L_Charge_Devoir(id,id_seance)
{
  if (id_seance=="")
  {
    Charge_Dialog("index2.php?module=cahier&action=editview_devoirs&id="+id,"<?php echo $Langue['LBL_MODIFIER_DEVOIRS']; ?>");
  }
  else
  {
    Cahier_L_Charge_Seance(id_seance);
  }	
}

function Cahier_L_Supprime_Seance(id)
{
  <?php if ($_SESSION['affiche_devoirs']=="1") { ?>
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_SEANCE2']; ?></div>');
  <?php } else { ?>
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_SEANCE2']; ?></div><div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textgauche"><strong><?php echo $Langue['MSG_SUPPRIMER_SEANCE3']; ?></strong></div></div>');
  <?php } ?>
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_SUPPRIMER_SEANCE']; ?>",
			resizable: false,
			draggable: false,
			height:200,
			width:450,
			modal: true,
			buttons:[
        {
          text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
				  click: function()
          {
            Message_Chargement(4,1);
					  $( this ).dialog( "close" );
            var request = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=delete_seance&id="+id});
            request.done(function(msg)
            {
              Message_Chargement(1,0);
//              document.location.href="#haut_page";
              $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
					  });
          }
				},
				{
          text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
				  click: function()
          {
					  $( this ).dialog( "close" );
				  }
			  }]
		 });
}

function Cahier_L_Supprime_Devoir(id)
{
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_DEVOIRS2']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_SUPPRIMER_DEVOIRS']; ?>",
			resizable: false,
			draggable: false,
			height:200,
			width:450,
			modal: true,
			buttons:[
        {
          text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
				  click: function()
          {
            Message_Chargement(4,1);
					  $( this ).dialog( "close" );
            var request = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=delete_devoirs&id="+id});
            request.done(function(msg)
            {
              Message_Chargement(1,0);
//              document.location.href="#haut_page";
              $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
					  });
          }
				},
				{
          text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
				  click: function()
          {
					  $( this ).dialog( "close" );
				  }
			  }]
		 });
}
<?php } else { echo "});"; } ?>
</script>
