<?php
if ($_SESSION['type_util']=="D")
// **********************************************
// * Configuration des onglets par le directeur *
// **********************************************
{
  // Sauvegarde des onglets du directeur
  $p="";
  $ok=false;
  if (isset($_POST['directeur'])) { $onglet_D=$_POST['directeur']; $ok=true; }
  foreach ($onglet['D'] AS $cle => $value)
  {
    if (in_array($cle,$onglet_obligatoire))
    {
      $p=$p.$cle.",";
    }
    else
    {
      if ($ok==true)
      {
        if (in_array($cle,$onglet_D))
        {
          $p=$p.$cle.",";
        }
      }
    }
  }
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".substr($p,0,strlen($p)-1)."' WHERE parametre='onglet_D'");

  // Sauvegarde des onglets du professeur
  $p="";
  $ok=false;
  if (isset($_POST['enseignant'])) { $onglet_P=$_POST['enseignant']; $ok=true; }
  foreach ($onglet['P'] AS $cle => $value)
  {
    if (in_array($cle,$onglet_obligatoire))
    {
      $p=$p.$cle.",";
    }
    else
    {
      if ($ok==true)
      {
        if (in_array($cle,$onglet_P))
        {
          $p=$p.$cle.",";
        }
      }
    }
  }
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".substr($p,0,strlen($p)-1)."' WHERE parametre='onglet_P'");

  // Sauvegarde des onglets des parents
  $p="";
  $ok=false;
  if (isset($_POST['eleve'])) { $onglet_E=$_POST['eleve']; $ok=true; }
  foreach ($onglet['E'] AS $cle => $value)
  {
    if (in_array($cle,$onglet_obligatoire))
    {
      $p=$p.$cle.",";
    }
    else
    {
      if ($ok==true)
      {
        if (in_array($cle,$onglet_E))
        {
          $p=$p.$cle.",";
        }
      }
    }
  }
  $req=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".substr($p,0,strlen($p)-1)."' WHERE parametre='onglet_E'");
}
else
// **********************************************
// * Configuration des onglets par l'enseignant *
// **********************************************
{
  // Sauvegarde des onglets du professeur
  $p="";
  $ok=false;
  if (isset($_POST['enseignant'])) { $onglet_P=$_POST['enseignant']; $ok=true; }
  foreach ($onglet['P'] AS $cle => $value)
  {
    if (in_array($cle,$onglet_obligatoire))
    {
      $p=$p.$cle.",";
    }
    else
    {
      if ($ok==true)
      {
        if (in_array($cle,$onglet_P))
        {
          $p=$p.$cle.",";
        }
      }
    }
  }
  $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='onglet_P'");
  if (mysql_num_rows($req)=="")
  {
    $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','onglet_P','".substr($p,0,strlen($p)-1)."')");
  }
  else
  {
    $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".substr($p,0,strlen($p)-1)."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='onglet_P'");
  }

  // Sauvegarde des onglets des parents
  $p="";
  $ok=false;
  if (isset($_POST['eleve'])) { $onglet_E=$_POST['eleve']; $ok=true; }
  foreach ($onglet['E'] AS $cle => $value)
  {
    if (in_array($cle,$onglet_obligatoire))
    {
      $p=$p.$cle.",";
    }
    else
    {
      if ($ok==true)
      {
        if (in_array($cle,$onglet_E))
        {
          $p=$p.$cle.",";
        }
      }
    }
  }
  $req=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='onglet_E'");
  if (mysql_num_rows($req)=="")
  {
    $req=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','onglet_E','".substr($p,0,strlen($p)-1)."')");
  }
  else
  {
    $req=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".substr($p,0,strlen($p)-1)."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='onglet_E'");
  }
}
?>