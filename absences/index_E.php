<?php
function Affiche_Calendrier_Eleve($mois,$annee)
{
global $liste_choix, $gestclasse_config_plus, $Format_Date_PHP;

  $hauteur=50;
  echo '<div class="ui-widget ui-widget-content ui-corner-all">';
  echo '<div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all" style="padding:7px;text-align:center">';
  echo $liste_choix['mois'][$mois].' '.$annee;
  echo '</div>';
  echo '<table class="ui-datepicker-calendar" width=100%><thead><tr>';
  for ($i=1;$i<=7;$i++)
  {
    echo '<th style="padding:5px;width:14%"><span title="'.$liste_choix['jour_long'][$i].'">'.$liste_choix['jour_court'][$i].'</span></th>';
  }
  echo '</tr></thead><tbody>';
  $premier_jour_mois=date("N",mktime(0,0,0,$mois,1,$annee));
  for ($i=1;$i<$premier_jour_mois;$i++)
  {
    if ($i==1) { echo '<tr>'; }
    echo '<td>&nbsp;</td>';
  }
  $nb_jour=date("t",mktime(0,0,0,$mois,1,$annee));
  for ($i=1;$i<=$nb_jour;$i++)
  {
    $jour=date("w",mktime(0,0,0,$mois,$i,$annee));
		$class="";
		$plus="";
		$plus_apres="";
		$style="";
		$msg="";
		if (date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))==date("Y-m-d")) { $class=" ui-state-highlight"; }
		switch ($jour)
		{
			case "0":
				$plus_apres='</tr>';
				$class="ui-state-default".$class;
				$msg='<p class="textdroite">'.$i.'</p>';
			break;
			case "1":
				$plus="<tr>";
				if ($gestclasse_config_plus['matin'.$jour]=="0" && $gestclasse_config_plus['am'.$jour]=="0")
				{
					$class="ui-state-default".$class;
					$msg='<p class="textdroite">'.$i.'</p>';
				}
				else
				{
					$req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND date_fin>='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND zone='".$gestclasse_config_plus['zone']."'");
					$req2=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
					if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="") 
					{ 
						$class="ui-widget-content".$class; 
						$style='font-weight:bold;text-decoration:none;';
						if ($gestclasse_config_plus['matin'.$jour]=="0") { $msg='<div class="ui-state-default floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; } 
						else 
						{ 
						  $date_en_cours=date('Y-m-d',mktime(0,0,0,$mois,$i,$annee));
							$id_eleve=$_SESSION['id_util'];
							$req_abs=mysql_query("SELECT * FROM `absences` WHERE date='$date_en_cours' AND id_eleve='".$id_eleve."' AND matin='1'");
							if (mysql_num_rows($req_abs)=="")
							{
						    $msg='<div class="ui-state-content floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; 
							}
							else
							{
                $req10=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<'$date_en_cours' AND date_fin>'$date_en_cours'"); // Valide une absence si la date est comprise entre les deux dates d'un justificatif
                $req20=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin quand le justificatif est sur une journée
                $req40=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin ou la journée quand le justificatif commence le jour de l'absence
                $req60=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours'"); // Valide une absence le matin quand le justificatif se termine le jour de l'absence
                if (mysql_num_rows($req10)!="" || mysql_num_rows($req20)!="" || mysql_num_rows($req40)!="" || mysql_num_rows($req60)!="")
                {
									$msg='<div class="absence_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; 
								}
								else
                {
									$msg='<div class="absence_non_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; 
								}
							}
						}
						if ($gestclasse_config_plus['am'.$jour]=="0") { $msg .='<div class="ui-state-default floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; } 
						else 
						{ 
						  $date_en_cours=date('Y-m-d',mktime(0,0,0,$mois,$i,$annee));
							$id_eleve=$_SESSION['id_util'];
							$req_abs=mysql_query("SELECT * FROM `absences` WHERE date='$date_en_cours' AND id_eleve='".$id_eleve."' AND apres_midi='1'");
							if (mysql_num_rows($req_abs)=="")
							{
  						  $msg .='<div class="ui-state-content floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; 
							}
							else
							{
                $req10=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<'$date_en_cours' AND date_fin>'$date_en_cours'"); // Valide une absence si la date est comprise entre les deux dates d'un justificatif
                $req30=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi quand le justificatif est sur une journée
                $req50=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours'"); // Valide une absence l'après-midi quand le justificatif commence le jour de l'absence
                $req70=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi ou la journée quand le justificatif se termine le jour de l'absence
                if (mysql_num_rows($req10)!="" || mysql_num_rows($req30)!="" || mysql_num_rows($req50)!="" || mysql_num_rows($req70)!="")
                {
    						  $msg .='<div class="absence_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; 
								}
								else
                {
    						  $msg .='<div class="absence_non_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; 
								}
							}
						}
					} 
					else 
					{ 
						$class="ui-state-default".$class; 
						$msg='<p class="textdroite">'.$i.'</p>';
					}		
				}
				break;
			default:
				if ($gestclasse_config_plus['matin'.$jour]=="0" && $gestclasse_config_plus['am'.$jour]=="0")
				{
					$class="ui-state-default".$class;
					$msg='<p class="textdroite">'.$i.'</p>';
				}
				else
				{
					$req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND date_fin>='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND zone='".$gestclasse_config_plus['zone']."'");
					$req2=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
					if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="") 
					{ 
						$class="ui-widget-content".$class; 
						$style='font-weight:bold;text-decoration:none;';
						if ($gestclasse_config_plus['matin'.$jour]=="0") { $msg='<div class="ui-state-default floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; } 
						else 
						{ 
						  $date_en_cours=date('Y-m-d',mktime(0,0,0,$mois,$i,$annee));
							$id_eleve=$_SESSION['id_util'];
							$req_abs=mysql_query("SELECT * FROM `absences` WHERE date='$date_en_cours' AND id_eleve='".$id_eleve."' AND matin='1'");
							if (mysql_num_rows($req_abs)=="")
							{
						    $msg='<div class="ui-state-content floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; 
							}
							else
							{
                $req10=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<'$date_en_cours' AND date_fin>'$date_en_cours'"); // Valide une absence si la date est comprise entre les deux dates d'un justificatif
                $req20=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin quand le justificatif est sur une journée
                $req40=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin ou la journée quand le justificatif commence le jour de l'absence
                $req60=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours'"); // Valide une absence le matin quand le justificatif se termine le jour de l'absence
                if (mysql_num_rows($req10)!="" || mysql_num_rows($req20)!="" || mysql_num_rows($req40)!="" || mysql_num_rows($req60)!="")
                {
									$msg='<div class="absence_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; 
								}
								else
                {
									$msg='<div class="absence_non_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px">&nbsp;</div>'; 
								}
							}
						}
						if ($gestclasse_config_plus['am'.$jour]=="0") { $msg .='<div class="ui-state-default floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; } 
						else 
						{ 
						  $date_en_cours=date('Y-m-d',mktime(0,0,0,$mois,$i,$annee));
							$id_eleve=$_SESSION['id_util'];
							$req_abs=mysql_query("SELECT * FROM `absences` WHERE date='$date_en_cours' AND id_eleve='".$id_eleve."' AND apres_midi='1'");
							if (mysql_num_rows($req_abs)=="")
							{
  						  $msg .='<div class="ui-state-content floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; 
							}
							else
							{
                $req10=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<'$date_en_cours' AND date_fin>'$date_en_cours'"); // Valide une absence si la date est comprise entre les deux dates d'un justificatif
                $req30=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi quand le justificatif est sur une journée
                $req50=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours'"); // Valide une absence l'après-midi quand le justificatif commence le jour de l'absence
                $req70=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi ou la journée quand le justificatif se termine le jour de l'absence
                if (mysql_num_rows($req10)!="" || mysql_num_rows($req30)!="" || mysql_num_rows($req50)!="" || mysql_num_rows($req70)!="")
                {
    						  $msg .='<div class="absence_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; 
								}
								else
                {
    						  $msg .='<div class="absence_non_justifiee floatgauche" style="border:0px;margin:0px;padding:0px;width:50%;height:'.$hauteur.'px"><p class="textdroite">'.$i.'</p></div>'; 
								}
							}
						}
					} 
					else 
					{ 
						$class="ui-state-default".$class; 
						$msg='<p class="textdroite">'.$i.'</p>';
					}		
				}
				break;
		}
		echo $plus.'<td class="'.$class.'" style="padding:0px;margin:0px;height:'.$hauteur.'px;vertical-align:top;'.$style.'">'.$msg;
		echo '</td>'.$plus_apres;
  }
  echo '</tbody></table></div>';
}

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $annee=$_SESSION['annee_scolaire'];
  }
  else
  {
	  $annee=$_SESSION['annee_scolaire']+1;
	  $annee=$_SESSION['annee_scolaire'].'-'.$annee;
  }
