<?php
  if ($_GET['document']=="1")
  {
    switch ($_GET['type'])
    {
      case "A":
        $where="";
        if ($_GET['option']=="P") { $where='WHERE (date_sortie="0000-00-00" OR date_sortie>="'.date("Y-m-d").'")'; }
		if ($_GET['type_personnel']!="")
		{
		  if ($where=="") { $where="WHERE "; } else { $where=$where." AND "; }
		  if ($_GET['type_personnel']=="P") { $where .="(type='P' OR type='D')"; } else { $where .="type='".$_GET['type_personnel']."'"; }
		}
        $req=mysql_query("SELECT * FROM `profs` $where ORDER BY nom ".$_GET['ordre'].", prenom ".$_GET['ordre']);
        break;
      case "C":
        $where="";
        if ($_GET['option']=="P") { $where='WHERE (date_sortie="0000-00-00" OR date_sortie>="'.date("Y-m-d").'")'; }
		if ($_GET['type_personnel']!="")
		{
		  if ($where=="") { $where="WHERE "; } else { $where=$where." AND "; }
		  if ($_GET['type_personnel']=="P") { $where .="(type='P' OR type='D')"; } else { $where .="type='".$_GET['type_personnel']."'"; }
		}
        $req=mysql_query("SELECT * FROM `profs` $where ORDER BY date_entree ".$_GET['ordre'].", nom ".$_GET['ordre'].", prenom ".$_GET['ordre']);
        break;
      case "I":
        $where="";
        if ($_GET['option']=="P") { $where='WHERE (date_sortie="0000-00-00" OR date_sortie>="'.date("Y-m-d").'")'; }
		if ($_GET['type_personnel']!="")
		{
		  if ($where=="") { $where="WHERE "; } else { $where=$where." AND "; }
		  if ($_GET['type_personnel']=="P") { $where .="(type='P' OR type='D')"; } else { $where .="type='".$_GET['type_personnel']."'"; }
		}
        $req=mysql_query("SELECT * FROM `profs` $where ORDER BY identifiant ".$_GET['ordre'].", nom ".$_GET['ordre'].", prenom ".$_GET['ordre']);
        break;
      case "T":
        $where="";
		if ($_GET['type_personnel']!="")
		{
		  if ($where=="") { $where="WHERE "; } else { $where=$where." AND "; }
		  if ($_GET['type_personnel']=="P") { $where .="(type='P' OR type='D')"; } else { $where .="type='".$_GET['type_personnel']."'"; }
		}
        if ($_GET['option']=="P") { $where='WHERE (date_sortie="0000-00-00" OR date_sortie>="'.date("Y-m-d").'")'; }
        $req=mysql_query("SELECT * FROM `profs` $where ORDER BY type ".$_GET['ordre'].", nom ".$_GET['ordre'].", prenom ".$_GET['ordre']);
        break;
    }
	if ($_GET['forme']=="L")
	{
		foreach ($tableau_variable['personnels'] AS $cle)
		{
		  $tableau_variable['personnels'][$cle['nom']]['value'] = "";
		}

		$tpl = new template("personnels");
		$tpl->set_file("gliste","listview_impression.html");
		$tpl->set_block('gliste','liste_entete','liste_bloc');

		foreach ($Langue AS $cle => $value)
		{
		  $tpl->set_var(strtoupper($cle),$value);
		}
	  
		$contenu_html=$tpl->parse('liste_bloc','liste_entete',true);
		
		$tpl->set_file("gliste2","listview_impression.html");
		$tpl->set_block('gliste2','liste','liste_bloc2');
		$nbr_lignage = mysql_num_rows($req);
		$ligne="even";
		for ($i=1;$i<=$nbr_lignage;$i++)
		{
		  foreach ($tableau_variable['personnels'] AS $cle)
		  {
			$tableau_variable['personnels'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
		  }
		  foreach ($tableau_variable['personnels'] AS $cle)
		  {
			if (Variables_Affiche($cle)!="")
			{
			  $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
			}
			else
			{
			  $tpl->set_var(strtoupper($cle['nom']), "&nbsp;");
			}
		  }
		  if ((mysql_result($req,$i-1,"identifiant")!="" && mysql_result($req,$i-1,"passe")!="") && (mysql_result($req,$i-1,"date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"date_sortie")>=date("Y-m-d")))
		  {
			$tpl->set_var("ACCES",mysql_result($req,$i-1,"identifiant"));
		  }
		  else
		  {
			$tpl->set_var("ACCES","&nbsp;");
		  }
		  if (mysql_result($req,$i-1,"date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"date_sortie")>=date("Y-m-d"))
		  {
			$tpl->set_var("SORTI",$ligne);
		  }
		  else
		  {
			$tpl->set_var("SORTI",'sorti');
		  }
		  $tpl->parse('liste_bloc2','liste',true);
		  if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
		}
		$contenu_html=$contenu_html.$tpl->get_var('liste_bloc2')."</tbody></table>";
	}
	else
	{
	  $contenu_html='<div class="titre_page">'.$liste_choix['type_user_impression'][$_GET['type_personnel']].'</div><br /><br />';
	  if ($_GET['orientation']=="P") { $colonne_max=3;$width=33; } else { $colonne_max=4;$width=25; }
	  $largeur_totale=$colonne_max*225;
	  $contenu_html .='<table cellspacing=0 cellpadding=0 border=0 style="width:100%">';
	  $colonne=1;
	  for ($i=1;$i<=mysql_num_rows($req);$i++)
	  {
	    if ($colonne=="1") { $contenu_html .='<tr>'; }
	    if (file_exists("cache/photos/".mysql_result($req,$i-1,'id').".jpg"))
	    {
		  $contenu_html .='<td style="width:'.$width.'%;height:310px;text-align:center;vertical-align:top"><img src="cache/photos/'.mysql_result($req,$i-1,'id').'.jpg" width="190px" height="250px" border=0><br /><br />'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom').'</td>';
  	    }
	    else
	    {
		  if (mysql_result($req,$i-1,'civilite')=="" || mysql_result($req,$i-1,'civilite')=="1")
		  {
		    $contenu_html .='<td style="width:'.$width.'%;height:310px;text-align:center;vertical-align:top"><img src="images/homme.png" width="190px" height="250px" border=0><br /><br />'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom').'</td>';
		  }
		  else
		  {
		    $contenu_html .='<td style="width:'.$width.'%;height:310px;text-align:center;vertical-align:top"><img src="images/femme.png" width="190px" height="250px" border=0><br /><br />'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom').'</td>';
		  }
	    }
		$colonne++;
		if ($colonne==$colonne_max+1) { $contenu_html .='</tr>'; $colonne=1; }
	  }
	  if ($colonne!="1")
	  {
		  for ($i=$colonne;$i<=$colonne_max;$i++)
		  {
			$contenu_html .='<td style="width:'.$width.'%;height:310px;text-align:center;vertical-align:top">&nbsp;</td>';
		  }
		  $contenu_html .='</tr>';
	  }
	  $contenu_html .='</table>';
	}
  }
  else
  {
    $id_prof=$_GET['id_prof'];
    foreach ($tableau_variable['personnels'] AS $cle)
    {
      $tableau_variable['personnels'][$cle['nom']]['value'] = "";
    }
    $req=mysql_query("SELECT * FROM `profs` WHERE id='$id_prof'");
    foreach ($tableau_variable['personnels'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['personnels'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
    $typage=mysql_result($req,0,'type');

    $tpl = new template("personnels");
    $tpl->set_file("gform","detailview_impression.html");
    $tpl->set_block('gform','formulaire','liste_bloc');

    foreach ($Langue AS $cle => $value)
    {
      $tpl->set_var(strtoupper($cle),$value);
    }
  
    foreach ($tableau_variable['personnels'] AS $cle)
    {
      $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
    }
    if (mysql_result($req,0,"passe")!="") { $tpl->set_var("PASSE",$Langue['LBL_CRYPTE']); }
    if (file_exists("cache/photos/".$id_prof.".jpg"))
    {
      $tpl->set_var("PHOTO","cache/photos/".$id_prof.".jpg");
    }
    else
    {
      if (mysql_result($req,0,'civilite')=="" || mysql_result($req,0,'civilite')=="1")
	  {
	    $tpl->set_var("PHOTO","images/homme.png");
  	  }
      else
	  {
	    $tpl->set_var("PHOTO","images/femme.png");
 	  }
    }

    $msg='<table id="listing_classes" class="display" cellpadding=0 cellspacing=0 style="width:100%;margin-top:10px;">';
    $msg=$msg.'<thead><tr>';
    $msg=$msg.'<th class="centre" style="width:5%">&nbsp;</th>';
    $msg=$msg.'<th class="centre" style="width:25%">'.$Langue['LST_ANNEES_SCOLAIRES'].'</th>';
    $msg=$msg.'<th class="centre" style="width:25%">'.$Langue['LST_CLASSES'].'</th>';
    $msg=$msg.'<th class="centre" style="width:25%">'.$Langue['LST_NIVEAUX'].'</th>';
    $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LST_QUALITE'].'</th>';
    $msg=$msg.'</tr></thead><tbody>';
    $ligne="even";
    $req=mysql_query("SELECT classes_profs.*, classes.* FROM `classes_profs`,`classes` WHERE  classes_profs.id_prof='".$id_prof."' AND classes_profs.id_classe=classes.id AND (classes_profs.type='T' OR classes_profs.type='E') ORDER BY classes.annee DESC, classes_profs.type DESC, classes.nom_classe ASC");
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
	  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	  {
		  $anneee=date("Y");
	  }
	  else
	  {
		  $annee2=mysql_result($req,$i-1,'classes.annee')+1;
		  if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $anneee=date("Y")-1; } else { $anneee=date("Y"); }
	  }
      $msg=$msg.'<tr class="'.$ligne.'"><td class="centre" style="width:5%">';
      if ($anneee==mysql_result($req,$i-1,'classes.annee')) { $msg=$msg.'<img src="images/fleche_'.$Sens_Ecriture.'.png" width=12 height=10 border=0>'; $bold=';font-weight:bold;'; } else { $msg=$msg.'&nbsp;'; $bold=''; }
      $msg=$msg.'</td>';
	  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	  {
        $msg=$msg.'<td class="centre" style="width:25%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'</td>';
	  }
	  else
	  {
        $msg=$msg.'<td class="centre" style="width:25%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'-'.$annee2.'</td>';
	  }
      $msg=$msg.'<td class="centre" style="width:25%'.$bold.'">'.mysql_result($req,$i-1,'classes.nom_classe').'</td>';
      $msg=$msg.'<td class="centre" style="width:25%'.$bold.'">'.str_replace('|',', ',Liste_Niveaux('','value','',mysql_result($req,$i-1,'classes.id'),false)).'</td>';
      $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.$liste_choix['type_user_classe'][mysql_result($req,$i-1,'classes_profs.type')].'</td></tr>';
      if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
    }
    $msg=$msg.'</tbody></table>';
    if ($typage=="D" || $typage=="P")
    {
      $tpl->set_var("LISTE_CLASSES",'<div class="entete_div">'.$Langue['LBL_LISTE_CLASSES'].'</div>'.$msg);
    }
    else
    {
      $tpl->set_var("LISTE_CLASSES",'');
    }

  if (file_exists("commun/mpdf/mpdf.php"))
  {
    if ($_GET['orientation']=="P")
	{
	  $tpl->set_var('PAGE_BREAK','<pagebreak />');
	  $tpl->set_var('PAGE_BREAK2','');
	  $tpl->set_var('PAGE_BREAK3','');
	}
	else
	{
	  $tpl->set_var('PAGE_BREAK','');
	  $tpl->set_var('PAGE_BREAK2','');
	  $tpl->set_var('PAGE_BREAK3','<pagebreak />');
	}
  }
  else
  {
    if ($_GET['orientation']=="P")
	{
	  $tpl->set_var('PAGE_BREAK','');
	  $tpl->set_var('PAGE_BREAK2','</page><page pageset="old">');
	  $tpl->set_var('PAGE_BREAK3','');
	}
	else
	{
	  $tpl->set_var('PAGE_BREAK','');
	  $tpl->set_var('PAGE_BREAK2','');
	  $tpl->set_var('PAGE_BREAK3','');
	}
  }  
    
    $contenu_html=$tpl->parse('liste_bloc','formulaire',true);
  }
  include "commun/impression.php";
?>

