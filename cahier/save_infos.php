<?php
  $id_n=$_POST['id_niveau2'];
  
for($i=0;$i<count($id_n);$i++)
{
  $_POST['id_niveau']=$id_n[$i];
  $id=$_POST['id'];

  if ($id=="")
  {
    $id=Construit_Id("cahierjournal",20);
    $req = mysql_query("INSERT INTO `cahierjournal` (id) VALUES ('$id')");
  }

  foreach ($tableau_variable['cahier'] AS $cle)
  {
    if ($cle['nom']!="id")
    {
      if ($cle['type']=="checkbox" && !isset($_POST[$cle['nom']])) { $_POST[$cle['nom']]=0; }
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
      $req = mysql_query("UPDATE `cahierjournal` SET ".$cle['nom']."='".$_POST[$cle['nom']]."' WHERE id='$id'");
    }
  }
  
  if ($_POST['date_faire']!="")
  {
    $req = mysql_query("SELECT * FROM `devoirs` WHERE id_seance='$id'");
    if (mysql_num_rows($req)!="")
    {
      $ido=mysql_result($req,0,'id');
      $req = mysql_query("UPDATE `devoirs` SET date_faire='".Date_Convertir($_POST['date_faire'],$Format_Date_PHP,"Y-m-d")."', contenu='".$_POST['devoirs']."', id_matiere='".$_POST['id_matiere']."' WHERE id='$ido'");
    }
    else
    {
      $ido=Construit_Id("devoirs",20);
      $req = mysql_query("INSERT INTO `devoirs` (id,id_prof,id_classe,id_niveau,id_matiere,date_donnee,date_faire,contenu,id_seance) VALUES ('$ido','".$_POST['id_prof']."','".$_POST['id_classe']."','".$_POST['id_niveau']."','".$_POST['id_matiere']."','".$_POST['date']."','".Date_Convertir($_POST['date_faire'],$Format_Date_PHP,"Y-m-d")."','".$_POST['devoirs']."','$id')");
    }
  }
}

  if ($_POST['afaire']=="nouveau")
  {
    echo $_POST['id_classe'].'-'.$_POST['id_niveau'].'-'.$_POST['heure_fin'];
  }
  else
  {
    echo $_POST['contenu_e'];
  }
?>