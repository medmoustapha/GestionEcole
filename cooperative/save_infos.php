<?php
  $id=$_POST['id'];
  
  $_POST['montant']=str_replace(' ','',str_replace(",",".",$_POST['montant']));
  if ($_POST['type']=="D") { $_POST['montant']=(-1)*$_POST['montant']; }

  if ($id=="")
  {
    $id=Construit_Id("cooperative".$_SESSION['cooperative_scolaire'],50);
    $req = mysql_query("INSERT INTO `cooperative".$_SESSION['cooperative_scolaire']."` (id) VALUES ('$id')");
  }

  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    if ($cle['nom']!="id")
    {
      $faire=true;
      if ($cle['type']=="checkbox" && !isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=0; }
      if ($cle['type']=="date" && isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=Date_Convertir($_POST[$cle['nom']],$Format_Date_PHP,"Y-m-d"); }
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
        $req = mysql_query("UPDATE `cooperative".$_SESSION['cooperative_scolaire']."` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
      }
    }
  }
  
  echo $id;
?>