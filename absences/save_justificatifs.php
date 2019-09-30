<?php
  $id=$_POST['id'];

  if ($id=="")
  {
    $id=Construit_Id("absences_justificatifs",20);
    $req = mysql_query("INSERT INTO `absences_justificatifs` (id) VALUES ('$id')");
  }

  // On sauvegarde le justificatif
  foreach ($tableau_variable['justificatifs'] AS $cle)
  {
    if ($cle['nom']!="id")
    {
      if ($cle['type']=="checkbox" && !isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=0; }
      if ($cle['type']=="date" && isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=Date_Convertir($_POST[$cle['nom']],$Format_Date_PHP,"Y-m-d"); }
      $req = mysql_query("UPDATE `absences_justificatifs` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
    }
  }
  
  // On crée les absences associées au justificatif
  $id_eleve=$_POST['id_eleve'];
  $date_debut=$_POST['date_debut'];
  $date_fin=$_POST['date_fin'];
  $heure_debut=$_POST['heure_debut'];
  $heure_fin=$_POST['heure_fin'];
  // Cas où le justificatif est sur une journée
  if ($date_debut==$date_fin)
  {
    $req=mysql_query("SELECT * FROM `absences` WHERE id_eleve='$id_eleve' AND date='$date_debut'");
    if (mysql_num_rows($req)=="")
    {
      if ($heure_debut=="M") { $matin='1'; } else { $matin='0'; }
      if ($heure_fin=="S") { $am='1'; } else { $am='0'; }
      $req=mysql_query("INSERT INTO `absences` (date,id_eleve,matin,apres_midi) VALUES ('$date_debut','$id_eleve','$matin','$am')");
    }
    else
    {
      $matin=mysql_result($req,0,'matin');
      $am=mysql_result($req,0,'apres_midi');
      if ($heure_debut=="M") { $matin='1'; }
      if ($heure_fin=="S") { $am='1'; }
      $req=mysql_query("UPDATE `absences` SET matin='$matin', apres_midi='$am' WHERE date='$date_debut' AND id_eleve='$id_eleve'");
    }
  }
  else
  // Cas où le justificatif est sur plusieurs jours
  {
    // Cas du premier jour
    $req=mysql_query("SELECT * FROM `absences` WHERE id_eleve='$id_eleve' AND date='$date_debut'");
    if (mysql_num_rows($req)=="")
    {
      if ($heure_debut=="M") { $matin='1'; } else { $matin='0'; }
      $req=mysql_query("INSERT INTO `absences` (date,id_eleve,matin,apres_midi) VALUES ('$date_debut','$id_eleve','$matin','1')");
    }
    else
    {
      $matin=mysql_result($req,0,'matin');
      if ($heure_debut=="M") { $matin='1'; }
      $req=mysql_query("UPDATE `absences` SET matin='$matin', apres_midi='1' WHERE date='$date_debut' AND id_eleve='$id_eleve'");
    }
    
    // Jours entre les deux
    $date_en_cours_fin=date("Y-m-d",mktime(0,0,0,substr($date_fin,5,2),substr($date_fin,8,2)-1,substr($date_fin,0,4)));
    if ($date_debut!=$date_en_cours_fin)
    {
      $decal=0;
      do
      {
        $decal=$decal+1;
        $date_en_cours=date("Y-m-d",mktime(0,0,0,substr($date_debut,5,2),substr($date_debut,8,2)+$decal,substr($date_debut,0,4)));
        $req= mysql_query("SELECT * FROM `absences` WHERE id_eleve='$id_eleve' AND date='$date_en_cours'");
        if (mysql_num_rows($req)=="")
        {
          $req= mysql_query("INSERT INTO `absences` (date,id_eleve,matin,apres_midi) VALUES ('$date_en_cours','$id_eleve','1','1')");
        }
        else
        {
          $req= mysql_query("UPDATE `absences` SET matin='1', apres_midi='1' WHERE id_eleve='$id_eleve' AND date='$date_en_cours'");
        }
      } while ($date_en_cours!=$date_en_cours_fin);
    }

    // Cas du dernier jour
    $req=mysql_query("SELECT * FROM `absences` WHERE id_eleve='$id_eleve' AND date='$date_fin'");
    if (mysql_num_rows($req)=="")
    {
      if ($heure_fin=="S") { $am='1'; } else { $am='0'; }
      $req=mysql_query("INSERT INTO `absences` (date,id_eleve,matin,apres_midi) VALUES ('$date_fin','$id_eleve','1','$am')");
    }
    else
    {
      $am=mysql_result($req,0,'apres_midi');
      if ($heure_fin=="S") { $am='1'; }
      $req=mysql_query("UPDATE `absences` SET matin='1', apres_midi='$am' WHERE date='$date_fin' AND id_eleve='$id_eleve'");
    }
  }

  echo $id;
?>