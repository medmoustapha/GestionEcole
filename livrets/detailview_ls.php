<?php
  if ($_SESSION['type_util']=="E")
  {
    $id_eleve=$_SESSION['id_util'];
  }
  else
  {
    $id_eleve=$_GET['id_eleve'];
  }

  if (isset($_GET['annee_scolaire_ls']))
  {
		$annee_scolaire_ls=$_GET['annee_scolaire_ls'];
  }
  else
  {
    $annee_scolaire_ls=$_SESSION['annee_scolaire'];
  }
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $annee_debut=$annee_scolaire_ls.$gestclasse_config_plus['debut_annee_scolaire'];
	  $annee_fin=$annee_scolaire_ls.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $annee_debut=$annee_scolaire_ls.$gestclasse_config_plus['debut_annee_scolaire'];
	  $a=$annee_scolaire_ls+1;
	  $annee_fin=$a.$gestclasse_config_plus['fin_annee_scolaire'];
  }

  $req=mysql_query("SELECT classes.*, eleves_classes.*, eleves.* FROM `classes`,`eleves_classes`,`eleves` WHERE classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve='$id_eleve' AND eleves_classes.id_eleve=eleves.id AND classes.annee='$annee_scolaire_ls'");
  $id_classe_eleve=mysql_result($req,0,'classes.id');
  
  /* Construction des années scolaires */
  $msg_annee='<select name="annee_scolaire_ls" id="annee_scolaire_ls" class="text ui-widget-content ui-corner-all">';
  $msg_annee2='<select name="annee_scolaire_ls2" id="annee_scolaire_ls2" class="text ui-widget-content ui-corner-all">';
  $req=mysql_query("SELECT classes.*, eleves_classes.* FROM `classes`,`eleves_classes` WHERE classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve='$id_eleve' ORDER BY classes.annee DESC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $msg_annee .='<option value="'.mysql_result($req,$i-1,'classes.annee').'"';
    $msg_annee2 .='<option value="'.mysql_result($req,$i-1,'classes.annee').'"';
    $annee_plus=mysql_result($req,$i-1,'classes.annee')+1;
    if (mysql_result($req,$i-1,'classes.annee')==$annee_scolaire_ls) { $msg_annee .= ' SELECTED';$msg_annee2 .= ' SELECTED'; }
    if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	{
      $msg_annee .='>'.mysql_result($req,$i-1,'classes.annee').'</option>';
      $msg_annee2 .='>'.mysql_result($req,$i-1,'classes.annee').'</option>';
	}
	else
	{
      $msg_annee .='>'.mysql_result($req,$i-1,'classes.annee').'-'.$annee_plus.'</option>';
      $msg_annee2 .='>'.mysql_result($req,$i-1,'classes.annee').'-'.$annee_plus.'</option>';
	}
  }
  $msg_annee .='</select>';
  $msg_annee2 .='</select>';
  
  $req = mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves_classes.id_classe='".$id_classe_eleve."' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$annee_fin' ORDER BY eleves.nom ASC, eleves.prenom ASC");
  $msg_eleves='<select name="nav_liste_eleve" id="nav_liste_eleve" class="text ui-widget-content ui-corner-all">';
  $msg_eleves2='<select name="nav_liste_eleve2" id="nav_liste_eleve2" class="text ui-widget-content ui-corner-all">';
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $msg_eleves .='<option value="'.mysql_result($req,$i-1,'eleves.id').'"';
    $msg_eleves2 .='<option value="'.mysql_result($req,$i-1,'eleves.id').'"';
	if (mysql_result($req,$i-1,'eleves.id')==$id_eleve) { $msg_eleves .=' SELECTED'; $msg_eleves2 .=' SELECTED'; }
	$msg_eleves .='>'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</option>';
	$msg_eleves2 .='>'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</option>';
  }
  $msg_eleves .='</select>';
  $msg_eleves2 .='</select>';
?>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Button" name="Imprimer_LS" id="Imprimer_LS" value="<?php echo $Langue['BTN_IMPRIMER']; ?>">
  </td>
  <td class="droite" nowrap>
