<?php
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['calcul_moyenne']."' WHERE parametre='calcul_moyenne'");

  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P1'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_P1'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P1'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_P1'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P2'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_P2'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P2'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_P2'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P3'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_P3'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P3'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_P3'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P4'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_P4'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P4'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_P4'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_P5'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_P5'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_P5'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_P5'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_T1'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_T1'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_T1'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_T1'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_T2'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_T2'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_T2'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_T2'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['debut_T3'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='debut_T3'");
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".Date_Convertir($_POST['fin_T3'],$Format_Date_PHP,'Y-m-d')."' WHERE parametre='fin_T3'");

	// On recherche la classe dont est titulaire l'enseignant
	$req=mysql_query("SELECT classes.*, classes_profs.* FROM `classes`,`classes_profs` WHERE classes.id=classes_profs.id_classe AND classes_profs.id_prof='".$_SESSION['id_util']."' AND classes_profs.type='T' AND classes.annee='".$_SESSION['annee_scolaire']."'");
	if (mysql_num_rows($req)!="")
	{
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
	}
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['decoupage_livret']."' WHERE parametre='decoupage_livret'");

  $_POST['i0']=0;
  for ($i=0;$i<=9;$i++)
  {
    if ($_POST['c'.$i]!="")
    {
      $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['c'.$i]."' WHERE parametre='c".$i."'");
      $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['s'.$i]."' WHERE parametre='s".$i."'");
      $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['couleur'.$i]."' WHERE parametre='couleur".$i."'");
      $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['couleur_fond'.$i]."' WHERE parametre='couleur_fond".$i."'");
      if ($i==0)
      {
        $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['i0']."' WHERE parametre='i0'");
      }
      else
      {
        $j=$i-1;
        $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".$_POST['s'.$j]."' WHERE parametre='i".$i."'");
      }
    }
  }
?>