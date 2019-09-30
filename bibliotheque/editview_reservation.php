<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=date("Y");
  }
  else
  {
    if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=date("Y")-1; } else { $annee=date("Y"); }
  }
  
  Param_Utilisateur($_SESSION['id_util'],$annee);
  $id="";
  $id_emprunteur="";
  $type_emprunteur="";
  $id_livre="";
  $date_emprunt=date("Y-m-d");
  $date_retour="";
  $id_prof="";

  // Si on transmet l'id d'un emprunt
  if (isset($_GET['id']))
  {
    $id=$_GET['id'];
		$req=mysql_query("SELECT * FROM `bibliotheque_emprunt` WHERE id='$id'");
		$id_emprunteur=mysql_result($req,0,'id_util');
		$id_livre=mysql_result($req,0,'id_livre');
		$type_emprunteur=mysql_result($req,0,'type_util');
		$date_emprunt=mysql_result($req,0,'date_emprunt');
		$req=mysql_query("SELECT * FROM `bibliotheque` WHERE id='$id_livre'");
		$id_prof=mysql_result($req,0,'id_prof');
		if ($id_prof=="")
		{
			$msg='<input type=hidden name="id_prof_emprunt" id="id_prof_emprunt" value=""><label class="label_detail">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</label>';
		}
		else
		{
			$msg='<input type=hidden name="id_prof_emprunt" id="id_prof_emprunt" value="'.$id_prof.'"><label class="label_detail">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</label>';
		}
		$req2=mysql_query("SELECT * FROM `bibliotheque` WHERE id='".$id_livre."'");
		$msg_livre='<input type=hidden name="id_livre_emprunt" id="id_livre_emprunt" value="'.$id_livre.'"><label class="label_detail">'.mysql_result($req2,0,'reference').' - '.mysql_result($req2,0,'titre').'</label>';
		if ($type_emprunteur=="P")
		{
				$req2=mysql_query("SELECT * FROM `profs` WHERE id='".$id_emprunteur."'");
				$msg_emprunteur='<input type=hidden name="id_util_emprunt" id="id_util_emprunt" value="P|'.$id_emprunteur.'"><label class="label_detail">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</label>';
		}
		else
		{
				$req2=mysql_query("SELECT * FROM `eleves` WHERE id='".$id_emprunteur."'");
				$msg_emprunteur='<input type=hidden name="id_util_emprunt" id="id_util_emprunt" value="E|'.$id_emprunteur.'"><label class="label_detail">'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</label>';
		}
  }
  else
  {
    $id_livre=$_GET['id_livre'];
		$req=mysql_query("SELECT * FROM `bibliotheque` WHERE id='$id_livre'");
		$id_prof=mysql_result($req,0,'id_prof');
		if ($id_prof=="")
		{
			$msg='<input type=hidden name="id_prof_emprunt" id="id_prof_emprunt" value=""><label class="label_detail">'.$Langue['LBL_BIBLIOTHEQUE_ECOLE'].'</label>';
		}
		else
		{
			$msg='<input type=hidden name="id_prof_emprunt" id="id_prof_emprunt" value="'.$id_prof.'"><label class="label_detail">'.$Langue['LBL_BIBLIOTHEQUE_CLASSE2'].'</label>';
		}
		$req2=mysql_query("SELECT * FROM `bibliotheque` WHERE id='".$id_livre."'");
		$msg_livre='<input type=hidden name="id_livre_emprunt" id="id_livre_emprunt" value="'.$id_livre.'"><label class="label_detail">'.mysql_result($req2,0,'reference').' - '.mysql_result($req2,0,'titre').'</label>';
	  // Construction de la liste des emprunteurs
	  $msg_emprunteur='<select class="text ui-widget-content ui-corner-all" name="id_util_emprunt" id="id_util_emprunt">';
	  $msg_emprunteur .='<option class="option_gras" value="">'.$Langue['LBL_PERSONNELS'].'</option>';
	  $req=mysql_query("SELECT * FROM `profs` WHERE date_sortie='0000-00-00' OR date_sortie>'".date("Y-m-d")."' ORDER BY nom ASC, prenom ASC");
	  for ($i=1;$i<=mysql_num_rows($req);$i++)
	  {
			$msg_emprunteur .='<option value="P|'.mysql_result($req,$i-1,'id').'"';
			if (mysql_result($req,$i-1,'id')==$id_emprunteur AND $type_emprunteur=='P') { $msg_emprunteur .=' SELECTED'; }
			$msg_emprunteur .='>&nbsp;&nbsp;&nbsp;&nbsp;'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom').'</option>';
	  }
	  $req=mysql_query("SELECT classes.*, eleves_classes.*, eleves.* FROM `classes`,`eleves_classes`,`eleves` WHERE classes.id=eleves_classes.id_classe AND eleves.id=eleves_classes.id_eleve AND classes.annee='$annee' ORDER BY classes.nom_classe ASC, eleves.nom ASC, eleves.prenom ASC");
	  $classe="";
	  for ($i=1;$i<=mysql_num_rows($req);$i++)
	  {
			if ($classe!=mysql_result($req,$i-1,'classes.id'))
			{
				$msg_emprunteur .='<option class="option_gras" value="">'.mysql_result($req,$i-1,'classes.nom_classe').'</option>';
				$classe=mysql_result($req,$i-1,'classes.id');
			}
			$msg_emprunteur .='<option value="E|'.mysql_result($req,$i-1,'eleves.id').'"';
			if (mysql_result($req,$i-1,'eleves.id')==$id_emprunteur AND $type_emprunteur=='E') { $msg_emprunteur .=' SELECTED'; }
			$msg_emprunteur .='>&nbsp;&nbsp;&nbsp;&nbsp;'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</option>';
	  }
	  $msg_emprunteur .='</select>';
  }
