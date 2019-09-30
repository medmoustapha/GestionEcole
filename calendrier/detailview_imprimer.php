<?php if (!isset($_GET['id_a_imprimer'])) { $_GET['id_a_imprimer']=""; $document1="CHECKED"; $document2=""; } else { $document2="CHECKED"; $document1=""; } ?>

<?php
  $annee_avant=date("Y")-1;
  $annee_apres=date("Y")+1;
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
      <td class="droite" style="width:6%"><input type="radio" id="document" name="document" value="1" <?php echo $document1; ?>></td>
      <td class="gauche" style="width:94%"><label class="label_detail_non_gras"><?php echo $Langue['LBL_IMPRESSION_CALENDRIER']; ?></label></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="calendrier_impression" name="calendrier_impression" value="J" <?php if ($_SESSION['calendrier']=="jour") { echo 'CHECKED'; } ?>> <?php echo $Langue['LBL_IMPRESSION_CALENDRIER_QUOTIDIEN']; ?> : <input type=text class="text ui-widget-content ui-corner-all" id="jour_impression" name="jour_impression" value="<?php echo Date_Convertir($_SESSION['date_en_cours_calendrier'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="calendrier_impression" name="calendrier_impression" value="M" <?php if ($_SESSION['calendrier']=="mois") { echo 'CHECKED'; } ?>> <?php echo $Langue['LBL_IMPRESSION_CALENDRIER_MOIS']; ?> : <select name="mois_impression" id="mois_impression" class="text ui-widget-content ui-corner-all">
<?php
			foreach ($liste_choix['mois'] AS $cle => $value)
			{
			  echo '<option value="'.$cle.'"';
				if ($cle==$_SESSION['mois_en_cours']) { echo ' SELECTED'; }
				echo '>'.$value.'</option>';
			}
?>
			</select>&nbsp;<select name="annee_impression" id="annee_impression" class="text ui-widget-content ui-corner-all">
<?php
			for ($i=$annee_avant;$i<=$annee_apres;$i++)
			{
				echo '<option value="'.$i.'"';
				if ($i==$_SESSION['annee_en_cours']) { echo ' SELECTED'; }
				echo '>'.$i.'</option>';
			}
?>
			  </select>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="calendrier_impression" name="calendrier_impression" value="A" <?php if ($_SESSION['calendrier']=="annee") { echo 'CHECKED'; } ?>> <?php echo $Langue['LBL_IMPRESSION_CALENDRIER_ANNEE']; ?> : <select name="annee2_impression" id="annee2_impression" class="text ui-widget-content ui-corner-all">
<?php
			for ($i=$annee_avant;$i<=$annee_apres;$i++)
			{
				echo '<option value="'.$i.'"';
				if ($i==$_SESSION['annee_en_cours']) { echo ' SELECTED'; }
				echo '>'.$i.'</option>';
			}
?>
			  </select>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:100%" colspan=2>&nbsp;</td>
    </tr>
    </table>
  </td>
  <td class="textgauche marge5_gauche" style="width:50%" valign=top>
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
<tr>
	<td colspan=2>
    <table cellspacing=0 cellpadding=0 class="tableau_editview2" style="width:100%">
    <tr>
      <td class="droite" style="width:3%"><input type="radio" id="document" name="document" value="2" <?php echo $document2; ?>></td>
      <td class="gauche" style="width:97%"><label class="label_detail_non_gras"><?php echo $Langue['LBL_IMPRESSION_REUNION']; ?> : <select name="id_reunion" id="id_reunion" class="text ui-widget-content ui-corner-all">
