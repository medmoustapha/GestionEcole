<?php
  if ($_SESSION['type_util']=="D") { Param_Utilisateur('',$_SESSION['annee_scolaire']); } else { Param_Utilisateur($_SESSION['id_util'],$_SESSION['annee_scolaire']); }

  if (table_ok($gestclasse_config['param_connexion']['base'],"etablissement".$_SESSION['annee_scolaire'])==true)
  {
	if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	{
	  $debut_P1=$_SESSION['annee_scolaire']."-01-01";
	  $debut_P2=$_SESSION['annee_scolaire']."-03-15";
	  $debut_P3=$_SESSION['annee_scolaire']."-06-01";
	  $debut_P4=$_SESSION['annee_scolaire']."-07-15";
	  $debut_P5=$_SESSION['annee_scolaire']."-10-01";
	  $fin_P1=$_SESSION['annee_scolaire']."-03-14";
	  $fin_P2=$_SESSION['annee_scolaire']."-05-31";
	  $fin_P3=$_SESSION['annee_scolaire']."-07-14";
	  $fin_P4=$_SESSION['annee_scolaire']."-09-30";
	  $fin_P5=$_SESSION['annee_scolaire']."-12-31";
	  $debut_T1=$_SESSION['annee_scolaire']."-01-01";
	  $debut_T2=$_SESSION['annee_scolaire']."-05-01";
	  $debut_T3=$_SESSION['annee_scolaire']."-09-01";
	  $fin_T1=$_SESSION['annee_scolaire']."-04-30";
	  $fin_T2=$_SESSION['annee_scolaire']."-08-31";
	  $fin_T3=$_SESSION['annee_scolaire']."-12-31";
	}
	else
	{
	  $a=$_SESSION['annee_scolaire']+1;
	  $debut_P1=$_SESSION['annee_scolaire']."-08-01";
	  $debut_P2=$_SESSION['annee_scolaire']."-11-01";
	  $debut_P3=$a."-01-01";
	  $debut_P4=$a."-03-01";
	  $debut_P5=$a."-05-01";
	  $fin_P1=$_SESSION['annee_scolaire']."-10-31";
	  $fin_P2=$_SESSION['annee_scolaire']."-12-31";
	  $fin_P3=$a."-02-28";
	  $fin_P4=$a."-04-30";
	  $fin_P5=$a."-07-31";
	  $debut_T1=$_SESSION['annee_scolaire']."-08-01";
	  $debut_T2=$a."-01-01";
	  $debut_T3=$a."-05-01";
	  $fin_T1=$_SESSION['annee_scolaire']."-12-31";
	  $fin_T2=$a."-04-30";
	  $fin_T3=$a."-07-31";
	}
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_P1'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_P1','$debut_P1')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_P2'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_P2','$debut_P2')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_P3'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_P3','$debut_P3')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_P4'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_P4','$debut_P4')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_P5'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_P5','$debut_P5')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_P1'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_P1','$fin_P1')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_P2'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_P2','$fin_P2')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_P3'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_P3','$fin_P3')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_P4'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_P4','$fin_P4')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_P5'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_P5','$fin_P5')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_T1'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_T1','$debut_T1')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_T2'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_T2','$debut_T2')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='debut_T3'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('debut_T3','$debut_T3')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_T1'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_T1','$fin_T1')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_T2'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_T2','$fin_T2')"); }
    $req=mysql_query("SELECT * FROM `etablissement".$_SESSION['annee_scolaire']."` WHERE parametre='fin_T3'");
	if (mysql_num_rows($req)=="") { $req=mysql_query("INSERT INTO `etablissement".$_SESSION['annee_scolaire']."` (parametre,valeur) VALUES ('fin_T3','$fin_T3')"); }
    if ($_SESSION['type_util']=="D") { Param_Utilisateur('',$_SESSION['annee_scolaire']); } else { Param_Utilisateur($_SESSION['id_util'],$_SESSION['annee_scolaire']); }
  }