?>
<a name="haut_formulaire"></a>
<form action="index2.php" method="POST" id="form_editview_emprunt" name="Detail">
<input type="hidden" id="module" name="module" value="bibliotheque">
<input type="hidden" id="action" name="action" value="save_reservation">
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<div id="msg_ok3"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer_Emprunt" name="Enregistrer_Emprunt" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerFermer_Emprunt" name="EnregistrerFermer_Emprunt" value="<?php echo $Langue['BTN_ENREGISTRER_FERMER']; ?>">
    <input type="Button" id="EnregistrerNouveau_Emprunt" name="EnregistrerNouveau_Emprunt" value="<?php echo $Langue['BTN_EMPRUNT_ENREGISTRER_AJOUTER_RESERVATION']; ?>">
    <input type="Button" id="Annuler_Emprunt" name="Annuler_Emprunt" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_BIBLIOTHEQUE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><?php echo $msg; ?></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_RESERVATION_LIVRE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><div id="liste_livre_emprunt"><?php echo $msg_livre; ?></div></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_RESERVATION_EMPRUNTEUR']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><div id="liste_emprunteur"><?php echo $msg_emprunteur; ?></div></td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_RESERVATION_DATE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><input type=text class="text ui-widget-content ui-corner-all" id="date_emprunt" name="date_emprunt" value="<?php echo Date_Convertir($date_emprunt,"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer_Emprunt2" name="Enregistrer_Emprunt2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerFermer_Emprunt2" name="EnregistrerFermer_Emprunt2" value="<?php echo $Langue['BTN_ENREGISTRER_FERMER']; ?>">
    <input type="Button" id="EnregistrerNouveau_Emprunt2" name="EnregistrerNouveau_Emprunt2" value="<?php echo $Langue['BTN_EMPRUNT_ENREGISTRER_AJOUTER_RESERVATION']; ?>">
    <input type="Button" id="Annuler_Emprunt2" name="Annuler_Emprunt2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer_Emprunt").button();
  $("#EnregistrerFermer_Emprunt").button();
  $("#Annuler_Emprunt").button();
  $("#EnregistrerNouveau_Emprunt").button();
  $("#EnregistrerNouveau_Emprunt2").button();
  $("#Enregistrer_Emprunt2").button();
  $("#EnregistrerFermer_Emprunt2").button();
  $("#Annuler_Emprunt2").button();
  $("#Annuler_Emprunt").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Annuler_Emprunt2").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  
  /* Création des champs de saisie des dates */
  $( "#date_emprunt" ).datepicker(
  { 
    dateFormat: "<?php echo $Format_Date_Calendar; ?>", 
		showOn: "button", 
		buttonImage: "images/calendar.gif", 
		buttonImageOnly: true 
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer_Emprunt").click(function()
  {
    action_save="edit";
  });
  
  $("#form_editview_emprunt").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#id_util_emprunt"))) { bValid=false; }
    if (!checkValue($("#id_livre_emprunt"))) { bValid=false; }
    if (!checkRegexp($("#date_emprunt"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    event.preventDefault();
    if ( bValid )
    {
			Message_Chargement(2,1);
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
				decoupe=msg.split("|");
				switch (action_save)
				{
					case "edit":
						Charge_Dialog3("index2.php?module=bibliotheque&action=editview_reservation&id="+decoupe[0],"<?php echo $Langue['BTN_RESERVATION_SAISIR']; ?>");
						if ($("#dialog-form").dialog("isOpen"))
						{
							Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id="+decoupe[1],"<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
						}
						break;
					case "nouveau":
						Charge_Dialog3("index2.php?module=bibliotheque&action=editview_reservation&id_livre="+decoupe[1],"<?php echo $Langue['BTN_RESERVATION_SAISIR']; ?>");
						if ($("#dialog-form").dialog("isOpen"))
						{
								$("#dialog-form").dialog( "close" );
						}
						break;
					case "fermer":
						if ($("#dialog-form").dialog("isOpen"))
						{
							Charge_Dialog("index2.php?module=bibliotheque&action=detailview&id="+decoupe[1],"<?php echo $Langue['LBL_FICHE_LIVRE']; ?>");
						}
						$("#dialog-niveau2").dialog( "close" );
						break;
				}
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
  	  $("#msg_ok3").fadeIn( 1000 );
  	  $("#msg_ok3").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
   	  setTimeout(function()
      {
        $("#msg_ok3").effect("blind",1000);
      }, 3000 );
    }
  });
  
  $("#Enregistrer_Emprunt2").click(function()
  {
    action_save="edit";
    $("#form_editview_emprunt").submit();
  });

  $("#EnregistrerNouveau_Emprunt").click(function()
  {
    action_save="nouveau";
    $("#form_editview_emprunt").submit();
  });
  $("#EnregistrerNouveau_Emprunt2").click(function()
  {
    action_save="nouveau";
    $("#form_editview_emprunt").submit();
  });

  $("#EnregistrerFermer_Emprunt").click(function()
  {
    action_save="fermer";
    $("#form_editview_emprunt").submit();
  });

  $("#EnregistrerFermer_Emprunt2").click(function()
  {
    action_save="fermer";
    $("#form_editview_emprunt").submit();
  });
});
</script>
