<?php
  if (!isset($_GET['voircolonne'])) { $_GET['voircolonne']="1"; }
?>
<div class="floatgauche"><button id="colonne_centre" name="colonne_centre"><?php echo $Langue['BTN_PARTIE_CENTRALE']; ?></button>&nbsp;<button id="colonne_droite" name="colonne_droite"><?php echo $Langue['BTN_PARTIE_DROITE']; ?></button></div>
<div class="aide2"><button id="aide_fenetre" name="aide_fenetre"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 10px;"><?php echo $Langue['EXPL_AJOUT_PANNEAU']; ?></div></div><br />
<?php if ($_GET['voircolonne']=="1") { ?>
	<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation"><?php echo $Langue['LBL_PANNEAUX_RATTACHES_MODULE']; ?></div>
	<table class=display cellspacing=0 cellpadding=0>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_EMAIL']; ?></label></td>
	  <td class="gauche"><input type="button" id="email" name="email" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('email','5','<?php echo $Langue['LBL_EMAIL_TITRE']; ?>','1')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_BIBLIO_P_D']; ?></label></td>
	  <td class="gauche"><input type="button" id="bibliotheque" name="bibliotheque" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('bibliotheque','<?php if ($gestclasse_config_plus['biblio_ecole']=="1") { echo "E"; } else { echo "C"; } ?>|5','<?php echo $Langue['LBL_BIBLIO_TITRE_P_D']; ?>','1')"></td>
	</tr>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_REUNIONS']; ?></label></td>
	  <td class="gauche"><input type="button" id="reunions" name="reunions" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('reunions','L|5','<?php echo $Langue['LBL_REUNIONS_TITRE']; ?>','1')"></td>
	</tr>
	</table>
	<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation ui-div-separation-haut"><?php echo $Langue['LBL_PANNEAUX_INFOS']; ?></div>
	<table class=display cellspacing=0 cellpadding=0>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_NEWS_D']; ?></label></td>
	  <td class="gauche"><input type="button" id="actualites" name="actualites" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('news','T|5','<?php echo $Langue['LBL_NEWS_TITRE2']; ?>','1')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_TACHE']; ?></label></td>
	  <td class="gauche"><input type="button" id="taches" name="taches" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('taches','5','<?php echo $Langue['LBL_TACHE_TITRE']; ?>','1')"></td>
	</tr>
	</table>
	<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation ui-div-separation-haut"><?php echo $Langue['LBL_PANNEAUX_EXTERIEUR']; ?></div>
	<table class=display cellspacing=0 cellpadding=0>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_MINISTERE']; ?></label></td>
	  <td class="gauche"><input type="button" id="ministere" name="ministere" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('ministere','M|5','<?php echo $Langue['LBL_MINISTERE_TITRE']; ?>','1')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_GESTECOLE']; ?></label></td>
	  <td class="gauche"><input type="button" id="gestecole" name="gestecole" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('gestecole','5','<?php echo $Langue['LBL_GESTECOLE_TITRE']; ?>','1')"></td>
	</tr>
	</table>
<?php } else { ?>
	<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation"><?php echo $Langue['LBL_PANNEAUX_LATERAUX']; ?></div>
	<table class=display cellspacing=0 cellpadding=0>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_JOURNAUX']; ?></label></td>
	  <td class="gauche"><input type="button" id="journaux" name="journaux" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('journaux','5|F|U','<?php echo $Langue['LBL_JOURNAUX_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_ANNUAIRE']; ?></label></td>
	  <td class="gauche"><input type="button" id="annuaire" name="annuaire" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('annuaire','','<?php echo $Langue['LBL_ANNUAIRE_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_BLAGUES']; ?></label></td>
	  <td class="gauche"><input type="button" id="blague" name="blague" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('blague','','<?php echo $Langue['LBL_BLAGUES_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_CALCULETTE']; ?></label></td>
	  <td class="gauche"><input type="button" id="calculette" name="calculette" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('calculette','','<?php echo $Langue['LBL_CALCULETTE_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_FETE_JOUR']; ?></label></td>
	  <td class="gauche"><input type="button" id="fetedujour" name="fetedujour" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('fetedujour','','<?php echo $Langue['LBL_FETE_JOUR']; ?>','2')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_HOROSCOPE']; ?></label></td>
	  <td class="gauche"><input type="button" id="horoscope" name="horoscope" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('horoscope','belier','<?php echo $Langue['LBL_HOROSCOPE_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_HOROSCOPEC']; ?></label></td>
	  <td class="gauche"><input type="button" id="horoscope_c" name="horoscope_c" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('horoscope_c','rat','<?php echo $Langue['LBL_HOROSCOPEC_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_ITINERAIRE']; ?></label></td>
	  <td class="gauche"><input type="button" id="itineraire" name="itineraire" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('itineraire','','<?php echo $Langue['LBL_ITINERAIRE_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_PROGRAMMETV']; ?></label></td>
	  <td class="gauche"><input type="button" id="programmetv" name="programmetv" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('programmetv','1','<?php echo $Langue['LBL_PROGRAMMETV_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="odd">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_RADIO']; ?></label></td>
	  <td class="gauche"><input type="button" id="radio" name="radio" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('radio','ContactFM|1','<?php echo $Langue['LBL_RADIO_TITRE']; ?>','2')"></td>
	</tr>
	<tr class="even">
	  <td class="gauche" width=100%><label class="label_detail_non_gras"><?php echo $Langue['EXPL_RECHERCHE']; ?></label></td>
	  <td class="gauche"><input type="button" id="rechercher_gw" name="rechercher_gw" value="<?php echo $Langue['BTN_AJOUTER']; ?>" onClick="Accueil_Ajout_Panneau('rechercher_gw','','<?php echo $Langue['LBL_RECHERCHE']; ?>','2')"></td>
	</tr>
	</table>
<?php } ?>
<br />
<script language="Javascript">
$(document).ready(function()
{
  $("#colonne_centre").button();
  $("#colonne_droite").button();
  $("#colonne_centre").click(function()
  {
    Charge_Dialog("index2.php?module=accueil&action=ajouter_panneau&voircolonne=1","<?php echo $Langue['LBL_PERSONNALISER_ACCUEIL']; ?>");
  });  
  $("#colonne_droite").click(function()
  {
    Charge_Dialog("index2.php?module=accueil&action=ajouter_panneau&voircolonne=2","<?php echo $Langue['LBL_PERSONNALISER_ACCUEIL']; ?>");
  });  

  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
    window.open("http://www.doxconception.com/site/index.php/directeur-page-daccueil.html","Aide");
  });
  $("#email").button();
  $("#bibliotheque").button();
  $("#reunions").button();

  $("#actualites").button();
  $("#taches").button();

  $("#ministere").button();
  $("#gestecole").button();

  $("#annuaire").button();
  $("#blague").button();
  $("#calculette").button();
  $("#fetedujour").button();
  $("#horoscope").button();
  $("#horoscope_c").button();
  $("#itineraire").button();
  $("#journaux").button();
  $("#programmetv").button();
  $("#radio").button();
  $("#rechercher_gw").button();
});
</script>
