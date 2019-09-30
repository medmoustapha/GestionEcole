<?php
  if (isset($_POST['cooperative_mandataires_liste']))
  {
    $cooperative_mandataires_liste=$_POST['cooperative_mandataires_liste'];
	$cooperative_mandataires="";
	for ($i=0;$i<count($cooperative_mandataires_liste);$i++)
	{
	  $cooperative_mandataires=$cooperative_mandataires.$cooperative_mandataires_liste[$i].'|';
	}
	$_POST['cooperative_mandataires']=substr($cooperative_mandataires,0,strlen($cooperative_mandataires)-1);
  }
  
  $req=mysql_query("SELECT * FROM `etablissement`");
  for($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (isset($_POST[mysql_result($req,$i-1,'parametre')]))
    {
      if ($_SESSION['type_util']=="D")
      {
		$req2=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='".mysql_result($req,$i-1,'parametre')."'");
		if (mysql_num_rows($req2)=="")
		{
	      $req3=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('".mysql_result($req,$i-1,'parametre')."','".str_replace("\r\n",'<br>',$_POST[mysql_result($req,$i-1,'parametre')])."')");
		}
		else
		{
	      $req3=mysql_query("UPDATE `etablissement".$_SESSION['annee_scolaire']."` SET valeur='".str_replace("\r\n",'<br>',$_POST[mysql_result($req,$i-1,'parametre')])."' WHERE parametre='".mysql_result($req,$i-1,'parametre')."'");
		}
      }
      else
      {
        $param=mysql_result($req,$i-1,'parametre');
        $req2=mysql_query("SELECT * FROM `param_profs_plus".$_SESSION['annee_scolaire']."` WHERE id_prof='".$_SESSION['id_util']."' AND parametre='".$param."'");
        if (mysql_num_rows($req2)!="")
        {
          $req3=mysql_query("UPDATE `param_profs_plus".$_SESSION['annee_scolaire']."` SET valeur='".$_POST[$param]."' WHERE id_prof='".$_SESSION['id_util']."' AND parametre='".$param."'");
        }
        else
        {
          $req3=mysql_query("INSERT INTO `param_profs_plus".$_SESSION['annee_scolaire']."` (id_prof,parametre,valeur) VALUES ('".$_SESSION['id_util']."','".$param."','".$_POST[$param]."')");
        }
      }
    }
  }
  
  $req=mysql_query("SELECT * FROM `config`");
  for($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (isset($_POST[mysql_result($req,$i-1,'parametre')]))
    {
	  $req3=mysql_query("UPDATE `config` SET valeur='".$_POST[mysql_result($req,$i-1,'parametre')]."' WHERE parametre='".mysql_result($req,$i-1,'parametre')."'");
    }
  }	
  
  if ($_SESSION['type_util']=="D" && $_POST['id']=="messagerie")
  {
    $req3=mysql_query("DELETE FROM `config` WHERE parametre='message_connexion'");
    $req3=mysql_query("INSERT INTO `config` (parametre,valeur) VALUES ('message_connexion','".$_POST['message_c']."')");
  }
  
  if ($_SESSION['type_util']=="D")
  {
	  if ($_POST['id']=="horaires")
	  {
		if ($_POST['zone']=="P")
		{
		  $req=mysql_query("DELETE FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='etendue_annee_scolaire'");
		  $req=mysql_query("DELETE FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_annee_scolaire'");
		  $req=mysql_query("DELETE FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_annee_scolaire'");
		  if ($_POST['etendue']=="1")
		  {
			$req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('etendue_annee_scolaire','1')");
		  }
		  else
		  {
			$req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('etendue_annee_scolaire','2')");
			$req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_annee_scolaire','".Date_Convertir($_POST['debut_as'],$Format_Date_PHP,'Y-m-d')."')");
			$req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_annee_scolaire','".Date_Convertir($_POST['fin_as'],$Format_Date_PHP,'Y-m-d')."')");
		  }
		}
	  }
  }

// foreach($_POST as $key => $val) echo '$_POST["'.$key.'"]='.$val.'<br />';

  echo $_POST['id'];
?>