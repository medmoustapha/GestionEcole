<?php
  $id=$_POST['id'];
  
  $id_util2=$_POST['id_util2'];
  $id_util="";
  for ($i=0;$i<count($id_util2);$i++)
  {
    $id_util=$id_util.$id_util2[$i].',';
  }	
  $id_util=substr($id_util,0,strlen($id_util)-1);
  $_POST['id_util']=$id_util;
  
  if ($id=="")
  {
    $id=Construit_Id("reunions",15);
    $req = mysql_query("INSERT INTO `reunions` (id) VALUES ('$id')");
  }

  foreach ($tableau_variable['agenda'] AS $cle)
  {
    if ($cle['nom']!="id")
    {
      $faire=true;
      if ($cle['type']=="checkbox" && !isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=0; }
      if ($cle['type']=="date" && isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=Date_Convertir($_POST[$cle['nom']],$Format_Date_PHP,"Y-m-d"); }
      if ($cle['type']=="editeur" && isset($_POST[$cle['nom'].'_e']))
      {
	    $_POST[$cle['nom']]=$_POST[$cle['nom'].'_e'];
	  }
      if ($cle['type']=="varchar" && isset($_POST[$cle['nom']]))
      {
        if ($cle['majuscule']=="1")
        {
          $_POST[$cle['nom']]=strtoupper($_POST[$cle['nom']]);
        }
      }
      if ($cle['type']=="password" && isset($_POST[$cle['nom']]))
      {
        if (isset($_POST[$cle['nom']]) && $_POST[$cle['nom']]!="")
        {
          $_POST[$cle['nom']]=md5($_POST[$cle['nom']]);
        }
        else
        {
          $faire=false;
        }
      }
      if ($faire==true)
      {
        $req = mysql_query("UPDATE `reunions` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
      }
    }
  }
  
  echo $id;
?>