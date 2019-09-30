<?php
function Affiche_Calendrier_Impression($mois,$annee,$hauteur,$extension)
{
	global $liste_choix, $gestclasse_config_plus, $Format_Date_PHP, $contenu_html, $Sens_Ecriture;

  $contenu_html .='<table class="calendrier" cellpadding=0 cellspacing=0 style="width:100%;"><thead><tr>';
  for ($i=1;$i<=7;$i++)
  {
    $contenu_html .='<th style="width:14%;">'.$liste_choix['jour_'.$extension][$i].'</th>';
  }
  $contenu_html .='</tr></thead><tbody>';
  $premier_jour_mois=date("N",mktime(0,0,0,$mois,1,$annee));
  $dernier_jour_mois_avant=date("t",mktime(0,0,0,$mois-1,1,$annee));
  for ($i=1;$i<$premier_jour_mois;$i++)
  {
    if ($i==1) { $contenu_html .='<tr>'; }
		$c=$dernier_jour_mois_avant-$premier_jour_mois+$i+1;
    $contenu_html .='<td class="jour_normal" style="width:14%;background-color:#d0d0d0;vertical-align:top;"><p class="textdroite">'.$c.'</p></td>';
  }
  $nb_jour=date("t",mktime(0,0,0,$mois,1,$annee));
  for ($i=1;$i<=$nb_jour;$i++)
  {
    $jour=date("w",mktime(0,0,0,$mois,$i,$annee));
		$class="";
		$plus="";
		$plus_apres="";
		$style="";
		if (date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))==date("Y-m-d")) { $class=" aujourdhui"; }
		switch ($jour)
		{
			case "0":
				$plus_apres='</tr>';
				$class="sans_travail".$class;
				break;
			case "1":
				$plus="<tr>";
				if ($gestclasse_config_plus['matin'.$jour]=="0" && $gestclasse_config_plus['am'.$jour]=="0")
				{
							$class="sans_travail".$class;
				}
				else
				{
					$req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND date_fin>='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND zone='".$gestclasse_config_plus['zone']."'");
					$req2=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
					if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="") 
					{ 
						$class="jour_normal".$class; 
					} 
					else 
					{ 
						$class="sans_travail".$class; 
					}		
				}
				break;
			default:
				if ($gestclasse_config_plus['matin'.$jour]=="0" && $gestclasse_config_plus['am'.$jour]=="0")
				{
							$class="sans_travail".$class;
				}
				else
				{
					$req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND date_fin>='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND zone='".$gestclasse_config_plus['zone']."'");
					$req2=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
					if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="") 
					{ 
						$class="jour_normal".$class; 
					} 
					else 
					{ 
						$class="sans_travail".$class; 
					}		
				}
				break;
		}
		$contenu_html .=$plus.'<td class="'.$class.'" style="height:'.$hauteur.'px;vertical-align:top;width:14%;'.$style.'"><p class="textdroite">'.$i.'</p>';
		$id_util=$_SESSION['type_util'].$_SESSION['id_util'];
		$req=mysql_query("SELECT * FROM `reunions` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (id_util LIKE '".$id_util.",%' OR id_util LIKE '%,".$id_util."' OR id_util='".$id_util."' OR id_util LIKE '%,".$id_util.",%') ORDER BY heure_debut ASC");
		for ($o=1;$o<=mysql_num_rows($req);$o++)
		{
			$contenu_html .='<p>';
			if ($extension=="court")
			{
//				$contenu_html .='&nbsp;'.$liste_choix['type_reunion_court'][mysql_result($req,$o-1,'type')];
				$contenu_html .='&nbsp;'.substr(mysql_result($req,$o-1,'heure_debut'),0,5).'-'.substr(mysql_result($req,$o-1,'heure_fin'),0,5).' : '.$liste_choix['type_reunion_court'][mysql_result($req,$o-1,'type')];
			}
			else
			{
				$contenu_html .='&nbsp;'.substr(mysql_result($req,$o-1,'heure_debut'),0,5).'-'.substr(mysql_result($req,$o-1,'heure_fin'),0,5).' : '.$liste_choix['type_reunion'][mysql_result($req,$o-1,'type')];
			}
			$contenu_html .='</p>';
		}
		$contenu_html .='</td>'.$plus_apres;
  }
	if ($jour<>0)
	{
		for ($i=$jour+1;$i<=7;$i++)
		{
		  $c=$i-$jour;
			$contenu_html .='<td class="jour_normal" style="vertical-align:top;width:14%;background-color:#d0d0d0;"><p class="textdroite">'.$c.'</p></td>';
		}
	}
	if (substr($contenu_html,strlen($contenu_html)-5,5)!="</tr>") { $contenu_html .='</tr>'; }
  $contenu_html .='</tbody></table>';
}

  if ($_GET['document']=="1")
	{
	  switch ($_GET['calendrier'])
		{
		  case "J":
			  $contenu_html='<div class="titre_page">'.$Langue['LBL_IMPRESSION_CALENDRIER_QUOTIDIEN'].' '.$_GET['jour'].'</div><br><br>';
				$id_util=$_SESSION['type_util'].$_SESSION['id_util'];
				$req=mysql_query("SELECT * FROM `reunions` WHERE date='".Date_Convertir($_GET['jour'],$Format_Date_PHP,'Y-m-d')."' AND (id_util LIKE '".$id_util.",%' OR id_util LIKE '%,".$id_util."' OR id_util='".$id_util."' OR id_util LIKE '%,".$id_util.",%') ORDER BY heure_debut ASC, heure_fin ASC");
				$contenu_html .='<table cellspacing=0 cellpadding=0 style="width:100%" class="display">';
				$contenu_html .='<tr><th class="centre" style="width:20%">'.$Langue['LBL_IMPRESSION_DATE_REUNION'].'</th><th class="centre" style="width:25%">'.$Langue['LBL_IMPRESSION_TYPE_REUNION'].'</th><th class="centre" style="width:55%">'.$Langue['LBL_IMPRESSION_DESCRIPTIF_REUNION'].'</th></tr>';
				$Ligne="even";
				for ($i=1;$i<=mysql_num_rows($req);$i++)
				{
				  $contenu_html .='<tr class="'.$Ligne.'">';
					$contenu_html .='<td class="centre" style="width:20%">'.$Langue['LBL_IMPRESSION_DE'].' '.substr(mysql_result($req,$i-1,'heure_debut'),0,5).' '.$Langue['LBL_IMPRESSION_A'].' '.substr(mysql_result($req,$i-1,'heure_fin'),0,5).'</td>';
					$contenu_html .='<td class="centre" style="width:25%">'.$liste_choix['type_reunion'][mysql_result($req,$i-1,'type')].'</td>';
					$contenu_html .='<td class="gauche" style="width:55%">'.mysql_result($req,$i-1,'resume').'</td>';
					$contenu_html .='</tr>';
				  if ($Ligne=="even") { $Ligne="odd"; } else { $Ligne="even"; }
				}
				$contenu_html .='</table>';
			  break;
			case "M":
			  $contenu_html='<div class="titre_page">'.$Langue['LBL_IMPRESSION_CALENDRIER_MOIS2'].' '.$liste_choix['mois'][$_GET['mois']].' '.$_GET['annee'].'</div><br><br>';
				Affiche_Calendrier_Impression($_GET['mois'],$_GET['annee'],90,'long');
			  break;
			case "A":
			  $contenu_html="";
				for ($i=1;$i<=12;$i++)
				{
				  $contenu_html .='<div class="titre_page">'.$Langue['LBL_IMPRESSION_CALENDRIER_ANNEE'].' '.$_GET['annee2'].'</div>'; 
			    $contenu_html .='<div class="entete_div textcentre">'.$liste_choix['mois'][$i].' '.$_GET['annee2'].'</div><br>';
				  Affiche_Calendrier_Impression($i,$_GET['annee2'],85,'court');
					if ($i!=12) 
					{ 
					  if (file_exists("commun/mpdf/mpdf.php")) { $contenu_html .='<pagebreak />'; } else { $contenu_html .='</page><page pageset="old">'; }
					}
				}
			  break;
		}
	}
	else
	{
		foreach ($tableau_variable['agenda'] AS $cle)
		{
			$tableau_variable['agenda'][$cle['nom']]['value'] = "";
		}

		$req = mysql_query("SELECT * FROM `reunions` WHERE id = '" . $_GET['id_reunion'] . "'");
		foreach ($tableau_variable['agenda'] AS $cle)
		{
			if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['agenda'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
		}
		
		$tpl = new template("calendrier");
		$tpl->set_file("gform","detailview_impression.html");
		$tpl->set_block('gform','formulaire','liste_bloc');

		foreach ($Langue AS $cle => $value)
		{
			$tpl->set_var(strtoupper($cle),$value);
		}

		foreach ($tableau_variable['agenda'] AS $cle)
		{
			$tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
		}
		
		$id_util=explode(',',$tableau_variable['agenda']['id_util']['value']);
		$msg="";
		for ($i=0;$i<count($id_util);$i++)
		{
			$type=substr($id_util[$i],0,1);
			$id=substr($id_util[$i],1,strlen($id_util[$i]));
			if ($type=="E")
			{
				$req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id'");
			}
			else
			{
				$req2=mysql_query("SELECT * FROM `profs` WHERE id='$id'");
			}
			$msg=$msg.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').', ';
		}
		$tpl->set_var("ID_UTIL",substr($msg,0,strlen($msg)-2));
		
		$contenu_html=$tpl->parse('liste_bloc','formulaire',true);
	}
  include "commun/impression.php";
?>