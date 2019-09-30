<?php
  if ($_GET['document']=="1")
  {
    switch ($_GET['type'])
    {
      case "A":
        $req = mysql_query("SELECT * FROM `classes` WHERE annee='".$_SESSION['annee_scolaire']."' ORDER BY nom_classe ".$_GET['ordre']);
        break;
      case "N":
        $req = mysql_query("SELECT classes.*, classes_niveaux.*, listes.* FROM `classes`,`classes_niveaux`,`listes` WHERE classes.id=classes_niveaux.id_classe AND classes.annee='".$_SESSION['annee_scolaire']."' AND listes.nom_liste='niveaux' AND listes.id=classes_niveaux.id_niveau GROUP BY classes.id ORDER BY listes.ordre ".$_GET['ordre'].", classes.nom_classe ASC");
        break;
      case "E":
        $req=mysql_query("SELECT eleves.*, classes.*, eleves_classes.*, count(eleves_classes.id_eleve) FROM `classes`,`eleves_classes`,`eleves` WHERE (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."') AND eleves.id=eleves_classes.id_eleve AND eleves_classes.id_classe=classes.id GROUP BY eleves_classes.id_classe ORDER BY count(eleves_classes.id_eleve) ".$_GET['ordre'].", classes.nom_classe ASC");
        break;
      case "P":
        $req = mysql_query("SELECT classes.*,classes_profs.*,profs.* FROM `classes`,`classes_profs`,`profs` WHERE classes.id=classes_profs.id_classe AND classes_profs.id_prof=profs.id AND classes.annee='".$_SESSION['annee_scolaire']."' GROUP BY classes.id ORDER BY profs.nom ".$_GET['ordre'].", profs.prenom ".$_GET['ordre']);
        break;
    }

    foreach ($tableau_variable['classes'] AS $cle)
    {
      $tableau_variable['classes'][$cle['nom']]['value'] = "";
    }

    $tpl = new template("classes");
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
      foreach ($tableau_variable['classes'] AS $cle)
      {
        $tableau_variable['classes'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
      }
      foreach ($tableau_variable['classes'] AS $cle)
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
      // Calcul le nombre d'élèves
      $req2=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."') AND eleves.id=eleves_classes.id_eleve AND eleves_classes.id_classe='".mysql_result($req,$i-1,'classes.id')."'");
      if (mysql_num_rows($req2)=="") { $tpl->set_var("NB_ELEVE", "0"); } else { $tpl->set_var("NB_ELEVE", mysql_num_rows($req2)); }

      // Niveaux
      $req2=mysql_query("SELECT classes_niveaux.*, listes.* FROM `classes_niveaux`, `listes` WHERE classes_niveaux.id_classe='".mysql_result($req,$i-1,'classes.id')."' AND classes_niveaux.id_niveau=listes.id AND listes.nom_liste='niveaux' ORDER BY listes.ordre ASC");
      $msg="";
      for ($j=1;$j<=mysql_num_rows($req2);$j++)
      {
        $msg=$msg.mysql_result($req2,$j-1,'listes.intitule').", ";
      }
      $tpl->set_var("NIVEAUX",substr($msg,0,strlen($msg)-2));

      // Titulaire
      $req2=mysql_query("SELECT classes_profs.*, profs.* FROM `classes_profs`, `profs` WHERE classes_profs.type='T' AND classes_profs.id_classe='".mysql_result($req,$i-1,'classes.id')."' AND classes_profs.id_prof=profs.id");
      $tpl->set_var("ID_PROF",mysql_result($req2,0,'profs.id'));
      $tpl->set_var("TITULAIRE",$liste_choix['civilite'][mysql_result($req2,0,'civilite')]." ".mysql_result($req2,0,'nom')." ".mysql_result($req2,0,'prenom'));

      // Décharges
      $req2=mysql_query("SELECT classes_profs.*, profs.* FROM `classes_profs`, `profs` WHERE classes_profs.type='E' AND classes_profs.id_classe='".mysql_result($req,$i-1,'classes.id')."' AND classes_profs.id_prof=profs.id");
      $msg="";
      if (mysql_num_rows($req2)!="")
      {
        for ($j=1;$j<=mysql_num_rows($req2);$j++)
        {
          $msg=$msg.$liste_choix['civilite'][mysql_result($req2,$j-1,'civilite')]." ".mysql_result($req2,$j-1,'nom')." ".mysql_result($req2,$j-1,'prenom');
          $msg=$msg."<br>";
        }
        $tpl->set_var("DECHARGE",substr($msg,0,strlen($msg)-4));
      }
      else
      {
        $tpl->set_var("DECHARGE","&nbsp;");
      }
      $tpl->set_var("SORTI",$ligne);
      $tpl->parse('liste_bloc2','liste',true);
      if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
    }
    $contenu_html=$contenu_html.$tpl->get_var('liste_bloc2')."</tbody></table>";
  }
  else
  {
    $id_classe=$_GET['id_classe'];
    if ($_GET['document2']=="1")
    {
      foreach ($tableau_variable['classes'] AS $cle)
      {
        $tableau_variable['classes'][$cle['nom']]['value'] = "";
      }
      $req=mysql_query("SELECT * FROM `classes` WHERE id='$id_classe'");
      foreach ($tableau_variable['classes'] AS $cle)
      {
        if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['classes'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
      }

      $tpl = new template("classes");
      $tpl->set_file("gform","detailview_impression.html");
      $tpl->set_block('gform','formulaire','liste_bloc');

      foreach ($Langue AS $cle => $value)
      {
        $tpl->set_var(strtoupper($cle),$value);
      }
  
      foreach ($tableau_variable['classes'] AS $cle)
      {
        $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
      }
      $tpl->set_var("ANNEE",Liste_Annee('',$type='value',mysql_result($req,0,'annee')));

      // Affichage du tableau avec les différents intervenants dans la classe
      $liste_titulaire=Liste_Profs('','value',mysql_result($req,0,'id'),'','T',false);
      $liste_decharge=Liste_Profs('','value',mysql_result($req,0,'id'),'','E',false);
      $liste_decloisonnement=Liste_Profs('','value',mysql_result($req,0,'id'),'','D',false);
      $liste_atsem=Liste_Profs('','value',mysql_result($req,0,'id'),'','S',false);
      $liste_intervenant=Liste_Profs('','value',mysql_result($req,0,'id'),'','I',false);
      $liste_autre=Liste_Profs('','value',mysql_result($req,0,'id'),'','U',false);
      $liste_niveaux=Liste_Niveaux('','value','',mysql_result($req,0,'id'),false);

      $nb_ligne=0;
      $tableau_titulaire=explode("|",$liste_titulaire);
      $tableau_decharge=explode("|",$liste_decharge);
      if (count($tableau_decharge)>$nb_ligne) { $nb_ligne=count($tableau_decharge); }
      $tableau_decloisonnement=explode("|",$liste_decloisonnement);
      if (count($tableau_decloisonnement)>$nb_ligne) { $nb_ligne=count($tableau_decloisonnement); }
      $tableau_atsem=explode("|",$liste_atsem);
      if (count($tableau_atsem)>$nb_ligne) { $nb_ligne=count($tableau_atsem); }
      $tableau_intervenant=explode("|",$liste_intervenant);
      if (count($tableau_intervenant)>$nb_ligne) { $nb_ligne=count($tableau_intervenant); }
      $tableau_autre=explode("|",$liste_autre);
      if (count($tableau_autre)>$nb_ligne) { $nb_ligne=count($tableau_autre); }
      for ($i=1;$i<$nb_ligne;$i++) { $tableau_titulaire[$i]="&nbsp;"; }
      for ($i=count($tableau_decharge);$i<$nb_ligne;$i++) { $tableau_decharge[$i]="&nbsp;"; }
      for ($i=count($tableau_decloisonnement);$i<$nb_ligne;$i++) { $tableau_decloisonnement[$i]="&nbsp;"; }
      for ($i=count($tableau_atsem);$i<$nb_ligne;$i++) { $tableau_atsem[$i]="&nbsp;"; }
      for ($i=count($tableau_intervenant);$i<$nb_ligne;$i++) { $tableau_intervenant[$i]="&nbsp;"; }
      for ($i=count($tableau_autre);$i<$nb_ligne;$i++) { $tableau_autre[$i]="&nbsp;"; }

      $msg='<table id="listing_profs" class="display" cellpadding=0 cellspacing=0 style="width:100%;margin-top:10px;">';
      $msg=$msg.'<tr><th style="width:17%;" class="centre">'.$Langue['LST_TITULAIRE'].'</th>';
      $msg=$msg.'<th style="width:16%;" class="centre">'.$Langue['LST_DECHARGES'].'</th>';
      $msg=$msg.'<th style="width:17%;" class="centre">'.$Langue['LST_DECLOISONNEMENTS'].'</th>';
      $msg=$msg.'<th style="width:17%;" class="centre">'.$Langue['LST_ATSEM'].'</th>';
      $msg=$msg.'<th style="width:16%;" class="centre">'.$Langue['LST_INTERVENANTS_EXTERIEURS'].'</th>';
      $msg=$msg.'<th style="width:17%;" class="centre">'.$Langue['LST_AUTRES_INTERVENANTS'].'</th></tr>';
      $ligne="even";
      for ($i=0;$i<$nb_ligne;$i++)
      {
        $msg=$msg.'<tr class="'.$ligne.'"><td style="width:17%;" class="centre">'.$tableau_titulaire[$i].'</td>';
        $msg=$msg.'<td style="width:16%;" class="centre">'.$tableau_decharge[$i].'</td>';
        $msg=$msg.'<td style="width:17%;" class="centre">'.$tableau_decloisonnement[$i].'</td>';
        $msg=$msg.'<td style="width:17%;" class="centre">'.$tableau_atsem[$i].'</td>';
        $msg=$msg.'<td style="width:16%;" class="centre">'.$tableau_intervenant[$i].'</td>';
        $msg=$msg.'<td style="width:17%;" class="centre">'.$tableau_autre[$i].'</td></tr>';
        if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
      }
      $msg=$msg."</table>";
      $tpl->set_var("LISTE_PERSONNEL",$msg);
      $tpl->set_var("NIVEAUX",str_replace("|",", ",$liste_niveaux));
    }
    // Affichage de la liste des élèves
    $msg='<table id="listing_eleves" class="display" cellpadding=0 cellspacing=0 style="width:100%;margin-top:10px;">';
    $msg=$msg.'<tr><th style="width:34%;" class="centre">'.$Langue['LST_CLASSE_ELEVES'].'</th>';
    $msg=$msg.'<th style="width:18%;" class="centre">'.$Langue['LST_CLASSE_SEXE'].'</th>';
    $msg=$msg.'<th style="width:18%;" class="centre">'.$Langue['LST_CLASSE_NAISSANCE'].'</th>';
    $msg=$msg.'<th style="width:15%;" class="centre">'.$Langue['LST_CLASSE_NIVEAUX'].'</th>';
    $msg=$msg.'<th style="width:15%;" class="centre">'.$Langue['LST_CLASSE_REDOUBLEMENT'].'</th></tr>';
    if ($_GET['option_liste']=="2") { $where="(eleves.date_sortie='0000-00-00' OR eleves.date_sortie>'".date("Y-m-d")."') AND"; } else { $where=""; }
    switch ($_GET['type_liste'])
    {
      case "A": $req=mysql_query("SELECT eleves_classes.*, eleves.*, listes.* FROM `eleves_classes`,`eleves`,`listes` WHERE  $where eleves_classes.id_classe='".$id_classe."' AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_niveau=listes.id ORDER BY eleves.nom ".$_GET['ordre_liste'].", eleves.prenom ".$_GET['ordre_liste']); break;
      case "C": $req=mysql_query("SELECT eleves_classes.*, eleves.*, listes.* FROM `eleves_classes`,`eleves`,`listes` WHERE  $where eleves_classes.id_classe='".$id_classe."' AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_niveau=listes.id ORDER BY eleves.date_naissance ".$_GET['ordre_liste'].", eleves.nom ASC, eleves.prenom ASC"); break;
      case "N": $req=mysql_query("SELECT eleves_classes.*, eleves.*, listes.* FROM `eleves_classes`,`eleves`,`listes` WHERE  $where eleves_classes.id_classe='".$id_classe."' AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_niveau=listes.id ORDER BY listes.ordre ".$_GET['ordre_liste'].", eleves.nom ASC, eleves.prenom ASC"); break;
      case "S":
        if ($_GET['ordre_liste']=="ASC") { $_GET['ordre_liste']=="DESC"; } else { $_GET['ordre_liste']=="ASC"; }
        $req=mysql_query("SELECT eleves_classes.*, eleves.*, listes.* FROM `eleves_classes`,`eleves`,`listes` WHERE  eleves_classes.id_classe='".$id_classe."' AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_niveau=listes.id ORDER BY eleves.sexe ".$_GET['ordre_liste'].", eleves.nom ASC, eleves.prenom ASC");
        break;
    }
    $ligne="even";
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      if (mysql_result($req,$i-1,"eleves.date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"eleves.date_sortie")>=date("Y-m-d"))
      {
        $msg=$msg.'<tr class="'.$ligne.'">';
      }
      else
      {
        $msg=$msg.'<tr class="sorti">';
      }
      $msg=$msg.'<td style="width:34%;" class="gauche">'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</td>';
      $msg=$msg.'<td style="width:18%;" class="centre">'.$liste_choix['sexe'][mysql_result($req,$i-1,'eleves.sexe')].'</td>';
      $msg=$msg.'<td style="width:18%;" class="centre">'.Date_Convertir(mysql_result($req,$i-1,'eleves.date_naissance'),"Y-m-d",$Format_Date_PHP).'</td>';
      $msg=$msg.'<td style="width:15%;" class="centre">'.mysql_result($req,$i-1,'listes.intitule').'</td>';
      if (mysql_result($req,$i-1,'eleves_classes.redoublement')=="1")
      {
        $msg=$msg.'<td style="width:15%;" class="centre">Oui</td>';
      }
      else
      {
        $msg=$msg.'<td style="width:15%;" class="centre">&nbsp;</td>';
      }
      $msg=$msg.'</tr>';
      if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
    }
    $msg=$msg.'</table>';

    if ($_GET['document2']=="1")
    {
      $tpl->set_var("LISTE_ELEVES",$msg);
      $contenu_html=$tpl->parse('liste_bloc','formulaire',true);
    }
    else
    {
      $req=mysql_query("SELECT * FROM `classes` WHERE id='$id_classe'");
      $contenu_html='<div class="titre_page">'.$Langue['LBL_IMPRESSION_LISTE_ELEVES'].' '.mysql_result($req,0,'nom_classe').'</div><br /><br />'.$msg;
    }
  }
  include "commun/impression.php";
?>