?>
<a name="haut_formulaire"></a>
<form action="index2.php" method=POST name="form_editview" id="form_editview">
<input type="hidden" name="module" value="configuration">
<input type="hidden" name="action" value="save_livrets_<?php echo $_SESSION['type_util']; ?>">
<font id="msg_ok"></font>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input tabIndex=26 type="Submit" id="Enregistrer" name="Enrigistrer" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input tabIndex=27 type="Button" id="Annuler" name="Annuler" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite">
    <input tabIndex=28 type="Button" id="aide_fenetre" name="aide_fenetre" value="<?php echo $Langue['BTN_AIDE']; ?>">
  </td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_LIVRETS_PERIODICITE']; ?></div>
<table cellspacing=0 cellpadding=0 border=0 width=100% class="tableau_editview">
<tr>
  <td width=15% class="droite"><label class="label_class"><?php echo $Langue['LBL_LIVRETS_PUBLICATION']; ?> :</label></td>
  <td width=85% class="gauche"><input tabIndex=1 type=radio name=decoupage_livret value="P" <?php if ($gestclasse_config_plus['decoupage_livret']=="P") { echo "CHECKED"; } ?>><?php echo $Langue['LBL_LIVRETS_PUBLICATION_PERIODE']; ?></td>
</tr>
<tr>
  <td width=15% class="droite"><label class="label_class">&nbsp;</label></td>
  <td width=85% class="gauche"><input tabIndex=2 type=radio name=decoupage_livret value="T" <?php if ($gestclasse_config_plus['decoupage_livret']=="T") { echo "CHECKED"; } ?>><?php echo $Langue['LBL_LIVRETS_PUBLICATION_TRIMESTRE']; ?></td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_LIVRETS_PERIODE_TRIMESTRE']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="centre" width=15%>&nbsp;</td>
  <td class="centre" width=17%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_PERIODE1']; ?></strong></label></td>
  <td class="centre" width=17%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_PERIODE2']; ?></strong></label></td>
  <td class="centre" width=17%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_PERIODE3']; ?></strong></label></td>
  <td class="centre" width=17%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_PERIODE4']; ?></strong></label></td>
  <td class="centre" width=17%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_PERIODE5']; ?></strong></label></td>
