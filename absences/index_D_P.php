<?php
  if (!isset($_SESSION['tableau_absences']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement		4 : Recherche
    $_SESSION['tableau_absences']="30|0|1|asc";
  }
  
  if (!isset($_SESSION['recherche_absence']))
  {
		$_SESSION['recherche_absence']='||';
  }
  
  $tableau_personnalisation=explode("|",$_SESSION['tableau_absences']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_absence']);
	
  if ($_SESSION['type_util']!="D")
  {
    $req=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".$_SESSION['id_classe_cours']."' AND id_prof='".$_SESSION['id_util']."'");
    if (mysql_num_rows($req)!="")
    {
      $type_user_pour_classe=mysql_result($req,0,'type');
    }
  }
  else
  {
    $type_user_pour_classe="T";
  }

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $msg='<select name=mois_s id=mois_s class="text ui-widget-content ui-corner-all">';
	  for ($i=1;$i<=12;$i++)
	  {
		$msg=$msg.'<option value="'.$i.'"';
		if ($i==$_SESSION['mois_en_cours']) { $msg=$msg.' SELECTED'; $annee_r=$_SESSION['annee_scolaire']; }
		$msg=$msg.'>'.$liste_choix['mois'][$i].' '.$_SESSION['annee_scolaire'].'</option>';
	  }
	  $msg=$msg.'</select>';
  }
  else
  {
	  $msg='<select name=mois_s id=mois_s class="text ui-widget-content ui-corner-all">';
	  for ($i=$gestclasse_config_plus['mois_annee_scolaire']+1;$i<=12;$i++)
	  {
		$msg=$msg.'<option value="'.$i.'"';
		if ($i==$_SESSION['mois_en_cours']) { $msg=$msg.' SELECTED'; $annee_r=$_SESSION['annee_scolaire']; }
		$msg=$msg.'>'.$liste_choix['mois'][$i].' '.$_SESSION['annee_scolaire'].'</option>';
	  }
	  $annee=$_SESSION['annee_scolaire']+1;
	  for ($i=1;$i<=$gestclasse_config_plus['mois_annee_scolaire'];$i++)
	  {
		$msg=$msg.'<option value="'.$i.'"';
		if ($i==$_SESSION['mois_en_cours']) { $msg=$msg.' SELECTED'; $annee_r=$annee; }
		$msg=$msg.'>'.$liste_choix['mois'][$i].' '.$annee.'</option>';
	  }
	  $msg=$msg.'</select>';
  }
	
  $nb_jour=date("t",mktime(0,0,0,$_SESSION['mois_en_cours'],1,$annee_r));
	$tableau_recherche['absence']['justificatif']['recherche']=$nb_jour+2;
	$tableau_recherche['absence']['motif']['recherche']=$nb_jour+3;
	
	$tableau_recherche['absence']['eleve']['valeur_recherche']=$tableau_recherche2[0];
	$tableau_recherche['absence']['justificatif']['valeur_recherche']=$tableau_recherche2[1];
	$tableau_recherche['absence']['motif']['valeur_recherche']=$tableau_recherche2[2];
?>
<ul id="Menu_Absences" class="contextMenu">
    <li class="matin">
        <a href="#matin"><?php echo $Langue['LBL_MENU_MATIN']; ?></a>
    </li>
    <li class="am">
        <a href="#am"><?php echo $Langue['LBL_MENU_AM']; ?></a>
    </li>
    <li class="journee">
        <a href="#journee"><?php echo $Langue['LBL_MENU_ABSENT_JOURNEE']; ?></a>
    </li>
    <li class="separator">
        <a href="#present"><?php echo $Langue['LBL_MENU_PRESENT_JOURNEE']; ?></a>
    </li>
    <li class="justificatif separator">
        <a href="#justificatif"><?php echo $Langue['LBL_MENU_CREER_JUSTIFICATIF']; ?></a>
    </li>
    <li class="quit separator">
        <a href="#fermer"><?php echo $Langue['LBL_MENU_FERMER']; ?></a>
    </li>
</ul>
<div class="titre_page"><?php echo $Langue['LBL_REGISTRE']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<table cellpadding=0 cellspacing=0 style="width:100%" border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <button id="Justificatif"><?php echo $Langue['BTN_CREER_JUSTIFICATIF']; ?></button>&nbsp;<button id="Rechercher_Liste"><?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?></button>&nbsp;<button id="Imprimer"><?php echo $Langue['BTN_IMPRIMER']; ?></button>
  </td>
  <td class="droite" valign=middle nowrap>
    <div class="ui-widget ui-state-default ui-corner-right floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_MOIS']; ?> : <?php echo $msg; ?></div>
    <?php if ($_SESSION['type_util']=="D") { ?>
      <div class="ui-widget ui-state-default floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_CLASSE']; ?> : <?php echo Liste_Classes("classes_s",'form',$_SESSION['annee_scolaire'],$_SESSION['id_classe_cours'],'',true); ?></div>
      <div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_ANNEE_SCOLAIRE_COURS']; ?> : <?php echo Liste_Annee("annee_s",'form',$_SESSION['annee_scolaire']); ?></div>
    <?php } else { ?>
      <div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_CLASSE']; ?> : <?php echo Liste_Classes("classes_s",'form',$_SESSION['annee_scolaire'],$_SESSION['id_classe_cours'],$_SESSION['id_util'],false); ?></div>
    <?php } ?>
  </td>
</tr>
</table>

<div class="ui-widget marge10_bas" id="affiche_recherche_absence" style="visibility:hidden;display:none;"><div class="contour_recherche ui-corner-all">
<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
  <td class="textgauche" width=100%>
		<div class="titre_recherche"><?php echo $Langue['LBL_RECHERCHE_CIBLEE']; ?></div>
	</td>
</tr>
<tr>
  <td class="textgauche" width=100%>
			<div class="recherche"><label class="label_recherche"><?php echo $Langue['LBL_ELEVE']; ?> :</label><label class="label_recherche_champ"><?php echo Recherche_Cle($tableau_recherche['absence']['eleve']); ?></label></div>
			<div class="recherche"><label class="label_recherche"><?php echo $Langue['LBL_TYPE_JUSTIFICATIF']; ?> :</label><label class="label_recherche_champ"><?php echo Recherche_Cle($tableau_recherche['absence']['justificatif']); ?></label></div>
			<div class="recherche"><label class="label_recherche"><?php echo $Langue['LBL_MOTIF']; ?> :</label><label class="label_recherche_champ"><?php echo Recherche_Cle($tableau_recherche['absence']['motif']); ?></label></div>
	</td>
</tr>
<tr>
  <td class="textdroite" width=100%>
		<button tabindex=105 id="vider_absence" name="vider_absence"><?php echo $Langue['BTN_VIDER']; ?></button>&nbsp;<button tabindex=104 id="rechercher_absence" name="rechercher_absence"><?php echo $Langue['BTN_RECHERCHER']; ?></button>
	</td>
</tr>
</table>
</div></div>

<?php
  // On vérifie que le titulaire utilise la fonctionnalité
  if (strpos($gestclasse_config_plus['onglet_P'],"absences")===false)
  {
    echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_PAS_REGISTRE'].'</strong></div></div>';
  }
  else
  {
?>
<!-- ************************ -->
<!-- * Tableau des absences * -->
<!-- ************************ -->
<div id="listview">
<table id="listing_donnees_absences" class="display" cellpadding=0 cellspacing=0>
<thead>
<tr>
<th align=center><?php echo $Langue['LST_NUMERO_COLONNE']; ?></th>
<th align=center><?php echo $Langue['LBL_ELEVES']; ?></th>
<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=$_SESSION['annee_scolaire'];
  }
  else
  {
    if ($_SESSION['mois_en_cours']<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=$_SESSION['annee_scolaire']+1; } else { $annee=$_SESSION['annee_scolaire']; }
  }
  $nb_jour=date("t",mktime(0,0,0,$_SESSION['mois_en_cours'],1,$annee));
  $width='{ "sWidth": "50px", "bSortable": false },{ "sWidth": "250px", "bSortable": false },';
	$jour_debut=date("w",mktime(0,0,0,$_SESSION['mois_en_cours'],1,$annee));
	$jour=$jour_debut;
 	if ($_SESSION['mois_en_cours']<9) { $moisi="0".$_SESSION['mois_en_cours']; } else { $moisi=$_SESSION['mois_en_cours']; }
  for ($i=1;$i<=$nb_jour;$i++)
  {
    echo '<th class="centre">'.$liste_choix['jour_court'][$jour].' '.$i.'</th>';
    $width=$width.'{ "sWidth": "50px", "bSortable": false },';
    $nb_eleves_matin[$i]=0;
    $nb_eleves_am[$i]=0;
    $nb_presents_matin[$i]=0;
    $nb_presents_am[$i]=0;
		$tableau_matin[$i]=0;
		$tableau_am[$i]=0;
		if ($jour==0)
		{
		  $tableau_matin[$i]=1;
			$tableau_am[$i]=1;
		}
		else
		{
			if ($gestclasse_config_plus['matin'.$jour]=="0") { $tableau_matin[$i]=1; }
			if ($gestclasse_config_plus['am'.$jour]=="0") { $tableau_am[$i]=1; }
			if ($tableau_matin[$i]=="0" || $tableau_am[$i]=="0")
			{
				if ($i<=9) { $date_en_cours=$annee.'-'.$moisi.'-0'.$i; } else { $date_en_cours=$annee.'-'.$moisi.'-'.$i; }
				$req_vacances=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".$date_en_cours."' AND date_fin>='".$date_en_cours."' AND zone='".$gestclasse_config_plus['zone']."'");
				$req_feries=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".$date_en_cours."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
				if (mysql_num_rows($req_vacances)!="" || mysql_num_rows($req_feries)!="")
				{
					$tableau_matin[$i]=1;
					$tableau_am[$i]=1;
				}
			}
		}
		$jour++;
		if ($jour==7) { $jour=0; }
  }
  $width=$width.'{ "bVisible": false },{ "bVisible": false },{ "sWidth": "250px", "bSortable": false }';
?>
<th class="centre">&nbsp;</th>
<th class="centre">&nbsp;</th>
<th class="centre"><?php echo $Langue['LBL_JUSTIFICATIFS']; ?></th>
</tr>
</thead>
<tbody>
<?php
  $premier_jour_mois=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours'],1,$annee));
  $premier_jour_mois_suivant=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours']+1,1,$annee));
  if ($_SESSION['id_classe_cours']=="")
  {
    $req=mysql_query("SELECT eleves.* FROM `eleves` WHERE (eleves.date_entree<'$premier_jour_mois_suivant' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='$premier_jour_mois')) ORDER BY eleves.nom ASC, eleves.prenom ASC");
  }
  else
  {
    $req=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE (eleves.date_entree<'$premier_jour_mois_suivant' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='$premier_jour_mois')) AND eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_eleve=eleves.id ORDER BY eleves.nom ASC, eleves.prenom ASC");
  }

  $index_justificatif=Array();
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $liste_justificatif="";
    $liste_justificatif2="";
    $liste_justificatif3="";
    echo '<tr><td class="centre">'.$i.'</td>';
    echo '<td class="gauche" style="height:20px;"><a title="'.$Langue['SPAN_VOIR_ELEVE'].'" href="#null" onClick="Absences_Charge_Eleve(\''.mysql_result($req,$i-1,'id').'\')">'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom').'</a>';
    $de=mysql_result($req,$i-1,'date_entree');
    if (substr($de,0,7)==$annee.'-'.$moisi) { echo " (".$Langue['LBL_ARRIVE_LE']." ".Date_Convertir($de,"Y-m-d",$Format_Date_PHP).")"; }
    $ds=mysql_result($req,$i-1,'date_sortie');
    if (substr($ds,0,7)==$annee.'-'.$moisi) { echo " (".$Langue['LBL_SORTI_LE']." ".Date_Convertir($ds,"Y-m-d",$Format_Date_PHP).")"; }
    echo '</td>';
    $id_eleve=mysql_result($req,$i-1,'eleves.id');
		$jour=$jour_debut;
    for ($j=1;$j<=$nb_jour;$j++)
    {
//      unset($index_justificatif);
      $param_plus="";
      // Récupération des vacances scolaires et jours fériés

      if ($tableau_matin[$j]!="1" || $tableau_am[$j]!="1")
      // Si le jour n'est pas dimanche ou un jour de vacances
      {
			  if ($j<=9) { $date_en_cours=$annee.'-'.$moisi.'-0'.$j; } else { $date_en_cours=$annee.'-'.$moisi.'-'.$j; }
        // On regarde si l'élève est arrivé durant le mois
        if ($de>$date_en_cours)
        {
          echo '<td class="centre sorti">&nbsp;</td>';
        }
        else
        {
          // On regarde si l'élève est sorti durant le mois
          if ($ds!="0000-00-00" && $ds<=$date_en_cours)
          {
            echo '<td class="centre sorti">&nbsp;</td>';
          }
          else
          {
            // On vérifie que c'est un jour travaillé soit le matin ou l'après-midi
            if ($gestclasse_config_plus['matin'.$jour]=="1" || $gestclasse_config_plus['am'.$jour]=="1")
            {
              if ($gestclasse_config_plus['matin'.$jour]=="1") { $nb_eleves_matin[$j]=$nb_eleves_matin[$j]+1; }
              if ($gestclasse_config_plus['am'.$jour]=="1") { $nb_eleves_am[$j]=$nb_eleves_am[$j]+1; }
              if ($gestclasse_config_plus['matin'.$jour]=="0") { $nb_eleves_matin[$j]=-1; }
              if ($gestclasse_config_plus['am'.$jour]=="0") { $nb_eleves_am[$j]=-1; }
              // On recherche si l'élève est absent
              $req_absence=mysql_query("SELECT * FROM `absences` WHERE date='".$date_en_cours."' AND id_eleve='$id_eleve'");
              // Elève non absent
              if (mysql_num_rows($req_absence)=="")
              {
                echo '<td id="'.$id_eleve.'_'.$j.'" class="centre" style="padding:3px" onMouseOver="Absences_Creer_Menu(\''.$id_eleve.'_'.$j.'\')">&nbsp;</td>';
                if ($gestclasse_config_plus['matin'.$jour]=="1") { $nb_presents_matin[$j]=$nb_presents_matin[$j]+1; }
                if ($gestclasse_config_plus['am'.$jour]=="1") { $nb_presents_am[$j]=$nb_presents_am[$j]+1; }
              }
              else
              // Elève absent à un moment dans la journée
              {
							  $absenteisme_matin=mysql_result($req_absence,0,'matin');
								$absenteisme_am=mysql_result($req_absence,0,'apres_midi');
								$order=array("\r\n","\r","\n");
                $req1=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<'$date_en_cours' AND date_fin>'$date_en_cours'"); // Valide une absence si la date est comprise entre les deux dates d'un justificatif
                $req2=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin quand le justificatif est sur une journée
                $req3=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi quand le justificatif est sur une journée
                $req4=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours' AND heure_debut='M'"); // Valide une absence le matin ou la journée quand le justificatif commence le jour de l'absence
                $req5=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut='$date_en_cours' AND date_fin<>'$date_en_cours'"); // Valide une absence l'après-midi quand le justificatif commence le jour de l'absence
                $req6=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours'"); // Valide une absence le matin quand le justificatif se termine le jour de l'absence
                $req7=mysql_query("SELECT * FROM `absences_justificatifs` WHERE id_eleve='$id_eleve' AND date_debut<>'$date_en_cours' AND date_fin='$date_en_cours' AND heure_fin='S'"); // Valide une absence l'après-midi ou la journée quand le justificatif se termine le jour de l'absence
								$nb1=mysql_num_rows($req1);
								$nb2=mysql_num_rows($req2);
								$nb3=mysql_num_rows($req3);
								$nb4=mysql_num_rows($req4);
								$nb5=mysql_num_rows($req5);
								$nb6=mysql_num_rows($req6);
								$nb7=mysql_num_rows($req7);
                if ($nb1!="" || $nb2!="" || $nb4!="" || $nb6!="")
                {
                  $matin=true;
                  if ($nb1!="")
                  {
                    if (!in_array(mysql_result($req1,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req1,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req1,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req1,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req1,0,'date_debut'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_debut'][mysql_result($req1,0,'heure_debut')].' '.$Langue['LBL_TOOLTIP_AU_JUSTIFICATIF'].' '.Date_Convertir(mysql_result($req1,0,'date_fin'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_fin'][mysql_result($req1,0,'heure_fin')].'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req1,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req1,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req1,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req1,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req1,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req1,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req1,0,'type')].', '; }
                    }
                  }
                  if ($nb2!="")
                  {
                    if (!in_array(mysql_result($req2,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req2,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req2,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req2,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req2,0,'date_debut'),'Y-m-d',$Format_Date_PHP).'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req2,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req2,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req2,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req2,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req2,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req2,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req2,0,'type')].', '; }
                    }
                  }
                  if ($nb4!="")
                  {
                    if (!in_array(mysql_result($req4,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req4,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req4,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req4,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req4,0,'date_debut'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_debut'][mysql_result($req4,0,'heure_debut')].' '.$Langue['LBL_TOOLTIP_AU_JUSTIFICATIF'].' '.Date_Convertir(mysql_result($req4,0,'date_fin'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_fin'][mysql_result($req4,0,'heure_fin')].'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req4,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req4,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req4,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req4,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req4,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req4,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req4,0,'type')].', '; }
                    }
                  }
                  if ($nb6!="")
                  {
                    if (!in_array(mysql_result($req6,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req6,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req6,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req6,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req6,0,'date_debut'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_debut'][mysql_result($req6,0,'heure_debut')].' '.$Langue['LBL_TOOLTIP_AU_JUSTIFICATIF'].' '.Date_Convertir(mysql_result($req6,0,'date_fin'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_fin'][mysql_result($req6,0,'heure_fin')].'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req6,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req6,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req6,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req6,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req6,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req6,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req6,0,'type')].', '; }
                    }
                  }
                } else { $matin=false; }
                if ($nb1!="" || $nb3!="" || $nb5!="" || $nb7!="")
                {
                  $am=true;
                  if ($nb1!="")
                  {
                    if (!in_array(mysql_result($req1,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req1,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req1,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req1,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req1,0,'date_debut'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_debut'][mysql_result($req1,0,'heure_debut')].' '.$Langue['LBL_TOOLTIP_AU_JUSTIFICATIF'].' '.Date_Convertir(mysql_result($req1,0,'date_fin'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_fin'][mysql_result($req1,0,'heure_fin')].'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req1,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req1,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req1,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req1,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req1,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req1,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req1,0,'type')].', '; }
                    }
                  }
                  if ($nb3!="")
                  {
                    if (!in_array(mysql_result($req3,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req3,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req3,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req3,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req3,0,'date_debut'),'Y-m-d',$Format_Date_PHP).'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req3,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req3,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req3,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req3,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req3,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req3,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req3,0,'type')].', '; }
                    }
                  }
                  if ($nb5!="")
                  {
                    if (!in_array(mysql_result($req5,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req5,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req5,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req5,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req5,0,'date_debut'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_debut'][mysql_result($req5,0,'heure_debut')].' '.$Langue['LBL_TOOLTIP_AU_JUSTIFICATIF'].' '.Date_Convertir(mysql_result($req5,0,'date_fin'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_fin'][mysql_result($req5,0,'heure_fin')].'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req5,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req5,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req5,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req5,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req5,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req5,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req5,0,'type')].', '; }
                    }
                  }
                  if ($nb7!="")
                  {
                    if (!in_array(mysql_result($req7,0,'id'),$index_justificatif))
                    {
                      $index_justificatif[]=mysql_result($req7,0,'id');
                      if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") { $liste_justificatif3=$liste_justificatif3.mysql_result($req7,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req7,0,'type').','; $liste_justificatif=$liste_justificatif.'<a class="tooltipaff" onMouseOver="Absences_Voir_Tooltil(\''.Date_Convertir(mysql_result($req7,0,'date_debut'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_debut'][mysql_result($req7,0,'heure_debut')].' '.$Langue['LBL_TOOLTIP_AU_JUSTIFICATIF'].' '.Date_Convertir(mysql_result($req7,0,'date_fin'),'Y-m-d',$Format_Date_PHP).' '.$liste_choix['absence_fin'][mysql_result($req7,0,'heure_fin')].'\',\''.str_replace("'","\'",$liste_choix['type_justificatif'][mysql_result($req7,0,'type')]).'\',\''.str_replace($order,'<br>',str_replace('"','&quot;',str_replace("'","\'",mysql_result($req7,0,'motif')))).'\')" href=#null onClick="Absences_Modif_Justificatif(\''.mysql_result($req7,0,'id').'\')">'.$liste_choix['type_justificatif_abrege'][mysql_result($req7,0,'type')].'</a>, '; } else { $liste_justificatif3=$liste_justificatif3.mysql_result($req7,0,'motif').',';$liste_justificatif2=$liste_justificatif2.mysql_result($req7,0,'type').','; $liste_justificatif=$liste_justificatif.$liste_choix['type_justificatif_abrege'][mysql_result($req7,0,'type')].', '; }
                    }
                  }
                } else { $am=false; }
                // Absence le matin mais pas l'après-midi
                if ($absenteisme_matin=="1" && $absenteisme_am=="0")
                {
                  if ($gestclasse_config_plus['matin'.$jour]=="1")
                  {
                    if ($gestclasse_config_plus['am'.$jour]=="1") { $nb_presents_am[$j]=$nb_presents_am[$j]+1; }
                    echo '<td id="'.$id_eleve.'_'.$j.'" class="centre" style="padding:3px" onMouseOver="Absences_Creer_Menu(\''.$id_eleve.'_'.$j.'\')">';
                    if ($matin==true) { echo '<img src="images/excuse_matin.png" width=45 height=20 border=0></td>'; } else { echo '<img src="images/non_excuse_matin.png" width=45 height=20 border=0></td>'; }
                  }
                  else
                  {
                    echo '<td id="'.$id_eleve.'_'.$j.'" class="centre" style="padding:3px" onMouseOver="Absences_Creer_Menu(\''.$id_eleve.'_'.$j.'\')">&nbsp;</td>';
                  }
                }
                // Absence l'après-midi mais pas le matin
                if ($absenteisme_matin=="0" && $absenteisme_am=="1")
                {
                  if ($gestclasse_config_plus['am'.$jour]=="1")
                  {
                    if ($gestclasse_config_plus['matin'.$jour]=="1") { $nb_presents_matin[$j]=$nb_presents_matin[$j]+1; }
                    echo '<td id="'.$id_eleve.'_'.$j.'" class="centre" style="padding:3px" onMouseOver="Absences_Creer_Menu(\''.$id_eleve.'_'.$j.'\')">';
                    if ($am==true) { echo '<img src="images/excuse_am.png" width=45 height=20 border=0></td>'; } else { echo '<img src="images/non_excuse_am.png" width=45 height=20 border=0></td>'; }
                  }
                  else
                  {
                    echo '<td id="'.$id_eleve.'_'.$j.'" class="centre" style="padding:0px" onMouseOver="Absences_Creer_Menu(\''.$id_eleve.'_'.$j.'\')">&nbsp;</td>';
                  }
                }
                // Absence toute la journée
                if ($absenteisme_matin=="1" && $absenteisme_am=="1")
                {
                  if ($gestclasse_config_plus['matin'.$jour]=="1" && $gestclasse_config_plus['am'.$jour]=="1")
                  {
                    $image="excuse_journee";
                    if ($matin==false && $am==false) { $image="non_excuse_journee"; }
                    // Justificatif pour le matin mais pas l'après-midi
                    if ($matin==true && $am==false) { $image="excuse_journee_matin"; }
                    // Justificatif pour l'après-midi mais pas le matin
                    if ($matin==false && $am==true) { $image="excuse_journee_am"; }
                  }
                  if ($gestclasse_config_plus['matin'.$jour]=="1" && ($gestclasse_config_plus['am'.$jour]=="0" || $gestclasse_config_plus['am'.$jour]==""))
                  {
                    $image="excuse_matin";
                    if ($matin==false) { $image="non_excuse_matin"; }
                  }
                  if (($gestclasse_config_plus['matin'.$jour]=="" || $gestclasse_config_plus['matin'.$jour]=="0") && $gestclasse_config_plus['am'.$jour]=="1")
                  {
                    $image="excuse_am";
                    if ($am==false) { $image="non_excuse_am"; }
                  }
                  echo '<td id="'.$id_eleve.'_'.$j.'" class="centre" style="padding:3px" onMouseOver="Absences_Creer_Menu(\''.$id_eleve.'_'.$j.'\')"><img src="images/'.$image.'.png" width=45 height=20 border=0></td>';
                }
              }
            }
            else
            {
              $nb_eleves_matin[$j]=-1;
              $nb_eleves_am[$j]=-1;
              echo '<td class="centre sorti">&nbsp;</td>';
            }
          }
        }
      }
      else
      // Le jour est un dimanche, un jour férié ou un jour de vacances
      {
        echo '<td class="centre sorti">&nbsp;</td>';
        $nb_eleves_matin[$j]=-1;
        $nb_eleves_am[$j]=-1;
      }
			$jour++;
			if ($jour==7) { $jour=0; }
    }
    echo '<td class="gauche">'.substr($liste_justificatif2,0,strlen($liste_justificatif2)-1).'</td>';
    echo '<td class="gauche">'.substr($liste_justificatif3,0,strlen($liste_justificatif3)-1).'</td>';
    echo '<td class="gauche">'.substr($liste_justificatif,0,strlen($liste_justificatif)-2).'</td></tr>';
  }

  // Affichage du tableau de statistique
  $total_absence=0;
  $total_eleve=0;
  
  echo '<tr><td class="centre">&nbsp;</td><td class="gauche" style="height:20px"><strong>'.$Langue['LBL_MATIN_NB_ABSENTS'].'</strong></td>';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_matin[$i]!=-1)
    {
      $nb=$nb_eleves_matin[$i]-$nb_presents_matin[$i];
      $total_absence=$total_absence+$nb;
      $total_eleve=$total_eleve+$nb_eleves_matin[$i];
      echo '<td class="centre"><strong>'.$nb.'</strong></td>';
    }
    else
    {
      echo '<td class="centre sorti">&nbsp;</td>';
    }
  }
  echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
  echo '<tr><td class="centre">&nbsp;</td><td class="gauche" style="height:20px"><strong><u>'.$Langue['LBL_MATIN_NB_PRESENTS'].'</strong></td>';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_matin[$i]!=-1)
    {
      echo '<td class="centre"><strong>'.$nb_presents_matin[$i].'</strong></td>';
    }
    else
    {
      echo '<td class="centre sorti">&nbsp;</td>';
    }
  }
  echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
  echo '<tr><td class="centre">&nbsp;</td><td class="gauche" style="height:20px"><strong>'.$Langue['LBL_AM_NB_ABSENTS'].'</strong></td>';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_am[$i]!=-1)
    {
      $nb=$nb_eleves_am[$i]-$nb_presents_am[$i];
      $total_absence=$total_absence+$nb;
      $total_eleve=$total_eleve+$nb_eleves_am[$i];
      echo '<td class="centre"><strong>'.$nb.'</strong></td>';
    }
    else
    {
      echo '<td class="centre sorti">&nbsp;</td>';
    }
  }
  echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
  echo '<tr><td class="centre">&nbsp;</td><td class="gauche" style="height:20px"><strong>'.$Langue['LBL_AM_NB_PRESENTS'].'</strong></td>';
  for ($i=1;$i<=$nb_jour;$i++)
  {
    if ($nb_eleves_am[$i]!=-1)
    {
      echo '<td class="centre"><strong>'.$nb_presents_am[$i].'</strong></td>';
    }
    else
    {
      echo '<td class="centre sorti">&nbsp;</td>';
    }
  }
  echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
