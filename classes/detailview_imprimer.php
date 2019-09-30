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
      <td class="gauche" style="width:94%" colspan=2><label class="label_detail_non_gras"><?php echo $Langue['LBL_IMPRESSION_LISTE_CLASSES']; ?></label></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:55%">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_IMPRESSION_OPTIONS']; ?> : <select class="text ui-widget-content ui-corner-all" name="type" id="type">
        <option value="A"><?php echo $Langue['LBL_IMPRESSION_ORDRE_ALPHA']; ?></option>
        <option value="N"><?php echo $Langue['LBL_IMPRESSION_NIVEAU']; ?></option>
        <option value="E"><?php echo $Langue['LBL_IMPRESSION_EFFECTIF']; ?></option>
        <option value="P"><?php echo $Langue['LBL_IMPRESSION_TITULAIRE']; ?></option>
        </select>
      </td>
      <td class="gauche" style="width:39%">
        <select class="text ui-widget-content ui-corner-all" name="ordre" id="ordre">
        <option value="ASC"><?php echo $Langue['LBL_IMPRESSION_ORDRE_CROISSANT']; ?></option>
        <option value="DESC"><?php echo $Langue['LBL_IMPRESSION_ORDRE_DECROISSANT']; ?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:100%" colspan=3>&nbsp;</td>
    </tr>
    <tr>
      <td class="droite" style="width:6%"><input type="radio" id="document" name="document" value="2" <?php echo $document2; ?>></td>
      <td class="gauche" style="width:94%" colspan=2><label class="label_detail_non_gras"><?php echo $Langue['LBL_IMPRESSION_FICHE_CLASSE']; ?> : <?php echo Liste_Classes("id_classe","form",$_SESSION['annee_scolaire'],$_GET['id_a_imprimer'],"",false); ?></label></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:55%">&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="document2" name="document2" value="1" checked> <?php echo $Langue['LBL_IMPRESSION_FICHE_COMPLETE']; ?></td>
      <td class="gauche" style="width:39%"><input type="radio" id="document2" name="document2" value="2"> <?php echo $Langue['LBL_IMPRESSION_LISTE_ELEVES_SEULE']; ?></td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:55%">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_IMPRESSION_LISTE']; ?> : <select class="text ui-widget-content ui-corner-all" name="type_liste" id="type_liste">
        <option value="A"><?php echo $Langue['LBL_IMPRESSION_ORDRE_ALPHA']; ?></option>
        <option value="C"><?php echo $Langue['LBL_IMPRESSION_ORDRE_CHRONO']; ?></option>
        <option value="N"><?php echo $Langue['LBL_IMPRESSION_NIVEAU']; ?></option>
        <option value="S"><?php echo $Langue['LBL_IMPRESSION_SEXE']; ?></option>
        </select>
      </td>
      <td class="gauche" style="width:39%">
        <select class="text ui-widget-content ui-corner-all" name="ordre_liste" id="ordre_liste">
        <option value="ASC"><?php echo $Langue['LBL_IMPRESSION_ORDRE_CROISSANT']; ?></option>
        <option value="DESC"><?php echo $Langue['LBL_IMPRESSION_ORDRE_DECROISSANT']; ?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td class="droite" style="width:6%">&nbsp;</td>
      <td class="gauche" style="width:94%" colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_IMPRESSION_ELEVES']; ?> : <select class="text ui-widget-content ui-corner-all" name="option_liste" id="option_liste">
        <option value="1"><?php echo $Langue['LBL_IMPRESSION_TOUS']; ?></option>
        <option value="2"><?php echo $Langue['LBL_IMPRESSION_INSCRITS']; ?></option>
        </select>
      </td>
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
		window.open("http://www.doxconception.com/site/index.php/directeur-classes/article/134-classes-documents-imprimables.html","Aide");
  });

  /* Impression de la liste des classes */
  $("#type").change(function()
  {
    $('input[name=document]').val(['1']);
  });
  $("#ordre").change(function()
  {
    $('input[name=document]').val(['1']);
  });

  /* Impression d'une classe */
  $("#id_classe").change(function()
  {
    $('input[name=document]').val(['2']);
  });
  $("#document2").click(function()
  {
    $('input[name=document]').val(['2']);
  });
  $("#document2").change(function()
  {
    $('input[name=document]').val(['2']);
  });
  $("#type_liste").change(function()
  {
    $('input[name=document]').val(['2']);
  });
  $("#ordre_liste").change(function()
  {
    $('input[name=document]').val(['2']);
  });
  $("#option_liste").change(function()
  {
    $('input[name=document]').val(['2']);
  });

  /* Boutons d'impression */
  $("#Imprimer_Detail").button();
  $("#Imprimer_Detail2").button();
	$("#Imprimer_Detail").click(function()
  {
    data = "document="+$("#document:checked").val()+"&type="+$("#type").val()+"&ordre="+$("#ordre").val()+"&id_classe="+$("#id_classe").val()+"&document2="+$("#document2:checked").val()+"&type_liste="+$("#type_liste").val()+"&ordre_liste="+$("#ordre_liste").val()+"&option_liste="+$("#option_liste").val()+"&orientation="+$("#orientation:checked").val()+"&couleur="+$("#couleur:checked").val()+"&numerotation="+$("#numerotation").val();
    window.open("index2.php?module=classes&action=imprimer_lancer&"+data,"Impression");
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
