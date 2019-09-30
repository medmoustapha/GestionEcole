<?php
  $msg='';
  switch ($_SESSION['type_util'])
  {
    case "D":
      $req_ann=mysql_query("SELECT * FROM `eleves` WHERE date_naissance LIKE '%".date("-m-d")."' AND (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."') ORDER BY nom ASC, prenom ASC");
      if (mysql_num_rows($req_ann)!="")
      {
        for ($i=1;$i<=mysql_num_rows($req_ann);$i++)
        {
          if ($i<>"1")
          {
            if ($i<mysql_num_rows($req_ann))
            {
              $msg=$msg.', <strong>'.mysql_result($req_ann,$i-1,'prenom').' '.mysql_result($req_ann,$i-1,'nom').'</strong>';
            }
            else
            {
              $msg=$msg.' '.$Langue['LBL_ANNIVERSAIRE_ET'].' <strong>'.mysql_result($req_ann,$i-1,'prenom').' '.mysql_result($req_ann,$i-1,'nom').'</strong>';
            }
          }
          else
          {
            $msg=$msg.'<strong>'.mysql_result($req_ann,$i-1,'prenom').' '.mysql_result($req_ann,$i-1,'nom').'</strong>';
          }
        }
        $msg=$Langue['LBL_ANNIVERSAIRE1']." ".$msg.".";
      }
      break;
    case "P":
      $req_ann=mysql_query("SELECT classes_profs.*, classes.*, eleves_classes.*, eleves.* FROM `classes_profs`,`classes`,`eleves_classes`,`eleves` WHERE eleves.date_naissance LIKE '%".date("-m-d")."' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".date("Y-m-d")."') AND eleves.id=eleves_classes.id_eleve AND eleves_classes.id_classe=classes.id AND classes.annee='".$_SESSION['annee_scolaire']."' AND classes.id=classes_profs.id_classe AND classes_profs.id_prof='".$_SESSION['id_util']."' ORDER BY eleves.nom ASC, eleves.prenom ASC");
      if (mysql_num_rows($req_ann)!="")
      {
        for ($i=1;$i<=mysql_num_rows($req_ann);$i++)
        {
          if ($i<>"1")
          {
            if ($i<mysql_num_rows($req_ann))
            {
              $msg=$msg.', <strong>'.mysql_result($req_ann,$i-1,'prenom').' '.mysql_result($req_ann,$i-1,'nom').'</strong>';
            }
            else
            {
              $msg=$msg.' '.$Langue['LBL_ANNIVERSAIRE_ET'].' <strong>'.mysql_result($req_ann,$i-1,'prenom').' '.mysql_result($req_ann,$i-1,'nom').'</strong>';
            }
          }
          else
          {
            $msg=$msg.'<strong>'.mysql_result($req_ann,$i-1,'prenom').' '.mysql_result($req_ann,$i-1,'nom').'</strong>';
          }
        }
        $msg=$Langue['LBL_ANNIVERSAIRE1']." ".$msg.".";
      }
      break;
    case "E":
      $req_ann=mysql_query("SELECT * FROM `eleves` WHERE date_naissance LIKE '%".date("-m-d")."' AND id='".$_SESSION['id_util']."'");
      if (mysql_num_rows($req_ann)!="")
      {
        $msg=$Langue['LBL_ANNIVERSAIRE1']." <strong>".mysql_result($req_ann,0,'eleves.prenom').' '.mysql_result($req_ann,0,'eleves.nom')."</strong>. ".$Langue['LBL_ANNIVERSAIRE2'];
      }
      break;
  }
  if ($msg!="")
  {
    echo '<div class="ui-widget" style="float:center;min-width:'.$larg.'px"><div class="ui-state-highlight ui-corner-all margin10_bas marge10_tout textcentre">'.$msg.'</div></div>';
  }
?>