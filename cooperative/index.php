<?php
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_presente'");
  if (mysql_result($req_coop,0,'valeur')=="0")
  {
    include "cooperative/pas_coop.php";
  }
  else
  {
	  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_mandataires'");
	  $gestclasse_config_plus['cooperative_mandataires']=mysql_result($req_coop,0,'valeur');
	  $mandataires=explode('|',$gestclasse_config_plus['cooperative_mandataires']);
	  $mandataire='U';
	  if (in_array($_SESSION['id_util'],$mandataires)) { $mandataire='M'; }
	  
	  $req_exercice=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."`");
	  
	  if (mysql_num_rows($req_exercice)=="")
	  {
		include "cooperative/ouverture_exercice.php";
	  }
	  else
	  {
		if (!isset($_SESSION['cooperative_visuel'])) { $_SESSION['cooperative_visuel']='journal_general'; }
		include "cooperative/".$_SESSION['cooperative_visuel'].".php";
	  }
  }
?>