</tr>
<tr>
  <td class="gauche" width=15%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_DEBUT']; ?></strong></label></td>
  <td class="centre" width=17%><div id="div_debut_P1"><?php echo Date_Convertir($gestclasse_config_plus['debut_P1'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_P1" id="debut_P1" value="'.Date_Convertir($gestclasse_config_plus['debut_P1'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
  <td class="centre" width=17%><div id="div_debut_P2"><?php echo Date_Convertir($gestclasse_config_plus['debut_P2'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_P2" id="debut_P2" value="'.Date_Convertir($gestclasse_config_plus['debut_P2'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
  <td class="centre" width=17%><div id="div_debut_P3"><?php echo Date_Convertir($gestclasse_config_plus['debut_P3'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_P3" id="debut_P3" value="'.Date_Convertir($gestclasse_config_plus['debut_P3'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
  <td class="centre" width=17%><div id="div_debut_P4"><?php echo Date_Convertir($gestclasse_config_plus['debut_P4'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_P4" id="debut_P4" value="'.Date_Convertir($gestclasse_config_plus['debut_P4'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
  <td class="centre" width=17%><div id="div_debut_P5"><?php echo Date_Convertir($gestclasse_config_plus['debut_P5'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_P5" id="debut_P5" value="'.Date_Convertir($gestclasse_config_plus['debut_P5'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
</tr>
<tr>
  <td class="gauche" width=15%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_FIN']; ?></strong></label></td>
  <td class="centre" width=17%><input type=text class="text ui-widget-content ui-corner-all" id="fin_P1" name="fin_P1" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_P1'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
  <td class="centre" width=17%><input type=text class="text ui-widget-content ui-corner-all" id="fin_P2" name="fin_P2" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_P2'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
  <td class="centre" width=17%><input type=text class="text ui-widget-content ui-corner-all" id="fin_P3" name="fin_P3" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_P3'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
  <td class="centre" width=17%><input type=text class="text ui-widget-content ui-corner-all" id="fin_P4" name="fin_P4" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_P4'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
  <td class="centre" width=17%><input type=text class="text ui-widget-content ui-corner-all" id="fin_P5" name="fin_P5" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_P5'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="centre" width=16%>&nbsp;</td>
  <td class="centre" width=28%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_TRIMESTRE1']; ?></strong></label></td>
  <td class="centre" width=28%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_TRIMESTRE2']; ?></strong></label></td>
  <td class="centre" width=28%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_TRIMESTRE3']; ?></strong></label></td>
</tr>
<tr>
  <td class="gauche" width=16%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_DEBUT']; ?></strong></label></td>
  <td class="centre" width=28%><div id="div_debut_T1"><?php echo Date_Convertir($gestclasse_config_plus['debut_T1'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_T1" id="debut_T1" value="'.Date_Convertir($gestclasse_config_plus['debut_T1'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
  <td class="centre" width=28%><div id="div_debut_T2"><?php echo Date_Convertir($gestclasse_config_plus['debut_T2'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_T2" id="debut_T2" value="'.Date_Convertir($gestclasse_config_plus['debut_T2'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
  <td class="centre" width=28%><div id="div_debut_T3"><?php echo Date_Convertir($gestclasse_config_plus['debut_T3'],"Y-m-d",$Format_Date_PHP).'</div><input type="hidden" name="debut_T3" id="debut_T3" value="'.Date_Convertir($gestclasse_config_plus['debut_T3'],"Y-m-d",$Format_Date_PHP).'">'; ?></td>
</tr>
<tr>
  <td class="gauche" width=16%><label class="label_listview"><strong><?php echo $Langue['LBL_LIVRETS_FIN']; ?></strong></label></td>
  <td class="centre" width=28%><input type=text class="text ui-widget-content ui-corner-all" id="fin_T1" name="fin_T1" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_T1'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
  <td class="centre" width=28%><input type=text class="text ui-widget-content ui-corner-all" id="fin_T2" name="fin_T2" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_T2'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
  <td class="centre" width=28%><input type=text class="text ui-widget-content ui-corner-all" id="fin_T3" name="fin_T3" value="<?php echo Date_Convertir($gestclasse_config_plus['fin_T3'],"Y-m-d",$Format_Date_PHP); ?>" size=10 maxlength=10></td>
</tr>
</table>
<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
    $fin=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else  
  {
    $debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	$annee=$_SESSION['annee_scolaire']+1;
    $fin=$annee.$gestclasse_config_plus['fin_annee_scolaire'];
  }
  $plus=', minDate:new Date('.substr($debut,0,4).','.substr($debut,5,2).'-1,'.substr($debut,8,2).')';
  $plus=$plus.', maxDate:new Date('.substr($fin,0,4).','.substr($fin,5,2).'-1,'.substr($fin,8,2).')';
?>
<script language="Javascript">
$(document).ready(function()
{
  $( "#fin_P1" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  $( "#fin_P2" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  $( "#fin_P3" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  $( "#fin_P4" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  $( "#fin_P5" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  $( "#fin_T1" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  $( "#fin_T2" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  $( "#fin_T3" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true <?php echo $plus; ?> });
  
  $("#fin_P1").change(function() { $("#div_debut_P2").html(Ajouter_Un_Jour($("#fin_P1").val(),'<?php echo $Format_Date_PHP; ?>'));$("#debut_P2").val(Ajouter_Un_Jour($("#fin_P1").val(),'<?php echo $Format_Date_PHP; ?>')); });
  $("#fin_P2").change(function() { $("#div_debut_P3").html(Ajouter_Un_Jour($("#fin_P2").val(),'<?php echo $Format_Date_PHP; ?>'));$("#debut_P3").val(Ajouter_Un_Jour($("#fin_P2").val(),'<?php echo $Format_Date_PHP; ?>')); });
  $("#fin_P3").change(function() { $("#div_debut_P4").html(Ajouter_Un_Jour($("#fin_P3").val(),'<?php echo $Format_Date_PHP; ?>'));$("#debut_P4").val(Ajouter_Un_Jour($("#fin_P3").val(),'<?php echo $Format_Date_PHP; ?>')); });
  $("#fin_P4").change(function() { $("#div_debut_P5").html(Ajouter_Un_Jour($("#fin_P4").val(),'<?php echo $Format_Date_PHP; ?>'));$("#debut_P5").val(Ajouter_Un_Jour($("#fin_P4").val(),'<?php echo $Format_Date_PHP; ?>')); });
  $("#fin_T1").change(function() { $("#div_debut_T2").html(Ajouter_Un_Jour($("#fin_T1").val(),'<?php echo $Format_Date_PHP; ?>'));$("#debut_T2").val(Ajouter_Un_Jour($("#fin_T1").val(),'<?php echo $Format_Date_PHP; ?>')); });
  $("#fin_T2").change(function() { $("#div_debut_T3").html(Ajouter_Un_Jour($("#fin_T2").val(),'<?php echo $Format_Date_PHP; ?>'));$("#debut_T3").val(Ajouter_Un_Jour($("#fin_T2").val(),'<?php echo $Format_Date_PHP; ?>')); });
});
</script>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_LIVRETS_NOTATION']; ?></div>
<table cellspacing=0 cellpadding=0 border=0 width=100% class="tableau_editview">
<tr>
  <td width=100% class="gauche">
    <table class="display" cellpadding=0 cellspacing=0>
    <tr>
      <th rowspan=2 width=25% class="centre" class="ui-state-default"><?php echo $Langue['LBL_LIVRETS_COMPETENCE_MARQUEE']; ?></th>
      <th colspan=2 width=30% class="centre" class="ui-state-default"><?php echo $Langue['LBL_LIVRETS_TAUX_REUSSITE']; ?></th>
      <th rowspan=2 width=15% class="centre" class="ui-state-default"><?php echo $Langue['LBL_LIVRETS_COULEUR_ECRITURE']; ?></th>
      <th rowspan=2 width=15% class="centre" class="ui-state-default"><?php echo $Langue['LBL_LIVRETS_COULEUR_FOND']; ?></th>
      <th rowspan=2 width=15% class="centre" class="ui-state-default"><?php echo $Langue['LBL_LIVRETS_RESULTAT']; ?></th>
    </tr>
    <tr>
      <th width=15% class="centre" class="ui-state-default"><?php echo $Langue['LBL_LIVRETS_BORNE_INFERIEURE']; ?></th>
      <th width=15% class="centre" class="ui-state-default"><?php echo $Langue['LBL_LIVRETS_BORNE_SUPERIEURE']; ?></th>
    </tr>
<?php
    $Ligne="even";
    for ($i=0;$i<=9;$i++)
    {
      echo '<tr class="'.$Ligne.'">';
      if ($gestclasse_config_plus['c'.$i]!="")
      {
        $disabled="";
        $dernier=$i;
        $taux=$gestclasse_config_plus['i'.$i]." %";
        $selected=$gestclasse_config_plus['s'.$i];
        $background="background:".$gestclasse_config_plus['couleur'.$i];
        $background_fond="background:".$gestclasse_config_plus['couleur_fond'.$i];
        if ($gestclasse_config_plus['couleur'.$i]=="") { $image="transparent"; } else { $image="vide"; }
        if ($gestclasse_config_plus['couleur_fond'.$i]=="") { $image_fond="transparent"; } else { $image_fond="vide"; }
        $msg='<p style="min-width:50px;background:'.$gestclasse_config_plus['couleur_fond'.$i].';color:'.$gestclasse_config_plus['couleur'.$i].';padding:5px">'.$gestclasse_config_plus['c'.$i].'</p>';
      }
      else
      {
        $disabled="DISABLED";
        $selected=100;
        $taux="&nbsp;";
        $background="";
        $background_fond="";
        $image="vide";
        $image_fond="vide";
        $msg="";
      }
      echo '<td class="centre"><input type=text class="text ui-widget-content ui-corner-all" id=notation'.$i.' name=c'.$i.' value="'.$gestclasse_config_plus['c'.$i].'" size=40 maxlength=25 '.$disabled.' onChange="Change_Resultat(\''.$i.'\')"></td>';
      echo '<td class="centre"><font id="borne_inf'.$i.'">'.$taux.'</font></td>';
      echo '<td class="centre"><select name=s'.$i.' id="borne_sup'.$i.'" class="text ui-widget-content ui-corner-all" onChange="Change_Borne_Sup('.$i.')" '.$disabled.'>';
      for ($j=5;$j<=100;$j=$j+5)
      {
        echo '<option value="'.$j.'"';
        if ($j==$selected) { echo " SELECTED"; }
        echo '>'.$j.' %</option>';
      }
      echo '</select></td>';
      echo '<td class="centre"><div class="textcentre marge40_gauche"><div class="colorSelector" id="colorSelector_couleur'.$i.'"><div style="background-color: '.$gestclasse_config_plus['couleur'.$i].'"></div></div><input type=hidden id=couleur'.$i.' name=couleur'.$i.' value="'.$gestclasse_config_plus['couleur'.$i].'"></div></td>';
      echo '<script language="Javascript">
           $(document).ready(function()
           {
             $("#colorSelector_couleur'.$i.'").ColorPicker(
             {
               color:"'.$gestclasse_config_plus['couleur'.$i].'",
	             onShow: function (colpkr)
               {
		             $(colpkr).fadeIn(500);
		             $(colpkr).css("z-index", "5000");
		             return false;
               },
	             onHide: function (colpkr)
               {
		             $(colpkr).fadeOut(500);
		             return false;
              },
	            onChange: function (hsb, hex, rgb)
              {
		            $("#colorSelector_couleur'.$i.' div").css("backgroundColor", "#" + hex);
		            $("#couleur'.$i.'").val("#" + hex);
		            Change_Resultat("'.$i.'");
              }
            });
          });
          </script>';
      echo '<td class="centre"><div class="textcentre marge40_gauche"><div class="colorSelector" id="colorSelector_fond'.$i.'"><div style="background-color: '.$gestclasse_config_plus['couleur_fond'.$i].'"></div></div><input type=hidden id=couleur_fond'.$i.' name=couleur_fond'.$i.' value="'.$gestclasse_config_plus['couleur_fond'.$i].'"></div></td>';
      echo '<script language="Javascript">
           $(document).ready(function()
           {
             $("#colorSelector_fond'.$i.'").ColorPicker(
             {
               color:"'.$gestclasse_config_plus['couleur_fond'.$i].'",
	             onShow: function (colpkr)
               {
		             $(colpkr).fadeIn(500);
		             $(colpkr).css("z-index", "5000");
		             return false;
               },
	             onHide: function (colpkr)
               {
		             $(colpkr).fadeOut(500);
		             return false;
              },
	            onChange: function (hsb, hex, rgb)
              {
		            $("#colorSelector_fond'.$i.' div").css("backgroundColor", "#" + hex);
		            $("#couleur_fond'.$i.'").val("#" + hex);
		            Change_Resultat("'.$i.'");
              }
            });
          });
          </script>';
      echo '</td><td class="centre"><div id="resultat'.$i.'">'.$msg.'</div></td>';
      echo '</tr>';
      if ($Ligne=="even") { $Ligne="odd"; } else { $Ligne="even"; }
    }
?>
    </table>
<script language="Javascript">
dernier=<?php echo $dernier; ?>;

function Change_Resultat(id)
{
  if (document.getElementById('notation'+id).value!="" && document.getElementById('couleur'+id).value!="" && document.getElementById('couleur_fond'+id).value!="")
  {
    msg='<p style="min-width:50px;background:'+document.getElementById('couleur_fond'+id).value+';color:'+document.getElementById('couleur'+id).value+';padding:5px;">'+document.getElementById('notation'+id).value+'</p>';
    document.getElementById('resultat'+id).innerHTML=msg;
  }
}

function Change_Borne_Sup(id)
{
  id2=id+1;
  document.getElementById('borne_inf'+id2).innerHTML=document.getElementById('borne_sup'+id).value+" %";
  if (document.getElementById('borne_sup'+id).value==100 && id!=0)
  {
    for (i=id+1;i<=9;i++)
    {
      document.getElementById('borne_sup'+i).disabled=true;
      document.getElementById('borne_sup'+i).value=100;
      document.getElementById('notation'+i).disabled=true;
      document.getElementById('notation'+i).value="";
      document.getElementById('couleur'+i).value="";
      document.getElementById('couleur_fond'+i).value="";
      document.getElementById('borne_inf'+i).innerHTML="";
      document.getElementById('resultat'+i).innerHTML="";
    }
    dernier=id;
  }
  if (id==dernier)
  {
    if (document.getElementById('borne_sup'+id).value!=100)
    {
      if (id2!=9)
      {
        document.getElementById('borne_sup'+id2).disabled=false;
        document.getElementById('borne_sup'+id2).value=100;
      }
      document.getElementById('notation'+id2).disabled=false;
      dernier=id2;
    }
  }
}
</script>
  </td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_LIVRETS_CALCUL_RESULTATS']; ?></div>
<table cellspacing=0 cellpadding=0 border=0 width=100% class="tableau_editview2">
<tr>
  <td width=15% class="droite"><label class="label_class"><?php echo $Langue['LBL_LIVRETS_METHODE_CALCUL']; ?> :</label></td>
  <td width=85% class="gauche"><input tabIndex=1 type=radio name=calcul_moyenne value="D" <?php if ($gestclasse_config_plus['calcul_moyenne']=="D") { echo "CHECKED"; } ?>><?php echo $Langue['LBL_LIVRETS_DERNIERE_EVALUATION']; ?></td>
</tr>
<tr>
  <td width=15% class="droite"><label class="label_class">&nbsp;</label></td>
  <td width=85% class="gauche"><input tabIndex=2 type=radio name=calcul_moyenne value="M" <?php if ($gestclasse_config_plus['calcul_moyenne']=="M") { echo "CHECKED"; } ?>><?php echo $Langue['LBL_LIVRETS_MOYENNE_SANS_PONDERATION']; ?></td>
</tr>
<tr>
  <td width=15% class="droite"><label class="label_class">&nbsp;</label></td>
  <td width=85% class="gauche"><input tabIndex=2 type=radio name=calcul_moyenne value="P" <?php if ($gestclasse_config_plus['calcul_moyenne']=="P") { echo "CHECKED"; } ?>><?php echo $Langue['LBL_LIVRETS_MOYENNE_AVEC_PONDERATION']; ?></td>
</tr>
<tr>
  <td width=15% class="droite"><label class="label_class">&nbsp;</label></td>
  <td width=85% class="gauche"><input tabIndex=2 type=radio name=calcul_moyenne value="C" <?php if ($gestclasse_config_plus['calcul_moyenne']=="C") { echo "CHECKED"; } ?>><?php echo $Langue['LBL_LIVRETS_MOYENNE_CONTROLES_COEFFICIENTES']; ?></td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input tabIndex=24 type="Button" id="Enregistrer2" name="Enrigistrer2" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input tabIndex=25 type="Button" id="Annuler2" name="Annuler2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
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
<?php if ($_SESSION['type_util']=="D") { ?>
    window.open("http://www.doxconception.com/site/index.php/directeur-configuration/article/241-directeur-config-configurer-les-livrets-scolaires.html","Aide");
<?php } else { ?>
    window.open("http://www.doxconception.com/site/index.php/prof-configuration/article/240-prof-config-configurer-les-livrets-scolaires.html","Aide");
<?php } ?>
  });

  /* Création des boutons */
  $("#Enregistrer").button();
  $("#Enregistrer2").button();
  $("#Annuler").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="edit";
  });
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		for (i=0;i<=dernier;i++)
		{
      if (!checkValue($("#notation"+i))) { bValid=false; }
      if (!checkValue($("#couleur"+i))) { bValid=false; }
      if (!checkValue($("#couleur_fond"+i))) { bValid=false; }
    }
    event.preventDefault();
		/* On contrôle la saisie du formulaire */
    if ( bValid )
    {
      updateTips("save","configuration","<?php echo $Langue['LBL_LIVRETS_PARAMETRES_LS']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        Message_Chargement(2,1);
        parent.calcul.location.href='users/calcul_moyenne_tout.php?id_titulaire=<?php echo $_SESSION['id_util']; ?>&annee=<?php echo $_SESSION['annee_scolaire']; ?>';
        Charge_Dialog("index2.php?module=configuration&action=config_livrets","<?php echo $Langue['LBL_LIVRETS_PARAMETRES_LS']; ?>");
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","configuration","<?php echo $Langue['LBL_LIVRETS_PARAMETRES_LS']; ?>");
      action_save="rien";
    }
  });

  $("#Enregistrer2").click(function()
  {
    action_save="edit";
    $("#form_editview").submit();
  });
});
</script>
