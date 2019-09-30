<?php
  Param_Utilisateur($_SESSION['id_util'],$_SESSION['annee_scolaire']);
  $date_en_cours=Date_Convertir($_GET['date_en_cours'],$Format_Date_PHP,"Y-m-d");
  $nb_classe=$_GET['nb_classe'];
  $affiche_devoirs=$_GET['devoirs'];
  
  $datation=Date_Convertir($date_en_cours,'Y-m-d',$Format_Date_PHP);
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

  $contenu_html='<div class="titre_page">'.$Langue['LBL_IMPRESSION_CAHIER_JOURNAL_DU'].' '.$datation.'</div><br /><br />';

  // On recherche le nombre de classes / niveaux faisant l'objet du séance dans le cahier-journal
  $req_niveau=mysql_query("SELECT listes.*,cahierjournal.*,classes_profs.* FROM `classes_profs`,`listes`,`cahierjournal` WHERE cahierjournal.id_prof='".$_SESSION['id_util']."' AND cahierjournal.date='".$date_en_cours."' AND cahierjournal.id_niveau=listes.id AND classes_profs.id_classe=cahierjournal.id_classe AND classes_profs.id_prof=cahierjournal.id_prof ORDER BY classes_profs.type DESC, listes.ordre ASC");
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
  $nb_page=ceil($nb_niveau/$nb_classe);
  if ($_GET['orientation']=="P")
  {
	$largeur=90/$nb_classe;
	$largeur2=10;
  }
  else
  {
	$largeur=94/$nb_classe;
	$largeur2=6;
  }
  
      // On récupère l'heure de début et l'heure de fin de la journée
      $date=date("w",mktime(0,0,0,substr($date_en_cours,5,2),substr($date_en_cours,8,2),substr($date_en_cours,0,4)));
      $req=mysql_query("SELECT * FROM `cahierjournal` WHERE id_prof='".$_SESSION['id_util']."' AND date='".$date_en_cours."' ORDER BY heure_debut ASC");
      $req2=mysql_query("SELECT * FROM `cahierjournal` WHERE id_prof='".$_SESSION['id_util']."' AND date='".$date_en_cours."' ORDER BY heure_fin DESC");
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
	  for ($i=0;$i<$nb_page;$i++)
	  {
        $contenu[$i]='<table cellspacing=0 cellpadding=0 border=0 style="width:100%" class="heures">';
        $contenu[$i]=$contenu[$i].'<tr><th style="text-align:center;width:'.$largeur2.'%;border-bottom:0px;border-left:2px solid #000000;">'.$Langue['LBL_IMPRESSION_HORAIRES'].'</th>';
	  }
      for ($i=1;$i<=$nb_niveau;$i++)
      {
        $req_c=mysql_query("SELECT * FROM `classes` WHERE id='".$classe[$i-1]."'");
        $req_n=mysql_query("SELECT * FROM `listes` WHERE id='".$niveau[$i-1]."'");
		$page=ceil($i/$nb_classe)-1;
        $contenu[$page]=$contenu[$page].'<th style="text-align:center;width:'.$largeur.'%;border-left:0px;border-bottom:0px">'.mysql_result($req_c,0,'nom_classe').' - '.mysql_result($req_n,0,'intitule').'</th>';
      }
	  for ($i=0;$i<$nb_page;$i++)
	  {
        $contenu[$i]=$contenu[$i].'</tr>';
      }
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
								for ($k=0;$k<$nb_page;$k++)
								{
									$contenu[$k]=$contenu[$k].'<tr class=gris><td class="heure" style="width:'.$largeur2.'%;color:#000000">'.$heure.'</td>';
								}
                for ($k=1;$k<=$nb_niveau;$k++)
                {
									$page=ceil($k/$nb_classe)-1;
                  $contenu[$page]=$contenu[$page].'<td class="gris" style="text-align:center;width:'.$largeur.'%;color:#000000"><strong><br />'.$Langue['LBL_IMPRESSION_PAUSE_MIDI'].'<br /><br /></strong></td>';
                  $actuel[$k-1]=$actuel[$k-1]+1;
									$premier[$k-1]=1;
                }
              }
              else
              // Si ce n'est pas la pause méridienne
              {
                $ligne_en_cours=$ligne_en_cours+1;
                $extension="";
                if ($heure==$gestclasse_config_plus['jour_matin_fin'] && $gestclasse_config_plus['am'.$date]==0) { $extension='_fin'; }
                if ($heure==$heure_fin) { $extension='_fin'; }
                if ($j==0 || $j==15 || $j==30 || $j==45 || $heure==$gestclasse_config_plus['jour_am_debut'] || $heure==$heure_fin || $heure==$heure_debut) 
								{ 
									for ($k=0;$k<$nb_page;$k++)
									{
										$contenu[$k]=$contenu[$k].'<tr><td class="heure'.$extension.'" style="width:'.$largeur2.'%">'.$heure.'</td>'; 
									}
								} 
								else 
								{ 
									for ($k=0;$k<$nb_page;$k++)
									{
										$contenu[$k]=$contenu[$k].'<tr><td class="heure_non_marquee" style="width:'.$largeur2.'%">&nbsp;</td>'; 
									}
								}
                for ($k=1;$k<=$nb_niveau;$k++)
                {
									$page=ceil($k/$nb_classe)-1;
                  // On recherche si on a quelque chose pour la classe et le niveau
                  $req=mysql_query("SELECT * FROM `cahierjournal` WHERE id_prof='".$_SESSION['id_util']."' AND date='".$date_en_cours."' AND id_classe='".$classe[$k-1]."' AND id_niveau='".$niveau[$k-1]."' AND heure_debut>='$heure' ORDER BY heure_debut ASC");
                  if (mysql_num_rows($req)!="")
                  {
                    // Il y a quelque chose à l'heure où on est arrivé dans le tableau => On affiche le contenu de la séance
                    if ($heure.":00"==mysql_result($req,0,'heure_debut'))
                    {
                      $rowspan=(mktime(substr(mysql_result($req,0,'heure_fin'),0,2),substr(mysql_result($req,0,'heure_fin'),3,2),0,date("m"),date("d"),date("Y"))-mktime($i,$j,0,date("m"),date("d"),date("Y")))/300;
                      if (mysql_result($req,0,'id_matiere')!="RECRE")
                      {
                        $contenu[$page]=$contenu[$page].'<td class="contenu'.$extension.'" rowspan="'.$rowspan.'" style="width:'.$largeur.'%">';
                        $req2=mysql_query("SELECT * FROM `listes` WHERE nom_liste='matieres_cj' AND id='".mysql_result($req,0,'id_matiere')."'");
                        if (mysql_num_rows($req2)=="")
                        {
                          $valeur=$liste_choix['matieres_cj'][mysql_result($req,0,'id_matiere')];
                        }
                        else
                        {
                          $valeur=mysql_result($req2,0,'intitule');
                        }
                        $contenu[$page]=$contenu[$page].'<font class="sous_titre_cj">'.$Langue['LBL_IMPRESSION_MATIERE_HORAIRES'].' : </font>'.$valeur.' ('.$Langue['LBL_IMPRESSION_DE'].' '.substr(mysql_result($req,0,'heure_debut'),0,5).' '.$Langue['LBL_IMPRESSION_A'].' '.substr(mysql_result($req,0,'heure_fin'),0,5).')<br /><br />';
                        if (mysql_result($req,0,'objectifs')!="")
                        {
                          $contenu[$page]=$contenu[$page].'<font class="sous_titre_cj">'.$Langue['LBL_OBJECTIFS'].' :</font><br />&nbsp;&nbsp;&nbsp;-&nbsp;'.str_replace("\n","<br />&nbsp;&nbsp;&nbsp;-&nbsp;",mysql_result($req,0,'objectifs')).'<br /><br />';
                        }
                        if (mysql_result($req,0,'materiel')!="")
                        {
                          $contenu[$page]=$contenu[$page].'<font class="sous_titre_cj">'.$Langue['LBL_MATERIEL'].' :</font><br />&nbsp;&nbsp;&nbsp;-&nbsp;'.str_replace("\n","<br />&nbsp;&nbsp;&nbsp;-&nbsp;",mysql_result($req,0,'materiel')).'<br /><br />';
                        }
                        $contenu[$page]=$contenu[$page].'<font class="sous_titre_cj" style="line-height:18px">'.$Langue['LBL_DEROULEMENT'].' :</font><br /><font>'.str_replace("\r","",str_replace("\n","",mysql_result($req,0,'contenu'))).'</font>';
                        if ($affiche_devoirs=="1")
                        {
                          $req3=mysql_query("SELECT * FROM `devoirs` WHERE id_seance='".mysql_result($req,0,'id')."'");
                          if (mysql_num_rows($req3)!="")
                          {
                            $contenu[$page]=$contenu[$page].'<br /><br /><font class="sous_titre_cj">'.$Langue['LBL_IMPRESSION_DEVOIRS_A_FAIRE_POUR'].' '.Date_Convertir(mysql_result($req3,0,'date_faire'),"Y-m-d",$Format_Date_PHP).' :</font><br />&nbsp;&nbsp;&nbsp;-&nbsp;'.str_replace("\n","<br />&nbsp;&nbsp;&nbsp;-&nbsp;",mysql_result($req3,0,'contenu'));
                          }
                        }
                      }
                      else
                      {
                        $contenu[$page]=$contenu[$page].'<td class="contenu'.$extension.' gris" rowspan="'.$rowspan.'" style="text-align:center;width:'.$largeur.'%;color:#000000;vertical-align:middle"><strong>'.$Langue['LBL_RECREATION'].'</strong>';
                      }
                      $contenu[$page]=$contenu[$page].'</td>';
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
                        $contenu[$page]=$contenu[$page].'<td rowspan="'.$rowspan.'" class="contenu'.$extension.' gris textgauche" style="width:'.$largeur.'%" valign=top>&nbsp;</td>';
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
												if ($premier[$k-1]==1)
												{
                          $contenu[$page]=$contenu[$page].'<td style="width:'.$largeur.'%" class="gris textgauche" valign=top>&nbsp;</td>';
													$premier[$k-1]=0;	
												}
												else
												{
                          $contenu[$page]=$contenu[$page].'<td style="width:'.$largeur.'%" class="pas_haut textgauche" valign=top>&nbsp;</td>';
												}
                      }
                      else
                      {
                        $contenu[$page]=$contenu[$page].'<td style="width:'.$largeur.'%" class="contenu_fin textgauche" valign=top>&nbsp;</td>';
                      }
                      $actuel[$k-1]=$actuel[$k-1]+1;
                    }
                  }
                }
              }
							for ($k=0;$k<$nb_page;$k++)
							{
                $contenu[$k]=$contenu[$k].'</tr>';
							}
            }
          }
        }
      }
      for ($k=0;$k<$nb_page;$k++)
      {
        $contenu[$k]=$contenu[$k].'</table>';
      }
  
  for ($k=0;$k<$nb_page;$k++)
  {
    if ($k!=$nb_page-1)
		{
			if (file_exists("commun/mpdf/mpdf.php"))
			{
				$contenu_html=$contenu_html.$contenu[$k]."<pagebreak />";
			}
			else
			{
				$contenu_html=$contenu_html.$contenu[$k].'</page><page pageset="old">';
			}
		}
		else
		{
			$contenu_html=$contenu_html.$contenu[$k];
		}
  }
  
  // Impression des devoirs à faire pour le jour sélectionné
  if ($affiche_devoirs!="0")
  {
		if ($affiche_devoirs=="1")
		{
			$req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.id_seance='' AND devoirs.date_faire='".$date_en_cours."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
		}
		else
		{
			$req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.date_faire='".$date_en_cours."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
		}
		$ligne="even";
		$contenu_html=$contenu_html.'<br /><br /><div class="sous_titre_page">'.$Langue['LBL_IMPRESSION_DEVOIRS_A_FAIRE_POUR'].' '.$datation.'</div><br />';
		$contenu_html=$contenu_html.'<table class="heures" cellpadding=0 cellspacing=0 style="width:100%"><tr>';
		$contenu_html=$contenu_html.'<th style="border-left:2px solid #000000;width:10%;text-align:center">'.$Langue['LBL_IMPRESSION_DEVOIRS_DONNES_LE'].'</th>';
		$contenu_html=$contenu_html.'<th style="width:20%;text-align:center">'.$Langue['LBL_MATIERE'].'</th>';
		$contenu_html=$contenu_html.'<th style="width:70%;text-align:center">'.$Langue['LBL_DEVOIRS_TRAVAIL'].'</th></tr>';
		$classe="";
		$niveau="";
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
			if ($classe!=mysql_result($req,$i-1,'classes.id') || $niveau!=mysql_result($req,$i-1,'listes.id'))
			{
				$contenu_html=$contenu_html.'<tr><td colspan=3 class="classe textgauche" style="width:100%;"><strong>'.mysql_result($req,$i-1,'classes.nom_classe').' - '.mysql_result($req,$i-1,'listes.intitule').'</strong></td></tr>';
				$classe=mysql_result($req,$i-1,'classes.id');
				$niveau=mysql_result($req,$i-1,'listes.id');
			} 
			$contenu_html=$contenu_html.'<tr class="'.$ligne.'"><td style="width:10%;text-align:center;vertical-align:top;border-left:2px solid #000000;">'.Date_Convertir(mysql_result($req,$i-1,'devoirs.date_donnee'),'Y-m-d',$Format_Date_PHP).'</td>';
			$req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'devoirs.id_matiere')."' AND id_prof='".$_SESSION['id_util']."'");
			if (mysql_num_rows($req2)!="")
			{
				$valeur=mysql_result($req2,0,'intitule');
			}
			else
			{
				$valeur=$liste_choix['matieres_cj'][mysql_result($req,$i-1,'devoirs.id_matiere')];
			}
			$contenu_html=$contenu_html.'<td style="width:20%;text-align:center;vertical-align:top">'.$valeur.'</td>';
			$contenu_html=$contenu_html.'<td class="textgauche" style="width:70%;vertical-align:top">';
			$contenu_html=$contenu_html.'-&nbsp;'.str_replace("\n","<br />-&nbsp;",mysql_result($req,$i-1,'contenu')).'</td></tr>';
			if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
		}
		$contenu_html=$contenu_html.'</table>';
	
		/**************************************************/
		/* On affiche les devoirs donnés le jour en cours */
		/**************************************************/
		if ($affiche_devoirs=="1")
		{
			$req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.id_seance='' AND devoirs.date_donnee='".$_SESSION['date_en_cours']."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
		}
		else
		{
			$req=mysql_query("SELECT devoirs.*, classes.*, listes.*, classes_profs.* FROM `classes_profs`,`devoirs`,`classes`,`listes` WHERE devoirs.id_prof='".$_SESSION['id_util']."' AND devoirs.date_donnee='".$_SESSION['date_en_cours']."' AND devoirs.id_classe=classes.id AND devoirs.id_niveau=listes.id AND classes_profs.id_prof=devoirs.id_prof AND classes_profs.id_classe=classes.id ORDER BY classes_profs.type DESC, classes.nom_classe ASC, listes.ordre ASC, devoirs.id_matiere ASC");
		}
		$contenu_html=$contenu_html.'<br /><br /><div class="sous_titre_page">'.$Langue['LBL_DEVOIRS_DONNES_LE'].' '.$datation.'</div><br />';
		$contenu_html=$contenu_html.'<table class="heures" cellpadding=0 cellspacing=0 style="width:100%"><tr>';
		$contenu_html=$contenu_html.'<th style="border-left:2px solid #000000;width:10%;text-align:center">'.$Langue['LBL_DEVOIRS_A_FAIRE_POUR'].'</th>';
		$contenu_html=$contenu_html.'<th style="width:20%;text-align:center">'.$Langue['LBL_MATIERE'].'</th>';
		$contenu_html=$contenu_html.'<th style="width:70%;text-align:center">'.$Langue['LBL_DEVOIRS_TRAVAIL'].'</th></tr>';
		$classe="";
		$niveau="";
		$ligne="even";
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
			if ($classe!=mysql_result($req,$i-1,'classes.id') || $niveau!=mysql_result($req,$i-1,'listes.id'))
			{
				$contenu_html=$contenu_html.'<tr><td class="classe textgauche" colspan=3 style="width:100%;vertical-align:top"><strong>'.mysql_result($req,$i-1,'classes.nom_classe').' - '.mysql_result($req,$i-1,'listes.intitule').'</strong></td></tr>';
				$classe=mysql_result($req,$i-1,'classes.id');
				$niveau=mysql_result($req,$i-1,'listes.id');
			} 
			$contenu_html=$contenu_html.'<tr class="'.$ligne.'"><td style="width:10%;text-align:center;vertical-align:top;border-left:2px solid #000000;">'.Date_Convertir(mysql_result($req,$i-1,'devoirs.date_faire'),'Y-m-d',$Format_Date_PHP).'</td>';
			$req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'devoirs.id_matiere')."' AND id_prof='".$_SESSION['id_util']."'");
			if (mysql_num_rows($req2)!="")
			{
				$valeur=mysql_result($req2,0,'intitule');
			}
			else
			{
				$valeur=$liste_choix['matieres_cj'][mysql_result($req,$i-1,'devoirs.id_matiere')];
			}
			$contenu_html=$contenu_html.'<td style="width:20%;text-align:center;vertical-align:top">'.$valeur.'</td>';
			$contenu_html=$contenu_html.'<td class="textgauche" style="width:70%;vertical-align:top">';
			$contenu_html=$contenu_html.'-&nbsp;'.str_replace("\n","<br />-&nbsp;",mysql_result($req,$i-1,'contenu')).'</td></tr>';
			if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
		}
		$contenu_html=$contenu_html.'</table>';
  }
  
  include "commun/impression.php";
?>