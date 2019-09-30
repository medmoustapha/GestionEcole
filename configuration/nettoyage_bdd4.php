<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=date("Y");
  }
  else
  {
    if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=date("Y")-1; } else { $annee=date("Y"); }
  }
  $limite_age=date("Y-m-d",mktime(0,0,0,8,1,$annee-12));
  $limite_cj=date("Y-m-d",mktime(0,0,0,8,1,$annee-2));
  $limite_annee=date("Y-m-d",mktime(0,0,0,8,1,$annee));
  
// Suppression des élèves ayant 12 ans révolus ou étant en 6ème
  $req=mysql_query("SELECT * FROM `eleves` WHERE date_naissance<'$limite_age' OR sixieme='1'");
  if (mysql_num_rows($req)!="")
  {
    for ($i=1;$i<=mysql_num_rows($req);$i++)
	{
	  $id_eleve=mysql_result($req,$i-1,'id');
	  $req2=mysql_query("DELETE FROM `absences` WHERE id_eleve='$id_eleve'");
	  $req2=mysql_query("DELETE FROM `absences_justificatifs` WHERE id_eleve='$id_eleve'");
	  $req2=mysql_query("DELETE FROM `accueil_perso` WHERE id_eleve='$id_util'");
	  $req2=mysql_query("DELETE FROM `controles_resultats` WHERE id_eleve='$id_eleve'");
	  $req2=mysql_query("DELETE FROM `eleves_classes` WHERE id_eleve='$id_eleve'");
	  $req2=mysql_query("DELETE FROM `param_persos` WHERE id_prof='$id_eleve'");
	  $req2=mysql_query("DELETE FROM `bibliotheque_emprunt` WHERE id_util='$id_eleve' AND type_util='E'");
	  $req2=mysql_query("DELETE FROM `taches` WHERE id_util='$id_eleve' AND type_util='E'");
	  $req2=mysql_query("DELETE FROM `email` WHERE id_expediteur='$id_eleve' AND type_expediteur='E'");
	  $req2=mysql_query("DELETE FROM `signatures` WHERE id_util='$id_eleve' AND type_util='E'");
	  $req2=mysql_query("DELETE FROM `signatures` WHERE parametre LIKE '%|".$id_eleve."'");
	  $req2=mysql_query("DELETE FROM `livrets_appreciation` WHERE id_eleve='$id_eleve'");
	  $req2=mysql_query("DELETE FROM `contacts_eleves` WHERE id_eleve='$id_eleve'");
	  $req2=mysql_query("DELETE FROM `eleves` WHERE id='$id_eleve'");
	}
  }

// Suppression des contrôles inutiles
  $req=mysql_query("SELECT * FROM `controles` WHERE date<'$limite_annee'");
  if (mysql_num_rows($req)!="")
  {
    for ($i=1;$i<=mysql_num_rows($req);$i++)
	{
	  $id_controle=mysql_result($req,$i-1,'id');
      $req2=mysql_query("SELECT * FROM `controles_resultats` WHERE id_controle='$id_controle'");
	  if (mysql_num_rows($req2)=="")
	  {
        $req2=mysql_query("DELETE FROM `controles` WHERE id='$id_controle'");
        $req2=mysql_query("DELETE FROM `controles_competences` WHERE id_controle='$id_controle'");
	  }
	}
  }	
  
// Suppression des compétences inutiles
  $req=mysql_query("SELECT * FROM `competences` WHERE supprime<>'0000-00-00'");
  if (mysql_num_rows($req)!="")
  {
    for ($i=1;$i<=mysql_num_rows($req);$i++)
	{
	  $id_competence=mysql_result($req,$i-1,'id');
      $req2=mysql_query("SELECT * FROM `controles_resultats` WHERE id_competence='$id_competence'");
	  if (mysql_num_rows($req2)=="")
	  {
        $req2=mysql_query("DELETE FROM `competences` WHERE id='$id_competence'");
        $req2=mysql_query("DELETE FROM `controles_competences` WHERE id_competence='$id_competence'");
	  }
	}
  }	
  
// Suppression des données diverses de plus de 2 ans
  $req=mysql_query("DELETE FROM `accueil_news` WHERE date<'$limite_cj'");
  $req=mysql_query("DELETE FROM `reunions` WHERE date<'$limite_cj'");

// Suppression des cahiers-journaux de plus de 2 ans
  $req=mysql_query("DELETE FROM `cahierjournal` WHERE date<'$limite_cj'");
  $req=mysql_query("DELETE FROM `devoirs` WHERE date_donnee<'$limite_cj'");
?>