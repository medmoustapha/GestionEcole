<?php
  $banque=str_replace(" ","",str_replace(",",".",$_POST['banque']));
  $banque_p=str_replace(" ","",str_replace(",",".",$_POST['banque_p']));
  $caisse=str_replace(" ","",str_replace(",",".",$_POST['caisse']));
  $report=str_replace(" ","",str_replace(",",".",$_POST['report']));
  
  $date=date("Y-m-d");
  $annee_a=$_SESSION['cooperative_scolaire']-1;
  $id=Construit_Id("cooperative".$_SESSION['cooperative_scolaire'],50);
  if ($banque!=0.00)
  {
    $req=mysql_query("INSERT INTO `cooperative".$_SESSION['cooperative_scolaire']."` (id,date,mode,ligne_comptable,piece,id_classe,montant,pointe,libelle,banque,tiers,releve,reference_bancaire) VALUES
					('$id','$date','V','110','".$Langue['SQL_BILAN_COMPTABLE']."','','$banque','0','".$Langue['SQL_SOLDE_BANQUE']."','512','','','')");
  }
  $id=Construit_Id("cooperative".$_SESSION['cooperative_scolaire'],50);
  if ($banque_p!=0.00)
  {
    $req=mysql_query("INSERT INTO `cooperative".$_SESSION['cooperative_scolaire']."` (id,date,mode,ligne_comptable,piece,id_classe,montant,pointe,libelle,banque,tiers,releve,reference_bancaire) VALUES
					('$id','$date','V','110','".$Langue['SQL_BILAN_COMPTABLE']."','','$banque_p','0','".$Langue['SQL_SOLDE_BANQUE_POSTALE']."','514','','','')");
  }
  $id=Construit_Id("cooperative".$_SESSION['cooperative_scolaire'],50);
  if ($caisse!=0.00)
  {
    $req=mysql_query("INSERT INTO `cooperative".$_SESSION['cooperative_scolaire']."` (id,date,mode,ligne_comptable,piece,id_classe,montant,pointe,libelle,banque,tiers,releve,reference_bancaire) VALUES
					('$id','$date','V','110','".$Langue['SQL_BILAN_COMPTABLE']."','','$caisse','0','".$Langue['SQL_SOLDE_CAISSE']."','530','','','')");
  }

  $req=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  if (mysql_num_rows($req)=="")
  {
    $req=mysql_query("INSERT INTO `cooperative_bilan` (annee) VALUES ('".$_SESSION['cooperative_scolaire']."')");
  }
?>