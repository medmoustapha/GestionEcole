<?php
  $id_classe=$_POST['id_classe'];
  $date=$_SESSION['date_en_cours'];
  $nom_liste=$_POST['nom_liste'];

  $valeur_defaut=substr($_POST['valeur_defaut'],0,5);
  $req=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='$id_classe' AND type='T'");
  $id_prof=mysql_result($req,0,'id_prof');
  Param_Utilisateur($id_prof,$_SESSION['annee_scolaire']);
  $msg='<select name="'.$nom_liste.'" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
  $jour=date("w",mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4)));
	$heure_fin='';
	$heure_debut='';
  if ($gestclasse_config_plus['matin'.$jour]=="1")
  {
    $msg .='<option value="" class="option_gras">'.$Langue['LBL_MATIN'].'</option>';
    $heure_debut=$gestclasse_config_plus['jour_matin_debut'];
    if ($gestclasse_config_plus['am'.$jour]=="0")
    {
      $option=false;
      $heure_fin=$gestclasse_config_plus['jour_matin_fin'];
    }
    else
    {
      $option=true;
      $heure_fin=$gestclasse_config_plus['jour_am_fin'];
    }
  }
  else
  {
    $option=false;
		if ($gestclasse_config_plus['am'.$jour]=="1")
		{
			$msg .='<option value="" class="option_gras">'.$Langue['LBL_AM'].'</option>';
			$heure_debut=$gestclasse_config_plus['jour_am_debut'];
			$heure_fin=$gestclasse_config_plus['jour_am_fin'];
		}
  }

	if ($heure_debut!="" && $heure_fin!="")
	{
		for ($i=8;$i<=18;$i++)
		{
			for ($j=0;$j<=55;$j=$j+5)
			{
				$heure=date("H:i",mktime($i,$j,0,date("m"),date("d"),date("Y")));
				if ($heure>=$heure_debut && $heure<=$heure_fin)
				{
					if ($heure<=$gestclasse_config_plus['jour_matin_fin'] || $heure>=$gestclasse_config_plus['jour_am_debut'])
					{
						if ($heure==$gestclasse_config_plus['jour_am_debut'] && $option==true) { $msg .='<option value="" class="option_gras">'.$Langue['LBL_AM'].'</option>'; }
						$msg .='<option value="'.$heure.'"';
						if ($heure==$valeur_defaut) { $msg .=' SELECTED'; }
						$msg .='>'.$heure.'</option>';
					}
				}
			}
		}
	}
  $msg .='</select>';

  echo $msg;
?>