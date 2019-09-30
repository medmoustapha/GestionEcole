<?php
  $id=$_POST['id'];
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $debut_annee=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $fin_annee=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $debut_annee=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $fin_annee=$_SESSION['annee_scolaire']+1;
	  $fin_annee=$fin_annee.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  $req_categorie=mysql_query("SELECT * FROM `competences` WHERE id='$id'");
  $id_cat=mysql_result($req_categorie,0,'id_cat');

  $req=mysql_query("SELECT controles.*,controles_resultats.* FROM `controles`, `controles_resultats` WHERE controles.date>='$debut_annee' AND controles.date<='$fin_annee' AND controles.id=controles_resultats.id_controle AND controles_resultats.id_competence='$id'");
  if (mysql_num_rows($req)!="")
  {
    echo "error";
  }
  else
  {
    $req=mysql_query("SELECT * FROM `controles_resultats` WHERE id_competence='$id'");
    if (mysql_num_rows($req)!="")
    {
      $req=mysql_query("UPDATE `competences` SET supprime='".date("Y-m-d")."' WHERE id='$id'");
    }
    else
    {
      $req=mysql_query("DELETE FROM `competences` WHERE id='$id'");
    }

    $req_categorie=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$fin_annee' AND (supprime='0000-00-00' OR supprime>='$fin_annee') AND id_cat='$id_cat' ORDER BY ordre ASC");
    for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
    {
      $req=mysql_query("UPDATE `competences` SET ordre='$i' WHERE id='".mysql_result($req_categorie,$i-1,'id')."'");
    }
    echo "ok";
  }
?>