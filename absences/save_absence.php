<?php
  $type=$_POST['periode'];
  $id=$_POST['id'];
  $l=explode("_",$id);
  $id_eleve=$l[0];
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=$_SESSION['annee_scolaire'];
  }
  else
  {
    if ($_SESSION['mois_en_cours']<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=$_SESSION['annee_scolaire']+1; } else { $annee=$_SESSION['annee_scolaire']; }
  }
  $date=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours'],$l[1],$annee));

  switch ($type)
  {
    case "matin":
      $req=mysql_query("INSERT INTO `absences` (date,id_eleve,matin,apres_midi) VALUES ('$date','$id_eleve','1','0')");
      break;
    case "am":
      $req=mysql_query("DELETE FROM `absences` WHERE date='$date' AND id_eleve='$id_eleve'");
      $req=mysql_query("INSERT INTO `absences` (date,id_eleve,matin,apres_midi) VALUES ('$date','$id_eleve','0','1')");
      break;
    case "journee":
      $req=mysql_query("DELETE FROM `absences` WHERE date='$date' AND id_eleve='$id_eleve'");
      $req=mysql_query("INSERT INTO `absences` (date,id_eleve,matin,apres_midi) VALUES ('$date','$id_eleve','1','1')");
      break;
    case "present":
      $req=mysql_query("DELETE FROM `absences` WHERE date='$date' AND id_eleve='$id_eleve'");
      break;
  }
?>