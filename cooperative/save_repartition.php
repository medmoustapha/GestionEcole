<?php
  $motif=$_POST['motif'];
  $date=date("Y-m-d");
  
  $req=mysql_query("SELECT * FROM `classes` WHERE annee='".$_SESSION['cooperative_scolaire']."' ORDER BY nom_classe ASC");
  
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
	if (isset($_POST[mysql_result($req,$i-1,'id')]))
	{
	  if ($_POST[mysql_result($req,$i-1,'id')]!=0)
	  {
	    $id=Construit_Id("cooperative".$_SESSION['cooperative_scolaire'],50);
	    mysql_query("INSERT INTO `cooperative".$_SESSION['cooperative_scolaire']."` (id,date,id_classe,libelle,ligne_comptable,montant,pointe) VALUES ('$id','$date','".mysql_result($req,$i-1,'id')."','".$motif."','I','".str_replace(',','.',str_replace(' ','',$_POST[mysql_result($req,$i-1,'id')]))."','1')");
	  }
	}
  }
?>