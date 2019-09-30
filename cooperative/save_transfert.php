<?php
  $id=$_POST['id'];
  $banque=$_POST['banque'];
  $banque2=$_POST['banque2'];
  $date=Date_Convertir($_POST['date'],$Format_Date_PHP,'Y-m-d');
  $montant=(-1)*str_replace(' ','',str_replace(',','.',$_POST['montant']));
  $piece=$_POST['piece'];
  $libelle=$_POST['libelle'];
  $releve=$_POST['releve'];
  $reference_bancaire=$_POST['reference_bancaire'];
  $id_classe=$_POST['id_classe'];
  $pointe=$_POST['pointe'];
  
  if ($id=="")
  {
    $id=Construit_Id("cooperative".$_SESSION['cooperative_scolaire'],50);
  
    $req=mysql_query("INSERT INTO `cooperative".$_SESSION['cooperative_scolaire']."` (id,date,mode,ligne_comptable,piece,id_classe,montant,pointe,libelle,releve,banque,tiers,reference_bancaire) VALUES
	  ('$id','$date','D','$banque2','$piece','$id_classe','$montant','$pointe','$libelle','$releve','$banque','','$reference_bancaire')");	
  }
  else
  {
    $req=mysql_query("UPDATE `cooperative".$_SESSION['cooperative_scolaire']."` SET date='$date',ligne_comptable='$banque2',piece='$piece',id_classe='$id_classe',montant='$montant',libelle='$libelle',releve='$releve',pointe='$pointe',banque='$banque',reference_bancaire='$reference_bancaire' WHERE id='$id'");
  }
  
  echo $id;
?>