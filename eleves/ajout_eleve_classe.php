<?php
  $id_eleve=$_GET['id_eleve'];
  $req=mysql_query("SELECT * FROM `eleves` WHERE id='$id_eleve'");
  $nom_eleve=mysql_result($req,0,'eleves.nom')." ".mysql_result($req,0,'eleves.prenom');
  
  $annee="0000";
  $annee_en_cours="";
  $req=mysql_query("SELECT * FROM `classes` ORDER BY annee ASC");
  $msg='<select id="annee" name="annee" class="text ui-widget-content ui-corner-all">';
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if ($annee!=mysql_result($req,$i-1,'annee'))
	{
	  $annee=mysql_result($req,$i-1,'annee');
	  $req2=mysql_query("SELECT classes.*, eleves_classes.* FROM `classes`,`eleves_classes` WHERE classes.annee='$annee' AND classes.id=eleves_classes.id_classe AND eleves_classes.id_eleve='$id_eleve'");
	  if (mysql_num_rows($req2)=="")
	  {
	    $anneeplus=$annee+1;
		if ($annee_en_cours=="") 
		{ 
		  $annee_en_cours=$annee; 
    	  $req3=mysql_query("SELECT classes.* FROM `classes` WHERE annee='$annee_en_cours' ORDER BY nom_classe ASC");
		  $id_classe_cours=mysql_result($req3,0,'id');
		}
		if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
	      $msg=$msg.'<option value="'.$annee.'">'.$annee.'</option>';
		}
		else
		{
	      $msg=$msg.'<option value="'.$annee.'">'.$annee.'-'.$anneeplus.'</option>';
		}
	  }
	}
  }
  $msg=$msg.'</select>';
?>
<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_editview2 name=Detail>
<input type="hidden" id="module" name="module" value="eleves">
<input type="hidden" id="action" name="action" value="save_eleve_classe">
<input type="hidden" id="id_eleve" name="id_eleve" value="<?php echo $id_eleve; ?>">
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="EnregistrerEleve" name="EnregistrerEleve" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="AnnulerEleve" name="AnnulerEleve" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_ELEVE']; ?> :</label></td>
  <td class="gauche" width=85%><label class="label_detail"><?php echo $nom_eleve; ?></label></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_ANNEE_SCOLAIRE_COURS']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><?php echo $msg; ?></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_CLASSE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><div id="classe"><?php echo Liste_Classes("id_classe","form",$annee_en_cours,'','',false); ?></div></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_NIVEAU']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><div id="niveau"><?php echo Liste_Niveaux("id_niveau",'form','',$id_classe_cours,false); ?></div></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_REDOUBLEMENT']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><select id="redoublement" name="redoublement" class="text ui-widget-content ui-corner-all"><option value="0"><?php echo $Langue['LBL_NON']; ?></option><option value="1"><?php echo $Langue['LBL_OUI']; ?></option></select></td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="EnregistrerEleve2" name="EnregistrerEleve2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="AnnulerEleve2" name="AnnulerEleve2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#EnregistrerEleve").button();
  $("#AnnulerEleve").button();
  $("#EnregistrerEleve2").button();
  $("#AnnulerEleve2").button();
  $("#AnnulerEleve").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#AnnulerEleve2").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  
  $("#annee").change(function()
  {
    annee=$("#annee").val();
	$("#classe").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
    $("#niveau").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=eleves&action=change_annee&annee="+annee });
    request.done(function(msg)
    {
      $("#classe").html(msg);
	  classe=$("#id_classe").val();
      var request2 = $.ajax({type: "POST", url: "index2.php", data: "module=eleves&action=change_classe&id_classe="+classe });
      request2.done(function(msg)
      {
        $("#niveau").html(msg);
      });
    });
  });
  $("#id_classe").change(function()
  {
		Eleves_Ajout_Change_Classe();
  });
  $("#EnregistrerEleve2").click(function() { $("#form_editview2").submit(); });
  $("#form_editview2").submit(function(event)
  {
    Message_Chargement(2,1);
    event.preventDefault();
    var $form = $( this );
    url = $form.attr( 'action' );
    data = $form.serialize();
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
      Message_Chargement(1,1);
	  $("#dialog-niveau2").dialog( "close" );
	  Charge_Dialog("index2.php?module=eleves&action=detailview&id=<?php echo $id_eleve; ?>","<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
      $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
	});
  });
});

function Eleves_Ajout_Change_Classe()
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