?>
</tbody>
</table>
<br />
<?php if ($total_eleve!=0) { ?>
  <div align=center><div class="ui-widget ui-widget-content ui-corner-all marge10_tout font16 textcentre" style="width:300px;"><strong><u><?php echo $Langue['LBL_TAUX_ABSENCE']; ?></u> : <?php echo number_format(100*$total_absence/$total_eleve,2,","," "); ?> %</strong></div></div>
<?php } else { ?>
  <div align=center><div class="ui-widget ui-widget-content ui-corner-all marge10_tout font16 textcentre" style="width:300px;"><strong><u><?php echo $Langue['LBL_TAUX_ABSENCE']; ?></u> : 0,00 %</strong></div></div>
<?php } ?>
</div>

<?php
/*******************************************/
/* Signature électronique par le directeur */
/*******************************************/
	if ($gestclasse_config_plus['signature_registre']=="1")
	{
		$est_sans_signature=true;
		$complement="";
		$adresse_plus="";
		$texte=$Langue['LBL_SIGNATURE'];
		if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
		  $annee=$_SESSION['annee_scolaire'];
		}
		else
		{
		  if ($_SESSION['mois_en_cours']<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=$_SESSION['annee_scolaire']+1; } else { $annee=$_SESSION['annee_scolaire']; }
		}
		$parametre="absences|".$_SESSION['mois_en_cours']."|".$annee."|".$_SESSION['id_classe_cours'];
		if ($_SESSION['type_util']=="D" && $_SESSION['id_classe_cours']!="")
		{
		  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		  {
			$dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours']+1,1,$_SESSION['annee_scolaire']));
		  }
		  else
		  {
			  if ($_SESSION['mois_en_cours']<=$gestclasse_config_plus['mois_annee_scolaire'])
			  {
				$dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours']+1,1,$_SESSION['annee_scolaire']+1));
			  }
			  else
			  {
				$dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$_SESSION['mois_en_cours']+1,1,$_SESSION['annee_scolaire']));
			  }
		  }
		  $premier_jour_du_mois_en_cours=date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
		  if ($dernier_jour_du_mois<=$premier_jour_du_mois_en_cours)
		  {
			echo '<div class="textdroite">';
			include "commun/signature_electronique.php";
			echo '</div>';
		  }
		}
		else
		{
		  if ($_SESSION['id_classe_cours']!="")
		  {
			$req=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
			if (mysql_num_rows($req)=="")
			{
			  $est_sans_signature=true;
			}
			else
			{
			  include "commun/signature_electronique.php";
			}
		  }
		}
	}
	else
	{
	  $est_sans_signature=true;
	}

 } ?>

