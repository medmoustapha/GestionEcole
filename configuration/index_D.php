<div class="titre_page"><?php echo $Langue['LBL_CONFIGURATION']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<br><br><br>
<div class="floatdroite"><div class="ui-widget ui-state-default ui-corner-all marge10_gauche marge10_droite marge5_haut marge5_bas textcentre"><?php echo $Langue['LBL_CONFIGURATION_ANNEE_SCOLAIRE']; ?> : <select name="annee_s" id="annee_s" class="text ui-widget-content ui-corner-all">
<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
      $annee=date("Y");
	  $annee_p=$annee+1;
	  $msg ='<option value="'.$annee.'"';
	  if ($annee==$_SESSION['annee_scolaire']) { $msg .=' SELECTED'; }
	  $msg .='>'.$annee.'</option>';
      $msg .='<option value="'.$annee_p.'"';
	  if ($annee_p==$_SESSION['annee_scolaire']) { $msg .=' SELECTED'; }
	  $msg .='>'.$annee_p.'</option>';
  }
  else
  {
      if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire'])
      {
  	    $annee=date("Y")-1;
	  }
	  else
	  {
	    $annee=date("Y");
	  }
	  $annee_p=$annee+1;
	  $msg ='<option value="'.$annee.'"';
	  if ($annee==$_SESSION['annee_scolaire']) { $msg .=' SELECTED'; }
	  $msg .='>'.$annee.'-'.$annee_p.'</option>';
      $msg .='<option value="'.$annee_p.'"';
	  if ($annee_p==$_SESSION['annee_scolaire']) { $msg .=' SELECTED'; }
      $annee=$annee_p+1;
	  $msg .='>'.$annee_p.'-'.$annee.'</option>';
  }
	  echo $msg;
?>
</select></div></div><br><br><br>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_INFOS_ETAB']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview" style="width:100%">
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="coordonnees"><?php echo $Langue['BTN_COORDONNEES']; ?></button></td>
  <td class="centre" style="width:34%"><button style="width:300px" id="horaires"><?php echo $Langue['BTN_HORAIRES']; ?></button></td>
  <td class="centre" style="width:33%"><button style="width:300px" id="logo"><?php echo $Langue['BTN_LOGO']; ?></button></td>
</tr>
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="livrets"><?php echo $Langue['BTN_LIVRETS']; ?></button></td>
  <td class="centre" style="width:34%"><button style="width:300px" id="bibliotheque"><?php echo $Langue['BTN_BIBLIO']; ?></button></td>
  <td class="centre" style="width:33%"><button style="width:300px" id="cooperative"><?php echo $Langue['BTN_COOPERATIVE']; ?></button></td>
</tr>
</table>
<br /><br />
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_CONFIGURATION_APPLICATION']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview" style="width:100%">
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="application"><?php echo $Langue['BTN_CONFIGURATION_GENERALE']; ?></button></td>
  <td class="centre" style="width:34%"><button style="width:300px" id="onglets"><?php echo $Langue['BTN_ONGLETS']; ?></button></td>
  <td class="centre" style="width:33%"><button style="width:300px" id="listes"><?php echo $Langue['BTN_LISTES_CHOIX']; ?></button></td>
</tr>
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="signatures"><?php echo $Langue['LBL_CONFIG_SIGNATURE']; ?></button></td>
  <td class="centre" style="width:34%">&nbsp;</td>
  <td class="centre" style="width:33%">&nbsp;</td>
</tr>
</table>
<br /><br />
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_MAINTENANCE']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview" style="width:100%">
<tr>
  <?php
    $decalage=intval((strtotime(date("Y-m-d"))-strtotime($gestclasse_config_plus['sauve_bdd']))/86400)+1;
    if ($decalage>=15) 
	{ 
	  $msg='<font color="#FF0000"><strong>('.$Langue['LBL_DERNIERE_SAUVEGARDE2'].' '.$decalage.' '.$Langue['LBL_JOURS'].' '.Date_Convertir($gestclasse_config_plus['sauve_bdd'],"Y-m-d",$Format_Date_PHP).')</strong></font>';
	}
	else
	{ 
	  $msg='('.$Langue['LBL_DERNIERE_SAUVEGARDE'].' '.Date_Convertir($gestclasse_config_plus['sauve_bdd'],"Y-m-d",$Format_Date_PHP).')';
	}
  ?>
  <td class="centre" style="width:33%" valign=top><button style="width:300px" id="sauvebdd"><?php echo $Langue['BTN_SAUVE_BDD']; ?></button><br /><?php echo $msg; ?></td>
  <td class="centre" style="width:34%" valign=top><button style="width:300px" id="restaurebdd"><?php echo $Langue['BTN_RESTAURE_BDD']; ?></button></td>
  <td class="centre" style="width:33%" valign=top><button style="width:300px" id="nettoiebdd"><?php echo $Langue['BTN_NETTOYAGE_BDD']; ?></button></td>
