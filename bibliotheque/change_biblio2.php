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
  $id_prof=$_POST['id_prof'];
  
  $msg='<select class="text ui-widget-content ui-corner-all" id="id_cat" name="id_cat">';
  if ($id_prof=="")
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE id_prof='' AND nom_liste='categ_biblio_ecole' ORDER BY ordre ASC");
	for ($i=1;$i<=mysql_num_rows($req);$i++)
	{
	  $msg .='<option value="'.mysql_result($req,$i-1,'id').'">'.mysql_result($req,$i-1,'intitule').'</option>';
	}
  }	
  else
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE id_prof='$id_prof' AND nom_liste='categ_biblio_classe' ORDER BY ordre ASC");
	if (mysql_num_rows($req)!="")
	{
   	  for ($i=1;$i<=mysql_num_rows($req);$i++)
	  {
	    $msg .='<option value="'.mysql_result($req,$i-1,'id').'">'.mysql_result($req,$i-1,'intitule').'</option>';
	  }
	}
	else
	{
	  foreach ($liste_choix['categ_biblio_classe'] AS $cle => $value)
	  {
	    $msg .='<option value="$cle">'.$value.'</option>';
	  }
	}
  }	
  $msg .='</select>';
  
  echo $msg;
?>