<!-- ********************************** -->
<!-- * Fonctions Javascript et jQuery * -->
<!-- ********************************** -->
<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-registre-dappel.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-registre-dappel.html","Aide");
<?php } ?>
  });

<?php
  if (strpos($gestclasse_config_plus['onglet_P'],"absences")!==false)
  {
?>
    /* Création du tableau de données */
		longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
		page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
		colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
		ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
    var oTable_absence=$('#listing_donnees_absences').dataTable
    ({
      "bJQueryUI": true,
      "bProcessing": true,
			"sDom": '<"H"lr>t<"F"ip>',
      <?php if ($_SESSION['type_util']!="D") { ?>
        "aaSorting": [[ 1, "asc" ]],
        "bPaginate": false,
        "bLengthChange": false,
        "bInfo": false,
      <?php } else { ?>
        "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
        "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
        "sPaginationType": "full_numbers",
        "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
				"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
				"fnDrawCallback": function( oSettings ) 
				{
					var that = this;
					if ( oSettings.bSorted || oSettings.bFiltered )
					{
						this.$('td:first-child', {"filter":"applied"}).each( function (i) 
						{
							that.fnUpdate( i+1, this.parentNode, 0, false, false );
						} );
					}
					faire=false;
					colonne_index=oSettings.aaSorting[0][0];
					colonne_ordre=oSettings.aaSorting[0][1];
					if (longueur_tableau!=oSettings._iDisplayLength) { faire=true;longueur_tableau=oSettings._iDisplayLength; }
//					if (page_tableau!=oSettings._iDisplayStart) { faire=true;page_tableau=oSettings._iDisplayStart; }
					if (colonne_tableau!=colonne_index) { faire=true;colonne_tableau=colonne_index; }
					if (ordre_tableau!=colonne_ordre) { faire=true;ordre_tableau=colonne_ordre; }
					if (faire==true)
					{
						url = "index2.php";
						data = "module=users&action=save_perso&module_session=absences&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
						var request = $.ajax({type: "POST", url: url, data: data});
					}
				},
      <?php } ?>
      "bSort": false,
      "sScrollX": "100%",
      "aoColumns": [ <?php echo $width; ?> ],
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
    });
<?php if ($Sens_Ecriture=="ltr") { ?>
      var oFC = new FixedColumns( oTable_absence, { "iLeftColumns": 2,"sHeightMatch": "auto" } );
<?php } ?>

		$("#Rechercher_Liste").button();
		$("#Rechercher_Liste").click(function()
		{
			if (document.getElementById('affiche_recherche_absence').style.visibility=="hidden")
			{
				$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
				document.getElementById('affiche_recherche_absence').style.visibility="visible";
				document.getElementById('affiche_recherche_absence').style.display="block";
				$("#recherche_eleve").focus();
			}
			else
			{
				$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
				document.getElementById('affiche_recherche_absence').style.visibility="hidden";
				document.getElementById('affiche_recherche_absence').style.display="none";
			}
		});

		$("#rechercher_absence").button();
		$("#rechercher_absence").click(function()
		{
			recherche="";
<?php
			foreach ($tableau_recherche['absence'] AS $cle)
			{
				if (array_key_exists('recherche',$cle))
				{
					echo 'oTable_absence.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
					echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
				}
			}
?>
			url = "index2.php";
			data = "module=users&action=save_perso3&module_session=absence&recherche="+recherche;
			var request = $.ajax({type: "POST", url: url, data: data});
		});

		$("#vider_absence").button();
		$("#vider_absence").click(function()
		{
<?php
			foreach ($tableau_recherche['absence'] AS $cle)
			{
				if (array_key_exists('recherche',$cle))
				{
					echo '$("#recherche_'.$cle['nom'].'").val("");';
					echo 'oTable_absence.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				}
			}
?>
			url = "index2.php";
			data = "module=users&action=save_perso3&module_session=absence&recherche=|||";
			var request = $.ajax({type: "POST", url: url, data: data});
		});

	$("#rechercher_absence").click();
<?php if ($_SESSION['recherche_absence']!='||') { echo '$("#Rechercher_Liste").click();'; } ?>

    /* Création des boutons */
    $("#Imprimer").button({disabled:false});
<?php 
	if ($est_sans_signature==true) 
	{ 
?>  
      $("#Justificatif").button({disabled:false});
	  $("#Justificatif").click(function()
	  {
		Charge_Dialog("index2.php?module=absences&action=editview","<?php echo $Langue['LBL_CREER_JUSTIFICATIF']; ?>");
	  });
<?php 
	} 
	else 
	{ 
?>
      $("#Justificatif").button({disabled:true});
<?php 
	} 
?>
    $("#Imprimer").click(function()
    {
      Charge_Dialog("index2.php?module=absences&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
    });

<?php 
  } 
  else 
  { 
?>
    $("#Imprimer").button({disabled:true});
    $("#Justificatif").button({disabled:true});
<?php 
  } 
?>

  /* Choix de l'année en cours */
  $("#annee_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_annee.php";
     var data="annee_choisi="+$("#annee_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
  });

  /* Choix de la classe en cours */
<?php 
  if ($_SESSION['type_util']=="D") 
  { 
?>
    $("#classes_s").change(function()
    {
      Message_Chargement(1,1);
      var url="users/change_classe.php";
      var data="classe_choisie="+$("#classes_s").val();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
        $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
      });
    });
<?php 
  } 
  else 
  { 
?>
    $("#classes_s").change(function()
    {
      if ($("#classes_s").val()!="")
      {
        Message_Chargement(1,1);
        var url="users/change_classe.php";
        var data="classe_choisie="+$("#classes_s").val();
        var request = $.ajax({type: "POST", url: url, data: data});
        request.done(function(msg)
        {
          $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
          $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
        });
      }
    });
<?php 
    if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E") 
	{ 
?>
      $("#Imprimer").button({ disabled: false });
<?php 
	  if ($est_sans_signature==true) 
	  { 
?>  
         $("#Justificatif").button({ disabled: false });
<?php 
      } 
	  else 
	  { 
?>
         $("#Justificatif").button({ disabled: true });
<?php 
      } 
	} 
	else 
	{ 
?>
      $("#Imprimer").button({ disabled: true });
      $("#Justificatif").button({ disabled: true });
<?php 
    } 
  }
