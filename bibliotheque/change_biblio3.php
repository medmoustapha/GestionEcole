<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=date("Y");
  }
  else
  {
    if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=date("Y")-1; } else { $annee=date("Y"); }
  }
  Param_Utilisateur($_SESSION['id_util'],$annee);
// Mise à jour de la liste des livres quand on saisit un emprunt
  $valeur_defaut=$_POST['valeur_defaut'];
  $id_prof=$_POST['id_prof'];
  
  $msg_livre='<select class="text ui-widget-content ui-corner-all" name="id_livre_emprunt" id="id_livre_emprunt">';
  if ($id_prof=="")
  {
    $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat<>'O' ORDER BY reference ASC");
  }
  else
  {
    $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='$id_prof' AND etat<>'O' ORDER BY reference ASC");
  }
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $req2=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00'");
    if (mysql_num_rows($req2)=="") 
	{ 
      $msg_livre .='<option value="'.mysql_result($req,$i-1,'id').'"';
	  if (mysql_result($req,$i-1,'id')==$valeur_defaut) { $msg_livre .=' SELECTED'; }
      $msg_livre .='>'.mysql_result($req,$i-1,'reference').' - '.mysql_result($req,$i-1,'titre').'</option>';
	}	
  }
  $msg_livre .='</select>';

  echo $msg_livre;
?>