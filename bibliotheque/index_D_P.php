<?php
  if (!isset($_SESSION['tableau_bibliotheque']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement		4 : Recherche
    $_SESSION['tableau_bibliotheque']="30|0|2|asc";
  }

  if (!isset($_SESSION['colonnes_bibliotheque']))
  {
		$_SESSION['colonnes_bibliotheque']='{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":false,"bVisible":true}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":false,"bVisible":true}';
		$req=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".$_SESSION['id_util']."'");
		if (mysql_num_rows($req)!="")
		{
			if (mysql_result($req,0,'bibliotheque')!="")
			{
				$_SESSION['colonnes_bibliotheque']=mysql_result($req,0,'bibliotheque');
			}
		}
  }
  if (!isset($_SESSION['recherche_biblio']))
  {
		$_SESSION['recherche_biblio']='|||||||||';
  }
    
  $tableau_personnalisation=explode("|",$_SESSION['tableau_bibliotheque']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_biblio']);
  
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=date("Y");
  }
  else
  {
    if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=date("Y")-1; } else { $annee=date("Y"); }
  }
  
  Param_Utilisateur($_SESSION['id_util'],$annee);
  
  if ($gestclasse_config_plus['biblio_ecole']=="0" && $gestclasse_config_plus['biblio_classe']=="0")
  {
    echo '<div class="titre_page">'.$Langue['LBL_BIBLIOTHEQUE'].'</div><br />';
	echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_AUCUNE_BIBLIOTHEQUE'].'</strong></div></div>';
	exit;
  }
  
  if (!isset($_SESSION['livres_a_afficher']))
  {
    $_SESSION['livres_a_afficher']="U";
  }
  
  if (!isset($_SESSION['affiche_biblio']))
  {
    if ($gestclasse_config_plus['biblio_ecole']=="1")
    {
			if ($gestclasse_config_plus['biblio_classe']=="1")
			{
				$_SESSION['affiche_biblio']="1";
			}
			else
			{
				$_SESSION['affiche_biblio']="3";
			}
		}
		else
		{
				$_SESSION['affiche_biblio']="2";
		}
  }
  
