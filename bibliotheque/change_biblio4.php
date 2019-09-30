<?php
// Mise à jour de la liste des emprunteurs quand on saisit un emprunt
  if ($_POST['valeur_defaut']!="") { $valeur_defaut=explode("|",$_POST['valeur_defaut']); } else { $valeur_defaut[0]="";$valeur_defaut[1]=""; }
  $id_prof=$_POST['id_prof'];
  $annee=$_POST['annee'];
  Param_Utilisateur($_SESSION['id_util'],$annee);
  
  $msg_emprunteur='<select class="text ui-widget-content ui-corner-all" name="id_util_emprunt" id="id_util_emprunt">';
  $msg_emprunteur .='<option class="option_gras" value="">'.$Langue['LBL_PERSONNELS'].'</option>';
  $req=mysql_query("SELECT * FROM `profs` WHERE date_sortie='0000-00-00' OR date_sortie>'".date("Y-m-d")."' ORDER BY nom ASC, prenom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
	$msg_emprunteur .='<option value="P|'.mysql_result($req,$i-1,'id').'"';
	if (mysql_result($req,$i-1,'id')==$valeur_defaut[1] AND $valeur_defaut[0]=="P") { $msg_emprunteur .=' SELECTED'; }
	$msg_emprunteur .='>&nbsp;&nbsp;&nbsp;&nbsp;'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom').'</option>';
  }
  $req=mysql_query("SELECT classes.*, eleves_classes.*, eleves.* FROM `classes`,`eleves_classes`,`eleves` WHERE classes.id=eleves_classes.id_classe AND eleves.id=eleves_classes.id_eleve AND classes.annee='$annee' ORDER BY classes.nom_classe ASC, eleves.nom ASC, eleves.prenom ASC");
  $classe="";
  if ($id_prof=="") { $max=$gestclasse_config_plus['biblio_nombre_livres']; } else { $max=$gestclasse_config_plus['biblio_nombre_livres_classe']; }
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
	$req2=mysql_query("SELECT bibliotheque.*,bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque_emprunt.id_livre=bibliotheque.id AND bibliotheque.id_prof='$id_prof' AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.id_util='".mysql_result($req,$i-1,'eleves.id')."' AND bibliotheque_emprunt.type_util='E'");
	if (mysql_num_rows($req2)<$max)
	{
		if ($classe!=mysql_result($req,$i-1,'classes.id'))
		{
		  $msg_emprunteur .='<option class="option_gras" value="">'.mysql_result($req,$i-1,'classes.nom_classe').'</option>';
		  $classe=mysql_result($req,$i-1,'classes.id');
		}
		$msg_emprunteur .='<option value="E|'.mysql_result($req,$i-1,'eleves.id').'"';
		if (mysql_result($req,$i-1,'eleves.id')==$valeur_defaut[1] AND $valeur_defaut[0]=="E") { $msg_emprunteur .=' SELECTED'; }
		$msg_emprunteur .='>&nbsp;&nbsp;&nbsp;&nbsp;'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</option>';
	}
  }
  $msg_emprunteur .='</select>';

  echo $msg_emprunteur;
?>