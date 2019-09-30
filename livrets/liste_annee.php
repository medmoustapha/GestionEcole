<?php
  $id_eleve=$_POST['id_eleve'];
  $msg='&nbsp;&nbsp;&nbsp;&nbsp;'.$Langue['LBL_ANNEE_SCOLAIRE_COURS'].' : <select name="id_annee" id="id_annee" class="text ui-widget-content ui-corner-all">';
  $req=mysql_query("SELECT classes.*, eleves_classes.* FROM `classes`,`eleves_classes` WHERE eleves_classes.id_eleve='$id_eleve' AND eleves_classes.id_classe=classes.id ORDER BY classes.annee DESC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	{
		$msg .='<option value="'.mysql_result($req,$i-1,'classes.annee').'"';
		if ($_SESSION['annee_scolaire']==mysql_result($req,$i-1,'classes.annee')) { $msg .=' SELECTED'; }
		$msg .='>'.mysql_result($req,$i-1,'classes.annee').'</option>';
	}
	else
	{
		$a=mysql_result($req,$i-1,'classes.annee')+1;
		$msg .='<option value="'.mysql_result($req,$i-1,'classes.annee').'"';
		if ($_SESSION['annee_scolaire']==mysql_result($req,$i-1,'classes.annee')) { $msg .=' SELECTED'; }
		$msg .='>'.mysql_result($req,$i-1,'classes.annee').'-'.$a.'</option>';
	}
  }
  $msg .='</select>';
  echo $msg;
?>