  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
	<div class="portlet-content">
    <?php
	  $date=date("Y-m-d");
	  switch ($param[0])
	  {
		case "J": $req=mysql_query("SELECT * FROM `devoirs` WHERE id_classe='".$_SESSION['id_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND date_faire='$date' AND date_donnee<='$date'"); break;
		case "P": $req=mysql_query("SELECT * FROM `devoirs` WHERE id_classe='".$_SESSION['id_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND date_faire>'$date' AND date_donnee<='$date'"); break;
	  }
	  if (mysql_num_rows($req)!="")
	  {	
		$date_faire=mysql_result($req,0,'date_faire');
	    $ligne="even";
	    echo '<div class="sous_titre_page">'.$Langue['LBL_DEVOIRS_A_FAIRE'].' '.Date_Convertir(mysql_result($req,0,'date_faire'),"Y-m-d",$Format_Date_PHP).'</div>';
        echo '<table class="display" cellpadding=0 cellspacing=0 style="width:100%;" class="margin10_haut"><thead><tr>';
		echo '<th class="ui-state-default textcentre" style="width:14%">'.$Langue['LBL_DEVOIRS_DONNES'].'</th><th class="ui-state-default textcentre" style="width:14%">'.$Langue['LBL_DEVOIRS_MATIERE'].'</th><th class="ui-state-default textcentre" style="width:72%">'.$Langue['LBL_DEVOIRS_TRAVAIL'].'</th></tr>';
		echo '</thead><tbody>';
		for ($i=1;$i<=mysql_num_rows($req);$i++)
		{
		  if ($date_faire==mysql_result($req,$i-1,'date_faire'))
		  {
			echo '<tr class="'.$ligne.'"><td class="textcentre" style="width:14%" valign=top>'.Date_Convertir(mysql_result($req,$i-1,'date_donnee'),"Y-m-d",$Format_Date_PHP).'</td>';
			$req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'id_matiere')."'");
			if (mysql_num_rows($req2)!="")
			{
			  $valeur=mysql_result($req2,0,'intitule');
			}
			else
			{
			  $valeur=$liste_choix['matieres_cj'][mysql_result($req,$i-1,'devoirs.id_matiere')];
			}
			echo '<td class="textcentre" style="width:14%" valign=top>'.$valeur.'</td>';
			echo '<td class="textgauche" style="width:72%" valign=top><ul class="explic marge10_gauche" style="padding:0px;margin-top:0px;margin-bottom:0px;"><li>'.str_replace("\n","</li><li>",mysql_result($req,$i-1,'contenu')).'</li></ul></td></tr>';
			if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
		  }
		}
		echo '</tbody></table>';
	  }
	  else
	  {
	    echo '<div class="textcentre"><strong>'.$Langue['LBL_DEVOIRS_AUCUN'].'</strong></div>';
	  }	
	?>
    </div>
  </div>