<?php if ($_SESSION['type_util']=="E") { ?>
    <div class="ui-widget ui-state-default ui-corner-all floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_ANNEE_SCOLAIRE_COURS']; ?> : <?php echo $msg_annee; ?></div>
<?php } else { ?>
    <div class="ui-widget ui-state-default ui-corner-br ui-corner-tr floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_ALLER_A']; ?> : <?php echo $msg_eleves; ?></div>
    <div class="ui-widget ui-state-default ui-corner-bl ui-corner-tl floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_ANNEE_SCOLAIRE_COURS']; ?> : <?php echo $msg_annee; ?></div>
<?php } ?>
  </td>
</tr>
</table>

<!-- Affichage des informations sur la classe de l'année scolaire sélectionnée -->
<?php
  $req=mysql_query("SELECT classes.*, eleves_classes.*, eleves.* FROM `classes`,`eleves_classes`,`eleves` WHERE classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve='$id_eleve' AND eleves_classes.id_eleve=eleves.id AND classes.annee='$annee_scolaire_ls'");
?>
<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_INFOS_GENERALES']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_ELEVE']; ?> :</label></td>
  <td class="gauche" width=85%><label class="label_detail"><?php echo mysql_result($req,0,'eleves.nom').' '.mysql_result($req,0,'eleves.prenom'); ?></label></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_CLASSE']; ?> :</label></td>
  <td class="gauche" width=85%><label class="label_detail"><?php echo mysql_result($req,0,'classes.nom_classe'); ?></label></td>
  <?php $id_classe=mysql_result($req,0,'classes.id'); ?>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_NIVEAU']; ?> :</label></td>
  <?php $req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,0,'eleves_classes.id_niveau')."'"); ?>
  <td class="gauche" width=85%><label class="label_detail"><?php echo mysql_result($req2,0,'intitule'); ?></label></td>
  <?php $id_niveau=mysql_result($req2,0,'id'); ?>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_ENSEIGNANT']; ?> :</label></td>
  <?php $req2=mysql_query("SELECT profs.*,classes_profs.* FROM `profs`,`classes_profs` WHERE profs.id=classes_profs.id_prof AND classes_profs.id_classe='".mysql_result($req,0,'eleves_classes.id_classe')."' AND classes_profs.type='T'"); ?>
  <td class="gauche" width=85%><label class="label_detail"><?php echo $liste_choix['civilite_long'][mysql_result($req2,0,'profs.civilite')].' '.mysql_result($req2,0,'profs.nom'); ?></label></td>
</tr>
</table>

