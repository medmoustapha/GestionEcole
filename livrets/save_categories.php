<?php
  $id=$_POST['id'];
  $titre=$_POST['titre'];
  $parent=$_POST['parent'];
  $date_creation=date("Y-m-d");
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $debut_annee=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $annee_fin=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $debut_annee=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $fin_annee=$_SESSION['annee_scolaire']+1;
	  $annee_fin=$fin_annee.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='$parent' ORDER BY ordre DESC");
  if (mysql_num_rows($req_categorie)!="")
  {
    $ordre=mysql_result($req_categorie,0,'ordre')+1;
  }
  else
  {
    $ordre=1;
  }
  if ($id=="")
  {
    $id=Construit_Id("competences_categories",10);
    $req=mysql_query("INSERT INTO `competences_categories` (id,id_prof,id_niveau,titre,id_parent,ordre,supprime,cree) VALUES ('$id','".$_SESSION['titulaire_classe_cours']."','".$_SESSION['niveau_en_cours']."','".$titre."','$parent','$ordre','0000-00-00','$date_creation')");
  }
  else
  {
    $req=mysql_query("SELECT * FROM `competences_categories` WHERE id='$id'");
    $id_p=mysql_result($req,0,'id_parent');
    if ($id_p==$parent)
    {
      $req=mysql_query("UPDATE `competences_categories` SET titre='".$titre."' WHERE id='$id'");
    }
    else
    {
      $req=mysql_query("UPDATE `competences_categories` SET titre='".$titre."', id_parent='$parent', ordre='$ordre' WHERE id='$id'");
      $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='$id_p' ORDER BY ordre ASC");
      for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
      {
        $req=mysql_query("UPDATE `competences_categories` SET ordre='$i' WHERE id='".mysql_result($req_categorie,$i-1,'id')."'");
      }
    }
  }
?>