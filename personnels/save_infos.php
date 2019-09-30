<?php
  $id=$_POST['id'];

  if ($id=="")
  {
    $id=Construit_Id("profs",5);
    $req = mysql_query("INSERT INTO `profs` (id) VALUES ('$id')");
  }

  foreach ($tableau_variable['personnels'] AS $cle)
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
	  if (!isset($_POST[$cle['nom']])) { $faire=false; }
      if ($faire==true)
      {
        $req = mysql_query("UPDATE `profs` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
      }
    }
  }
  
  echo $id;
?>