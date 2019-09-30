<?php
  $req=mysql_query("SELECT * FROM `profs` WHERE identifiant='".$_POST['identifiant']."' AND (date_sortie='0000-00-00' OR date_sortie>'".date("Y-m-d")."')");
  $req2=mysql_query("SELECT * FROM `eleves` WHERE identifiant='".$_POST['identifiant']."' AND (date_sortie='0000-00-00' OR date_sortie>'".date("Y-m-d")."')");
  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="")
  {
    echo "1";
  }
  else
  {
    if (mysql_num_rows($req)!="")
    {
      if (mysql_result($req,0,'id')!=$_POST['id'])
      {
        echo "2";
      }
      else
      {
        echo "1";
      }
    }
    else
    {
      if (mysql_result($req2,0,'id')!=$_POST['id'])
      {
        echo "2";
      }
      else
      {
        echo "1";
      }
    }
  }
?>
