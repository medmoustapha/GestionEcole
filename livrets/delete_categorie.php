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
  $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id='$id'");
  $id_cat=mysql_result($req_categorie,0,'id_parent');
  $supprimable=true;
  $supprime_def=true;
  
  // Si la catégorie n'est pas une sous-catégorie, on regarde si elle contient des sous-catégories non supprimées
  if ($id_cat=="")
  {
    $req=mysql_query("SELECT * FROM `competences_categories` WHERE id_parent='$id' AND supprime='0000-00-00'");
    if (mysql_num_rows($req)!="")
    {
      echo "error";
    }
    else
    // On regarde si la catégorie comprend des compétences non supprimées
    {
      $req=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id' AND supprime='0000-00-00'");
      if (mysql_num_rows($req)!="")
      {
        echo "error";
      }
      else
      // La catégorie ne comprend aucune sous-catégorie ni compétence non supprimée. On regarde maintenant si on peut la supprimer définitivement
      {
        $req=mysql_query("SELECT * FROM `competences_categories` WHERE id_parent='$id' AND supprime!='0000-00-00'");
        $req2=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id' AND supprime!='0000-00-00'");
        if (mysql_num_rows($req)!="" || mysql_num_rows($req2)!="")
        {
          $req=mysql_query("UPDATE `competences_categories` SET supprime='".date("Y-m-d")."' WHERE id='$id'");
        }
        else
        {
          $req=mysql_query("DELETE FROM `competences_categories` WHERE id='$id'");
        }
        
        // On réordonne les catégories
        $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_parent='$id_cat' AND supprime='0000-00-00'");
        for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
        {
          $req=mysql_query("UPDATE `competences_categories` SET ordre='$i' WHERE id='".mysql_result($req_categorie,$i-1,'id')."'");
        }
        echo "ok";
      }
    }
  }
  else
  // La catégorie est une sous-catégorie : on recherche si elle comprend des compétences non supprimées
  {
    $req=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id' AND supprime='0000-00-00'");
    if (mysql_num_rows($req)!="")
    {
      echo "error";
    }
    else
    // La catégorie ne comprend aucune compétence non supprimée. On regarde maintenant si on peut la supprimer définitivement
    {
      $req=mysql_query("SELECT * FROM `competences` WHERE id_cat='$id' AND supprime!='0000-00-00'");
      if (mysql_num_rows($req)!="")
      {
        $req=mysql_query("UPDATE `competences_categories` SET supprime='".date("Y-m-d")."' WHERE id='$id'");
      }
      else
      {
        $req=mysql_query("DELETE FROM `competences_categories` WHERE id='$id'");
      }
      
      // On réordonne les catégories
      $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_parent='$id_cat' AND supprime='0000-00-00'");
      for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
      {
        $req=mysql_query("UPDATE `competences_categories` SET ordre='$i' WHERE id='".mysql_result($req_categorie,$i-1,'id')."'");
      }
      echo "ok";
    }
  }
?>