?>

  /* Choix du mois en cours */
  $("#mois_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_mois.php";
     var data="mois_choisi="+$("#mois_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
  });

  $( "#dialog:ui-dialog" ).dialog( "destroy" );

});

  function Absences_Modif_Absence(action,id)
  {
    if (action!="justificatif")
    {
      Message_Chargement(2,1);
      var url="index2.php";
      var data="module=absences&action=save_absence&periode="+action+"&id="+id;
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        Message_Chargement(1,1);
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
        $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
      });
    }
    else
    {
      Message_Chargement(1,1);
      Charge_Dialog("index2.php?module=absences&action=editview&id_a="+id,"<?php echo $Langue['LBL_CREER_JUSTIFICATIF']; ?>");
    }
  }
  
  function Absences_Modif_Justificatif(id)
  {
    <?php if ($est_sans_signature==true) { ?>  
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=absences&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_JUSTIFICATIF']; ?>");
	<?php } ?>
  }
  
  function Absences_Creer_Menu(id)
  {
    $("#"+id).contextMenu(
    {
      menu: "Menu_Absences"
    },
    function(action, el, pos)
    {
      var id=$(el).attr("id");
      if (action!="fermer")
      {
        Absences_Modif_Absence(action,id);
      }
    });
<?php
    if ($est_sans_signature==true) 
	{
      if ($_SESSION['type_util']!="D")
      {
        if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E")
        {
          echo '$("#"+id).enableContextMenu();';
        }
        else
        {
          echo '$("#"+id).disableContextMenu();';
        }
      }
      else
      {
        echo '$("#"+id).enableContextMenu();';
      }
    }
	else
	{
      echo '$("#"+id).disableContextMenu();';
	}
?>
  }
  
  function Absences_Charge_Eleve(id)
  {
    Message_Chargement(1,1);
    Charge_Dialog("index2.php?module=eleves&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
  }

function Absences_Voir_Tooltil(date,type,motif)
{
  msg="<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_DATE_JUSTIFICATIF']; ?></u> : "+date+"</p>";
  msg=msg+"<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_TYPE_JUSTIFICATIF']; ?></u> : "+type+"</p>";
  msg = msg+"<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_MOTIF_JUSTIFICATIF']; ?></u> : "+motif+"</p>";
  $(".tooltipaff").tipTip(
  {
    defaultPosition:'top',
	direction:'<?php echo $Sens_Ecriture; ?>',
	delay:100,
	maxWidth: 'auto',
	content:msg
  });
}
</script>
