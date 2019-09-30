<?php
  session_start();
  
  include "../config.php";

  include "../langues/fr-FR/config.php";
  include "../langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
	$Langue[$cle]=$Langue_Application[$cle];
  }

  if ($_SESSION['language_application']!="fr-FR")
  {
	if (file_exists("../langues/".$_SESSION['language_application']."/config.php"))
	{
	  include "../langues/".$_SESSION['language_application']."/config.php";
	}
	if (file_exists("../langues/".$_SESSION['language_application']."/commun.php"))
	{
	  include "../langues/".$_SESSION['language_application']."/commun.php";
	  foreach ($Langue_Application AS $cle => $value)
	  {
		$Langue[$cle]=$Langue_Application[$cle];
	  }
	}
  }
  
  include "../commun/fonctions.php";

  Connexion_DB();

  $id_classe=$_GET['id_classe'];
  $id_niveau=$_GET['id_niveau'];
  $id_titulaire=$_GET['id_titulaire'];
  $annee=$_GET['annee'];
  Param_Utilisateur($id_titulaire,$annee);

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $annee_fin=$annee.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $annee_fin=$annee+1;
	  $annee_fin=$annee_fin.$gestclasse_config_plus['fin_annee_scolaire'];
  }

if ($gestclasse_config_plus['decoupage_livret']=="T") { $oli=3; } else { $oli=5; }

/* On recalcule les moyennes pour toutes les périodes */
for ($poi=1;$poi<=$oli;$poi++)
{
  $trimestre=$gestclasse_config_plus['decoupage_livret'].$poi;
  
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
  foreach ($liste_choix['matieres_stat'] AS $cle => $value)
  {
	$nb_eleve_stat[$cle]=0;
  }
  for ($i=0;$i<=$max;$i++)
  {
    $total_classe[$i]=0;
	foreach ($liste_choix['matieres_stat'] AS $cle => $value)
	{
	  $total_stat[$cle][$i]=0;
	}
  }

  /* On calcule les résultats pour chaque élève de la classe */
  $req_eleve=mysql_query("SELECT * FROM `eleves_classes` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau'");
  for ($z=1;$z<=mysql_num_rows($req_eleve);$z++)
  {
    $id_eleve_boucle=mysql_result($req_eleve,$z-1,'eleves_classes.id_eleve');
    /* Initialisation des données */
    for ($i=0;$i<=$max;$i++)
    {
      $total_result[$i]=0;
	  foreach ($liste_choix['matieres_stat'] AS $cle => $value)
  	  {
  	    $total_result_stat[$cle][$i]=0;
	  }
    }

    /* On récupère les contrôles du trimestre et les compétences */
    $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$id_titulaire."' AND id_niveau='$id_niveau' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin')");
    for ($i=1;$i<=mysql_num_rows($req_competence);$i++)
    {
      $id_c=mysql_result($req_competence,$i-1,'id');
      $id_s=mysql_result($req_competence,$i-1,'statistiques');
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
        $total_result[$valeur]=$total_result[$valeur]+1;
		$total_result_stat[$id_s][$valeur]=$total_result_stat[$id_s][$valeur]+1;
      }
    }
    /* On calcule les moyennes de l'élève dans la boucle */
    $total_periode=0;
    foreach ($liste_choix['matieres_stat'] AS $cle => $value)
    {
  	  $total_periode_stat[$cle]=0;
    }
    for ($i=0;$i<=$max;$i++)
    {
      $total_periode=$total_periode+$total_result[$i];
	  foreach ($liste_choix['matieres_stat'] AS $cle => $value)
  	  {
	    $total_periode_stat[$cle]=$total_periode_stat[$cle]+$total_result_stat[$cle][$i];
	  }
    }
    if ($total_periode!=0) { $nb_eleve=$nb_eleve+1; }
    foreach ($liste_choix['matieres_stat'] AS $cle => $value)
    {
	  if($total_periode_stat[$cle]!=0) { $nb_eleve_stat[$cle]=$nb_eleve_stat[$cle]+1; }
	}
    for ($i=$max;$i>=0;$i--)
    {
      if ($total_periode!=0)
      {
        $total_classe[$i]=$total_classe[$i]+(100*$total_result[$i]/$total_periode);
      }
      foreach ($liste_choix['matieres_stat'] AS $cle => $value)
      {
	    if ($total_periode_stat[$cle]!=0) 
		{ 
		  $total_stat[$cle][$i]=$total_stat[$cle][$i]+(100*$total_result_stat[$cle][$i]/$total_periode_stat[$cle]); 
		}
	  }
    }
  }

  for ($i=$max;$i>=0;$i--)
  {
    $req=mysql_query("SELECT * FROM `moyenne_classe` WHERE marquage='$i' AND id_classe='$id_classe' AND id_niveau='$id_niveau' AND statistiques='G'");
    if (mysql_num_rows($req)=="")
    {
      $req=mysql_query("INSERT INTO `moyenne_classe` (id_classe,id_niveau,marquage,statistiques) VALUES ('$id_classe','$id_niveau','$i','G')");
    }
    foreach ($liste_choix['matieres_stat'] AS $cle => $value)
    {
      $req=mysql_query("SELECT * FROM `moyenne_classe` WHERE marquage='$i' AND id_classe='$id_classe' AND id_niveau='$id_niveau' AND statistiques='$cle'");
      if (mysql_num_rows($req)=="")
      {
        $req=mysql_query("INSERT INTO `moyenne_classe` (id_classe,id_niveau,marquage,statistiques) VALUES ('$id_classe','$id_niveau','$i','$cle')");
      }
	}
  }
  for ($i=$max;$i>=0;$i--)
  {
    if ($nb_eleve!=0)
    {
      $moy=$total_classe[$i]/$nb_eleve;
      $req=mysql_query("UPDATE `moyenne_classe` SET periode".substr($trimestre,1,1)."='$moy' WHERE marquage='$i' AND id_classe='$id_classe' AND id_niveau='$id_niveau' AND statistiques='G'");
    }
    else
    {
      $req=mysql_query("UPDATE `moyenne_classe` SET periode".substr($trimestre,1,1)."='0.00' WHERE marquage='$i' AND id_classe='$id_classe' AND id_niveau='$id_niveau' AND statistiques='G'");
    }
    foreach ($liste_choix['matieres_stat'] AS $cle => $value)
    {
      if ($nb_eleve_stat[$cle]!=0)
      {
        $moy=$total_stat[$cle][$i]/$nb_eleve_stat[$cle];
        $req=mysql_query("UPDATE `moyenne_classe` SET periode".substr($trimestre,1,1)."='$moy' WHERE marquage='$i' AND id_classe='$id_classe' AND id_niveau='$id_niveau' AND statistiques='$cle'"); 
      }
      else
      {
        $req=mysql_query("UPDATE `moyenne_classe` SET periode".substr($trimestre,1,1)."='0.00' WHERE marquage='$i' AND id_classe='$id_classe' AND id_niveau='$id_niveau' AND statistiques='$cle'");
      }
	}
  }
}
?>
