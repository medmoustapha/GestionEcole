<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_editview2 name=form_editview2>
<input type="hidden" id="module" name="module" value="configuration">
<input type="hidden" id="action" name="action" value="save_listes_intitule">
<input type="hidden" id="id" name="id" value="<?php echo $_GET['id']; ?>">
<input type="hidden" id="id_liste" name="id_liste" value="<?php echo $_GET['id_liste']; ?>">
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer" name="Enregistrer" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerNouveau" name="EnregistrerNouveau" value="<?php echo $Langue['BTN_ENREGISTRER_CREER_INTITULE']; ?>">&nbsp;
    <input type="Button" id="Annuler" name="Annuler" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview2">
<?php
  if ($_GET['id']!="")
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE id='".$_GET['id']."'");
    $intitule=mysql_result($req,0,'intitule');
    if ($listes_colonne[$_GET['id_liste']]=="2")
    {
      $intitule2=explode('|',$intitule);
      echo '<tr><td class="droite" width=15%><label class="label_class">'.$Langue['LBL_LISTES_ABREVIATION'].' <font color="#FF0000">*</font> :</label></td>';
      echo '<td class="gauche" width=85%><input type="text" class="text ui-widget-content ui-corner-all" name="abreviation" id="abreviation" value="'.$intitule2[0].'" size="5" maxlength="7"></td></tr>';
    }
    else
    {
      $intitule2[1]=$intitule;
      echo '<input type="hidden" name="abreviation" id="abreviation" value="-1">';
    }
  }
  else
  {
    if ($listes_colonne[$_GET['id_liste']]=="2")
    {
      echo '<tr><td class="droite" width=15%><label class="label_class">'.$Langue['LBL_LISTES_ABREVIATION'].' <font color="#FF0000">*</font> :</label></td>';
      echo '<td class="gauche" width=85%><input type="text" class="text ui-widget-content ui-corner-all" name="abreviation" id="abreviation" value="" size="5" maxlength="7"></td></tr>';
    }
    else
    {
      echo '<input type="hidden" name="abreviation" id="abreviation" value="-1">';
    }
    $intitule2[1]="";
  }
?>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_LISTES_INTITULE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%><input type="text" class="text ui-widget-content ui-corner-all" name="intitule" id="intitule" value="<?php echo $intitule2[1]; ?>" size="50" maxlength="200"></td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer2" name="Enregistrer2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="EnregistrerNouveau2" name="EnregistrerNouveau2" value="<?php echo $Langue['BTN_ENREGISTRER_CREER_INTITULE']; ?>">&nbsp;
    <input type="Button" id="Annuler2" name="Annuler2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite" valign=middle><font color="#FF0000">*</font> : <?php echo $Langue['LBL_CHAMP_OBLIGATOIRE']; ?></td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  /* Création des boutons */
  $("#Enregistrer").button();
  $("#Enregistrer2").button();
  $("#EnregistrerNouveau").button();
  $("#EnregistrerNouveau2").button();
  $("#Annuler").button();
  $("#Annuler2").button();
  
  /* Action des boutons */
  $("#Annuler").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Enregistrer").click(function()
  {
    action_save="fermer";
  });
  $("#Enregistrer2").click(function()
  {
    action_save="fermer";
    $("#form_editview2").submit();
  });
  $("#EnregistrerNouveau").click(function()
  {
    action_save="nouveau";
    $("#form_editview2").submit();
  });
  $("#EnregistrerNouveau2").click(function()
  {
    action_save="nouveau";
    $("#form_editview2").submit();
  });

  /* Sauvegarde des données */
  $("#form_editview2").submit(function(event)
  {
		var bValid = true;
    event.preventDefault();
		/* On contrôle la saisie du formulaire */
    if ( bValid )
    {
      Message_Chargement(2,1);
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        Message_Chargement(2,0);
        Charge_Dialog("index2.php?module=configuration&action=config_listes&id_liste="+msg,"<?php echo $Langue['LBL_LISTES_PERSONNALISER_LISTES']; ?>");
        if (action_save=="fermer")
        {
          $("#dialog-niveau2").dialog( "close" );
        }
        else
        {
          Charge_Dialog2("index2.php?module=configuration&action=editview_intitule&id_liste="+msg+"&id=","<?php echo $Langue['BTN_LISTES_AJOUTER_INTITULE']; ?>");
        }
      });
    }
    else
    {
      updateTips("error","configuration","<?php echo $Langue['LBL_LISTES_MODIFIER_INTITULE']; ?>");
      action_save="rien";
    }
  });
});
</script>
