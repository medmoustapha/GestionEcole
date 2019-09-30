<?php
  $req=mysql_query("UPDATE `cooperative_bilan` SET nb_200='".$_POST['nb_200']."',nb_100='".$_POST['nb_100']."',nb_50='".$_POST['nb_50']."',
												  nb_20='".$_POST['nb_20']."',nb_10='".$_POST['nb_10']."',nb_5='".$_POST['nb_5']."',
												  nb_2='".$_POST['nb_2']."',nb_1='".$_POST['nb_1']."',nb_05='".$_POST['nb_05']."',
												  nb_02='".$_POST['nb_02']."',nb_01='".$_POST['nb_01']."',nb_005='".$_POST['nb_005']."',
												  nb_002='".$_POST['nb_002']."',nb_001='".$_POST['nb_001']."' WHERE annee='".$_SESSION['cooperative_scolaire']."'");
?>