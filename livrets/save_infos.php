<?php
  $id=$_POST['id'];
  $idp=$_POST['id_prof2'];
  $_POST['id_prof']=$idp[0];
  
  if ($id=="")
  {
    $id=Construit_Id("controles",20);
    $req = mysql_query("INSERT INTO `controles` (id) VALUES ('$id')");
    $faire=false;
  }
  else
  {
    $req=mysql_query("SELECT * FROM `controles` WHERE id='$id'");
    $trimestre=mysql_result($req,0,'trimestre');
    $faire=true;
  }

  foreach ($tableau_variable['controles'] AS $cle)
  {
    if ($cle['nom']!="id")
    {
      if ($cle['type']=="checkbox" && !isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=0; }
      if ($cle['type']=="date" && isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=Date_Convertir($_POST[$cle['nom']],$Format_Date_PHP,"Y-m-d"); }
      if ($cle['type']=="varchar" && isset($_POST[$cle['nom']]))
      {
        if ($cle['majuscule']=="1")
        {
          $_POST[$cle['nom']]=strtoupper($_POST[$cle['nom']]);
        }
      }
      $req = mysql_query("UPDATE `controles` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
    }
  }

// *******************************************************
// * Sauvegarde des compétences testées lors du contrôle *
// *******************************************************

  // On récupère les compétences cochées

  $liste_competence=$_POST['competences'];
  // On contrôle les compétences et on supprime celles inutiles
  $req=mysql_query("SELECT * FROM `controles_competences` WHERE id_controle='$id'");
  for($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $id_c=mysql_result($req,$i-1,'id_competence');
    $id_c2=mysql_result($req,$i-1,'id');
    if (!in_array($id_c,$liste_competence))
    {
      $req2=mysql_query("DELETE FROM `controles_competences` WHERE id='$id_c2'");
      $req2=mysql_query("DELETE FROM `controles_resultats` WHERE id_controle='$id' AND id_competence='$id_c'");
    }
  }

  // On sauvegarde les nouvelles compétences cochées
  for($i=0;$i<count($liste_competence);$i++)
  {
    $req=mysql_query("SELECT * FROM `controles_competences` WHERE id_competence='".$liste_competence[$i]."' AND id_controle='$id'");
    if (mysql_num_rows($req)=="")
    {
      $id_c=Construit_Id("controles_competences",20);
      $req=mysql_query("INSERT INTO `controles_competences` (id,id_controle,id_competence) VALUES ('$id_c','$id','".$liste_competence[$i]."')");
    }
  }

  $_SESSION['trimestre_en_cours']=$_POST['trimestre'];
  
  echo $id;
?>