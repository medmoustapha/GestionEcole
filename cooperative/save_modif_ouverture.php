<?php
  $id=$_POST['id'];
  $montant=str_replace(" ","",str_replace(",",".",$_POST['montant']));
  $date=Date_Convertir($_POST['date'],$Format_Date_PHP,'Y-m-d');
  $libelle=$_POST['libelle'];
  $mode=$_POST['mode'];
  $id_classe=$_POST['id_classe'];
  $piece=$_POST['piece'];
  $pointe=$_POST['pointe'];
  $releve=$_POST['releve'];
  $banque=$_POST['banque'];
  $reference_bancaire=$_POST['reference_bancaire'];
  if ($_POST['type']=="D") { $montant=(-1)*$montant; }

  $req=mysql_query("UPDATE `cooperative".$_SESSION['cooperative_scolaire']."` SET montant='$montant',date='$date',libelle='$libelle',mode='$mode',id_classe='$id_classe',piece='$piece',pointe='$pointe',releve='$releve',banque='$banque',reference_bancaire='$reference_bancaire' WHERE id='$id'");
  echo $id;
?>