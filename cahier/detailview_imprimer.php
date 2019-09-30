<?php
  Param_Utilisateur($_SESSION['id_util'],$_SESSION['annee_scolaire']);
  if (isset($_GET['date_en_cours'])) { $date_en_cours=Date_Convertir($_GET['date_en_cours'],$Format_Date_PHP,"Y-m-d"); } else { $date_en_cours=$_SESSION['date_en_cours']; }
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $jour_max=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $an=$_SESSION['annee_scolaire']+1;
	  $jour_max=$an.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  
  // On recherche le nombre de classes / niveaux faisant l'objet du séance dans le cahier-journal
  $req_niveau=mysql_query("SELECT listes.*,cahierjournal.*,classes_profs.* FROM `classes_profs`,`listes`,`cahierjournal` WHERE cahierjournal.id_prof='".$_SESSION['id_util']."' AND cahierjournal.date='".$date_en_cours."' AND cahierjournal.id_niveau=listes.id AND classes_profs.id_classe=cahierjournal.id_classe AND classes_profs.id_prof=cahierjournal.id_prof ORDER BY classes_profs.type DESC, listes.ordre ASC");
  $nb_niveau=0;
  $niveau=Array();
  $classe=Array();
  for ($i=1;$i<=mysql_num_rows($req_niveau);$i++)
  {
    $trouve=false;
    for ($j=1;$j<=count($niveau);$j++)
    {
      if ($niveau[$j-1]==mysql_result($req_niveau,$i-1,'cahierjournal.id_niveau') && $classe[$j-1]==mysql_result($req_niveau,$i-1,'cahierjournal.id_classe'))
      {
        $trouve=true;
      }
    }
    if ($trouve==false)
    {
      $niveau[]=mysql_result($req_niveau,$i-1,'cahierjournal.id_niveau');
      $classe[]=mysql_result($req_niveau,$i-1,'cahierjournal.id_classe');
      $nb_niveau++;
      $actuel[]=1;
    }
  }
?>
<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_editview name=Detail_Impression>
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Button" id="Imprimer_Detail" name="Imprimer_Detail" value="<?php echo $Langue['BTN_IMPRIMER']; ?>">&nbsp;
    <input type="Button" id="Annuler_Impression" name="Annuler_Impression" value="<?php echo $Langue['BTN_ANNULER2']; ?>">&nbsp;
  </td>
  <td class="droite">
	  <button id="aide_fenetre" name="aide_fenetre"><?php echo $Langue['BTN_AIDE']; ?></button>
	</td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
  <td class="textgauche marge5_droite" style="width:50%" valign=top>
    <div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_IMPRESSION_DOCUMENTS']; ?></div>
    <table cellspacing=0 cellpadding=0 class="tableau_editview2" style="width:100%">
    <tr>
      <td class="droite" style="width:6%"><input type="radio" id="document" name="document" value="1" checked></td>
      <td class="gauche" style="width:94%"><label class="label_detail_non_gras"><?php echo $Langue['LBL_IMPRESSION_CAHIER_JOURNAL']; ?></label></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_IMPRESSION_DATE']; ?> : <input type=text class="text ui-widget-content ui-corner-all" id="date_en_cours" name="date_en_cours" value="<?php echo Date_Convertir($date_en_cours,"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_IMPRESSION_NB_CLASSES_PAGE']; ?> : <select name="nb_classe" id="nb_classe" class="text ui-widget-content ui-corner-all">
      <?php
        for ($i=1;$i<=$nb_niveau;$i++)
        {
          echo '<option value="'.$i.'"';
          echo '>'.$i.'</option>';
        }
      ?>
      </select></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_IMPRESSION_DEVOIRS']; ?> : <select name="devoirs" id="devoirs" class="text ui-widget-content ui-corner-all">
		<option value="1" <?php if ($_SESSION['affiche_devoirs']=="1") { echo " SELECTED"; } ?>><?php echo $Langue['LBL_IMPRESSION_DEVOIRS_DANS']; ?></option>
		<option value="2" <?php if ($_SESSION['affiche_devoirs']!="1") { echo " SELECTED"; } ?>><?php echo $Langue['LBL_IMPRESSION_DEVOIRS_DEHORS']; ?></option>
		<option value="0"><?php echo $Langue['LBL_IMPRESSION_DEVOIRS_PAS']; ?></option>
      </select></td>
    </tr>
    </table>
  </td>
  <td class="textgauche marge10_gauche" style="width:50%" valign=top>
    <div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_IMPRESSION_OPTIONS']; ?></div>
    <table cellspacing=0 cellpadding=0 class="tableau_editview2" style="width:100%">
    <tr>
      <td class="droite" style="width:25%"><label class="label_class"><?php echo $Langue['LBL_IMPRESSION_ORIENTATION']; ?> :</label></td>
      <td class="gauche" style="width:38%">
        <input type="radio" id="orientation" name="orientation" value="P" checked> <?php echo $Langue['LBL_IMPRESSION_ORIENTATION_PORTRAIT']; ?>
      </td>
      <td class="gauche" style="width:37%">
        <input type="radio" id="orientation" name="orientation" value="L"> <?php echo $Langue['LBL_IMPRESSION_ORIENTATION_PAYSAGE']; ?>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:100%" colspan=3>&nbsp;</td>
    </tr>
    <tr>
      <td class="droite" style="width:25%"><label class="label_class"><?php echo $Langue['LBL_IMPRESSION_COULEUR']; ?> :</label></td>
      <td class="gauche" style="width:38%">
        <input type="radio" id="couleur" name="couleur" value="C" checked> <?php echo $Langue['LBL_IMPRESSION_COULEUR_OUI']; ?>
      </td>
      <td class="gauche" style="width:37%">
        <input type="radio" id="couleur" name="couleur" value="G"> <?php echo $Langue['LBL_IMPRESSION_COULEUR_GRIS']; ?>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:100%" colspan=3>&nbsp;</td>
    </tr>
    <tr>
      <td class="droite" style="width:25%"><label class="label_class"><?php echo $Langue['LBL_IMPRESSION_NUMEROTATION_PAGE']; ?> :</label></td>
      <td class="gauche" style="width:75%" colspan=2>
        <select id="numerotation" name="numerotation" class="text ui-widget-content ui-corner-all">
		<?php
		  foreach ($liste_choix['type_numerotation_impression'] as $cle => $value)
		  {
		    echo '<option value="'.$cle.'">'.$value.'</option>';
		  }
		?>
		</select>
      </td>
    </tr>
    </table>
  </td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Imprimer_Detail2" name="Imprimer_Detail2" value="<?php echo $Langue['BTN_IMPRIMER']; ?>">&nbsp;
    <input type="Button" id="Annuler_Impression2" name="Annuler_Impression2" value="<?php echo $Langue['BTN_ANNULER2']; ?>">&nbsp;
  </td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cahier-journal/article/144-cahier-documents-imprimables.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cahier-journal/article/144-cahier-documents-imprimables.html","Aide");
