<?php
  $id_message=$_GET['id_message'];
	$_GET['orientation']="P";
	$_GET['couleur']="C";
	$_GET['numerotation']="";
	
  $req = mysql_query("SELECT * FROM `email` WHERE id = '$id_message'");
  $destinataire=explode(";",mysql_result($req,0,'id_destinataire'));

	$tpl = new template("email");
	$tpl->set_file("gform","detailview_impression.html");
	$tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

	$decaler=explode(':',$gestclasse_config_plus['decalage_horaire']);
	if (substr($decaler[0],0,1)=="-") { $decaler[1]=-$decaler[1]; }
  $tpl->set_var("DATE",$Langue['LBL_LE']." ".Date_Convertir(mysql_result($req,0,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req,0,'date'),11,2)-$decaler[0],substr(mysql_result($req,0,'date'),14,2)-$decaler[1],substr(mysql_result($req,0,'date'),17,2),01,01,2010)));
	$type_e=mysql_result($req,0,'type_expediteur');
	$id_e=mysql_result($req,0,'id_expediteur');
	if ($type_e=="E") { $req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id_e'"); } else { $req2=mysql_query("SELECT * FROM `profs` WHERE id='$id_e'"); }
  $tpl->set_var("EXPEDITEUR",mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom'));

	$destinataire=explode(";",mysql_result($req,0,'id_destinataire'));
	$msg="";
	for ($i=0;$i<count($destinataire);$i++)
	{
  	$type_e=substr($destinataire[$i],0,1);
  	$id_e=substr($destinataire[$i],1,strlen($destinataire[$i]));
	  if ($type_e=="E") { $req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id_e'"); } else { $req2=mysql_query("SELECT * FROM `profs` WHERE id='$id_e'"); }
	  if ($type_e==$_SESSION['type_util'] && $id_e==$_SESSION['id_util'])
	  {
	    $msg=$msg.'<u>'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</u>, ';
	  }
	  else
	  {
	    $msg=$msg.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').", ";
	  }
	}
	$tpl->set_var("DESTINATAIRE",substr($msg,0,strlen($msg)-2));
	$tpl->set_var("TITRE",mysql_result($req,0,'titre'));
	$tpl->set_var("MESSAGERIE",mysql_result($req,0,'messagerie'));
	$tpl->set_var("ID",mysql_result($req,0,'id'));

	$contenu_html=$tpl->parse('liste_bloc','formulaire',true);
  include "commun/impression.php";
?>