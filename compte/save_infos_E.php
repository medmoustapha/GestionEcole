<?php
  $id=$_POST['id'];

  foreach ($tableau_variable['eleves'] AS $cle)
  {
    if ($cle['nom']!="id" && $cle['nom']!="passe2")
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
        $req = mysql_query("UPDATE `eleves` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
      }
    }
  }
  
  $req=mysql_query("DELETE FROM `contacts_eleves` WHERE id_eleve='$id'");
  for ($i=1;$i<=5;$i++)
  {
    if ($_POST['contact_nom'.$i]!="")
	{
	  $id2=Construit_Id("contacts_eleves",20);
	  $req=mysql_query("INSERT INTO `contacts_eleves` (id,id_eleve,nom,adresse,lien,tel,tel2,portable) VALUES ('$id2','$id','".$_POST['contact_nom'.$i]."','".$_POST['contact_adresse'.$i]."','".$_POST['contact_lien'.$i]."','".$_POST['contact_tel'.$i]."','".$_POST['contact_tel2'.$i]."','".$_POST['contact_portable'.$i]."')");
	}
  }
?>