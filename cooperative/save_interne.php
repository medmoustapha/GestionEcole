<?php
  $id=$_POST['id'];
  $montant=str_replace(" ","",str_replace(",",".",$_POST['montant']));
  $date=Date_Convertir($_POST['date'],$Format_Date_PHP,'Y-m-d');
  $libelle=$_POST['libelle'];
  $id_classe=$_POST['id_classe'];
  $req=mysql_query("UPDATE `cooperative".$_SESSION['cooperative_scolaire']."` SET montant='$montant',date='$date',libelle='$libelle',id_classe='$id_classe' WHERE id='$id'");
  echo $id;
?>