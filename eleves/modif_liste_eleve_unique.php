<?php
  $id=$_GET['id'];
  $req=mysql_query("SELECT eleves.*, eleves_classes.*, classes.* FROM `eleves_classes`, `eleves`, `classes` WHERE eleves_classes.id='$id' AND eleves_classes.id_eleve=eleves.id AND eleves_classes.id_classe=classes.id");
  $nom_eleve=mysql_result($req,0,'eleves.nom')." ".mysql_result($req,0,'eleves.prenom');
  $id_classe=mysql_result($req,0,'eleves_classes.id_classe');
  $annee=mysql_result($req,0,'classes.annee');
  $anneeplus=$annee+1;
  $id_niveau=mysql_result($req,0,'eleves_classes.id_niveau');
  $redoublement=mysql_result($req,0,'eleves_classes.redoublement');
?>
<!-- BEGIN formulaire -->
<a name="haut_formulaire2"></a>
<form action="index2.php" method=POST id=form_editview2 name=Detail>
<input type="hidden" id="module" name="module" value="eleves">
<input type="hidden" id="action" name="action" value="save_modif_eleve">
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<input type="hidden" id="annee" name="annee" value="<?php echo $annee; ?>">
<div id="message2"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer_Eleve" name="Enregistrer_Eleve" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Annuler_Eleve" name="Annuler_Eleve" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>
<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_bas marge10_tout"><strong><?php echo $Langue['MSG_MODIF_ELEVE']; ?></strong></div></div>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_ELEVE_MODIF']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_ELEVE']; ?> :</td>
<td class="gauche" width=80%><label class="label_detail"><?php echo $nom_eleve; ?></label></td></tr>
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_ANNEE_SCOLAIRE_COURS']; ?> :</td>
<td class="gauche" width=80%><label class="label_detail"><?php if ($gestclasse_config_plus['etendue_annee_scolaire']=="1") { echo $annee; } else { echo $annee.'-'.$anneeplus; } ?></label></td></tr>
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_CLASSE']; ?> :</td>
<td class="gauche" width="80%"><?php echo Liste_Classes("id_classe","form",$annee,$id_classe,"",false); ?></td></tr>
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_NIVEAU']; ?> :</td>
<td class="gauche" width="80%"><div id="niveau"><?php echo Liste_Niveaux("id_niveau","form",$id_niveau,$id_classe,false); ?></div></td></tr>
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_REDOUBLEMENT']; ?> :</td>
<td class="gauche" width="80%">
<?php
  $msg='<select id=redoublement name=redoublement class="text ui-widget-content ui-corner-all">';
  foreach ($liste_choix['ouinon'] AS $cle => $value)
  {
    $msg=$msg.'<option value="'.$cle.'"';
    if ($cle==$redoublement) { $msg=$msg.' SELECTED'; }
    $msg=$msg.'>'.$value.'</option>';
  }
  $msg=$msg.'</select>';
  echo $msg;
?>
</td></tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer_Eleve").button();
  $("#Annuler_Eleve").button();
  $("#Annuler_Eleve").click(function()
  {
    $("#modif_liste").button({ disabled: false });
    $("#modif_liste2").button({ disabled: false });
    $("#dialog-niveau2").dialog( "close" );
  });
  
  $("#id_classe").change(function()
  {
		Eleves_Modif_Change_Classe();
  });

  $("#form_editview2").submit(function(event)
  {
    event.preventDefault();
    Message_Chargement(2,1);
    var $form = $( this );
    url = $form.attr( 'action' );
    data = $form.serialize();
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
      decoupage=msg.split("|");
      Charge_Dialog("index2.php?module=eleves&action=detailview&id="+decoupage[0],"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
      $("#tabs").tabs("load",3);
      parent.calcul.location.href='users/calcul_moyenne.php?id_classe='+decoupage[1]+'&id_niveau='+decoupage[2]+'&id_titulaire='+decoupage[3]+'&annee='+decoupage[4];
      $("#dialog-niveau2").dialog( "close" );
      Message_Chargement(1,0);
    });
  });
});

function Eleves_Modif_Change_Classe()
{
	classe=$("#id_classe").val();
	$("#niveau").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
	var request3 = $.ajax({type: "POST", url: "index2.php", data: "module=eleves&action=change_classe&id_classe="+classe });
	request3.done(function(msg)
	{
		$("#niveau").html(msg);
	});
}
</script>