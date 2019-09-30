<?php
  switch ($_GET['document'])
  {
    case "1":
      $id_mois=$_GET['id_mois'];
      include "absences/index_impression.php";
      break;
    case "2":
      $contenu_html="<style type='text/css'>";
      $contenu_html=$contenu_html."p { margin:0px; padding:0px; padding-top:10px;padding-bottom:10px; }";
      $contenu_html=$contenu_html."</style>";
      $contenu_html=$contenu_html.'<font style="font-face:Arial;font-size:48px;text-align:center;width:100%">';
      if ($_GET['orientation']=="P")
      {
        $contenu_html=$contenu_html.'<br><br><br><table align=center cellpadding=0 cellspacing=0 style="width:100%;"><tr><td style="text-align:center"><font style="font-face:Arial;font-size:48px;text-align:center;width:100%"><b>'.$Langue['LBL_IMPRESSION_REGISTRE']."</b><br>-:-:-:-:-:-:-</font></td></tr></table><br><br><br>";
      }
      else
      {
        $contenu_html=$contenu_html.'<br><table align=center cellpadding=0 cellspacing=0 style="width:100%;"><tr><td style="text-align:center"><font style="font-face:Arial;font-size:48px;text-align:center;width:100%"><b>'.$Langue['LBL_IMPRESSION_REGISTRE'].'</b><br>-:-:-:-:-:-:-</font></td></tr></table><br>';
      }
      $contenu_html=$contenu_html.'<table align=center cellpadding=0 cellspacing=0 style="width:80%;border:1px solid #000000;font-face:Arial;font-size:18px;text-align:center;padding:10px;background-color:#DADADA;">';
      $annee=$_SESSION['annee_scolaire']+1;
      // Affichage de l'année scolaire
      $contenu_html=$contenu_html.'<tr><td colspan=2 style="text-align:center"><p>&nbsp;</p></td></tr>';
      if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
      {
        $contenu_html=$contenu_html.'<tr><td colspan=2 style="text-align:center"><p><b>'.$Langue['LBL_IMPRESSION_ANNEE_SCOL'].' '.$_SESSION['annee_scolaire'].'</b><br>-:-:-:-:-:-:-</p></td></tr>';
	  }
	  else
      {
        $contenu_html=$contenu_html.'<tr><td colspan=2 style="text-align:center"><p><b>'.$Langue['LBL_IMPRESSION_ANNEE_SCOL'].' '.$_SESSION['annee_scolaire'].' - '.$annee.'</b><br>-:-:-:-:-:-:-</p></td></tr>';
	  }
      $contenu_html=$contenu_html.'<tr><td colspan=2 style="text-align:center"><p>&nbsp;</p></td></tr>';

      // Affichage du nom de l'enseignant
      $req=mysql_query("SELECT profs.*, classes_profs.* FROM `profs`, `classes_profs` WHERE classes_profs.id_classe='".$_SESSION['id_classe_cours']."' AND classes_profs.id_prof=profs.id AND classes_profs.type='T'");
      $contenu_html=$contenu_html.'<tr><td class="textdroite" style="width:30%;"><p>'.$Langue['LBL_IMPRESSION_CLASSE'].'&nbsp;:&nbsp;</p></td><td style="width:70%;text-align:left;"><p><b>'.$liste_choix['civilite_long'][mysql_result($req,0,'profs.civilite')].' '.mysql_result($req,0,'profs.nom').'</b></p></td></tr>';
      $contenu_html=$contenu_html.'<tr><td colspan=2 style="text-align:center"><p>&nbsp;</p></td></tr>';

      // Affichage des niveaux
      $req=mysql_query("SELECT listes.*, classes_niveaux.* FROM `classes_niveaux`, `listes` WHERE classes_niveaux.id_classe='".$_SESSION['id_classe_cours']."' AND classes_niveaux.id_niveau=listes.id AND listes.nom_liste='niveaux'");
      $niveau="";
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        $niveau=$niveau.mysql_result($req,$i-1,'intitule').', ';
      }
      $niveau=substr($niveau,0,strlen($niveau)-2);
      $contenu_html=$contenu_html.'<tr><td class="textdroite" style="width:30%;"><p>'.$Langue['LBL_IMPRESSION_NIVEAUX'].'&nbsp;:&nbsp;</p></td><td style="width:70%;text-align:left;"><p><b>'.$niveau.'</b></p></td></tr>';
      $contenu_html=$contenu_html.'<tr><td colspan=2 style="text-align:center"><p>&nbsp;</p></td></tr>';

      // Affichage de l'établissement
      $contenu_html=$contenu_html.'<tr><td class="textdroite" style="width:30%;vertical-align:top;"><p>'.$Langue['LBL_IMPRESSION_ETABLISSEMENT'].'&nbsp;:&nbsp;</p></td><td style="width:70%;text-align:left;vertical-align:top;"><p><b>'.$gestclasse_config_plus['nom'].'</b></p><p><b>'.str_replace("<br>","</b></p><p><b>",$gestclasse_config_plus['adresse']).'</b></p></td></tr>';
      $contenu_html=$contenu_html.'<tr><td colspan=2 style="text-align:center"><p>&nbsp;</p></td></tr>';
      $contenu_html=$contenu_html.'</table></font>';
      break;
    case "3":
      include "absences/statistiques_impression.php";
      break;
  }
  include "commun/impression.php";
?>

