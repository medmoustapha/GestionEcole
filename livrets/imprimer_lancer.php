<?php
  switch ($_GET['document'])
  {
    case "1":
      include "livrets/detailview_ls_imprimer.php";
      break;
    case "2":
	  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	  {
		  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
		  $annee_a=$_SESSION['annee_scolaire'];
		  $annee_fin=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
	  }
	  else
	  {
		  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
		  $annee_a=$_SESSION['annee_scolaire']+1;
		  $annee_fin=$annee_a.$gestclasse_config_plus['fin_annee_scolaire'];
	  }
      $id_classe=$_SESSION['id_classe_cours'];
      $id_niveau=$_SESSION['niveau_en_cours'];
      $trimestre=$_GET['id_trimestre'];
      $id_titulaire=$_SESSION['titulaire_classe_cours'];

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

      $nb_eleve=0;
      for ($i=0;$i<=$max;$i++)
      {
        $total_classe[$i]=0;
      }

      /* On calcule les résultats pour chaque élève de la classe */
      $req_eleve=mysql_query("SELECT eleves_classes.*, eleves.* FROM `eleves_classes`, `eleves` WHERE eleves_classes.id_classe='$id_classe' AND eleves_classes.id_niveau='$id_niveau' AND eleves.id=eleves_classes.id_eleve AND (eleves.date_entree<='".$gestclasse_config_plus['fin_'.$trimestre]."' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".$gestclasse_config_plus['debut_'.$trimestre]."')) ORDER BY eleves.nom ASC, eleves.prenom ASC");
      for ($z=1;$z<=mysql_num_rows($req_eleve);$z++)
      {
        $id_eleve_boucle=mysql_result($req_eleve,$z-1,'eleves_classes.id_eleve');
        $nom_eleve[$id_eleve_boucle]=mysql_result($req_eleve,$z-1,'eleves.nom')." ".mysql_result($req_eleve,$z-1,'eleves.prenom');
        /* Initialisation des données */
        for ($i=0;$i<=$max;$i++)
        {
          $total_result[$i][$id_eleve_boucle]=0;
        }

        /* On récupère les contrôles du trimestre et les compétences */
        $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$id_titulaire."' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin')");
        for ($i=1;$i<=mysql_num_rows($req_competence);$i++)
        {
          $id_c=mysql_result($req_competence,$i-1,'id');
          $req_resultat=mysql_query("SELECT controles.*,controles_resultats.* FROM `controles`,`controles_resultats` WHERE controles.id_classe='$id_classe' AND controles.id_niveau='$id_niveau' AND controles.id=controles_resultats.id_controle AND controles.trimestre='$trimestre' AND controles_resultats.id_competence='$id_c' AND controles_resultats.id_eleve='".$id_eleve_boucle."' ORDER BY controles.date DESC");
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
            $total_result[$valeur][$id_eleve_boucle]=$total_result[$valeur][$id_eleve_boucle]+1;
          }
        }
      }
      
      if ($_GET['option']=="R")
      {
        foreach ($nom_eleve AS $cle => $value)
        {
          $tot=0;
          for ($i=$max;$i>=0;$i--)
          {
            $tot=$tot+$total_result[$i][$cle];
          }
          for ($i=$max;$i>=0;$i--)
          {
            if ($tot!=0)
            {
              $moy[$i][$cle]=number_format(100*$total_result[$i][$cle]/$tot,2,',',' ');
            }
            else
            {
              $moy[$i][$cle]=0;
            }
          }
        }
        switch ($max)
        {
          case "1": array_multisort($moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING, SORT_ASC,$total_result[1],$total_result[0]); break;
          case "2": array_multisort($moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[2],$total_result[1],$total_result[0]); break;
          case "3": array_multisort($moy[3],SORT_NUMERIC,SORT_DESC,$moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[3],$total_result[2],$total_result[1],$total_result[0]); break;
          case "4": array_multisort($moy[4],SORT_NUMERIC,SORT_DESC,$moy[3],SORT_NUMERIC,SORT_DESC,$moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[4],$total_result[3],$total_result[2],$total_result[1],$total_result[0]); break;
          case "5": array_multisort($moy[5],SORT_NUMERIC,SORT_DESC,$moy[4],SORT_NUMERIC,SORT_DESC,$moy[3],SORT_NUMERIC,SORT_DESC,$moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[5],$total_result[4],$total_result[3],$total_result[2],$total_result[1],$total_result[0]); break;
          case "6": array_multisort($moy[6],SORT_NUMERIC,SORT_DESC,$moy[5],SORT_NUMERIC,SORT_DESC,$moy[4],SORT_NUMERIC,SORT_DESC,$moy[3],SORT_NUMERIC,SORT_DESC,$moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[6],$total_result[5],$total_result[4],$total_result[3],$total_result[2],$total_result[1],$total_result[0]); break;
          case "7": array_multisort($moy[7],SORT_NUMERIC,SORT_DESC,$moy[6],SORT_NUMERIC,SORT_DESC,$moy[5],SORT_NUMERIC,SORT_DESC,$moy[4],SORT_NUMERIC,SORT_DESC,$moy[3],SORT_NUMERIC,SORT_DESC,$moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[7],$total_result[6],$total_result[5],$total_result[4],$total_result[3],$total_result[2],$total_result[1],$total_result[0]); break;
          case "8": array_multisort($moy[8],SORT_NUMERIC,SORT_DESC,$moy[7],SORT_NUMERIC,SORT_DESC,$moy[6],SORT_NUMERIC,SORT_DESC,$moy[5],SORT_NUMERIC,SORT_DESC,$moy[4],SORT_NUMERIC,SORT_DESC,$moy[3],SORT_NUMERIC,SORT_DESC,$moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[8],$total_result[7],$total_result[6],$total_result[5],$total_result[4],$total_result[3],$total_result[2],$total_result[1],$total_result[0]); break;
          case "9": array_multisort($moy[9],SORT_NUMERIC,SORT_DESC,$moy[8],SORT_NUMERIC,SORT_DESC,$moy[7],SORT_NUMERIC,SORT_DESC,$moy[6],SORT_NUMERIC,SORT_DESC,$moy[5],SORT_NUMERIC,SORT_DESC,$moy[4],SORT_NUMERIC,SORT_DESC,$moy[3],SORT_NUMERIC,SORT_DESC,$moy[2],SORT_NUMERIC,SORT_DESC,$moy[1],SORT_NUMERIC,SORT_DESC,$moy[0],SORT_NUMERIC,SORT_DESC,$nom_eleve,SORT_STRING,SORT_ASC,$total_result[9],$total_result[8],$total_result[7],$total_result[6],$total_result[5],$total_result[4],$total_result[3],$total_result[2],$total_result[1],$total_result[0]); break;
        }
      }
      
      $ligne="even";
      $msg='<div class="titre_page">'.$Langue['LBL_IMPRESSION_RESULTATS_STAT_POUR'].' '.$liste_choix['livret_decoupage_livret'][$trimestre].'</div><br /><br />';
      $msg .='<table cellspacing=0 cellpadding=0 style="width:100%" class="livret">';
      $largeur=100-14*($max+1);
      $msg .='<tr><th style="width:'.$largeur.'%;text-align:center" rowspan=2>'.$Langue['LBL_IMPRESSION_ELEVE'].'</th>';
      for ($i=$max;$i>=0;$i--)
      {
        $msg .='<th style="width:14%;text-align:center" colspan=2>'.$gestclasse_config_plus['c'.$i].'</th>';
      }
      $msg .='</tr>';
      $msg .='<tr>';
      for ($i=$max;$i>=0;$i--)
      {
        $msg .='<th style="width:7%;text-align:center">'.$Langue['LBL_IMPRESSION_NOMBRE'].'</th>';
        $msg .='<th style="width:7%;text-align:center">'.$Langue['LBL_IMPRESSION_POURCENTAGE'].'</th>';
      }
      $msg .='</tr>';

      $li=1;
      foreach ($nom_eleve AS $cle => $value)
      {
        $class="";
        if ($li==count($nom_eleve)) { $class=' bas'; }
        $msg .='<tr class="'.$ligne.'"><td class="textgauche'.$class.'" style="width:'.$largeur.'%;">'.$value.'</td>';
        $tot=0;
        for ($i=$max;$i>=0;$i--)
        {
          $tot=$tot+$total_result[$i][$cle];
        }
        for ($i=$max;$i>=0;$i--)
        {
          $msg .='<td class="textcentre'.$class.'" style="width:7%;">'.$total_result[$i][$cle].'</td>';
          if ($tot!=0)
          {
            $msg .='<td class="textcentre'.$class.'" style="width:7%;">'.number_format(100*$total_result[$i][$cle]/$tot,2,',',' ').'%</td>';
          }
          else
          {
            $msg .='<td class="textcentre'.$class.'" style="width:7%;">--</td>';
          }
        }
        if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
        $li++;
        $msg .='</tr>';
      }
      $msg .='<tr class="'.$ligne.'"><td class="bas textgauche" style="width:'.$largeur.'%;"><strong>'.$Langue['LBL_IMPRESSION_MOYENNE'].'</strong></td>';
      for ($i=$max;$i>=0;$i--)
      {
        $req=mysql_query("SELECT * FROM `moyenne_classe` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND marquage='$i'");
        $msg .='<td class="bas textcentre" style="width:14%;" colspan=2><strong>'.number_format(mysql_result($req,0,'periode'.substr($trimestre,1,1)),2,',',' ').'%</strong></td>';
      }
      $msg .='</tr></table>';
      $contenu_html=$msg;
      break;
  }
  include "commun/impression.php";
?>

