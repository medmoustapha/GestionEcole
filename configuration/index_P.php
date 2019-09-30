<div class="titre_page"><?php echo $Langue['LBL_CONFIGURATION']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<br><br><br><br>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_INFOS_ETAB']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview" style="width:100%">
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="horaires"><?php echo $Langue['BTN_HORAIRES']; ?></button></td>
  <td class="centre" style="width:34%"><button style="width:300px" id="livrets"><?php echo $Langue['BTN_LIVRETS']; ?></button></td>
  <td class="centre" style="width:33%"><button style="width:300px" id="bibliotheque"><?php echo $Langue['BTN_BIBLIO2']; ?></button></td>
</tr>
</table>
<br /><br />
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_CONFIGURATION_APPLICATION']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview" style="width:100%">
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="onglets"><?php echo $Langue['BTN_ONGLETS']; ?></button></td>
  <td class="centre" style="width:34%"><button style="width:300px" id="listes"><?php echo $Langue['BTN_LISTES_CHOIX']; ?></button></td>
  <td class="centre" style="width:33%">&nbsp;</td>
</tr>
</table>

<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/prof-configuration.html","Aide");
  });

  /* Création des boutons */
  $("#horaires").button();
  $("#livrets").button();
  $("#bibliotheque").button();
  $("#onglets").button();
  $("#listes").button();

  $("#horaires").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=horaires","<?php echo $Langue['LBL_HORAIRES_TITRE_PROFS']; ?>");
  });
  $("#livrets").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=config_livrets","<?php echo $Langue['LBL_LIVRETS_PARAMETRES_LS_PROFS']; ?>");
  });
  $("#bibliotheque").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=bibliotheque","<?php echo $Langue['LBL_BIBLIO_TITRE_PROFS']; ?>");
  });

  $("#onglets").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=config_onglets","<?php echo $Langue['LBL_ONGLETS_TITRE']; ?>");
  });
  $("#listes").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=config_listes","<?php echo $Langue['LBL_LISTES_PERSONNALISER_LISTES']; ?>");
  });

  $("#annee_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_annee.php";
     var data="annee_choisi="+$("#annee_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
  });

  $( "#dialog:ui-dialog" ).dialog( "destroy" );

});
</script>
