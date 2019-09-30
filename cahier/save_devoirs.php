<?php
  $id_n=$_POST['id_niveau2'];
  $_POST['date_donnee']=Date_Convertir($_POST['date_donnee'],$Format_Date_PHP,"Y-m-d");
  $_POST['date_faire']=Date_Convertir($_POST['date_faire'],$Format_Date_PHP,"Y-m-d");

for($i=0;$i<count($id_n);$i++)
{
  $_POST['id_niveau']=$id_n[$i];
  $id=$_POST['id'];

  if ($id=="")
  {
    $id=Construit_Id("devoirs",20);
    $req = mysql_query("INSERT INTO `devoirs` (id) VALUES ('$id')");
  }

  foreach ($tableau_variable['devoirs'] AS $cle)
  {
    if ($cle['nom']!="id")
    {
      if ($cle['type']=="checkbox" && !isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=0; }
      if ($cle['type']=="varchar" && isset($_POST[$cle['nom']]))
      {
        if ($cle['majuscule']=="1")
        {
          $_POST[$cle['nom']]=strtoupper($_POST[$cle['nom']]);
        }
      }
      $req = mysql_query("UPDATE `devoirs` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
    }
  }
}

  if ($_POST['afaire']=="nouveau")
  {
    echo $_POST['id_classe'].'-'.$_POST['id_niveau'];
  }
  else
  {
    echo $id;
  }
?>