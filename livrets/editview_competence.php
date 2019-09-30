<?php
  $id="";
  $code="";
  $id_cat="";
  $intitule="";
  $code="";
  $statistiques="";

  if (isset($_GET['id']))
  {
    $id=$_GET['id'];
    $req=mysql_query("SELECT competences_categories.*, competences.* FROM `competences`,`competences_categories` WHERE competences.id='$id' AND competences_categories.id=competences.id_cat");
    $code=mysql_result($req,0,'competences.code');
    $intitule=mysql_result($req,0,'competences.intitule');
    $id_cat=mysql_result($req,0,'competences.id_cat');
    $statistiques=mysql_result($req,0,'competences.statistiques');
  }
?>
<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id="form_editview_competence" name="form_editview_competence">
<input type="hidden" id="module" name="module" value="livrets">
<input type="hidden" id="action" name="action" value="save_competences">
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<div id="msg_erreur"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer_Competence" name="Enregistrer_Competence" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerNouveau_Competence" name="EnregistrerNouveau_Competence" value="<?php echo $Langue['BTN_COMPETENCES_ENREGISTRER_CREER_COMPETENCE']; ?>">
    <input type="Button" id="Annuler_Competence" name="Annuler_Competence" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>

<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=10%><label class="label_class"><?php echo $Langue['LBL_COMPETENCES_CODE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=90%><input type="text" class="text ui-widget-content ui-corner-all" id="code" name="code" value="<?php echo $code; ?>" size=10 maxlength=10></td>
</tr>
<tr>
  <td class="droite" width=10%><label class="label_class"><?php echo $Langue['LBL_COMPETENCES_CATEGORIE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=90%><select class="text ui-widget-content ui-corner-all" id="id_cat" name="id_cat">
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
      if (mysql_result($req_categorie,$i-1,'id')==$id_cat) { echo ' SELECTED'; }
      echo '>'.mysql_result($req_categorie,$i-1,'titre').'</option>';
      $req_categorie2=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='".mysql_result($req_categorie,$i-1,'id')."' ORDER BY ordre ASC");
      for ($j=1;$j<=mysql_num_rows($req_categorie2);$j++)
      {
        echo '<option value="'.mysql_result($req_categorie2,$j-1,'id').'"';
        if (mysql_result($req_categorie2,$j-1,'id')==$id_cat) { echo ' SELECTED'; }
        echo '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.mysql_result($req_categorie2,$j-1,'titre').'</option>';
      }
    }
?>
    </select>
  </td>
</tr>
<tr>
  <td class="droite" width=10% valign=top><label class="label_class"><?php echo $Langue['LBL_COMPETENCES_INTITULE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=90%><textarea class="text ui-widget-content ui-corner-all" id="intitule" name="intitule" cols=65 rows=4><?php echo $intitule; ?></textarea></td>
</tr>
<tr>
  <td class="droite" width=10%><label class="label_class"><?php echo $Langue['LBL_COMPETENCES_TABLEAU_STAT']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=90%><select class="text ui-widget-content ui-corner-all" id="statistiques" name="statistiques">
  <?php
  foreach ($liste_choix['matieres_stat_court'] AS $cle => $value)
  {
    echo '<option value="'.$cle.'"';
	if ($cle==$statistiques) { echo ' SELECTED'; }
	echo '>'.$value.'</option>';
  }
  ?>
  </select>
  </td>
</tr>
</table>

<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer_Competence2" name="Enregistrer_Competence2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerNouveau_Competence2" name="EnregistrerNouveau_Competence2" value="<?php echo $Langue['BTN_COMPETENCES_ENREGISTRER_CREER_COMPETENCE']; ?>">
    <input type="Button" id="Annuler_Competence2" name="Annuler_Competence2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#Enregistrer_Competence").button();
  $("#Enregistrer_Competence2").button();
  $("#EnregistrerNouveau_Competence").button();
  $("#EnregistrerNouveau_Competence2").button();
  $("#Annuler_Competence").button();
  $("#Annuler_Competence2").button();
  $("#Annuler_Competence").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Annuler_Competence2").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  
  $("#form_editview_competence").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#code"))) { bValid=false; }
    if (!checkValue($("#intitule"))) { bValid=false; }
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
          Charge_Dialog2("index2.php?module=livrets&action=editview_competence","<?php echo $Langue['LBL_COMPETENCES_AJOUTER_COMPETENCE']; ?>");
        }
        else
        {
          $("#dialog-niveau2").dialog( "close" );
        }
        Charge_Dialog("index2.php?module=livrets&action=liste_competences","<?php echo $Langue['LBL_COMPETENCES_LISTE_COMPETENCES']; ?>");
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
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
  
  $("#Enregistrer_Competence").click(function()
  {
    action="rien";
  });
  $("#Enregistrer_Competence2").click(function()
  {
    action="rien";
    $("#form_editview_competence").submit();
  });
  $("#EnregistrerNouveau_Competence").click(function()
  {
    action="nouveau";
    $("#form_editview_competence").submit();
  });
  $("#EnregistrerNouveau_Competence2").click(function()
  {
    action="nouveau";
    $("#form_editview_competence").submit();
  });
  
  $("#code").focus();
});
</script>