// Récupération des informations
  foreach ($tableau_variable['bibliotheque'] AS $cle)
  {
    $tableau_variable['bibliotheque'][$cle['nom']]['value'] = "";
  }

  $msg_droite='<div class="ui-widget ui-state-default ui-corner-right floatdroite ui-div-listview textcentre">'.$Langue['LBL_LIVRE_A_AFFICHER'].' : ';
  $msg_droite .='<select class="text ui-widget-content ui-corner-all" name="livres_a_afficher" id="livres_a_afficher">';
  foreach ($liste_choix['livre_a_afficher'] AS $cle => $value)
  {
    $msg_droite .='<option value="'.$cle.'"';
	if ($cle==$_SESSION['livres_a_afficher']) { $msg_droite .=' SELECTED'; }
	$msg_droite .='>'.$value.'</option>';
  }
  $msg_droite .='</select></div>';
  $msg_droite .='<div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre">'.$Langue['LBL_BIBLIO_A_AFFICHER'].' : ';
  if ($gestclasse_config_plus['biblio_ecole']=="1" && $gestclasse_config_plus['biblio_classe']=="1")
  {
	$msg_droite .='<select class="text ui-widget-content ui-corner-all" name="affiche_biblio" id="affiche_biblio">';
	foreach ($liste_choix['liste_biblio'] AS $cle => $value)
	{
	  $msg_droite .='<option value="'.$cle.'"';
	  if ($cle==$_SESSION['affiche_biblio']) { $msg_droite .=' SELECTED'; }
	  $msg_droite .='>'.$value.'</option>';
	}
	$msg_droite .='</select></div>';
  }
  else
  {
	$msg_droite .='<select class="text ui-widget-content ui-corner-all" name="affiche_biblio" id="affiche_biblio">';
    if ($gestclasse_config_plus['biblio_ecole']=="1")
    {
      $msg_droite .='<option value="3">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</option>';
	}
	else
    {
      $msg_droite .='<option value="2">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</option>';
	}
	$msg_droite .='</select></div>';
  }
  
  switch ($_SESSION['affiche_biblio'])
  {
    case "1": $affiche=""; break;
		case "2": $affiche="2"; break;
		case "3": $affiche="2"; break;
  }
  
  $tpl = new template("bibliotheque");
  $tpl->set_file("gliste","listview".$affiche.".html");
  $tpl->set_block('gliste','liste_entete','liste_bloc');
  $tpl->set_var("MSG_DROITE",$msg_droite);

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

	$colonne=0;
	foreach ($tableau_recherche['biblio'] AS $cle)
	{
	  if (array_key_exists('recherche',$cle))
		{
			$cle['valeur_recherche']=$tableau_recherche2[$colonne];
			$tpl->set_var("RECHERCHE_".strtoupper($cle['nom']), Recherche_Cle($cle));
			$colonne++;
		}
	}
	$msg='<select tabindex=102 id="recherche_categorie" name="recherche_categorie" class="text ui-widget-content ui-corner-all">';
  $msg .='<option value=""></option>';
	if ($_SESSION['affiche_biblio']=="1" || $_SESSION['affiche_biblio']=="3")
	{
	  $msg .='<option class="option_gras" value="'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</option>';
		$req85=mysql_query("SELECT * FROM `listes` WHERE id_prof='' AND nom_liste='categ_biblio_ecole' ORDER BY ordre ASC");
		for ($iu=1;$iu<=mysql_num_rows($req85);$iu++)
		{
		  $msg .='<option value="'.mysql_result($req85,$iu-1,'intitule').'"';
			if (mysql_result($req85,$iu-1,'intitule')==$tableau_recherche2[1]) { $msg .=' SELECTED'; }
			$msg .='>'.mysql_result($req85,$iu-1,'intitule').'</option>';
		}
	}
	if ($_SESSION['affiche_biblio']=="1" || $_SESSION['affiche_biblio']=="2")
	{
	  $msg .='<option class="option_gras" value="'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</option>';
		$req85=mysql_query("SELECT * FROM `listes` WHERE id_prof='".$_SESSION['id_util']."' AND nom_liste='categ_biblio_classe' ORDER BY ordre ASC");
		for ($iu=1;$iu<=mysql_num_rows($req85);$iu++)
		{
		  $msg .='<option value="'.mysql_result($req85,$iu-1,'intitule').'"';
			if (mysql_result($req85,$iu-1,'intitule')==$tableau_recherche2[1]) { $msg .=' SELECTED'; }
			$msg .='>'.mysql_result($req85,$iu-1,'intitule').'</option>';
		}
	}
  $msg .='</select>';
	$tpl->set_var("RECHERCHE_CATEGORIE",$msg);
	
  $tpl->parse('liste_bloc','liste_entete',true);

