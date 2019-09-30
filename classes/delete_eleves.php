<?php
  $id=$_POST['id'];
  
  $req=mysql_query("SELECT * FROM `eleves_classes` WHERE id='$id'");
  $id_eleve=mysql_result($req,0,'id_eleve');
  $id_niveau=mysql_result($req,0,'id_niveau');
  $id_classe=mysql_result($req,0,'id_classe');
  $req=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='$id_classe' AND type='T'");
  $id_titulaire=mysql_result($req,0,'id_prof');

  $req=mysql_query("DELETE FROM `eleves_classes` WHERE id='$id'");
  $req=mysql_query("SELECT * FROM `controles` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau'");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $req2=mysql_query("DELETE FROM `controles_resultats` WHERE id_eleve='$id_eleve' AND id_controle='".mysql_result($req,$i-1,'id')."'");
  }
  echo $id_classe."|".$id_niveau."|".$id_titulaire;
?>
