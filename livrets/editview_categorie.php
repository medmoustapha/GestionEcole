<?php
  $id="";
  $titre="";
  $parent="";
  
  if (isset($_GET['id']))
  {
    $id=$_GET['id'];
    $req=mysql_query("SELECT * FROM `competences_categories` WHERE id='$id'");
    $titre=mysql_result($req,0,'titre');
    $parent=mysql_result($req,0,'id_parent');
  }
?>
<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id="form_editview_categorie" name="form_editview_categorie">
<input type="hidden" id="module" name="module" value="livrets">
<input type="hidden" id="action" name="action" value="save_categories">
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<div id="msg_erreur"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer_Categorie" name="Enregistrer_Categorie" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerNouveau_Categorie" name="EnregistrerNouveau_Categorie" value="<?php echo $Langue['BTN_COMPETENCES_ENREGISTRER_CREER_CATEGORIE']; ?>">
    <input type="Button" id="Annuler_Categorie" name="Annuler_Categorie" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>

<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_COMPETENCES_NOM_CATEGORIE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><input type="text" class="text ui-widget-content ui-corner-all" id="titre" name="titre" value="<?php echo $titre; ?>" size=50 maxlength=255></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_COMPETENCES_SOUS_CAT']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><select class="text ui-widget-content ui-corner-all" id="parent" name="parent">
    <option value=""><?php echo $Langue['LBL_COMPETENCES_RACINE']; ?></option>
<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $annee_fin=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $a=$_SESSION['annee_scolaire']+1;
	  $annee_fin=$a.$gestclasse_config_plus['fin_annee_scolaire'];
  }
    $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='' ORDER BY ordre ASC");
    for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
    {
      echo '<option value="'.mysql_result($req_categorie,$i-1,'id').'"';
      if (mysql_result($req_categorie,$i-1,'id')==$parent) { echo ' SELECTED'; }
      echo '>'.mysql_result($req_categorie,$i-1,'titre').'</option>';
    }
?>
    </select>
  </td>
</tr>
</table>

<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer_Categorie2" name="Enregistrer_Categorie2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerNouveau_Categorie2" name="EnregistrerNouveau_Categorie2" value="<?php echo $Langue['BTN_COMPETENCES_ENREGISTRER_CREER_CATEGORIE']; ?>">
    <input type="Button" id="Annuler_Categorie2" name="Annuler_Categorie2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#Enregistrer_Categorie").button();
  $("#Enregistrer_Categorie2").button();
  $("#EnregistrerNouveau_Categorie").button();
  $("#EnregistrerNouveau_Categorie2").button();
  $("#Annuler_Categorie").button();
  $("#Annuler_Categorie2").button();
  $("#Annuler_Categorie").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Annuler_Categorie2").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  
  $("#form_editview_categorie").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#titre"))) { bValid=false; }
    event.preventDefault();
    if ( bValid )
    {
      Message_Chargement(2,1);
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function()
      {
        Message_Chargement(1,1);
        if (action=="nouveau")
        {
          Charge_Dialog2("index2.php?module=livrets&action=editview_categorie","<?php echo $Langue['LBL_COMPETENCES_AJOUTER_CATEGORIE']; ?>");
        }
        else
        {
          $("#dialog-niveau2").dialog( "close" );
        }
        Charge_Dialog("index2.php?module=livrets&action=liste_competences","<?php echo $Langue['LBL_COMPETENCES_LISTE_COMPETENCES']; ?>");
      });
    }
    else
    {
			$("#dialog-niveau2").scrollTop(0);
      $("#msg_erreur").fadeIn( 1000 );
		  $("#msg_erreur").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
			setTimeout(function()
			{
				$("#msg_erreur").effect("blind",1000);
			}, 3000 );
    }
  });
  
  $("#Enregistrer_Categorie").click(function()
  {
    action="rien";
  });
  $("#Enregistrer_Categorie2").click(function()
  {
    action="rien";
    $("#form_editview_categorie").submit();
  });
  $("#EnregistrerNouveau_Categorie").click(function()
  {
    action="nouveau";
    $("#form_editview_categorie").submit();
  });
  $("#EnregistrerNouveau_Categorie2").click(function()
  {
    action="nouveau";
    $("#form_editview_categorie").submit();
  });
});
</script>

