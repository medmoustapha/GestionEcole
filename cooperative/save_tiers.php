<?php
  $type=$_POST['type_tiers'];
  $nom=$_POST['nom_tiers'];
  
  $req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '".$type."-%' ORDER BY id DESC");
  $numero=mysql_num_rows($req)+1;
  if ($numero<=9)
  {
    $numero=$type."-00".$numero;
  }
  else
  {
	  if ($numero<=99)
	  {
		$numero=$type."-0".$numero;
	  }
	  else
	  {
	    $numero=$type."-".$numero;
	  }
  }
  
  $req=mysql_query("INSERT INTO `cooperative_tiers` (id,nom) VALUES ('$numero','$nom')");
  
  $msg='<select name="tiers" class="text ui-widget-content ui-corner-all" id="tiers">';
  $msg=$msg.'<option value="401">'.$Langue['LBL_SAISIE_FOURNISSEURS'].'</option>';
  $req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '401-%' ORDER BY nom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
	$msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
	if ($numero==mysql_result($req,$i-1,'id')) { $msg=$msg.' SELECTED'; }
	$msg=$msg.'>'.mysql_result($req,$i-1,'nom').'</option>';
  }
  $msg=$msg.'<option value="411">'.$Langue['LBL_SAISIE_CLIENTS'].'</option>';
  $req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '411-%' ORDER BY nom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
	$msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
	if ($numero==mysql_result($req,$i-1,'id')) { $msg=$msg.' SELECTED'; }
	$msg=$msg.'>'.mysql_result($req,$i-1,'nom').'</option>';
  }
  $msg=$msg.'<select>';
  
  echo $msg;
?>