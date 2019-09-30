<?php
  $id=$_POST['id'];

  // Création ou modification de la classe
  if ($id=="")
  {
    $id=Construit_Id("classes",5);
    $req = mysql_query("INSERT INTO `classes` (id,nom_classe,annee) VALUES ('$id','".$_POST['nom_classe']."','".$_POST['annee']."')");
  }
  else
  {
    $req = mysql_query("UPDATE `classes` SET nom_classe='".$_POST['nom_classe']."',annee='".$_POST['annee']."' WHERE id='$id'");
  }
  
  // Suppression de tous les niveaux la classe
  $req = mysql_query("DELETE FROM `classes_niveaux` WHERE id_classe='$id'");

  // Sauvegarde des niveaux
  $id_niveau=$_POST['id_niveau'];
  for ($i=0;$i<count($id_niveau);$i++)
  {
    $id2=Construit_Id("classes_niveaux",10);
    $req = mysql_query("INSERT INTO `classes_niveaux` (id,id_classe,id_niveau) VALUES ('$id2','$id','".$id_niveau[$i]."')");
  }

  // Suppression de toutes les personnes intervenants dans la classe
  $req = mysql_query("DELETE FROM `classes_profs` WHERE id_classe='$id'");
  
  // Sauvegarde du titulaire
  $id2=Construit_Id("classes_profs",10);
  $id_titulaire=$_POST['id_titulaire'];
  $req = mysql_query("INSERT INTO `classes_profs` (id,id_classe,id_prof,type) VALUES ('$id2','$id','".$id_titulaire[0]."','T')");

  // Sauvegarde des décharges
  if (isset($_POST['id_decharge']))
  {
    $id_decharge=$_POST['id_decharge'];
    for ($i=0;$i<count($id_decharge);$i++)
    {
      $id2=Construit_Id("classes_profs",10);
      $req = mysql_query("INSERT INTO `classes_profs` (id,id_classe,id_prof,type) VALUES ('$id2','$id','".$id_decharge[$i]."','E')");
    }
  }

  // Sauvegarde des décloisonnements
  if (isset($_POST['id_decloisonnement']))
  {
    $id_decloisonnement=$_POST['id_decloisonnement'];
    for ($i=0;$i<count($id_decloisonnement);$i++)
    {
      $id2=Construit_Id("classes_profs",10);
      $req = mysql_query("INSERT INTO `classes_profs` (id,id_classe,id_prof,type) VALUES ('$id2','$id','".$id_decloisonnement[$i]."','D')");
    }
  }

  // Sauvegarde des ATSEM
  if (isset($_POST['id_atsem']))
  {
    $id_atsem=$_POST['id_atsem'];
    for ($i=0;$i<count($id_atsem);$i++)
    {
      $id2=Construit_Id("classes_profs",10);
      $req = mysql_query("INSERT INTO `classes_profs` (id,id_classe,id_prof,type) VALUES ('$id2','$id','".$id_atsem[$i]."','S')");
    }
  }

  // Sauvegarde des intervenants extérieurs
  if (isset($_POST['id_intervenant']))
  {
    $id_intervenant=$_POST['id_intervenant'];
    for ($i=0;$i<count($id_intervenant);$i++)
    {
      $id2=Construit_Id("classes_profs",10);
      $req = mysql_query("INSERT INTO `classes_profs` (id,id_classe,id_prof,type) VALUES ('$id2','$id','".$id_intervenant[$i]."','I')");
    }
  }

  // Sauvegarde des autres intervenants
  if (isset($_POST['id_autre']))
  {
    $id_autre=$_POST['id_autre'];
    for ($i=0;$i<count($id_autre);$i++)
    {
      $id2=Construit_Id("classes_profs",10);
      $req = mysql_query("INSERT INTO `classes_profs` (id,id_classe,id_prof,type) VALUES ('$id2','$id','".$id_autre[$i]."','U')");
    }
  }
  
  $_SESSION['annee_scolaire']=$_POST['annee'];
  Change_Annee_Session($_POST['annee']);

  echo $id;
?>