<?php } ?>
  });

	  $( "#date_en_cours" ).datepicker(
	  { 
	    dateFormat: "<?php echo $Format_Date_Calendar; ?>", 
		showOn: "button", 
		buttonImage: "images/calendar.gif", 
		buttonImageOnly: true,
		minDate:new Date(<?php echo substr($jour_min,0,4); ?>,<?php echo substr($jour_min,5,2); ?>-1,<?php echo substr($jour_min,8,2); ?>),
		maxDate:new Date(<?php echo substr($jour_max,0,4); ?>,<?php echo substr($jour_max,5,2); ?>-1,<?php echo substr($jour_max,8,2); ?>),
	  });

  /***************************************************/
  /* En cas de changement d'option dans l'impression */
  /***************************************************/
  $( "#date_en_cours" ).change(function()
  {
    Charge_Dialog("index2.php?module=cahier&action=detailview_imprimer&date_en_cours="+$("#date_en_cours").val(),"<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });
  
  /* Boutons d'impression */
  <?php if ($nb_niveau!=0) { ?>
  $("#Imprimer_Detail").button({ disabled:false });
  $("#Imprimer_Detail2").button({ disabled:false });
  <?php } else { ?>
  $("#Imprimer_Detail").button({ disabled:true });
  $("#Imprimer_Detail2").button({ disabled:true });
  <?php } ?>
	$("#Imprimer_Detail").click(function()
  {
    data = "document="+$("#document:checked").val()+"&nb_classe="+$("#nb_classe").val()+"&date_en_cours="+$("#date_en_cours").val()+"&devoirs="+$("#devoirs").val()+"&orientation="+$("#orientation:checked").val()+"&couleur="+$("#couleur:checked").val()+"&numerotation="+$("#numerotation").val();
    window.open("index2.php?module=cahier&action=imprimer_lancer&"+data,"Impression");
    $("#dialog-form").dialog( "close" );
  });
	$("#Imprimer_Detail2").click(function()
  {
    $("#Imprimer_Detail").click();
  });

  /* Annuler l'impression */
  $("#Annuler_Impression").button();
  $("#Annuler_Impression2").button();
	$("#Annuler_Impression").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
	$("#Annuler_Impression2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
});
</script>
