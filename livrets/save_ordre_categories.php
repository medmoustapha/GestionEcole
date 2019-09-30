<?php
$id_cat=$_POST['id_cat'];
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

$req=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='$id_cat' ORDER BY ordre ASC");
for ($i=1;$i<=mysql_num_rows($req);$i++)
{
  $id=mysql_result($req,$i-1,'id');
  $ordre=mysql_result($req,$i-1,'ordre');
  $key=array_search($ordre,$_POST['ListeCategorie'.$id_cat])+1;
  $req2=mysql_query("UPDATE `competences_categories` SET ordre='$key' WHERE id='$id'");
}
?>