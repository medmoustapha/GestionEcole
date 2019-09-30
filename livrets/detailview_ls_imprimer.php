<?php
  $id_eleve=$_GET['id_eleve'];
  $annee_scolaire_ls=$_GET['id_annee'];
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $annee_debut=$annee_scolaire_ls.$gestclasse_config_plus['debut_annee_scolaire'];
	  $a=$annee_scolaire_ls;
	  $annee_fin=$annee_scolaire_ls.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $annee_debut=$annee_scolaire_ls.$gestclasse_config_plus['debut_annee_scolaire'];
	  $a=$annee_scolaire_ls+1;
	  $annee_fin=$a.$gestclasse_config_plus['fin_annee_scolaire'];
  }

  $req=mysql_query("SELECT classes.*, eleves_classes.*, eleves.* FROM `classes`,`eleves_classes`,`eleves` WHERE classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve='$id_eleve' AND eleves_classes.id_eleve=eleves.id AND classes.annee='$annee_scolaire_ls'");
  
  $req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,0,'eleves_classes.id_niveau')."'");
  $id_niveau=mysql_result($req2,0,'id');

  /* On affiche les informations de base de l'élève et de la classe */
  $msg='<table cellspacing=0 cellpadding=0 style="width:100%"><tr><td class="marge15_droite textcentre" style="width:15%;vertical-align:bottom;">';
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
  $msg .='<table cellspacing=0 cellpadding=0 class="display" style="width:100%;border:0px;"><tr><th style="width:100%;border:0px;text-align:center;font-size:18px">'.mysql_result($req2,0,'intitule').'</th></tr></table>';
  $msg .='</td>';
  $msg .='<td class="textcentre" style="width:85%" colspan=3><font style="font-family:Arial;font-size:50px;text-align:center;font-weight:bold">'.$Langue['LBL_IMPRESSION_LIVRET_SCOLAIRE2'].'</font><br />';
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $msg .='<font class="textcentre" style="font-family:Arial;font-size:18px;font-weight:normal;width:100%">'.$Langue['LBL_IMPRESSION_ANNEE_SCOLAIRE'].' '.$annee_scolaire_ls.'</font></td></tr>';
  }
  else
  {
    $msg .='<font class="textcentre" style="font-family:Arial;font-size:18px;font-weight:normal;width:100%">'.$Langue['LBL_IMPRESSION_ANNEE_SCOLAIRE'].' '.$annee_scolaire_ls.' / '.$a.'</font></td></tr>';
  }
  $msg .='<tr><td colspan=4>&nbsp;</td></tr>';
  $msg .='<tr><td colspan=4>&nbsp;</td></tr>';
  $msg .='<tr><td class="marge15_droite textcentre" style="width:15%;vertical-align:top;" rowspan=2><table cellspacing=0 cellpadding=0 class="display" style="width:100%;border:0px;"><tr><th class="textcentre" style="width:100%;border:0px;text-align:center;font-size:18px">'.$Langue['LBL_IMPRESSION_ELEVE2'].'</th></tr></table></td>';
  $msg .='<td class="gauche" style="width:32%;padding-bottom:15px"><label class="label_detail_non_gras">'.$Langue['LBL_IMPRESSION_NOM'].' : <strong>'.mysql_result($req,0,'eleves.nom').'</strong></label></td>';
  $msg .='<td class="gauche" style="width:32%;padding-bottom:15px"><label class="label_detail_non_gras">'.$Langue['LBL_IMPRESSION_PRENOM'].' : <strong>'.mysql_result($req,0,'eleves.prenom').'</strong></label></td>';
  $msg .='<td class="gauche" style="width:21%;padding-bottom:15px"><label class="label_detail_non_gras">'.$Langue['LBL_IMPRESSION_DATE_NAISSANCE'].' : <strong>'.Date_Convertir(mysql_result($req,0,'eleves.date_naissance'),"Y-m-d",$Format_Date_PHP).'</strong></label></td></tr>';
  $id_classe=mysql_result($req,0,'classes.id');
  $req2=mysql_query("SELECT profs.*,classes_profs.* FROM `profs`,`classes_profs` WHERE profs.id=classes_profs.id_prof AND classes_profs.id_classe='".mysql_result($req,0,'eleves_classes.id_classe')."' AND classes_profs.type='T'");
  $msg .='<tr><td style="width:85%" colspan=3><label class="label_detail_non_gras">'.$Langue['LBL_IMPRESSION_CLASSE_DE'].' <strong>'.$liste_choix['civilite_long'][mysql_result($req2,0,'profs.civilite')].' '.mysql_result($req2,0,'profs.nom').'</strong></label></td></tr>';
  $msg .='<tr><td colspan=4>&nbsp;</td></tr>';
  $msg .='<tr><td colspan=4>&nbsp;</td></tr>';

  /* On récupère les informations sur l'enseignant */
  $id_prof=mysql_result($req2,0,'profs.id');
  Param_Utilisateur($id_prof,$annee_scolaire_ls);

  $msg .='<tr><td class="marge15_droite textcentre" style="width:15%;vertical-align:top;"><table cellspacing=0 cellpadding=0 class="display" style="width:100%;border:0px;"><tr><th style="width:100%;border:0px;text-align:center;font-size:18px">'.$Langue['LBL_IMPRESSION_ECOLE'].'</th></tr></table></td>';
  $msg .='<td style="width:85%" colspan=3><label class="label_detail_non_gras">'.$gestclasse_config_plus['nom'].' - '.str_replace("<br>"," - ",$gestclasse_config_plus['adresse']).'</label></td></tr>';
  $msg .='</table><br /><br />';
  
  /* On affiche les informations sur la notation */
  $msg .='<table cellspacing=0 cellpadding=0 class="livret" style="width:100%;"><tr class="even"><td class="textgauche bas" style="width:15%;" rowspan=2>'.$Langue['LBL_IMPRESSION_CODE_APPRECIATION'].'</td>';
  /* On calcule les médianes */
  $max=0;
  for ($i=0;$i<=9;$i++)
  {
    if ($gestclasse_config_plus['c'.$i]!="")
    {
      $mediane[$i]=($gestclasse_config_plus['s'.$i]+$gestclasse_config_plus['i'.$i])/2;
      if ($_GET['couleur']=="G")
      {
        $gestclasse_config_plus['couleur'.$i]="#000000";
        $gestclasse_config_plus['couleur_fond'.$i]="";
      }
      $max=$i;
    }
  }

  $largeur=85/($max+1);
  for ($i=$max;$i>=0;$i--)
  {
    $msg .='<td class=bas style="width:'.$largeur.'%;text-align:center;border-bottom:0px"><strong>'.$gestclasse_config_plus['c'.$i].'</strong></td>';
  }
  $msg .='</tr><tr class="even">';
  for ($i=$max;$i>=0;$i--)
  {
    $msg .='<td class=bas style="width:'.$largeur.'%;text-align:center;">'.$Langue['LBL_IMPRESSION_DE'].' '.$gestclasse_config_plus['i'.$i].' '.$Langue['LBL_IMPRESSION_A'].' '.$gestclasse_config_plus['s'.$i].'%</td>';
  }
  $msg .='</tr></table>';
  $msg .='<br /><br />';
    
  $nb=count($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']]);
  $largeur=100-10*$nb;
  $largeur2=$largeur-12;
  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    for ($i=0;$i<=$max;$i++)
    {
      $total_result[$cle][$i]=0;
 	  foreach ($liste_choix['matieres_stat'] AS $cle2 => $value)
	  {
        $total_result_stat[$cle][$cle2][$i]=0;
	  }
   }
  }

  /* On récupère les catégories de compétences non supprimées */
  $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_parent='' AND id_prof='$id_prof' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') ORDER BY ordre ASC");
  for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
  {
    $trouve=false;
    $id_cat=mysql_result($req_categorie,$i-1,'id');
    $req_categorie2=mysql_query("SELECT * FROM `competences_categories` WHERE id_parent='$id_cat' AND id_prof='$id_prof' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') ORDER BY ordre ASC");
    if (mysql_num_rows($req_categorie2)!="") { $colspan="colspan=2"; } else { $colspan=""; }
    $msg2 ='<table class="livret" cellpadding=0 cellspacing=0 style="width:100%">';
    $msg2 .='<thead><tr>';
    $msg2 .='<th class="livret textcentre" '.$colspan.' style="width:'.$largeur.'%;">'.Convertir_En_Majuscule(mysql_result($req_categorie,$i-1,'titre')).'</th>';
    foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
    {
      $msg2 .='<th class="trimestre textcentre" style="width:10%;">'.$value.'</th>';
    }
    $msg2 .='</tr></thead>';

    $ligne="even";
    /* On récupère les compétences non supprimées */
    $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id_cat' AND id_prof='$id_prof' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') ORDER BY ordre ASC");
	if (mysql_num_rows($req_competence)!="") { $trouve=true; }
    for ($j=1;$j<=mysql_num_rows($req_competence);$j++)
    {
      $class='';
      if ($j==mysql_num_rows($req_competence)) { $class=' bas'; }
      $msg2 .='<tr class="'.$ligne.'"><td class="textgauche'.$class.'" '.$colspan.' style="width:'.$largeur.'%;">'.mysql_result($req_competence,$j-1,'code').' - '.mysql_result($req_competence,$j-1,'intitule').'</td>';
      $id_c=mysql_result($req_competence,$j-1,'id');
      $id_s=mysql_result($req_competence,$j-1,'statistiques');
      foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
      {
        /* On calcule le résultat de l'élève pour la compétence */
        $req_resultat=mysql_query("SELECT controles.*,controles_resultats.* FROM `controles`,`controles_resultats` WHERE controles.id=controles_resultats.id_controle AND controles.trimestre='$cle' AND controles_resultats.id_competence='$id_c' AND controles_resultats.id_eleve='".$id_eleve."' AND controles.id_classe='$id_classe' AND controles.id_niveau='$id_niveau' ORDER BY controles.date DESC");
        if (mysql_num_rows($req_resultat)!="")
        {
         switch ($gestclasse_config_plus['calcul_moyenne'])
         {
          case "D": $valeur=mysql_result($req_resultat,0,'controles_resultats.resultat'); break;
          case "M": $total=0;
            for ($u=1;$u<=mysql_num_rows($req_resultat);$u++)
            {
              $total=$total+$mediane[mysql_result($req_resultat,$u-1,'controles_resultats.resultat')];
            }
            $moyenne=$total/mysql_num_rows($req_resultat);
            for ($u=0;$u<=$max;$u++)
            {
              if ($moyenne>=$gestclasse_config_plus['i'.$u] && $moyenne<$gestclasse_config_plus['s'.$u]) { $valeur=$u; $u=$max+1; }
            }
            break;
          case "P": $total=0; $coeff=0;
            for ($u=1;$u<=mysql_num_rows($req_resultat);$u++)
            {
              $total=$total+(mysql_num_rows($req_resultat)-$u+1)*$mediane[mysql_result($req_resultat,$u-1,'controles_resultats.resultat')];
              $coeff=$coeff+(mysql_num_rows($req_resultat)-$u+1);
            }
            $moyenne=$total/$coeff;
            for ($u=0;$u<=$max;$u++)
            {
              if ($moyenne>=$gestclasse_config_plus['i'.$u] && $moyenne<$gestclasse_config_plus['s'.$u]) { $valeur=$u; $u=$max+1; }
            }
            break;
          case "C": $total=0; $coeff=0;
            for ($u=1;$u<=mysql_num_rows($req_resultat);$u++)
            {
              $total=$total+mysql_result($req_resultat,$u-1,'controles.coefficient')*$mediane[mysql_result($req_resultat,$u-1,'controles_resultats.resultat')];
              $coeff=$coeff+mysql_result($req_resultat,$u-1,'controles.coefficient');
            }
            $moyenne=$total/$coeff;
            for ($u=0;$u<=$max;$u++)
            {
              if ($moyenne>=$gestclasse_config_plus['i'.$u] && $moyenne<$gestclasse_config_plus['s'.$u]) { $valeur=$u; $u=$max+1; }
            }
            break;
         }
         $total_result[$cle][$valeur]=$total_result[$cle][$valeur]+1;
         $total_result_stat[$cle][$id_s][$valeur]=$total_result_stat[$cle][$id_s][$valeur]+1;
         $msg2 .='<td class="textcentre'.$class.'" style="width:10%;background:'.$gestclasse_config_plus['couleur_fond'.$valeur].';color:'.$gestclasse_config_plus['couleur'.$valeur].'">'.$gestclasse_config_plus['c'.$valeur].'</td>';
        }
        else
        {
          $msg2 .='<td class="textcentre'.$class.'" style="width:10%;">&nbsp;</td>';
        }
      }  // fin du foreach
      if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
      $msg2 .='</tr>';
    }
    /* On récupère les catégories de compétences secondaires non supprimées */
    for ($j=1;$j<=mysql_num_rows($req_categorie2);$j++)
    {
      $id_cat=mysql_result($req_categorie2,$j-1,'id');
      $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id_cat' AND id_prof='$id_prof' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') ORDER BY ordre ASC");
      $ligne="even";
	  if (mysql_num_rows($req_competence)!="")
	  {
	    $trouve=true;
        $msg2 .='<tr class="'.$ligne.'"><td class="bas textgauche" rowspan="'.mysql_num_rows($req_competence).'" style="width:12%;color:#000000"><strong>'.mysql_result($req_categorie2,$j-1,'titre').'</strong></td>';
        /* On récupère les compétences non supprimées */
        for ($k=1;$k<=mysql_num_rows($req_competence);$k++)
        {
          $class="";
          if ($k==mysql_num_rows($req_competence)) { $class=' bas'; }
          $id_c=mysql_result($req_competence,$k-1,'id');
          $id_s=mysql_result($req_competence,$k-1,'statistiques');
          if ($k!=1) { $msg2 .='<tr class="'.$ligne.'">'; }
          $msg2 .='<td class="textgauche'.$class.'" style="width:'.$largeur2.'%;">'.mysql_result($req_competence,$k-1,'code').' - '.mysql_result($req_competence,$k-1,'intitule').'</td>';
          foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
          {
            $req_resultat=mysql_query("SELECT controles.*,controles_resultats.* FROM `controles`,`controles_resultats` WHERE controles.id=controles_resultats.id_controle AND controles.trimestre='$cle' AND controles_resultats.id_competence='$id_c' AND controles_resultats.id_eleve='".$id_eleve."' AND controles.id_classe='$id_classe' AND controles.id_niveau='$id_niveau' ORDER BY controles.date DESC");
            if (mysql_num_rows($req_resultat)!="")
            {
             switch ($gestclasse_config_plus['calcul_moyenne'])
             {
              case "D": $valeur=mysql_result($req_resultat,0,'controles_resultats.resultat'); break;
              case "M": $total=0;
                for ($u=1;$u<=mysql_num_rows($req_resultat);$u++)
                {
                  $total=$total+$mediane[mysql_result($req_resultat,$u-1,'controles_resultats.resultat')];
                }
                $moyenne=$total/mysql_num_rows($req_resultat);
                for ($u=0;$u<=$max;$u++)
                {
                  if ($moyenne>=$gestclasse_config_plus['i'.$u] && $moyenne<$gestclasse_config_plus['s'.$u]) { $valeur=$u; $u=$max+1; }
                }
                break;
              case "P": $total=0; $coeff=0;
                for ($u=1;$u<=mysql_num_rows($req_resultat);$u++)
                {
                  $total=$total+(mysql_num_rows($req_resultat)-$u+1)*$mediane[mysql_result($req_resultat,$u-1,'controles_resultats.resultat')];
                  $coeff=$coeff+(mysql_num_rows($req_resultat)-$u+1);
                }
                $moyenne=$total/$coeff;
                for ($u=0;$u<=$max;$u++)
                {
                  if ($moyenne>=$gestclasse_config_plus['i'.$u] && $moyenne<$gestclasse_config_plus['s'.$u]) { $valeur=$u; $u=$max+1; }
                }
                break;
              case "C": $total=0; $coeff=0;
                for ($u=1;$u<=mysql_num_rows($req_resultat);$u++)
                {
                  $total=$total+mysql_result($req_resultat,$u-1,'controles.coefficient')*$mediane[mysql_result($req_resultat,$u-1,'controles_resultats.resultat')];
                  $coeff=$coeff+mysql_result($req_resultat,$u-1,'controles.coefficient');
                }
                $moyenne=$total/$coeff;
                for ($u=0;$u<=$max;$u++)
                {
                  if ($moyenne>=$gestclasse_config_plus['i'.$u] && $moyenne<$gestclasse_config_plus['s'.$u]) { $valeur=$u; $u=$max+1; }
                }
                break;
             }
             $total_result[$cle][$valeur]=$total_result[$cle][$valeur]+1;
             $total_result_stat[$cle][$id_s][$valeur]=$total_result_stat[$cle][$id_s][$valeur]+1;
             $msg2 .='<td class="textcentre'.$class.'" style="width:10%;background:'.$gestclasse_config_plus['couleur_fond'.$valeur].';color:'.$gestclasse_config_plus['couleur'.$valeur].'">'.$gestclasse_config_plus['c'.$valeur].'</td>';
            }
            else
            {
              $msg2 .='<td class="textcentre'.$class.'" style="width:10%;">&nbsp;</td>';
            }
          }
          if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
          $msg2 .='</tr>';
        }
	  }	
    }
    $msg2 .='</table><br />';
	if ($trouve==true) { $msg .=$msg2; }
  }

	// *************************************
	// * Affichage du tableau statistiques *
	// *************************************

	if ($_GET['stat']!="R")
	{  
	  $msg .='<table class="livret" cellpadding=0 cellspacing=0 style="width:100%"><tr>';
	  $largeur=100-18*count($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']]);
	  $msg .='<th class="textcentre" rowspan=2 style="width:'.$largeur.'%;">'.Convertir_En_Majuscule($Langue['LBL_TOUTES_MATIERES']).'<br />'.$Langue['LBL_COMPETENCES_MARQUEES'].'</th>';
	  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
	  {
		$msg .='<th class="textcentre" style="width:18%;" colspan=2>'.$value.'</th>';
	  }
	  $msg .='</tr><tr>';
	  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle)
	  {
		$msg .='<th class="textcentre" style="width:9%;">'.$Langue['LBL_ELEVE'].'</th><th class="textcentre" style="width:9%;">'.$Langue['LBL_CLASSE'].'</th>';
	  }
	  $msg .='</tr>';
	  /* On calcule le total des compétences de la période */
	  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
	  {
		$total_periode[$cle]=0;
		for ($i=0;$i<=$max;$i++)
		{
		  $total_periode[$cle]=$total_periode[$cle]+$total_result[$cle][$i];
		}
	  }

	  $ligne="even";
	  for ($i=$max;$i>=0;$i--)
	  {
		$class='';
		if ($i==0) { $class=' bas'; }
		$req_r=mysql_query("SELECT * FROM `moyenne_classe` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND marquage='$i' AND statistiques='G'");
		$msg .='<tr class="'.$ligne.'"><td class="textgauche'.$class.'" style="width:'.$largeur.'%;">'.$gestclasse_config_plus['c'.$i].'</td>';
		foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
		{
		  if ($total_periode[$cle]!=0)
		  {
			$msg .='<td class="textcentre'.$class.'" style="width:9%;">'.number_format(100*$total_result[$cle][$i]/$total_periode[$cle],2,',',' ').' %</td>';
		  }
		  else
		  {
			$msg .='<td class="textcentre'.$class.'" style="width:9%;">&nbsp;</td>';
		  }
		  if (mysql_num_rows($req_r)!="")
		  {
			if (mysql_result($req_r,0,'periode'.substr($cle,1,1))!="0.00")
			{
			  $msg .='<td class="textcentre'.$class.'" style="width:9%;">'.number_format(mysql_result($req_r,0,'periode'.substr($cle,1,1)),2,',',' ').' %</td>';
			}
			else
			{
			  $msg .='<td class="textcentre'.$class.'" style="width:9%;">&nbsp;</td>';
			}
		  }
		  else
		  {
			$msg .='<td class="textcentre'.$class.'" style="width:9%;">&nbsp;</td>';
		  }
		}
		if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
		$msg .='</tr>';
	  }
	  $msg .='</table><br />';
	}
	  
	// ******************************************************
	// * Affichage des tableaux secondaires de statistiques *
	// ******************************************************

	if ($_GET['stat']=="D")
	{
		foreach ($liste_choix['matieres_stat'] AS $cle23 => $value23)
		{
		  $msg .='<table class="livret" cellpadding=0 cellspacing=0 style="width:100%"><tr>';
		  $largeur=100-18*count($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']]);
		  $msg .='<th class="textcentre" rowspan=2 style="width:'.$largeur.'%;">'.Convertir_En_Majuscule($value23).'<br />'.$Langue['LBL_COMPETENCES_MARQUEES'].'</th>';
		  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
		  {
			$msg .='<th class="textcentre" style="width:18%;" colspan=2>'.$value.'</th>';
		  }
		  $msg .='</tr><tr>';
		  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle)
		  {
			$msg .='<th class="textcentre" style="width:9%;">'.$Langue['LBL_ELEVE'].'</th><th class="textcentre" style="width:9%;">'.$Langue['LBL_CLASSE'].'</th>';
		  }
		  $msg .='</tr>';
		  /* On calcule le total des compétences de la période */
		  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
		  {
			$total_periode_stat[$cle][$cle23]=0;
			for ($i=0;$i<=$max;$i++)
			{
			  $total_periode_stat[$cle][$cle23]=$total_periode_stat[$cle][$cle23]+$total_result_stat[$cle][$cle23][$i];
			}
		  }

		  $ligne="even";
		  for ($i=$max;$i>=0;$i--)
		  {
			$class='';
			if ($i==0) { $class=' bas'; }
			$req_r=mysql_query("SELECT * FROM `moyenne_classe` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND marquage='$i' AND statistiques='$cle23'");
			$msg .='<tr class="'.$ligne.'"><td class="textgauche'.$class.'" style="width:'.$largeur.'%;">'.$gestclasse_config_plus['c'.$i].'</td>';
			foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
			{
			  if ($total_periode_stat[$cle][$cle23]!=0)
			  {
				$msg .='<td class="textcentre'.$class.'" style="width:9%;">'.number_format(100*$total_result_stat[$cle][$cle23][$i]/$total_periode_stat[$cle][$cle23],2,',',' ').' %</td>';
			  }
			  else
			  {
				$msg .='<td class="textcentre'.$class.'" style="width:9%;">&nbsp;</td>';
			  }
			  if (mysql_num_rows($req_r)!="")
			  {
				if (mysql_result($req_r,0,'periode'.substr($cle,1,1))!="0.00")
				{
				  $msg .='<td class="textcentre'.$class.'" style="width:9%;">'.number_format(mysql_result($req_r,0,'periode'.substr($cle,1,1)),2,',',' ').' %</td>';
				}
				else
				{
				  $msg .='<td class="textcentre'.$class.'" style="width:9%;">&nbsp;</td>';
				}
			  }
			  else
			  {
				$msg .='<td class="textcentre'.$class.'" style="width:9%;">&nbsp;</td>';
			  }
			}
			if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
			$msg .='</tr>';
		  }
		  $msg .='</table><br />';
		}
	}

	// ******************************************
	// * Affichage du tableau des appréciations *
	// ******************************************
    if (file_exists("commun/mpdf/mpdf.php"))
    {
	  $msg .='<pagebreak />';
	}
	else
	{
	  $msg .='</page><page pageset="old">';
	}
    $msg .='<table class="livret" cellpadding=0 cellspacing=0 style="width:100%">';
	$msg .='<tr><th class="textcentre" colspan=2 style="width:100%;">'.Convertir_En_Majuscule($Langue['LBL_IMPRESSION_REMARQUES']).'</th></tr>';
	if (file_exists("commun/mpdf/mpdf.php"))
	{
	  if ($_GET['orientation']=="P") { $hauteur=82; $hauteur2=56; } else { $hauteur=53; $hauteur2=37; }
	}
	else
	{
	  if ($_GET['orientation']=="P") { $hauteur=69; $hauteur2=43; } else { $hauteur=40; $hauteur2=23; }
	}

    $hauteur3=$hauteur-$hauteur2;
	foreach ($liste_choix['livret_decoupage_'.$gestclasse_config_plus['decoupage_livret']] AS $cle23 => $value23)
	{
		$msg .='<tr><th class="textcentre" colspan=2 style="width:100%;">'.$value23.'</th></tr>';
		$msg .='<tr class="even"><td class="textgauche bas" style="width:66%;height:'.$hauteur.'mm;vertical-align:top" rowspan=2><strong>'.$Langue['LBL_APPRECIATION_ENSEIGNANT'].'</strong>';
		// Recherche de l'appréciation et de la signature de l'enseignant
		if ($gestclasse_config_plus['appreciation_livrets']=="1" && $gestclasse_config_plus['signature_livrets']=="1")
		{
			$parametre='livrets|'.$cle23.'|'.$annee_scolaire_ls.'|D|'.$id_eleve;
			$req_signature2=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
			$parametre='livrets|'.$cle23.'|'.$annee_scolaire_ls.'|P|'.$id_eleve;
			$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
			if (mysql_num_rows($req_signature)!="" && mysql_num_rows($req_signature2)!="")
			{
			  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle23' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='P'");
			  if (mysql_num_rows($req_appreciation)!="")
			  {
				$msg .='<p>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
				$req_p=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req_signature,0,'id_util')."'");
				$msg .='<p style="text-align:center">'.$Langue['MSG_SIGNATURE_SIGNE_PAR'].' '.$liste_choix['civilite_long'][mysql_result($req_p,0,'civilite')].' '.mysql_result($req_p,0,'nom').' '.$Langue['MSG_SIGNATURE_LE'].' '.Date_Convertir(mysql_result($req_signature,0,'date'),'Y-m-d',$Format_Date_PHP).'<br>'.$Langue['MSG_SIGNATURE_CLE'].' : '.mysql_result($req_signature,0,'id').'</p>';
			  }
			}	
		}
		if ($gestclasse_config_plus['appreciation_livrets']=="1" && $gestclasse_config_plus['signature_livrets']=="0")
		{
		  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle23' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='P'");
		  if (mysql_num_rows($req_appreciation)!="")
		  {
			$msg .='<p>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
		  }
		}
		$msg .='</td>';
		$msg .='<td class="bas textgauche" style="width:34%;height:'.$hauteur2.'mm;vertical-align:top"><strong>'.$Langue['LBL_APPRECIATION_DIRECTEUR'].'</strong>';
		// Recherche de l'appréciation et de la signature du Directeur
		if ($gestclasse_config_plus['appreciation_livrets']=="1" && $gestclasse_config_plus['signature_livrets']=="1")
		{
			$parametre='livrets|'.$cle23.'|'.$annee_scolaire_ls.'|D|'.$id_eleve;
			$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
			if (mysql_num_rows($req_signature)!="")
			{
			  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle23' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='D'");
			  if (mysql_num_rows($req_appreciation)!="")
			  {
				$msg .='<p>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
			  }
			  $req_p=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req_signature,0,'id_util')."'");
			  $msg .='<p style="text-align:center">'.$Langue['MSG_SIGNATURE_SIGNE_PAR'].' '.$liste_choix['civilite_long'][mysql_result($req_p,0,'civilite')].' '.mysql_result($req_p,0,'nom').' '.$Langue['MSG_SIGNATURE_LE'].' '.Date_Convertir(mysql_result($req_signature,0,'date'),'Y-m-d',$Format_Date_PHP).'<br>'.$Langue['MSG_SIGNATURE_CLE'].' : '.mysql_result($req_signature,0,'id').'</p>';
			}
		}
		if ($gestclasse_config_plus['appreciation_livrets']=="1" && $gestclasse_config_plus['signature_livrets']=="0")
		{
		  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle23' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='D'");
		  if (mysql_num_rows($req_appreciation)!="")
		  {
			$msg .='<p>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
		  }
		}
		$msg .='</td></tr>';
		$msg .='<tr class="even"><td class="bas textgauche" style="width:34%;height:'.$hauteur3.'mm;vertical-align:top"><strong>'.$Langue['LBL_APPRECIATION_PARENTS'].'</strong>';
		// Recherche de la signature des parents
        $parametre='livrets|'.$cle23.'|'.$annee_scolaire_ls.'|E|'.$id_eleve;
		$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
		if (mysql_num_rows($req_signature)!="")
		{
			$req_p=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req_signature,0,'id_util')."'");
			$msg .='<br><p style="text-align:center">'.$Langue['MSG_SIGNATURE_SIGNE_PAR_PARENTS'].' '.mysql_result($req_p,0,'nom').' '.mysql_result($req_p,0,'prenom').' '.$Langue['MSG_SIGNATURE_LE'].' '.Date_Convertir(mysql_result($req_signature,0,'date'),'Y-m-d',$Format_Date_PHP).'<br>'.$Langue['MSG_SIGNATURE_CLE'].' : '.mysql_result($req_signature,0,'id').'</p>';
		}	
		$msg .='</td></tr>';
	}
	$msg .='</table>';
	
  $contenu_html=$msg;
?>