?>
<div class="titre_page"><?php echo $Langue['LBL_ABSENCES_ELEVES_TITRE'].' '.$annee; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	{
    $mois_en_cours=1;
	}
	else
	{
	  $mois_en_cours=substr($gestclasse_config_plus['debut_annee_scolaire'],1,2);
		if (substr($mois_en_cours,0,1)=="0") { $mois_en_cours=substr($mois_en_cours,1,1); }
	}
	$annee_en_cours=$_SESSION['annee_scolaire'];
	echo '<table cellspacing=0 cellpadding=0 border=0 width=100%>';
	for ($i=1;$i<=4;$i++)
	{
		$plus2="";
		if ($i<4) { $plus2='border-bottom:15px transparent solid;'; }	
		echo '<tr>';
		for ($j=1;$j<=3;$j++)
		{
			$plus="";
			if ($j<3) 
			{ 
				if ($Sens_Ecriture=="ltr")
				{
					$plus='border-right:15px transparent solid;'; 
				}
				else
				{
					$plus='border-left:15px transparent solid;'; 
				}
			}
			echo '<td width=33% valign=top style="'.$plus.$plus2.'">';
				Affiche_Calendrier_Eleve($mois_en_cours,$annee_en_cours);
				$mois_en_cours++;
				if ($mois_en_cours==13) { $mois_en_cours=1; $annee_en_cours++; }
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '</table><br>';
	echo '<table cellspacing=0 cellpadding=0 width=100% border=0>';
	echo '<tr><td class="textgauche" valign=middle><div class="ui-state-default floatgauche" style="width:30px;height:30px">&nbsp;</div></td><td width=25% class="textgauche marge10_gauche" valign=middle>'.$Langue['LBL_ABSENCES_ELEVES_NON_TRAVAILLES'].'</td>';
	echo '<td class="textgauche" valign=middle><div class="ui-state-default floatgauche" style="background:none;width:30px;height:30px">&nbsp;</div></td><td width=25% class="textgauche marge10_gauche" valign=middle>'.$Langue['LBL_ABSENCES_ELEVES_PRESENTS'].'</td>';
	echo '<td class="textgauche" valign=middle><div class="absence_justifiee floatgauche" style="width:30px;height:30px">&nbsp;</div></td><td width=25% class="textgauche marge10_gauche" valign=middle>'.$Langue['LBL_ABSENCES_ELEVES_JUSTIFIEES'].'</td>';
	echo '<td class="textgauche" valign=middle><div class="absence_non_justifiee" style="width:30px;height:30px">&nbsp;</div></td><td width=25% class="textgauche marge10_gauche" valign=middle>'.$Langue['LBL_ABSENCES_ELEVES_NON_JUSTIFIEES'].'</td></tr>';
	echo '</table>';
?>
<script language="Javascript">
$(document).ready(function()
{
  /***************************************************/
  /* En cas de changement d'option dans l'impression */
  /***************************************************/

  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();
		window.open("http://www.doxconception.com/site/index.php/parent-absences/article/154-voir-les-absences-de-votre-enfant.html","Aide");
  });
});