<!-- Affichage des résultats de l'élève -->
<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_RESULTATS_OBTENUS']; ?></div>
<?php
  /* On récupère les informations sur l'enseignant */
  $id_prof=mysql_result($req2,0,'profs.id');
  Param_Utilisateur($id_prof,$annee_scolaire_ls);

  /* On calcule les médianes */
  $max=0;
  for ($i=0;$i<=9;$i++)
  {
    if ($gestclasse_config_plus['c'.$i]!="")
    {
      $mediane[$i]=($gestclasse_config_plus['s'.$i]+$gestclasse_config_plus['i'.$i])/2;
      $max=$i;
    }
  }

  $nb=count($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']]);
  $largeur=100-9*$nb;
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
    echo '<table class="display" cellpadding=0 cellspacing=0 style="width:100%">';
    echo '<thead><tr>';
    echo '<th class="ui-state-default centre" style="width:'.$largeur.'%">&nbsp;'.Convertir_En_Majuscule(mysql_result($req_categorie,$i-1,'titre')).'</th>';
    foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
    {
      echo '<th class="ui-state-default centre" style="width:9%">'.$value.'</th>';
    }
    echo '</tr></thead><tbody>';

    $ligne="even";
    /* On récupère les compétences non supprimées */
    $id_cat=mysql_result($req_categorie,$i-1,'id');
    $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id_cat' AND id_prof='$id_prof' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') ORDER BY ordre ASC");
    for ($j=1;$j<=mysql_num_rows($req_competence);$j++)
    {
      echo '<tr class="'.$ligne.'"><td class="gauche">&nbsp;'.mysql_result($req_competence,$j-1,'code').' - '.mysql_result($req_competence,$j-1,'intitule').'</td>';
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
         echo '<td class="centre" style="background:'.$gestclasse_config_plus['couleur_fond'.$valeur].';color:'.$gestclasse_config_plus['couleur'.$valeur].'">'.$gestclasse_config_plus['c'.$valeur].'</td>';
        }
        else
        {
          echo '<td class="centre">&nbsp;</td>';
        }
      }  // fin du foreach
      if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
      echo '</tr>';
    }
    /* On récupère les catégories de compétences secondaires non supprimées */
    $req_categorie2=mysql_query("SELECT * FROM `competences_categories` WHERE id_parent='$id_cat' AND id_prof='$id_prof' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') ORDER BY ordre ASC");
    for ($j=1;$j<=mysql_num_rows($req_categorie2);$j++)
    {
      echo '<tr><td class="gauche sorti" style="color:#000000">&nbsp;<strong>'.mysql_result($req_categorie2,$j-1,'titre').'</strong></td>';
      foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
      {
        echo '<td class="sorti">&nbsp;</td>';
      }
      echo '</tr>';
      $ligne="even";
      /* On récupère les compétences non supprimées */
      $id_cat=mysql_result($req_categorie2,$j-1,'id');
      $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id_cat' AND id_prof='$id_prof' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') ORDER BY ordre ASC");
      for ($k=1;$k<=mysql_num_rows($req_competence);$k++)
      {
        $id_c=mysql_result($req_competence,$k-1,'id');
        $id_s=mysql_result($req_competence,$k-1,'statistiques');
        echo '<tr class="'.$ligne.'"><td class="gauche">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.mysql_result($req_competence,$k-1,'code').' - '.mysql_result($req_competence,$k-1,'intitule').'</td>';
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
           echo '<td class="centre" style="background:'.$gestclasse_config_plus['couleur_fond'.$valeur].';color:'.$gestclasse_config_plus['couleur'.$valeur].'">'.$gestclasse_config_plus['c'.$valeur].'</td>';
          }
          else
          {
            echo '<td class="centre">&nbsp;</td>';
          }
        }
        if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
        echo '</tr>';
      }
    }
    echo '</tbody></table><br />';
  }
?>

<!-- Affichage des statistiques de l'élève -->
<br />
<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_STATISTIQUES_REUSSITE']; ?></div>
<table id="listing_stats" class="display" cellpadding=0 cellspacing=0 style="width:100%">
<thead>
<tr>
<?php
  $largeur=100-16*count($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']]);
  echo '<th rowspan=2 style="width:'.$largeur.'%" class="centre">'.Convertir_En_Majuscule($Langue['LBL_TOUTES_MATIERES']).'<br />'.$Langue['LBL_COMPETENCES_MARQUEES'].'</th>';
  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    echo '<th style="width:16%" colspan=2 class="ui-state-default centre">'.$value.'</th>';
  }
  echo '</tr><tr>';
  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle)
  {
    echo '<th style="width:8%" class="centre">'.$Langue['LBL_ELEVE'].'</th><th style="width:8%" class="centre">'.$Langue['LBL_CLASSE'].'</th>';
  }
?>
</tr>
</thead>
<tbody>
<?php
  /* On calcule le total des compétences de la période */
  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    $total_periode[$cle]=0;
    for ($i=0;$i<=$max;$i++)
    {
      $total_periode[$cle]=$total_periode[$cle]+$total_result[$cle][$i];
    }
  }

  for ($i=$max;$i>=0;$i--)
  {
    $req_r=mysql_query("SELECT * FROM `moyenne_classe` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND marquage='$i' AND statistiques='G'");
    echo '<tr><td style="width:'.$largeur.'%" class="gauche">'.$gestclasse_config_plus['c'.$i].'</td>';
    foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
    {
      if ($total_periode[$cle]!=0)
      {
        echo '<td style="width:8%" class="centre">'.number_format(100*$total_result[$cle][$i]/$total_periode[$cle],2,',',' ').' %</td>';
      }
      else
      {
        echo '<td style="width:8%" class="centre">&nbsp;</td>';
      }
      if (mysql_num_rows($req_r)!="")
      {
        if (mysql_result($req_r,0,'periode'.substr($cle,1,1))!="0.00")
        {
          echo '<td style="width:8%" class="centre">'.number_format(mysql_result($req_r,0,'periode'.substr($cle,1,1)),2,',',' ').' %</td>';
        }
        else
        {
          echo '<td style="width:8%" class="centre">&nbsp;</td>';
        }
      }
      else
      {
        echo '<td style="width:8%" class="centre">&nbsp;</td>';
      }
    }
    echo '</tr>';
  }
