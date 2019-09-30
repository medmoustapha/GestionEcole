<?php
  $id=$_POST['id'];
  $req=mysql_query("SELECT * FROM `email` WHERE id='$id'");
  if (mysql_result($req,0,'id_expediteur')==$_SESSION['id_util'] && mysql_result($req,0,'type_expediteur')==$_SESSION['type_util'])
  {
		$destinataire_etat=explode(';',mysql_result($req,0,'id_dossier_dest'));
		$trouve=false;
		for ($i=0;$i<count($destinataire_etat);$i++)
		{
			if ($destinataire_etat[$i]!="S") { $trouve=true; $i=count($destinataire_etat); }
		}
    if ($trouve==false)
    {
		  $id_pj=mysql_result($req,0,'pj');
			$liste_pj=explode(', ',$id_pj);
			for ($j=0;$j<count($liste_pj);$j++)
			{
			  unlink("cache/email/".$liste_pj[$j]);
			}
			$req=mysql_query("DELETE FROM `email` WHERE id='$id'");
    }
    else
    {
			$req=mysql_query("UPDATE `email` SET id_dossier_exp='S' WHERE id='$id'");
    }
  }
  else
  {
		$destinataire_etat=explode(';',mysql_result($req,0,'id_dossier_dest'));
		$destinataire=explode(';',mysql_result($req,0,'id_destinataire'));
		$key=array_search($_SESSION['type_util'].$_SESSION['id_util'],$destinataire);
		$destinataire_etat[$key]="S";
		$trouve=false;
		for ($i=0;$i<count($destinataire_etat);$i++)
		{
			if ($destinataire_etat[$i]!="S") { $trouve=true; $i=count($destinataire_etat); }
		}
    if ($trouve==false && mysql_result($req,0,'id_dossier_exp')=="S")
    {
		  $id_pj=mysql_result($req,0,'pj');
			$liste_pj=explode(', ',$id_pj);
			for ($j=0;$j<count($liste_pj);$j++)
			{
			  unlink("cache/email/".$liste_pj[$j]);
			}
      $req=mysql_query("DELETE FROM `email` WHERE id='$id'");
    }
    else
		{
			$id_dossier_dest=implode(";",$destinataire_etat);
      $req=mysql_query("UPDATE `email` SET id_dossier_dest='$id_dossier_dest' WHERE id='$id'");
		}
  }
?>