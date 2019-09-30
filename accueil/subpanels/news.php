  <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem_<?php echo $i_panneau; ?>">
		<div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
      <div class="floatdroite">
        <?php if ($_SESSION['type_util']!="E") { ?>
          <a href="#null" title="<?php echo $Langue['LBL_CREER_NEWS']; ?>" onClick="Ajouter_News()"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;
        <?php } ?>
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
      </div>
    </div>
    <?php
      $nb_par_page=$param[1];
      $cible=$param[0];
    ?>
		<div class="portlet-content">
		<?php
    switch ($_SESSION['type_util'])
    {
      case 'D':
		    $req=mysql_query("SELECT * FROM `accueil_news` WHERE id_prof='".$_SESSION['id_util']."' ORDER BY date DESC");
		    break;
      case 'P':
        switch ($cible)
        {
		      case "T": $req=mysql_query("SELECT * FROM `accueil_news` WHERE id_prof='".$_SESSION['id_util']."' OR ((cible='T' OR cible='P') AND date<='".date("Y-m-d")."') ORDER BY date DESC"); break;
		      case "P": $req=mysql_query("SELECT * FROM `accueil_news` WHERE id_prof='".$_SESSION['id_util']."' ORDER BY date DESC"); break;
		      case "D": $req=mysql_query("SELECT * FROM `accueil_news` WHERE (cible='T' OR cible='P') AND date<='".date("Y-m-d")."' ORDER BY date DESC"); break;
        }
		    break;
      case 'E':
		    $req=mysql_query("SELECT * FROM `profs` WHERE type='D' AND date_sortie='0000-00-00'");
		    $id_d=mysql_result($req,0,'id');
        switch ($cible)
        {
  		    case "T": $req=mysql_query("SELECT * FROM `accueil_news` WHERE (id_prof='".$_SESSION['titulaire_classe_cours']."' AND date<='".date("Y-m-d")."') OR (id_prof='$id_d' AND (cible='T' OR cible='A') AND date<='".date("Y-m-d")."') ORDER BY date DESC"); break;
  		    case "P": $req=mysql_query("SELECT * FROM `accueil_news` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND date<='".date("Y-m-d")."' ORDER BY date DESC"); break;
  		    case "D": $req=mysql_query("SELECT * FROM `accueil_news` WHERE id_prof='$id_d' AND (cible='T' OR cible='A') AND date<='".date("Y-m-d")."' ORDER BY date DESC"); break;
        }
		    break;
		}
		$nb=mysql_num_rows($req);
		$nb_page=ceil($nb/$nb_par_page);
?>
    <input type="hidden" id="nb_par_page_<?php echo $id_panneau; ?>" name="nb_par_page_<?php echo $id_panneau; ?>" value="<?php echo $nb_par_page; ?>">
    <input type="hidden" id="page_<?php echo $id_panneau; ?>" name="page_<?php echo $id_panneau; ?>" value="1">
    <input type="hidden" id="max_<?php echo $id_panneau; ?>" name="max_<?php echo $id_panneau; ?>" value="<?php echo $nb; ?>">
