<?php
  if ($_GET['document']=="1")
  {
    if ($_GET['option']=="P") { $where="(eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."') AND"; } else { $where=""; }
    switch ($_GET['type'])
    {
      case "A":
        if ($_GET['id_classe']=="")
        {
          if ($_GET['option']=="P") { $where="WHERE (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."')"; } else { $where=""; }
          $req = mysql_query("SELECT * FROM `eleves` $where ORDER BY eleves.nom ".$_GET['ordre'].", eleves.prenom ".$_GET['ordre']);
        }
        else
        {
          $req = mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE $where eleves_classes.id_classe='".$_GET['id_classe']."' AND eleves_classes.id_eleve=eleves.id ORDER BY eleves.nom ".$_GET['ordre'].", eleves.prenom ".$_GET['ordre']);
        }
        break;
      case "C":
        if ($_GET['id_classe']=="")
        {
          if ($_GET['option']=="P") { $where="WHERE (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."')"; } else { $where=""; }
          $req = mysql_query("SELECT * FROM `eleves` $where ORDER BY date_naissance ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        else
        {
          $req = mysql_query("SELECT eleves.*,eleves_classes.* FROM `eleves`, `eleves_classes` WHERE $where eleves_classes.id_classe='".$_GET['id_classe']."' AND eleves_classes.id_eleve=eleves.id ORDER BY date_naissance ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        break;
      case "L":
        if ($_GET['id_classe']=="")
        {
          $req = mysql_query("SELECT eleves.*,eleves_classes.*,classes.* FROM `eleves`, `eleves_classes`, `classes` WHERE $where classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve=eleves.id AND classes.annee='".$_SESSION['annee_scolaire']."' ORDER BY classes.nom_classe ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        else
        {
          $req = mysql_query("SELECT eleves.*,eleves_classes.*,classes.* FROM `eleves`, `eleves_classes`, `classes` WHERE $where eleves_classes.id_classe='".$_GET['id_classe']."' AND classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve=eleves.id ORDER BY eleves.nom ".$_GET['ordre'].", eleves.prenom ".$_GET['ordre']);
        }
        break;
      case "N":
        if ($_GET['id_classe']=="")
        {
          $req = mysql_query("SELECT eleves.*,eleves_classes.*,classes.*,listes.* FROM `eleves`, `eleves_classes`, `classes`, `listes` WHERE $where classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_niveau=listes.id ORDER BY listes.intitule ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        else
        {
          $req = mysql_query("SELECT eleves.*,eleves_classes.*,classes.*,listes.* FROM `eleves`, `eleves_classes`, `classes`, `listes` WHERE $where eleves_classes.id_classe='".$_GET['id_classe']."' AND classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_niveau=listes.id ORDER BY listes.intitule ".$_GET['ordre'].", eleves.prenom ".$_GET['ordre']);
        }
        break;
      case "S":
        if ($_GET['ordre']=="ASC") { $_GET['ordre']="DESC"; } else { $_GET['ordre']="ASC"; }
        if ($_GET['id_classe']=="")
        {
          if ($_GET['option']=="P") { $where="WHERE (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."')"; } else { $where=""; }
          $req = mysql_query("SELECT * FROM `eleves` $where ORDER BY sexe ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        else
        {
          $req = mysql_query("SELECT eleves.*,eleves_classes.* FROM `eleves`, `eleves_classes` WHERE $where eleves_classes.id_classe='".$_GET['id_classe']."' AND eleves_classes.id_eleve=eleves.id ORDER BY sexe ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        break;
      case "I":
        if ($_GET['id_classe']=="")
        {
          if ($_GET['option']=="P") { $where="WHERE (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."')"; } else { $where=""; }
          $req = mysql_query("SELECT * FROM `eleves` $where ORDER BY identifiant ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        else
        {
          $req = mysql_query("SELECT eleves.*,eleves_classes.* FROM `eleves`, `eleves_classes` WHERE $where eleves_classes.id_classe='".$_GET['id_classe']."' AND eleves_classes.id_eleve=eleves.id ORDER BY identifiant ".$_GET['ordre'].", eleves.nom ASC, eleves.prenom ASC");
        }
        break;
    }

	if ($_GET['forme']=="L")
	{
		foreach ($tableau_variable['eleves'] AS $cle)
		{
		  $tableau_variable['eleves'][$cle['nom']]['value'] = "";
		}

		$tpl = new template("eleves");
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
		  foreach ($tableau_variable['eleves'] AS $cle)
		  {
			$tableau_variable['eleves'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
		  }
		  foreach ($tableau_variable['eleves'] AS $cle)
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
		  $req2=mysql_query("SELECT eleves_classes.*, classes.*, listes.* FROM `eleves_classes`,`classes`,`listes` WHERE eleves_classes.id_eleve='".mysql_result($req,$i-1,"id")."' AND eleves_classes.id_classe=classes.id AND classes.annee='".$_SESSION['annee_scolaire']."' AND eleves_classes.id_niveau=listes.id");
		  if (mysql_num_rows($req2)!='')
		  {
			$tpl->set_var("CLASSE",mysql_result($req2,0,'classes.nom_classe'));
			$tpl->set_var("NIVEAU",mysql_result($req2,0,'listes.intitule'));
		  }
		  else
		  {
			$tpl->set_var("CLASSE","");
			$tpl->set_var("NIVEAU","");
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
	  $contenu_html='<div class="titre_page">'.$Langue['LBL_IMPRESSION_FORME_TROMBINOSCOPE'].'</div><br /><br />';
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
		  if (mysql_result($req,$i-1,'sexe')=="1")
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
    $id_eleve=$_GET['id_eleve'];
    foreach ($tableau_variable['eleves'] AS $cle)
    {
      $tableau_variable['eleves'][$cle['nom']]['value'] = "";
    }
    $req=mysql_query("SELECT * FROM `eleves` WHERE id='$id_eleve'");
    foreach ($tableau_variable['eleves'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['eleves'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }

    $tpl = new template("eleves");
    $tpl->set_file("gform","detailview_impression.html");
    $tpl->set_block('gform','formulaire','liste_bloc');

    foreach ($Langue AS $cle => $value)
    {
      $tpl->set_var(strtoupper($cle),$value);
    }

    foreach ($tableau_variable['eleves'] AS $cle)
    {
      $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
    }
    if (mysql_result($req,0,"passe")!="") { $tpl->set_var("PASSE",$Langue['LBL_CRYPTE']); }
    if (file_exists("cache/photos/".$id_eleve.".jpg"))
    {
      $tpl->set_var("PHOTO","cache/photos/".$id_eleve.".jpg");
    }
    else
    {
      if (mysql_result($req,0,'sexe')=="1")
	  {
	    $tpl->set_var("PHOTO","images/homme.png");
  	  }
      else
	  {
	    $tpl->set_var("PHOTO","images/femme.png");
 	  }
    }

	  $req2=mysql_query("SELECT * FROM `contacts_eleves` WHERE id_eleve='$id_eleve' ORDER BY nom ASC");
	  for ($i=1;$i<=mysql_num_rows($req2);$i++)
	  {
		$tpl->set_var('CONTACT_NOM_'.$i,mysql_result($req2,$i-1,'nom'));
		$tpl->set_var('CONTACT_LIEN_'.$i,mysql_result($req2,$i-1,'lien'));
		$tpl->set_var('CONTACT_ADRESSE_'.$i,mysql_result($req2,$i-1,'adresse'));
		$tpl->set_var('CONTACT_TEL_'.$i,mysql_result($req2,$i-1,'tel'));
		$tpl->set_var('CONTACT_TEL2_'.$i,mysql_result($req2,$i-1,'tel2'));
		$tpl->set_var('CONTACT_PORTABLE_'.$i,mysql_result($req2,$i-1,'portable'));
	  }
	  $debut=mysql_num_rows($req2)+1;
	  for ($i=$debut;$i<=5;$i++)
	  {
		$tpl->set_var('CONTACT_NOM_'.$i,'&nbsp;');
		$tpl->set_var('CONTACT_LIEN_'.$i,'&nbsp;');
		$tpl->set_var('CONTACT_ADRESSE_'.$i,'&nbsp;');
		$tpl->set_var('CONTACT_TEL_'.$i,'&nbsp;');
		$tpl->set_var('CONTACT_TEL2_'.$i,'&nbsp;');
		$tpl->set_var('CONTACT_PORTABLE_'.$i,'&nbsp;');
	  }
		if (file_exists("commun/mpdf/mpdf.php"))
		{
			if ($_GET['orientation']=="P")
			{
				$tpl->set_var('PAGE_BREAK','<pagebreak />');
				$tpl->set_var('PAGE_BREAK2','');
			}
			else
			{
				$tpl->set_var('PAGE_BREAK','');
				$tpl->set_var('PAGE_BREAK2','<pagebreak />');
			}
		}
		else
		{
			if ($_GET['orientation']=="P")
			{
				$tpl->set_var('PAGE_BREAK','</page><page pageset="old">');
				$tpl->set_var('PAGE_BREAK2','');
			}
			else
			{
				$tpl->set_var('PAGE_BREAK','');
				$tpl->set_var('PAGE_BREAK2','</page><page pageset="old">');
			}
		}  
		$contenu_html=$tpl->parse('liste_bloc','formulaire',true);
	
	  $msg='<table id="listing_classes" class="display" cellpadding=0 cellspacing=0 style="width:100%">';
	  $msg=$msg.'<thead><tr><th class="centre" style="width:5%">&nbsp;</th>';
	  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_CLASSES_ANNEES_SCOLAIRES'].'</th>';
	  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_CLASSES_CLASSES'].'</th>';
	  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_CLASSES_NIVEAUX'].'</th>';
	  $msg=$msg.'<th class="centre" style="width:20%">'.$Langue['LBL_CLASSES_ENSEIGNANTS'].'</th>';
	  $msg=$msg.'<th class="centre" style="width:15%">'.$Langue['LBL_CLASSES_REDOUBLEMENT'].'</th>';
	  $msg=$msg.'</tr></thead><tbody>';
    $ligne="even";

	  $req=mysql_query("SELECT eleves_classes.*, classes.*, listes.* FROM `eleves_classes`, `classes`, `listes` WHERE eleves_classes.id_eleve='".$id_eleve."' AND eleves_classes.id_classe=classes.id AND eleves_classes.id_niveau=listes.id ORDER BY classes.annee DESC");
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
		$annee2=mysql_result($req,$i-1,'classes.annee')+1;
		$lien="";$lien_fin="";
		if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
		  $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'</td>';
		}
		else
		{
		  $msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.mysql_result($req,$i-1,'classes.annee').'-'.$annee2.'</td>';
		}
		$msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.mysql_result($req,$i-1,'classes.nom_classe').'</td>';
		$msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.mysql_result($req,$i-1,'listes.intitule').'</td>';
		$req2=mysql_query("SELECT classes_profs.*, profs.* FROM `classes_profs`, `profs` WHERE classes_profs.id_classe='".mysql_result($req,$i-1,'classes.id')."' AND classes_profs.type='T' AND classes_profs.id_prof=profs.id");
		$msg=$msg.'<td class="centre" style="width:20%'.$bold.'">'.$liste_choix['civilite'][mysql_result($req2,0,'profs.civilite')].' '.mysql_result($req2,0,'profs.nom').'</td>';
	    $msg=$msg.'<td class="centre" style="width:15%'.$bold.'">'.$liste_choix['ouinon2'][mysql_result($req,$i-1,'eleves_classes.redoublement')].'</td></tr>';
        if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
	  }
	  $msg=$msg.'</tbody></table>';
		$contenu_html=$contenu_html.'<div class="entete_div">'.$Langue['LBL_CLASSES_LISTE'].'</div><br>'.$msg;
  }
  include "commun/impression.php";
?>

