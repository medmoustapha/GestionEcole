<?php
  if ($_GET['document']=="1")
  {
	  $bibliotheque=$_GET['bibliotheque'];
		if ($bibliotheque=="E")
		{
			switch ($_GET['type'])
			{
				case "T": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' ORDER BY id_cat ASC, titre ASC"); break;
				case "U": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
				case "N": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
				case "E": $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); break;
				case "R": 
				  $date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt'],date("Y")));
				  $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.date_emprunt<='".$date_retard."' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); 
			    break;
				case "S": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat='S' ORDER BY id_cat ASC, titre ASC"); break;
				case "O": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat='O' ORDER BY id_cat ASC, titre ASC"); break;
			}
		  $date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt'],date("Y")));
	  }
		else
		{
			switch ($_GET['type'])
			{
				case "T": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' ORDER BY id_cat ASC, titre ASC"); break;
				case "U": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
				case "N": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
				case "E": $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='".$_SESSION['id_util']."' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); break;
				case "R": 
					$date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt_classe'],date("Y")));
					$req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='".$_SESSION['id_util']."' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.date_emprunt<='".$date_retard."' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); 
					break;
				case "S": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat='S' ORDER BY id_cat ASC, titre ASC"); break;
				case "O": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat='O' ORDER BY id_cat ASC, titre ASC"); break;
			}
			$date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt_classe'],date("Y")));
		}
		$contenu_html='<div class="titre_page">'.$Langue['LBL_BIBLIOTHEQUE_TITRE_IMPRESSION'].'</div><br /><br />';
		$contenu_html .='<table id="listing_donnees_biblio" class="display" cellpadding=0 cellspacing=0  style="width:100%"><thead><tr>';
		$contenu_html .='<th style="width:10%" class="centre">'.$Langue['LBL_REFERENCE'].'</th><th style="width:50%" class="centre">'.$Langue['LBL_TITRE'].'</th><th style="width:20%" class="centre">'.$Langue['LBL_AUTEUR'].'</th><th style="width:20%" class="centre">'.$Langue['LBL_EMPRUNT_EMPRUNTEUR'].'</th></tr></thead><tbody>';
		$Ligne="even";
		$id_cat="";
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
			$aafficherdansliste=true;
			if ($_GET['type']=="N")
			{
				$req2=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00'");
				if (mysql_num_rows($req2)!="") { $aafficherdansliste=false; }
			}
			if ($aafficherdansliste==true)
			{
			  $faire=true;
				$req_emprunt=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00'");
				if (mysql_num_rows($req_emprunt)!="")
				{
					if (mysql_result($req_emprunt,0,'date_emprunt')<$date_retard)
					{
						$plus="retard";
						$faire=false;
					}
				}
				if ($faire==true)
				{
					switch (mysql_result($req,$i-1,"etat"))
					{
						case "S":
							$plus="livreasortir";
							break;
						case "O":
							$plus="livresorti";
							break;
						default:
							$plus="";
							break;
					}
				}
				
				if ($id_cat!=mysql_result($req,$i-1,'id_cat'))
				{
				  if (mysql_result($req,$i-1,'id_prof')=="")
					{
					  $req25=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'id_cat')."' AND id_prof=''");
					}
					else
					{
					  $req25=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'id_cat')."' AND id_prof='".mysql_result($req,$i-1,'id_prof')."'");
					}
				  $contenu_html .='<tr class="categorie"><td colspan=4 class="gauche">'.mysql_result($req25,0,'intitule').'</td></tr>';
					$id_cat=mysql_result($req,$i-1,'id_cat');
				}
				$contenu_html .='<tr class="'.$Ligne.'">';
				$contenu_html .='<td class="centre '.$plus.'" style="width:10%">'.mysql_result($req,$i-1,'reference').'</td>';
				$contenu_html .='<td class="gauche '.$plus.'" style="width:50%">'.mysql_result($req,$i-1,'titre').'</td>';
				$contenu_html .='<td class="gauche '.$plus.'" style="width:20%">'.mysql_result($req,$i-1,'auteur').'</td>';

				$req52=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00'");
				if (mysql_num_rows($req52)!="")
				{
					$type_util=mysql_result($req52,0,'type_util');
					if ($type_util=="P")
					{
						$req53=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req52,0,'id_util')."'");
					}
					else
					{
						$req53=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req52,0,'id_util')."'");
					}
				  $contenu_html .='<td class="centre '.$plus.'" style="width:20%">'.mysql_result($req53,0,'nom').' '.mysql_result($req53,0,'prenom').'</td>';
				}
				else
				{
				  $contenu_html .='<td class="centre '.$plus.'" style="width:20%">&nbsp;</td>';
				}
				$contenu_html .='</tr>';
				if ($Ligne=="even") { $Ligne="odd"; } else { $Ligne="even"; }
			}
		}
		$contenu_html .='</tbody></table>';
  }
  else
  {
    $id_livre=$_GET['id_livre'];
    foreach ($tableau_variable['bibliotheque'] AS $cle)
    {
      $tableau_variable['bibliotheque'][$cle['nom']]['value'] = "";
    }
    $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id='$id_livre'");
    foreach ($tableau_variable['bibliotheque'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['bibliotheque'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }

		if ($tableau_variable['bibliotheque']['id_prof']['value']=="")
		{
		$tableau_variable['bibliotheque']['id_cat']['type']='liste_bdd';
		$tableau_variable['bibliotheque']['id_cat']['nomliste']='categ_biblio_ecole';
		}

  	$tpl = new template("bibliotheque");
    $tpl->set_file("gform","detailview_impression.html");
    $tpl->set_block('gform','formulaire','liste_bloc');

    foreach ($Langue AS $cle => $value)
    {
      $tpl->set_var(strtoupper($cle),$value);
    }

    foreach ($tableau_variable['bibliotheque'] AS $cle)
    {
      $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
    }

		if ($tableau_variable['bibliotheque']['id_prof']['value']!="")
		{
			$req2=mysql_query("SELECT * FROM `profs` WHERE id='".$tableau_variable['bibliotheque']['id_prof']['value']."'");
		$tpl->set_var("BIBLIOTHEQUE",$Langue['LBL_BIBLIOTHEQUE_CLASSE']." ".$liste_choix['civilite_long'][mysql_result($req2,0,'civilite')].' '.mysql_result($req2,0,'nom'));
		}
		else
		{
		$tpl->set_var("BIBLIOTHEQUE",$Langue['LBL_BIBLIOTHEQUE_ECOLE']);
		}

		if ($tableau_variable['bibliotheque']['couverture']['value']!="")
		{
			list($width, $height, $type, $attr) = getimagesize($tableau_variable['bibliotheque']['couverture']['value']);
		if ($width>175)
		{
			$height2=175*$height/$width;
			if ($height2>250)
			{
				$width2=250*$width/$height;
			$height2=250;
			}
			else
			{
				$width2=175;
			}
		}
		else
		{
			if ($height>250)
			{
				$width2=250*$width/$height;
			$height2=250;
			}
			else
			{
				$height2=$height;
			$width2=$width;
			}
		}
		$tpl->set_var("COUVERTURE",'<img src="'.$tableau_variable['bibliotheque']['couverture']['value'].'" height='.$height2.' width='.$width2.' border=0>');
		}
		else
		{
		$tpl->set_var("COUVERTURE",'<img src="images/pas_couverture.png" width=175 height=250 border=0>');
		}

		$msg='<br><table id="listing_emprunts" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
		$msg=$msg.'<thead><tr>';
		$msg=$msg.'<th style="width:60%" class="centre">'.$Langue['LBL_EMPRUNT_EMPRUNTEUR'].'</th>';
		$msg=$msg.'<th style="width:20%" class="centre">'.$Langue['LBL_EMPRUNT_DATE_EMPRUNT'].'</th>';
		$msg=$msg.'<th style="width:20%" class="centre">'.$Langue['LBL_EMPRUNT_DATE_RETOUR'].'</th>';
		$msg=$msg.'</tr></thead><tbody>';
		$Ligne="even";
		$req=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".$id_livre."' ORDER BY date_emprunt DESC");
		if (mysql_num_rows($req)=="")
		{
		  $msg .='<tr class="even"><td colspan=3 style="width:100%" class="centre">'.$Langue['LBL_AUCUN_EMPRUNT'].'</td></tr>';
		}
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
			if (mysql_result($req,$i-1,'type_util')=="P")
			{
				$req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'id_util')."'");
			}
			else
			{
				$req2=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req,$i-1,'id_util')."'");
			}
			if ($_SESSION['type_util']=="D")
			{
					$msg=$msg.'<tr class="'.$Ligne.'"><td class="gauche" style="width:60%">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';
			}
			else
			{
				if (mysql_result($req,$i-1,'type_util')=="P")
				{
						$msg=$msg.'<tr class="'.$Ligne.'"><td class="gauche" style="width:60%">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';
				}
				else
				{
						$msg=$msg.'<tr class="'.$Ligne.'"><td class="gauche" style="width:60%">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</td>';
				}
			}
			$msg=$msg.'<td class="centre" style="width:20%">'.Date_Convertir(mysql_result($req,$i-1,'date_emprunt'),'Y-m-d',$Format_Date_PHP).'</td>'; 
			if (mysql_result($req,$i-1,'date_retour')!="0000-00-00")
			{
				$msg=$msg.'<td class="centre" style="width:20%">'.Date_Convertir(mysql_result($req,$i-1,'date_retour'),'Y-m-d',$Format_Date_PHP).'</td></tr>'; 
			}
			else
			{
				$msg=$msg.'<td class="centre" style="width:20%">&nbsp;</td></tr>'; 
			}
			if ($Ligne=="even") { $Ligne="odd"; } else { $Ligne="even"; }
		}
		$msg=$msg.'</tbody></table>';
		$tpl->set_var("LISTE_EMPRUNT",$msg);
    $contenu_html=$tpl->parse('liste_bloc','formulaire',true);
  }
  include "commun/impression.php";
?>