<?php
		for ($i=1;$i<=$nb;$i++)
		{
      if ($i<=$param[1]) { $style='style="visibility:visible;display:block;"'; } else { $style='style="visibility:hidden;display:none;"'; }
	  $nouveau="";
	  if (mysql_result($req,$i-1,'date')>$_SESSION['derniere_connexion']) { $nouveau=' <font color=#FF0000>('.$Langue['LBL_NEWS_NOUVEAU'].')</font>'; }
	  if (mysql_result($req,$i-1,'date')>date("Y-m-d")) { $nouveau=' <font color=#0000FF>('.$Langue['LBL_NEWS_A_PARAITRE'].')</font>'; }
      echo '<div '.$style.' id="news_'.$id_panneau.'_'.$i.'"><div class="ui-widget ui-widget-content ui-corner-all"><div class="ui-widget ui-widget-header ui-state-default ui-corner-all marge5_tout textgauche" style="float:none;margin-bottom:7px">'.mysql_result($req,$i-1,'titre').$nouveau;
      if (mysql_result($req,$i-1,'id_prof')==$_SESSION['id_util']) { echo '<div class="floatdroite"><a href="#null" onClick="Modif_News(\''.mysql_result($req,$i-1,'id').'\')" title="'.$Langue['LBL_MODIFIER_NEWS2'].'"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a href="#null" onClick="Supprimer_News(\''.mysql_result($req,$i-1,'id').'\')" title="'.$Langue['LBL_SUPPRIMER_NEWS2'].'"><img src="images/supprimer.png" width=12 height=12 border=0></a></div>'; }
      echo '</div>';
      echo '<div class="marge5_tout">'.str_replace("\n",'<br />',mysql_result($req,$i-1,'contenu')).'</div>';
      echo '<div class="marge5_tout textdroite font10">';
      $req2=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req,$i-1,'id_prof')."'");
      echo $Langue['LBL_NEWS_ECRIT_PAR']." ".$liste_choix['civilite_long'][mysql_result($req2,0,'civilite')]." ".mysql_result($req2,0,'nom').", ";
      if (mysql_result($req,$i-1,'date')<=date("Y-m-d")) { echo $Langue['LBL_NEWS_LE']." ".Date_Convertir(mysql_result($req,$i-1,'date'),"Y-m-d",$Format_Date_PHP); } else { echo $Langue['LBL_NEWS_LE2']." ".Date_Convertir(mysql_result($req,$i-1,'date'),"Y-m-d",$Format_Date_PHP); }
      echo '</div>';
      echo '</div><br /></div>';
    }
    echo '<div class="textcentre">';
    for($i=1;$i<=$nb_page;$i++)
    {
      if ($i=="1")
      {
        echo '<a id="lien_'.$id_panneau.'_'.$i.'" href="#null" class="fg-button ui-button ui-state-default ui-state-disabled" onClick="Accueil_Modif_Page_'.$id_panneau.'('.$i.')">&nbsp;'.$i.'&nbsp;</a>';
      }
      else
      {
        echo '<a id="lien_'.$id_panneau.'_'.$i.'" href="#null" class="fg-button ui-button ui-state-default" onClick="Accueil_Modif_Page_'.$id_panneau.'('.$i.')">&nbsp;'.$i.'&nbsp;</a>';
      }
    }
    echo '</div>';
		?>
    </div>
	</div>
<script language="Javascript">
function Accueil_Modif_Page_<?php echo $id_panneau; ?>(page)
{
  max=document.getElementById('max_<?php echo $id_panneau; ?>').value;
  page_actuelle=document.getElementById('page_<?php echo $id_panneau; ?>').value;
  nb_par_page=document.getElementById('nb_par_page_<?php echo $id_panneau; ?>').value;
  debut=(page_actuelle-1)*nb_par_page+1;
  fin=page_actuelle*nb_par_page;
  if (fin>max) { fin=max; }
  for(i=debut;i<=fin;i++)
  {
    document.getElementById('news_<?php echo $id_panneau; ?>_'+i).style.visibility="hidden";
    document.getElementById('news_<?php echo $id_panneau; ?>_'+i).style.display="none";
  }
  $('#lien_<?php echo $id_panneau; ?>_'+page_actuelle).removeClass("ui-state-disabled");
  debut=(page-1)*nb_par_page+1;
  fin=page*nb_par_page;
  if (fin>max) { fin=max; }
  for(i=debut;i<=fin;i++)
  {
    document.getElementById('news_<?php echo $id_panneau; ?>_'+i).style.visibility="visible";
    document.getElementById('news_<?php echo $id_panneau; ?>_'+i).style.display="block";
  }
  $('#lien_<?php echo $id_panneau; ?>_'+page).addClass("ui-state-disabled");
  document.getElementById('page_<?php echo $id_panneau; ?>').value=page;
}
</script>