<?php
			$date_debut=date("Y")-1;
			$date_debut=$date_debut.'-01-01';
			$date_fin=date("Y")+1;
			$date_fin=$date_fin.'-12-31';
			$id_util=$_SESSION['type_util'].$_SESSION['id_util'];
			$req=mysql_query("SELECT * FROM `reunions` WHERE (date BETWEEN '".$date_debut."' AND '".$date_fin."') AND (id_util LIKE '".$id_util.",%' OR id_util LIKE '%,".$id_util."' OR id_util='".$id_util."' OR id_util LIKE '%,".$id_util.",%') ORDER BY date ASC, heure_debut ASC, heure_fin ASC");
			$annee="";
			for ($i=1;$i<=mysql_num_rows($req);$i++)
			{
			  if ($annee<>substr(mysql_result($req,$i-1,'date'),0,4))
				{
				  $annee=substr(mysql_result($req,$i-1,'date'),0,4);
					echo '<option value="" class="option_gras option_gris">'.$Langue['LBL_IMPRESSION_REUNIONS_ANNEE'].' '.$annee.'</option>';
				}  
			  echo '<option value="'.mysql_result($req,$i-1,'id').'"';
				if (mysql_result($req,$i-1,'id')==$_GET['id_a_imprimer']) { echo ' SELECTED'; }
				echo '>'.$Langue['LBL_IMPRESSION_LE'].' '.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_IMPRESSION_DE2'].' '.substr(mysql_result($req,$i-1,'heure_debut'),0,5).' '.$Langue['LBL_IMPRESSION_A'].' '.substr(mysql_result($req,$i-1,'heure_fin'),0,5).' - '.$liste_choix['type_reunion'][mysql_result($req,$i-1,'type')].' : '.mysql_result($req,$i-1,'resume').'</option>';
			}
?>
			</select></label></td>
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
  /***************************************************/
  /* En cas de changement d'option dans l'impression */
  /***************************************************/

  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
<?php
  switch ($_SESSION['type_util'])
	{
	  case "D":
?>
			window.open("http://www.doxconception.com/site/index.php/directeur-agenda/article/171-agenda-documents-imprimables.html","Aide");
<?php
			break;
		case "P":
?>
			window.open("http://www.doxconception.com/site/index.php/prof-agenda/article/171-agenda-documents-imprimables.html","Aide");
<?php
			break;
		case "E":
?>
			window.open("http://www.doxconception.com/site/index.php/parent-agenda/article/171-agenda-documents-imprimables.html","Aide");
<?php
			break;
		}
?>
  });

	$("#jour_impression").change(function()
	{
    $('input[name=document]').val(['1']);
    $('input[name=calendrier_impression]').val(['J']);
	});
	$("#mois_impression").change(function()
	{
    $('input[name=document]').val(['1']);
    $('input[name=calendrier_impression]').val(['M']);
	});
	$("#annee_impression").change(function()
	{
    $('input[name=document]').val(['1']);
    $('input[name=calendrier_impression]').val(['M']);
	});
	$("#annee2_impression").change(function()
	{
    $('input[name=document]').val(['1']);
    $('input[name=calendrier_impression]').val(['A']);
	});
	$("#id_reunion").change(function()
	{
    $('input[name=document]').val(['2']);
	});
	
  $( "#jour_impression" ).datepicker(
  { 
    dateFormat: "<?php echo $Format_Date_Calendar; ?>", 
		showOn: "button", 
		buttonImage: "images/calendar.gif", 
		buttonImageOnly: true,
		minDate:new Date(<?php echo $annee_avant; ?>,0,1),
		maxDate:new Date(<?php echo $annee_apres; ?>,11,31)
  });

  /* Boutons d'impression */
  $("#Imprimer_Detail").button();
  $("#Imprimer_Detail2").button();
	$("#Imprimer_Detail").click(function()
  {
	  faire=true;
	  if ($("#document:checked").val()=="2" && $("#id_reunion").val()=="") { faire=false; }
		if (faire==true)
		{
  		data = "document="+$("#document:checked").val()+"&calendrier="+$("#calendrier_impression:checked").val()+"&jour="+$("#jour_impression").val()+"&mois="+$("#mois_impression").val()+"&annee="+$("#annee_impression").val()+"&annee2="+$("#annee2_impression").val()+"&id_reunion="+$("#id_reunion").val()+"&orientation="+$("#orientation:checked").val()+"&couleur="+$("#couleur:checked").val()+"&numerotation="+$("#numerotation").val();
	  	window.open("index2.php?module=calendrier&action=imprimer_lancer&"+data,"Impression");
			$("#dialog-form").dialog( "close" );
		}
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
