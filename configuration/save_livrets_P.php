<?php
  /* On sauvegarde le découpage du livret */
  $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='decoupage_livret'");
  if (mysql_num_rows($req)=="")
  {
    $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','decoupage_livret','".$_POST['decoupage_livret']."')");
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='decoupage_livret'");
    $trimestre=mysql_result($req,0,'decoupage_livret');
  }
  else
  {
    $trimestre=mysql_result($req,0,'decoupage_livret');
    $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['decoupage_livret']."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='decoupage_livret'");
  }
  /* Si le découpage est différent d'avant */
  $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_P1'");
  if (mysql_num_rows($req)=="")
  {
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_P1','".Date_Convertir($_POST['debut_P1'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_P2','".Date_Convertir($_POST['debut_P2'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_P3','".Date_Convertir($_POST['debut_P3'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_P4','".Date_Convertir($_POST['debut_P4'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_P5','".Date_Convertir($_POST['debut_P5'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_T1','".Date_Convertir($_POST['debut_T1'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_T2','".Date_Convertir($_POST['debut_T2'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','debut_T3','".Date_Convertir($_POST['debut_T3'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_P1','".Date_Convertir($_POST['fin_P1'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_P2','".Date_Convertir($_POST['fin_P2'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_P3','".Date_Convertir($_POST['fin_P3'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_P4','".Date_Convertir($_POST['fin_P4'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_P5','".Date_Convertir($_POST['fin_P5'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_T1','".Date_Convertir($_POST['fin_T1'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_T2','".Date_Convertir($_POST['fin_T2'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','fin_T3','".Date_Convertir($_POST['fin_T3'],$Format_Date_PHP,'Y-m-d')."')");
  }
  else
  {
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P1'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_P1'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P1'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_P1'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P2'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_P2'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P2'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_P2'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P3'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_P3'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P3'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_P3'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P4'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_P4'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P4'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_P4'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P5'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_P5'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P5'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_P5'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_T1'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_T1'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_T1'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_T1'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_T2'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_T2'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_T2'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_T2'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_T3'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='debut_T3'");
	  $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_T3'],$Format_Date_PHP,'Y-m-d')."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='fin_T3'");
  }

    // On recherche la classe dont est titulaire l'enseignant
    $req=mysql_query("SELECT classes.*, classes_profs.* FROM `classes`,`classes_profs` WHERE classes.id=classes_profs.id_classe AND classes_profs.id_prof='".$_SESSION['id_util']."' AND classes_profs.type='T' AND classes.annee='".$_SESSION['annee_scolaire']."'");
    $id_classe=mysql_result($req,0,'classes.id');
    // Si le nouveau découpage est en trimestre
	if ($_POST['decoupage_livret']=="T")
	{
	  $req=mysql_query("UPDATE `controles` SET trimestre='T1' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_T1'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_T1'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("UPDATE `controles` SET trimestre='T2' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_T2'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_T2'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("UPDATE `controles` SET trimestre='T3' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_T3'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_T3'],$Format_Date_PHP,'Y-m-d')."')");
	}
	else
	{
	  $req=mysql_query("UPDATE `controles` SET trimestre='P1' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_P1'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_P1'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("UPDATE `controles` SET trimestre='P2' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_P2'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_P2'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("UPDATE `controles` SET trimestre='P3' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_P3'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_P3'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("UPDATE `controles` SET trimestre='P4' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_P4'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_P4'],$Format_Date_PHP,'Y-m-d')."')");
	  $req=mysql_query("UPDATE `controles` SET trimestre='P5' WHERE id_classe='$id_classe' AND (date>='".Date_Convertir($_POST['debut_P5'],$Format_Date_PHP,'Y-m-d')."' AND date<='".Date_Convertir($_POST['fin_P5'],$Format_Date_PHP,'Y-m-d')."')");
	}
  
  /* On sauvegarde la méthode de calcul de la notation */
  $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='calcul_moyenne'");
  if (mysql_num_rows($req)=="")
  {
    $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','calcul_moyenne','".$_POST['calcul_moyenne']."')");
  }
  else
  {
    $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['calcul_moyenne']."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='calcul_moyenne'");
  }

  $_POST['i0']=0;
  for ($i=0;$i<=9;$i++)
  {
    if ($_POST['c'.$i]!="")
    {
      /* Sauvegarde de l'intitulé de la compétence */
      $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='c".$i."'");
      if (mysql_num_rows($req)=="")
      {
        $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','c".$i."','".$_POST['c'.$i]."')");
      }
      else
      {
        $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['c'.$i]."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='c".$i."'");
      }
      
      /* Sauvegarde de la borne supérieure et inférieure de la compétence */
      $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='s".$i."'");
      if (mysql_num_rows($req)=="")
      {
        $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','s".$i."','".$_POST['s'.$i]."')");
        $j=$i+1;
        $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','i".$j."','".$_POST['s'.$i]."')");
      }
      else
      {
        $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['s'.$i]."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='s".$i."'");
        $j=$i+1;
        $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['s'.$i]."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='i".$j."'");
      }

      /* Sauvegarde de la couleur d'écriture de la compétence */
      $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='couleur".$i."'");
      if (mysql_num_rows($req)=="")
      {
        $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','couleur".$i."','".$_POST['couleur'.$i]."')");
      }
      else
      {
        $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['couleur'.$i]."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='couleur".$i."'");
      }

      /* Sauvegarde de la couleur d'écriture de la compétence */
      $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='couleur_fond".$i."'");
      if (mysql_num_rows($req)=="")
      {
        $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','couleur_fond".$i."','".$_POST['couleur_fond'.$i]."')");
      }
      else
      {
        $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['couleur_fond'.$i]."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='couleur_fond".$i."'");
      }
    }
  }
?>