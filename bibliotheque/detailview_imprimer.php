<?php if (!isset($_GET['id_a_imprimer'])) { $_GET['id_a_imprimer']=""; $document1="CHECKED"; $document2=""; } else { $document2="CHECKED"; $document1=""; } ?>
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
      <td class="gauche" style="width:94%"><label class="label_detail_non_gras"><?php echo $Langue['LBL_LISTE_LIVRES']; ?></label></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_BIBLIOTHEQUE']; ?> : <select class="text ui-widget-content ui-corner-all" name="bibliotheque" id="bibliotheque">
        <?php if ($gestclasse_config_plus['biblio_ecole']=="1") { ?><option value="E"><?php echo $Langue['LBL_BIBLIOTHEQUE_ECOLE']; ?></option><?php } ?>
        <?php if ($gestclasse_config_plus['biblio_classe']=="1") { ?><option value="C"><?php echo $Langue['LBL_BIBLIOTHEQUE_CLASSE2']; ?></option><?php } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_LIVRE_A_IMPRIMER']; ?> : <select class="text ui-widget-content ui-corner-all" name="type" id="type">
<?php
		foreach ($liste_choix['livre_a_afficher'] AS $cle => $value)
		{
          echo '<option value="'.$cle.'">'.$value.'</option>';
		}
?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:100%" colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td class="droite" style="width:6%"><input type="radio" id="document" name="document" value="2" <?php echo $document2; ?>></td>
      <td class="gauche" style="width:94%"><label class="label_detail_non_gras"><?php echo $Langue['LBL_FICHE_LIVRE']; ?> : <select name="id_livre" id="id_livre" class="text ui-widget-content ui-corner-all">
<?php
	  if ($gestclasse_config_plus['biblio_ecole']=="1")
	  {
	    if ($gestclasse_config_plus['biblio_classe']=="1")
			{
				echo '<option value="" class="option_gras">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</option>';
			}
	    $req_biblio=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='' ORDER BY reference ASC, titre ASC");
			for ($i=1;$i<=mysql_num_rows($req_biblio);$i++)
			{
				echo '<option value="'.mysql_result($req_biblio,$i-1,'bibliotheque.id').'"';
				if (mysql_result($req_biblio,$i-1,'id')==$_GET['id_a_imprimer']) { echo ' SELECTED'; }
				if (strlen(mysql_result($req_biblio,$i-1,'bibliotheque.titre'))>50) { $titre=substr(mysql_result($req_biblio,$i-1,'bibliotheque.titre'),0,49).'...'; } else { $titre=mysql_result($req_biblio,$i-1,'bibliotheque.titre'); }
				echo '>'.mysql_result($req_biblio,$i-1,'bibliotheque.reference').' - '.$titre.'</option>';
			}
	  }
	  if ($gestclasse_config_plus['biblio_classe']=="1")
	  {
	    if ($gestclasse_config_plus['biblio_ecole']=="1")
			{
				echo '<option value="" class="option_gras">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</option>';
			}
	    $req_biblio=mysql_query("SELECT * FROM `bibliotheque` WHERE id_prof='".$_SESSION['id_util']."' ORDER BY reference ASC, titre ASC");
			for ($i=1;$i<=mysql_num_rows($req_biblio);$i++)
			{
				echo '<option value="'.mysql_result($req_biblio,$i-1,'bibliotheque.id').'"';
				if (mysql_result($req_biblio,$i-1,'id')==$_GET['id_a_imprimer']) { echo ' SELECTED'; }
				if (strlen(mysql_result($req_biblio,$i-1,'bibliotheque.titre'))>50) { $titre=substr(mysql_result($req_biblio,$i-1,'bibliotheque.titre'),0,49).'...'; } else { $titre=mysql_result($req_biblio,$i-1,'bibliotheque.titre'); }
				echo '>'.mysql_result($req_biblio,$i-1,'bibliotheque.reference').' - '.$titre.'</option>';
			}
	  }
?>
	  </select></label></td>
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
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-bibliotheque/article/160-biblio-documents-imprimables.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-bibliotheque/article/160-biblio-documents-imprimables.html","Aide");
<?php } ?>
  });

  /* Impression de la liste des livres */
  $("#type").change(function()
  {
    $('input[name=document]').val(['1']);
  });
  $("#bibliotheque").change(function()
  {
    $('input[name=document]').val(['1']);
  });

  /* Impression de la fiche d'un livre */
  $("#id_livre").change(function()
  {
    $('input[name=document]').val(['2']);
  });

  /* Boutons d'impression */
  $("#Imprimer_Detail").button();
  $("#Imprimer_Detail2").button();
	$("#Imprimer_Detail").click(function()
  {
	  faire=true;
	  if ($("#document:checked").val()=="2" && $("#id_livre").val()=="") { faire=false; }
		if (faire==true)
		{
      data = "document="+$("#document:checked").val()+"&type="+$("#type").val()+"&bibliotheque="+$("#bibliotheque").val()+"&id_livre="+$("#id_livre").val()+"&orientation="+$("#orientation:checked").val()+"&couleur="+$("#couleur:checked").val()+"&numerotation="+$("#numerotation").val();
      window.open("index2.php?module=bibliotheque&action=imprimer_lancer&"+data,"Impression");
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
