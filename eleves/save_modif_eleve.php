<?php
  $id=$_POST['id'];
  $id_niveau=$_POST['id_niveau'];
  $id_classe=$_POST['id_classe'];
  $annee=$_POST['annee'];
  $redoublement=$_POST['redoublement'];

  $req=mysql_query("SELECT * FROM `eleves_classes` WHERE id='$id'");
  $id_n=mysql_result($req,0,'id_niveau');
  $id_e=mysql_result($req,0,'id_eleve');
  $id_c=mysql_result($req,0,'id_classe');
  $req=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='$id_c' AND type='T'");
  $id_titulaire=mysql_result($req,0,'id_prof');

  if ($id_n!=$id_niveau[0] || $id_c!=$id_classe)
  {
    $req=mysql_query("SELECT * FROM `controles` WHERE id_classe='$id_c' AND id_niveau='$id_n'");
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      $req2=mysql_query("DELETE FROM `controles_resultats` WHERE id_eleve='$id_e' AND id_controle='".mysql_result($req,$i-1,'id')."'");
    }
  }
  
  $req=mysql_query("UPDATE `eleves_classes` SET id_classe='".$id_classe."', id_niveau='".$id_niveau[0]."', redoublement='$redoublement' WHERE id='$id'");

  echo $id_e.'|'.$id_c.'|'.$id_n.'|'.$id_titulaire.'|'.$annee;
?>