<?php
  if ($_POST['id_classe']!="")
  {
    $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_POST['id_classe'] . "' AND type='E'");
    $liste_profs="";
    for ($i=1;$i<=mysql_num_rows($req2);$i++)
    {
      $liste_profs=$liste_profs.mysql_result($req2,$i-1,'id_prof')."|";
    }
    $liste_decharge=Liste_Profs("id_decharge",'form','',substr($liste_profs,0,strlen($liste_profs)-1),'R',true,false,$_POST['id_titulaire'],'5');
  }
  else
  {
    $liste_decharge=Liste_Profs("id_decharge",'form','','','R',true,false,$_POST['id_titulaire'],'5');
  }
  
  echo $liste_decharge;
?>