</tr>
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="maj"><?php echo $Langue['BTN_MAJ_APPLICATION']; ?></button></td>
  <td class="centre" style="width:34%"><button style="width:300px" id="langues"><?php echo $Langue['BTN_LANGUE_AJOUT']; ?></button></td>
  <td class="centre" style="width:33%">&nbsp;</td>
</tr>
</table>
<br /><br />
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_OPTIONS_SUPPL']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview" style="width:100%">
<tr>
  <td class="centre" style="width:33%"><button style="width:300px" id="phpinfo"><?php echo $Langue['BTN_CONFIGURATION_PHPINFO']; ?></button></td>
  <td class="centre" style="width:34%"><button style="width:300px" id="vidercache"><?php echo $Langue['BTN_VIDER_CACHE']; ?></button></td>
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
    window.open("http://www.doxconception.com/site/index.php/directeur-configuration.html","Aide");
  });

  /* Création des boutons */
  $("#coordonnees").button();
  $("#horaires").button();
  $("#logo").button();
  $("#livrets").button();
  $("#bibliotheque").button();
  $("#cooperative").button();

  $("#application").button();
  $("#onglets").button();
  $("#listes").button();
  $("#signatures").button();

  $("#maj").button();
  $("#sauvebdd").button();
  $("#restaurebdd").button();
  $("#nettoiebdd").button();
  $("#langues").button();
  
  $("#phpinfo").button();
  $("#vidercache").button();

  /* Autour de l'établissement */
  $("#coordonnees").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=coordonnees","<?php echo $Langue['LBL_COORDONNEES_TITRE']; ?>");
  });
  $("#horaires").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=horaires","<?php echo $Langue['LBL_HORAIRES_TITRE']; ?>");
  });
  $("#livrets").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=config_livrets","<?php echo $Langue['LBL_LIVRETS_PARAMETRES_LS']; ?>");
  });
  $("#logo").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=config_logo","<?php echo $Langue['LBL_LOGO']; ?>");
  });
  $("#bibliotheque").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=bibliotheque","<?php echo $Langue['LBL_BIBLIO_TITRE']; ?>");
  });
  $("#cooperative").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=cooperative","<?php echo $Langue['LBL_COOPERATIVE_TITRE']; ?>");
  });

  /* Autour des personnalisations */
  $("#application").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=messagerie","<?php echo $Langue['LBL_CONFIG_TITRE']; ?>");
  });
  $("#onglets").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=config_onglets","<?php echo $Langue['LBL_ONGLETS_TITRE']; ?>");
  });
  $("#listes").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=config_listes","<?php echo $Langue['LBL_LISTES_PERSONNALISER_LISTES']; ?>");
  });
  $("#signatures").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=editview&id=signatures","<?php echo $Langue['LBL_CONFIG_SIGNATURE']; ?>");
  });

  /* Autour de la base de données */
  $("#sauvebdd").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=sauvegarde_bdd","<?php echo $Langue['BTN_SAUVE_BDD']; ?>");
  });
  $("#restaurebdd").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=restauration_bdd","<?php echo $Langue['LBL_RESTAURATION_TITRE']; ?>");
  });
  $("#nettoiebdd").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=nettoyage_bdd","<?php echo $Langue['BTN_NETTOYAGE_BDD']; ?>");
  });
  $("#maj").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=mise_a_jour","<?php echo $Langue['LBL_MAJ_TITRE']; ?>");
  });
  $("#langues").click(function()
  {
    Charge_Dialog("index2.php?module=configuration&action=langues","<?php echo $Langue['LBL_LANGUE_TITRE']; ?>");
  });
  
  $("#phpinfo").click(function()
  {
    window.open("phpinfo.php","phpinfo");
  });
  $("#vidercache").click(function()
  {
    Message_Chargement(11,1);
	var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=vider_cache"});
    request.done(function()
    {
	  Message_Chargement(12,1);
	  setTimeout(function()
	  {
		Message_Chargement(12,0);
	  }, 3000 );
  	});
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