?>
</tbody>
</table>
<br />
<?php

// ******************************************************
// * Affichage des tableaux secondaires de statistiques *
// ******************************************************

  foreach ($liste_choix['matieres_stat'] AS $cle23 => $value23)
  {
?>
<table id="listing_stats_<?php echo $cle23; ?>" class="display" cellpadding=0 cellspacing=0 style="width:100%">
<thead>
<tr>
<?php
  $largeur=100-16*count($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']]);
  echo '<th rowspan=2 style="width:'.$largeur.'%" class="centre">'.Convertir_En_Majuscule($value23).'<br />'.$Langue['LBL_COMPETENCES_MARQUEES'].'</th>';
  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    echo '<th style="width:16%" colspan=2 class="ui-state-default centre">'.$value.'</th>';
  }
  echo '</tr><tr>';
  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle)
  {
    echo '<th style="width:8%" class="centre">'.$Langue['LBL_ELEVE'].'</th><th style="width:8%" class="centre">'.$Langue['LBL_CLASSE'].'</th>';
  }
?>
</tr>
</thead>
<tbody>
<?php
  /* On calcule le total des compétences de la période */
  foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    $total_periode_stat[$cle][$cle23]=0;
    for ($i=0;$i<=$max;$i++)
    {
      $total_periode_stat[$cle][$cle23]=$total_periode_stat[$cle][$cle23]+$total_result_stat[$cle][$cle23][$i];
    }
  }

  for ($i=$max;$i>=0;$i--)
  {
    $req_r=mysql_query("SELECT * FROM `moyenne_classe` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND marquage='$i' AND statistiques='$cle23'");
    echo '<tr><td style="width:'.$largeur.'%" class="gauche">'.$gestclasse_config_plus['c'.$i].'</td>';
    foreach ($liste_choix['livret_decoupage_livret_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
    {
      if ($total_periode_stat[$cle][$cle23]!=0)
      {
        echo '<td style="width:8%" class="centre">'.number_format(100*$total_result_stat[$cle][$cle23][$i]/$total_periode_stat[$cle][$cle23],2,',',' ').' %</td>';
      }
      else
      {
        echo '<td style="width:8%" class="centre">&nbsp;</td>';
      }
      if (mysql_num_rows($req_r)!="")
      {
        if (mysql_result($req_r,0,'periode'.substr($cle,1,1))!="0.00")
        {
          echo '<td style="width:8%" class="centre">'.number_format(mysql_result($req_r,0,'periode'.substr($cle,1,1)),2,',',' ').' %</td>';
        }
        else
        {
          echo '<td style="width:8%" class="centre">&nbsp;</td>';
        }
      }
      else
      {
        echo '<td style="width:8%" class="centre">&nbsp;</td>';
      }
    }
    echo '</tr>';
  }
?>
</tbody>
</table>
<br />
<?php
  }
?>

<?php
  include "livrets/appreciations.php";
?>

<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" name="Imprimer_LS2" id="Imprimer_LS2" value="<?php echo $Langue['BTN_IMPRIMER']; ?>">
  </td>
  <td class="droite" nowrap>
<?php if ($_SESSION['type_util']=="E") { ?>
    <div class="ui-widget ui-state-default ui-corner-all floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_ANNEE_SCOLAIRE_COURS']; ?> : <?php echo $msg_annee2; ?></div>
<?php } else { ?>
    <div class="ui-widget ui-state-default ui-corner-br ui-corner-tr floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_ALLER_A']; ?> : <?php echo $msg_eleves2; ?></div>
    <div class="ui-widget ui-state-default ui-corner-bl ui-corner-tl floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_ANNEE_SCOLAIRE_COURS']; ?> : <?php echo $msg_annee2; ?></div>
<?php } ?>
  </td>
</tr>
</table>

<script language="Javascript">
$(document).ready(function()
{
  <?php if ($_SESSION['titulaire_classe_cours']==$_SESSION['id_util'] || $_SESSION['type_util']!="P") { ?>
  $("#Imprimer_LS").button({ disabled: false });
  $("#Imprimer_LS2").button({ disabled: false });
  <?php } else { ?>
  $("#Imprimer_LS").button({ disabled: true });
  $("#Imprimer_LS2").button({ disabled: true });
  <?php } ?>

  $("#Imprimer_LS").click(function()
  {
    Charge_Dialog("index2.php?module=livrets&action=detailview_imprimer&id_a_imprimer=<?php echo $id_eleve; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
	$("#Imprimer_LS2").click(function()
  {
    Charge_Dialog("index2.php?module=livrets&action=detailview_imprimer&id_a_imprimer=<?php echo $id_eleve; ?>","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
  
  $("#nav_liste_eleve").change(function()
  {
   Charge_Dialog("index2.php?module=livrets&action=detailview_ls&annee_scolaire_ls=<?php echo $annee_scolaire_ls; ?>&id_eleve="+$("#nav_liste_eleve").val(),"<?php echo $Langue['LBL_LIVRET_SCOLAIRE_ELEVE']; ?>");
  });
  
  $("#nav_liste_eleve2").change(function()
  {
   Charge_Dialog("index2.php?module=livrets&action=detailview_ls&annee_scolaire_ls=<?php echo $annee_scolaire_ls; ?>&id_eleve="+$("#nav_liste_eleve2").val(),"<?php echo $Langue['LBL_LIVRET_SCOLAIRE_ELEVE']; ?>");
  });

  /* En cas de changement d'année scolaire */
<?php if ($_SESSION['type_util']=="E") { ?>
  $("#annee_scolaire_ls").change(function()
  {
    Message_Chargement(1,1);
    $( "#Menu_principal" ).load("index2.php?module=livrets&action=detailview_ls&id_eleve=<?php echo $id_eleve; ?>&annee_scolaire_ls="+$("#annee_scolaire_ls").val(),function(response, status, xhr)
    {
      Message_Chargement(1,0);
    });
    event.preventDefault();
  });
  $("#annee_scolaire_ls2").change(function()
  {
    Message_Chargement(1,1);
    $( "#Menu_principal" ).load("index2.php?module=livrets&action=detailview_ls&id_eleve=<?php echo $id_eleve; ?>&annee_scolaire_ls="+$("#annee_scolaire_ls2").val(),function(response, status, xhr)
    {
      Message_Chargement(1,0);
    });
    event.preventDefault();
  });
<?php } else { ?>
  $("#annee_scolaire_ls").change(function()
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve=<?php echo $id_eleve; ?>&annee_scolaire_ls="+$("#annee_scolaire_ls").val(),"<?php echo $Langue['LBL_LIVRET_SCOLAIRE_ELEVE']; ?>");
  });
  $("#annee_scolaire_ls2").change(function()
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve=<?php echo $id_eleve; ?>&annee_scolaire_ls="+$("#annee_scolaire_ls2").val(),"<?php echo $Langue['LBL_LIVRET_SCOLAIRE_ELEVE']; ?>");
  });
<?php } ?>

  /* Tableau des listes de compétences */
  $('#listing_stats').dataTable
  (
    {
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
        "sZeroRecords": "<?php echo $Langue['LBL_AUCUN_RESULTAT']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>"
      },
    }
  );
<?php
  foreach ($liste_choix['matieres_stat'] AS $cle23 => $value)
  {
	echo '$("#listing_stats_'.$cle23.'").dataTable
		({
		  "bJQueryUI": true,
		  "bAutoWidth": false,
		  "bPaginate": false,
		  "bLengthChange": false,
		  "bFilter": false,
		  "bInfo": false,
		  "bSort": false,
		  "sDom": \'rt<"clear">\',
		  "oLanguage":
		  {
				"sProcessing":   "'.$Langue['LBL_TRAITEMENT'].'",
				"sZeroRecords": "'.$Langue['LBL_AUCUN_RESULTAT'].'",
				"sInfoEmpty": "'.$Langue['LBL_NO_DATA2'].'"
		  },
		});';
  }
?>  
});
</script>