// Affichage de la liste
  $tpl->set_file("gliste2","listview".$affiche.".html");
  $tpl->set_block('gliste2','liste','liste_bloc2');
  
  // Cas où on demande l'affichage de la bibliothèque d'école
  if ($_SESSION['affiche_biblio']=="1" || $_SESSION['affiche_biblio']=="3")
  {
    switch ($_SESSION['livres_a_afficher'])
    {
      case "T": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' ORDER BY id_cat ASC, titre ASC"); break;
      case "U": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
      case "N": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
      case "E": $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); break;
      case "R": 
	    $date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt'],date("Y")));
	    $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.date_emprunt<='".$date_retard."' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); 
		break;
      case "S": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat='S' ORDER BY id_cat ASC, titre ASC"); break;
      case "O": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' AND etat='O' ORDER BY id_cat ASC, titre ASC"); break;
    }
    $nbr_lignage = mysql_num_rows($req);
   	$date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt'],date("Y")));
    for ($i=1;$i<=$nbr_lignage;$i++)
    {
			$aafficherdansliste=true;
			if ($_SESSION['livres_a_afficher']=="N")
			{
				$req2=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00' AND reservation='0'");
				if (mysql_num_rows($req2)!="") { $aafficherdansliste=false; }
			}
			if ($aafficherdansliste==true)
			{
        foreach ($tableau_variable['bibliotheque_ecole'] AS $cle)
        {
          $tableau_variable['bibliotheque_ecole'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
        }
        foreach ($tableau_variable['bibliotheque_ecole'] AS $cle)
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

  	    switch (mysql_result($req,$i-1,"etat"))
				{
					case "S":
						$tpl->set_var("SORTI",'class="livreasortir"');
						break;
					case "O":
						$tpl->set_var("SORTI",'class="livresorti"');
						break;
					default:
						$tpl->set_var("SORTI","");
						break;
				}

				$req_emprunt=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00' ORDER BY reservation ASC, date_emprunt ASC");
				if (mysql_num_rows($req_emprunt)!="")
				{
				  if (mysql_result($req_emprunt,0,'reservation')=="0")
					{
						if (mysql_result($req_emprunt,0,'date_emprunt')<$date_retard)
						{
							$tpl->set_var("SORTI",'class="retard"');
						}
					}
					else
					{
						$tpl->set_var("SORTI",'class="reserve"');
					}
				}
	  
        if ($_SESSION['type_util']=="D")
        {
          $tpl->set_var("MODIFIER",'&nbsp;<a href="#null" title="'.$Langue['SPAN_MODIFIER_LIVRE'].'" onClick="Biblio_L_EditView_Util(\''.mysql_result($req,$i-1,'id').'\');"><img src="images/editer.png" width=12 height=12 border=0></a>');
        }
        else
        {
          $tpl->set_var("MODIFIER",'<img src="images/vide.png" width=12 height=12 border=0>');
        }
				$tpl->set_var("BIBLIO",$Langue['LBL_BIBLIOTHEQUE_ECOLE']);
	  
				$req52=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00' ORDER BY reservation ASC, date_emprunt ASC");
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
					if (mysql_result($req52,0,'reservation')=="0")
					{
					  $tpl->set_var("EMPRUNTEUR",mysql_result($req53,0,'nom').' '.mysql_result($req53,0,'prenom').' <a title="'.$Langue['SPAN_MODIFIER_EMPRUNT'].'" href=#null onClick="Biblio_L_Charge_Emprunt(\''.mysql_result($req52,0,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_CLORE_EMPRUNT'].'" href=#null onClick="Biblio_L_Valid_Emprunt(\''.mysql_result($req52,0,'id').'\')"><img src="images/retour_emprunt.png" width=12 height=12 border=0></a>');
					}
					else
					{
					  $tpl->set_var("EMPRUNTEUR",mysql_result($req53,0,'nom').' '.mysql_result($req53,0,'prenom').' <a title="'.$Langue['SPAN_RESERVATION_MODIFIER'].'" href=#null onClick="Biblio_L_Charge_Reservation(\''.mysql_result($req52,0,'id').'\',\''.mysql_result($req,$i-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_RESERVATION_EMPRUNT'].'" href=#null onClick="Biblio_L_Valid_Reservation(\''.mysql_result($req52,0,'id').'\')"><img src="images/reservation_emprunt.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_RESERVATION_SUPPRIMER'].'" href=#null onClick="Biblio_L_Supprimer_Reservation(\''.mysql_result($req52,0,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a>');
					}
				}
				else
				{
					$tpl->set_var("EMPRUNTEUR","");
				}
        $tpl->parse('liste_bloc2','liste',true);
			}	
    }
  }	

  // Cas où on demande l'affichage de la bibliothèque de classe
  if ($_SESSION['affiche_biblio']=="1" || $_SESSION['affiche_biblio']=="2")
  {
    switch ($_SESSION['livres_a_afficher'])
    {
      case "T": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' ORDER BY id_cat ASC, titre ASC"); break;
      case "U": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
      case "N": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat<>'O' ORDER BY id_cat ASC, titre ASC"); break;
      case "E": $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='".$_SESSION['id_util']."' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); break;
      case "R": 
	    $date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt_classe'],date("Y")));
	    $req=mysql_query("SELECT bibliotheque.*, bibliotheque_emprunt.* FROM `bibliotheque`,`bibliotheque_emprunt` WHERE bibliotheque.id_prof='".$_SESSION['id_util']."' AND bibliotheque.etat<>'O' AND bibliotheque.id=bibliotheque_emprunt.id_livre AND bibliotheque_emprunt.date_retour='0000-00-00' AND bibliotheque_emprunt.date_emprunt<='".$date_retard."' AND bibliotheque_emprunt.reservation='0' ORDER BY bibliotheque.id_cat ASC, bibliotheque.titre ASC"); 
		break;
      case "S": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat='S' ORDER BY id_cat ASC, titre ASC"); break;
      case "O": $req=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' AND etat='O' ORDER BY id_cat ASC, titre ASC"); break;
    }
    $nbr_lignage = mysql_num_rows($req);
    $date_retard=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$gestclasse_config_plus['biblio_duree_emprunt_classe'],date("Y")));
    for ($i=1;$i<=$nbr_lignage;$i++)
    {
			$aafficherdansliste=true;
			if ($_SESSION['livres_a_afficher']=="N")
			{
				$req2=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00' AND reservation='0'");
				if (mysql_num_rows($req2)!="") { $aafficherdansliste=false; }
			}
			if ($aafficherdansliste==true)
			{
				foreach ($tableau_variable['bibliotheque'] AS $cle)
				{
				$tableau_variable['bibliotheque'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
				}
				$tableau_variable['bibliotheque']['id_cat']['nom_prof']=$_SESSION['id_util'];
				foreach ($tableau_variable['bibliotheque'] AS $cle)
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

				switch (mysql_result($req,$i-1,"etat"))
				{
				case "S":
					$tpl->set_var("SORTI",'class="livreasortir"');
					break;
				case "O":
					$tpl->set_var("SORTI",'class="livresorti"');
					break;
				default:
					$tpl->set_var("SORTI","");
					break;
				}

				$req_emprunt=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00' ORDER BY reservation ASC, date_emprunt ASC");
				if (mysql_num_rows($req_emprunt)!="")
				{
					if (mysql_result($req_emprunt,0,'reservation')=="0")
					{
						if (mysql_result($req_emprunt,0,'date_emprunt')<$date_retard)
						{
							$tpl->set_var("SORTI",'class="retard"');
						}
					}
					else
					{
							$tpl->set_var("SORTI",'class="reserve"');
					}
				}
				
				if ($_SESSION['id_util']==mysql_result($req,$i-1,'id_prof'))
				{
				$tpl->set_var("MODIFIER",'&nbsp;<a href="#null" title="'.$Langue['SPAN_MODIFIER_LIVRE'].'" onClick="Biblio_L_EditView_Util(\''.mysql_result($req,$i-1,'id').'\');"><img src="images/editer.png" width=12 height=12 border=0></a>');
				}
				else
				{
				$tpl->set_var("MODIFIER",'');
				}

				$tpl->set_var("BIBLIO",$Langue['LBL_BIBLIOTHEQUE_CLASSE2']);

				$req52=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id_livre='".mysql_result($req,$i-1,'id')."' AND date_retour='0000-00-00' ORDER BY reservation ASC, date_emprunt ASC");
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
					if (mysql_result($req52,0,'reservation')=="0")
					{
					  $tpl->set_var("EMPRUNTEUR",mysql_result($req53,0,'nom').' '.mysql_result($req53,0,'prenom').' <a title="'.$Langue['SPAN_MODIFIER_EMPRUNT'].'" href=#null onClick="Biblio_L_Charge_Emprunt(\''.mysql_result($req52,0,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_CLORE_EMPRUNT'].'" href=#null onClick="Biblio_L_Valid_Emprunt(\''.mysql_result($req52,0,'id').'\')"><img src="images/retour_emprunt.png" width=12 height=12 border=0></a>');
					}
					else
					{
					  $tpl->set_var("EMPRUNTEUR",mysql_result($req53,0,'nom').' '.mysql_result($req53,0,'prenom').' <a title="'.$Langue['SPAN_RESERVATION_MODIFIER'].'" href=#null onClick="Biblio_L_Charge_Reservation(\''.mysql_result($req52,0,'id').'\',\''.mysql_result($req,$i-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_RESERVATION_EMPRUNT'].'" href=#null onClick="Biblio_L_Valid_Reservation(\''.mysql_result($req52,0,'id').'\')"><img src="images/reservation_emprunt.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_RESERVATION_SUPPRIMER'].'" href=#null onClick="Biblio_L_Supprimer_Reservation(\''.mysql_result($req52,0,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a>');
					}
				}
				else
				{
				$tpl->set_var("EMPRUNTEUR","");
				}

				$tpl->parse('liste_bloc2','liste',true);
			}
    }
  }	

  $tpl->pparse("affichage","gliste2");
?>

<script language="Javascript">
$(document).ready(function()
{
  $("#imprimer").button({disabled:false});
  $("#imprimer").click(function()
  {
    Charge_Dialog("index2.php?module=bibliotheque&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-bibliotheque.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-bibliotheque.html","Aide");
<?php } ?>
  });

	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
  /* Création du tableau de données */
  oTable_biblio=$('#listing_donnees_biblio').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "bAutoWidth": true,
      "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
			"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
			"aoColumns" : [ <?php echo str_replace("|",",",$_SESSION['colonnes_bibliotheque']); ?> ],
			"sDom": 'C<"clear"><"H"lr>t<"F"ip>',
			"oColVis": 
			{
				"buttonText": "<?php echo $Langue['LBL_TABLEAU_MONTRER_CACHER']; ?>",
				"aiExclude": [ 0,1,2,3,10 ],
				"fnStateChange": function(iColumn, bVisible)
				{
					url = "index2.php";
				  if (bVisible==false) { bVisible2=0; } else { bVisible2=1; }
  				data = "module=users&action=save_perso2&longueur=10&module_session=bibliotheque&colonne="+iColumn+"&visible="+bVisible2;
	  			var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
			"fnDrawCallback": function( oSettings ) 
			{
			  faire=false;
				colonne_index=oSettings.aaSorting[0][0];
				colonne_ordre=oSettings.aaSorting[0][1];
				if (longueur_tableau!=oSettings._iDisplayLength) { faire=true;longueur_tableau=oSettings._iDisplayLength; }
//				if (page_tableau!=oSettings._iDisplayStart) { faire=true;page_tableau=oSettings._iDisplayStart; }
				if (colonne_tableau!=colonne_index) { faire=true;colonne_tableau=colonne_index; }
				if (ordre_tableau!=colonne_ordre) { faire=true;ordre_tableau=colonne_ordre; }
				if (faire==true)
				{
					url = "index2.php";
					data = "module=users&action=save_perso&module_session=bibliotheque&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
					var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_ELEMENT_AFFICHES']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_NO_DATA']; ?>",
        "sInfo": "<?php echo $Langue['LBL_ELEMENT_AFFICHES2']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_RESULT_RECHERCHE']; ?>",
        "sSearch": "<?php echo $Langue['LBL_RECHERCHER_DATA']; ?>",
        "oPaginate":
        {
          "sFirst":    "<?php echo $Langue['LBL_DEBUT']; ?>",
          "sPrevious": "<?php echo $Langue['LBL_PRECEDENT']; ?>",
          "sNext":     "<?php echo $Langue['LBL_SUIVANT']; ?>",
          "sLast":     "<?php echo $Langue['LBL_FIN']; ?>"
        }
      },
    }
  ).rowGrouping({ sGroupingClass:"group_center", iGroupingColumnIndex2: "1" });

  $("#Rechercher_Liste").button();
	$("#Rechercher_Liste").click(function()
	{
	  if (document.getElementById('affiche_recherche_biblio').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_biblio').style.visibility="visible";
		  document.getElementById('affiche_recherche_biblio').style.display="block";
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_biblio').style.visibility="hidden";
		  document.getElementById('affiche_recherche_biblio').style.display="none";
		}
	});

  $("#rechercher_biblio").button();
	$("#rechercher_biblio").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['biblio'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_biblio.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=biblio&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_biblio").button();
	$("#vider_biblio").click(function()
	{
<?php
		foreach ($tableau_recherche['biblio'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_biblio.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=biblio&recherche=||||||||||";
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  /* EditView : Création des boutons et des actions associées */
<?php if ($_SESSION['type_util']!="D" && $gestclasse_config_plus['biblio_classe']!="1") { ?>
  $("#creer-element").button({disabled:true});
<?php } else { ?>
  $("#creer-element").button({disabled:false});
  $("#creer-element").click(function()
  {
	Charge_Dialog("index2.php?module=bibliotheque&action=editview","<?php echo $Langue['LBL_AJOUTER_LIVRE']; ?>");
  });
<?php } ?>
  $("#creer-emprunt").button({disabled:false});
  $("#creer-emprunt").click(function()
  {
	Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt","<?php echo $Langue['BTN_SAISIR_EMPRUNT']; ?>");
  });
	
  /* Quand on change les livres à afficher */
  $("#livres_a_afficher").change(function()
  {
    Message_Chargement(1,1);
    var url="index2.php";
		var livres_a_afficher=$("#livres_a_afficher").val();
    var data="module=bibliotheque&action=change_livres&livres_a_afficher="+livres_a_afficher;
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
      $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
    });
  });
  
  /* Quand on change la bibliothèque à afficher */
  $("#affiche_biblio").change(function()
  {
    Message_Chargement(1,1);
    var url="bibliotheque/change_biblio.php";
    var data="affiche_biblio="+$("#affiche_biblio").val();
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
      $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
    });	
  });

	$("#rechercher_biblio").click();
<?php if ($_SESSION['recherche_biblio']!='|||||||||') { echo '$("#Rechercher_Liste").click();'; } ?>
});

  /* Fonction pour charger la fiche d'un emprunt */
  function Biblio_L_Charge_Emprunt(id)
  {
    Charge_Dialog3("index2.php?module=bibliotheque&action=editview_emprunt&id="+id,"<?php echo $Langue['BTN_SAISIR_EMPRUNT']; ?>");
  }

  /* Fonction pour charger de DetailView d'un livre */
  function Biblio_L_DetailView_Util(id)
  {
    Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
  }

  /* Fonction pour charger l'EditView d'un livre */
  function Biblio_L_EditView_Util(id)
  {
    Charge_Dialog("index2.php?module=bibliotheque&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_LIVRE']; ?>");
  }

  /* Fonction pour clore l'emprunt */
  function Biblio_L_Valid_Emprunt(id)
  {
    $( "#dialog-confirm" ).html('<div style="text-align:left;line-height:24px;"><?php echo $Langue['MSG_RETOUR_LIVRE']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_EMPRUNT_VALIDER_EMPRUNT']; ?>",
			resizable: false,
			draggable: false,
			height:200,
			width:450,
			modal: true,
			buttons:[
			{
        text: "<?php echo $Langue['BTN_VALIDER']; ?>",
				click: function()
        {
          Message_Chargement(8,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: "module=bibliotheque&action=save_retour_emprunt&id_emprunt="+id});
          request.done(function(msg)
          {
						Message_Chargement(1,1);
						$( "#dialog-confirm" ).dialog( "close" );
						$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
						$("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
					});
        }
			},
			{
        text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
				click: function()
        {
					$( this ).dialog( "close" );
				}
			}]
		});
  }

  /* Fonction pour transformer une réservation en emprunt */
  function Biblio_L_Valid_Reservation(id)
  {
    $( "#dialog-confirm" ).html('<div style="text-align:left;line-height:24px;"><?php echo $Langue['MSG_RESERVATION_EMPRUNT']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_RESERVATION_VALIDER']; ?>",
			resizable: false,
			draggable: false,
			height:200,
			width:450,
			modal: true,
			buttons:[
			{
        text: "<?php echo $Langue['BTN_VALIDER']; ?>",
				click: function()
        {
          Message_Chargement(8,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: "module=bibliotheque&action=save_valider_reservation&id_emprunt="+id});
          request.done(function(msg)
          {
						Message_Chargement(1,1);
						$( "#dialog-confirm" ).dialog( "close" );
						$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
						$("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
					});
        }
			},
			{
        text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
				click: function()
        {
					$( this ).dialog( "close" );
				}
			}]
		});
  }

  /* Fonction pour transformer une réservation en emprunt */
  function Biblio_L_Supprimer_Reservation(id)
  {
    $( "#dialog-confirm" ).html('<div style="text-align:left;line-height:24px;"><?php echo $Langue['MSG_RESERVATION_SUPPRIMER']; ?></div>');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_RESERVATION_SUPPRIMER']; ?>",
			resizable: false,
			draggable: false,
			height:200,
			width:450,
			modal: true,
			buttons:[
			{
        text: "<?php echo $Langue['BTN_VALIDER']; ?>",
				click: function()
        {
          Message_Chargement(8,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: "module=bibliotheque&action=delete_reservation&id_emprunt="+id});
          request.done(function(msg)
          {
						Message_Chargement(1,1);
						$( "#dialog-confirm" ).dialog( "close" );
						$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
						$("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
					});
        }
			},
			{
        text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
				click: function()
        {
					$( this ).dialog( "close" );
				}
			}]
		});
  }
	
	function Biblio_L_Charge_Reservation(id,id_livre)
	{
		Charge_Dialog3("index2.php?module=bibliotheque&action=editview_reservation&id="+id+"&id_livre="+id_livre,"<?php echo $Langue['BTN_RESERVATION_SAISIR']; ?>");
	}
</script>
