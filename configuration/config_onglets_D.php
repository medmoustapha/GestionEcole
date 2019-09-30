<?php
	Param_Utilisateur('',$_SESSION['annee_scolaire']);
  $onglet_D=explode(",",$gestclasse_config_plus['onglet_D']);
  $onglet_P=explode(",",$gestclasse_config_plus['onglet_P']);
  $onglet_E=explode(",",$gestclasse_config_plus['onglet_E']);
?>
<a name="haut_formulaire"></a>
<form name="editview_form" id="editview_form" action="index2.php" method="POST">
<input type="hidden" id="module" name="module" value="configuration">
<input type="hidden" id="action" name="action" value="save_onglets">
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer" name="Enregistrer" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">
    <input type="Button" id="Annuler" name="Annuler" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite">
    <input type="Button" id="aide_fenetre" name="aide_fenetre" value="<?php echo $Langue['BTN_AIDE']; ?>">
  </td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview2" style="width:100%;padding-top:0px;margin-top:0px">
<tr>
  <td class="marge10_droite" style="width:33%;"><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_ONGLETS_DIRECTEUR']; ?></div></td>
  <td class="marge10_droite marge10_gauche" style="width:34%;"><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_ONGLETS_ENSEIGNANTS']; ?></div></td>
  <td class="marge10_gauche" style="width:33%;"><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_ONGLETS_PARENTS']; ?></div></td>
</tr>
<tr>
  <td class="marge10_droite gauche" style="width:33%;vertical-align:top;">
  <?php
    foreach ($onglet['D'] AS $cle => $value)
    {
      if (in_array($cle,$onglet_obligatoire))
      {
        echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="directeur" name="directeur[]" value="'.$cle.'" checked disabled> '.$value.'</p>';
      }
      else
      {
        if (in_array($cle,$onglet_D))
        {
          echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="directeur" name="directeur[]" value="'.$cle.'" checked> '.$value.'</p>';
        }
        else
        {
          echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="directeur" name="directeur[]" value="'.$cle.'"> '.$value.'</p>';
        }
      }
    }
  ?>
  </td>
  <td class="marge10_droite marge10_gauche gauche" style="width:34%;vertical-align:top;">
  <?php
    foreach ($onglet['P'] AS $cle => $value)
    {
      if (in_array($cle,$onglet_obligatoire))
      {
        echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="enseignant" name="enseignant[]" value="'.$cle.'" checked disabled> '.$value.'</p>';
      }
      else
      {
        if (in_array($cle,$onglet_P))
        {
          echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="enseignant" name="enseignant[]" value="'.$cle.'" checked> '.$value.'</p>';
        }
        else
        {
          echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="enseignant" name="enseignant[]" value="'.$cle.'"> '.$value.'</p>';
        }
      }
    }
  ?>
  </td>
  <td class="marge10_gauche gauche" style="width:33%;vertical-align:top;">
  <?php
    foreach ($onglet['E'] AS $cle => $value)
    {
      if (in_array($cle,$onglet_obligatoire))
      {
        echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="eleve" name="eleve[]" value="'.$cle.'" checked disabled> '.$value.'</p>';
      }
      else
      {
        if (in_array($cle,$onglet_E))
        {
          echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="eleve" name="eleve[]" value="'.$cle.'" checked> '.$value.'</p>';
        }
        else
        {
          echo '<p class="explic">&nbsp;&nbsp;&nbsp;<input type="checkbox" id="eleve" name="eleve[]" value="'.$cle.'"> '.$value.'</p>';
        }
      }
    }
  ?>
  </td>
</tr>
</table>

<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Enregistrer2" name="Enregistrer2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">
    <input type="Button" id="Annuler2" name="Annuler2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
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
    window.open("http://www.doxconception.com/site/index.php/directeur-configuration/article/236-directeur-configurer-les-onglets-accessibles-aux-differents-utilisateurs.html","Aide");
  });

  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#Enregistrer2").button();
  $("#Annuler").button();
  $("#Annuler2").button();
  
  /* Fonctions des boutons */
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  $("#Enregistrer").click(function()
  {
    action_save="edit";
  });
  $("#Enregistrer2").click(function()
  {
    action_save="edit";
    $("#editview_form").submit();
  });

  /* Formulaire */
  $("#editview_form").submit(function(event)
  {
    event.preventDefault();
    Message_Chargement(2,1);
    var $form = $( this );
    url = $form.attr( 'action' );
    data = $form.serialize();
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
			$("#dialog-form").scrollTop(0);
      Message_Chargement(2,0);
		  $("#msg_ok").fadeIn( 1000 );
      $("#msg_ok").html('<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout"><?php echo $Langue['MSG_ONGLETS_SAUVEGARDER']; ?></div></div>');
  		setTimeout(function()
      {
        $("#msg_ok").effect("blind",1000);
      }, 3000 );
    });
  });
});
</script>
