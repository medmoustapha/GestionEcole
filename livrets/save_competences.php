<?php
  $id=$_POST['id'];
  $intitule=$_POST['intitule'];
  $code=$_POST['code'];
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
  $id_cat=$_POST['id_cat'];
  $statistiques=$_POST['statistiques'];
  $req_categorie=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_cat='$id_cat' ORDER BY ordre DESC");
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
    $id=Construit_Id("competences",20);
    $req=mysql_query("INSERT INTO `competences` (id,id_prof,id_niveau,id_cat,intitule,code,ordre,supprime,cree,statistiques) VALUES ('$id','".$_SESSION['titulaire_classe_cours']."','".$_SESSION['niveau_en_cours']."','$id_cat','".$intitule."','".$code."','$ordre','0000-00-00','$date_creation','$statistiques')");
  }
  else
  {
    $req=mysql_query("SELECT * FROM `competences` WHERE id='$id'");
    $id_p=mysql_result($req,0,'id_cat');
    if ($id_p==$id_cat)
    {
      $req=mysql_query("UPDATE `competences` SET intitule='".$intitule."',code='".$code."',statistiques='$statistiques' WHERE id='$id'");
    }
    else
    {
      $req=mysql_query("UPDATE `competences` SET intitule='".$intitule."', code='".$code."', id_cat='$id_cat', ordre='$ordre',statistiques='$statistiques' WHERE id='$id'");
      $req_categorie=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_cat='$id_p' ORDER BY ordre ASC");
      for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
      {
        $req=mysql_query("UPDATE `competences` SET ordre='$i' WHERE id='".mysql_result($req_categorie,$i-1,'id')."'");
      }
    }
  }
?>