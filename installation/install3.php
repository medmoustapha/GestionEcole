<?php
  session_start();
  
  $_SESSION['accept_licence']="1";

  include "langues/fr-FR/installation.php";
  foreach ($Langue_Installation AS $cle => $value)
  {
		$Langue[$cle]=$value;
		$Langue_JS[$cle]=str_replace('"','&quot;',str_replace("'","\'",$value));
  }
  include "../langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
		$Langue[$cle]=$value;
		$Langue_JS[$cle]=str_replace('"','&quot;',str_replace("'","\'",$value));
  }
  if ($_SESSION['langue_install']!="fr-FR")
  {
		include "langues/".$_SESSION['langue_install']."/installation.php";
		foreach ($Langue_Installation AS $cle => $value)
		{
			$Langue[$cle]=$value;
			$Langue_JS[$cle]=str_replace('"','&quot;',str_replace("'","\'",$value));
		}
	}
?>
<!DOCTYPE html>
<head dir="<?php echo $Sens_Ecriture; ?>" lang="<?php echo $Langue_Valeur; ?>">
  <meta charset="utf-8">
  <title>Gest'Ecole - <?php echo $Langue['LBL_ETAPE3_TITRE']; ?></title>
      
  <!-- **************** -->
  <!-- * Fichiers CSS * -->
  <!-- **************** -->

  <!-- Fichiers CSS jQuery -->
    <link rel="stylesheet" href="../themes/redmond/jquery.ui.all_<?php echo $Sens_Ecriture; ?>.css">
    <link rel="stylesheet" href="../themes/redmond/personnel_<?php echo $Sens_Ecriture; ?>.css">
    <link rel="stylesheet" href="../themes/personnel_<?php echo $Sens_Ecriture; ?>.css">
		<link rel="stylesheet" href="../themes/jquery.datatables_<?php echo $Sens_Ecriture; ?>.css">

  <!-- Scripts jQuery fondamentaux -->
	  <script src="../commun/jquery/jquery.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.core.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.widget.js"></script>
	  <script src="../commun/jquery/ui/jquery.ui.mouse.js"></script>

  <!-- Scripts jQuery UI -->
	  <script src="../commun/jquery/ui/jquery.ui.button.js"></script>
<link rel="shortcut icon" href="../images/favicon.ico" />

<style type="text/css">
.vert { font-weight:bold;color:#008000; }
.rouge { font-weight:bold;color:#FF0000; }
</style>
</head>


<body dir="<?php echo $Sens_Ecriture; ?>">
<a name="haut_page"></a>
<!-- Entête de la page -->
<div align=center id="message" class="message_chargement ui-corner-all" style="visibility:hidden;z-index:5000;"></div>
<div class="ui-widget ui-widget-content ui-corner-all espacement_bas">
  <div class="ui-widget ui-widget-header ui-corner-all entete" style="height:40px;">
    <div class="floatgauche"><img src="../themes/images/logo_petit.png"> Gest'&Eacute;cole <font style="font-size:10px;font-weight:normal;">Version <?php echo $_SESSION['version_install']; ?></font></div>
  </div>
</div>

  <div class="ui-widget ui-widget-content ui-corner-all" style="min-height:630px;padding:10px" align=left>
  <div class="titre_page"><?php echo $Langue['LBL_ETAPE3_TITRE']; ?> : <?php echo $Langue['LBL_ETAPE1_EXPL6B']; ?></div><br /><br />
  <table class="display" align="center" cellspacing=0 cellpadding=0 width="100%">
  <tbody>
  <tr class="even">
    <td width="70%" style="padding:10px">
     <strong><?php echo $Langue['LBL_ETAPE3_EXPL1']; ?></strong>
    </td>
    <td width="20%" align=center>
<?php
      $disabled="false";
      $version = phpversion();
      echo $Langue['LBL_ETAPE3_EXPL1B']." : <b>".$version."</b>";
?>
    </td>
    <td width="10%" align=center>
      <?php if ($version>="5.2.0") { echo "<font class=vert>".$Langue['LBL_ETAPE3_OK']."</font>"; } else { echo "<font class=rouge>".$Langue['LBL_ETAPE3_ERREUR']."</font>"; $disabled="true"; } ?>
    </td>
  </tr>
  <tr class="odd">
    <td width="70%" style="padding:10px">
     <strong><?php echo $Langue['LBL_ETAPE3_EXPL2']; ?></strong>
    </td>
    <td width="20%" align=center>
      <?php if (function_exists( 'mysql_connect' )) { echo $Langue['LBL_ETAPE3_EXPL2B']; } else { echo $Langue['LBL_ETAPE3_EXPL2T']; } ?>
    </td>
    <td width="10%" align=center>
      <?php if (function_exists( 'mysql_connect' )) { echo "<font class=vert>".$Langue['LBL_ETAPE3_OK']."</font>"; } else { echo "<font class=rouge>".$Langue['LBL_ETAPE3_ERREUR']."</font>"; $disabled="true"; } ?>
    </td>
  </tr>
  <tr class="even">
    <td width="70%" style="padding:10px">
     <strong><?php echo $Langue['LBL_ETAPE3_EXPL3']; ?></strong>
    </td>
    <td width="20%" align=center>
      <?php if (is_writable(ini_get( 'session.save_path' ))) { echo $Langue['LBL_ETAPE3_EXPL3B']; } else { echo $Langue['LBL_ETAPE3_EXPL3T']; } ?>
    </td>
    <td width="10%" align=center>
      <?php if (is_writable(ini_get( 'session.save_path' ))) { echo "<font class=vert>".$Langue['LBL_ETAPE3_OK']."</font>"; } else { echo "<font class=rouge>".$Langue['LBL_ETAPE3_ERREUR']."</font>"; $disabled="true"; } ?>
    </td>
  </tr>
  <tr class="odd">
    <td width="70%" style="padding:10px">
     <strong><?php echo $Langue['LBL_ETAPE3_EXPL4']; ?> :</strong>
    </td>
    <td width="20%" align=center>&nbsp;</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr class="even">
    <td width="70%" style="padding:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Langue['LBL_ETAPE3_EXPL4B']; ?></td>
	<td width="20%" align=center>
    <?php if (!is_writable("..")) { echo $Langue['LBL_ETAPE3_EXPL3T']."</td>"; $inscriptible2="0"; } else { echo $Langue['LBL_ETAPE3_EXPL3B']."</td>";$inscriptible2="1"; } ?>
	</td>
	<td width="10%" align=center>
    <?php if (is_writable("..")) { echo "<font class=vert>".$Langue['LBL_ETAPE3_OK']."</font>"; } else { echo "<font class=rouge>".$Langue['LBL_ETAPE3_ERREUR']."</font>"; $disabled="true"; } ?>
	</td>
  </tr>
  <?php
   $repertoire=Array("absences","accueil","accueil/subpanels","accueil/subpanels/cache","aproposde","bibliotheque","cache","cache/documents","cache/email","cache/ged","cache/photos","cache/restore","cache/update","cache/upload","cahier","calendrier","classes","commun","commun/calculette","commun/colorpicker","commun/elfinder","commun/elfinder/css","commun/elfinder/files","commun/elfinder/img","commun/elfinder/js","commun/elfinder/js/i18n","commun/elfinder/js/proxy","commun/elfinder/php",
					 "commun/html2pdf","commun/html2pdf/_class","commun/html2pdf/_tcpdf_5.0.002","commun/html2pdf/_tcpdf_5.0.002/cache","commun/html2pdf/_tcpdf_5.0.002/config","commun/html2pdf/_tcpdf_5.0.002/config/lang","commun/html2pdf/_tcpdf_5.0.002/doc","commun/html2pdf/_tcpdf_5.0.002/examples","commun/html2pdf/_tcpdf_5.0.002/fonts","commun/html2pdf/_tcpdf_5.0.002/fonts/dejavu-fonts-ttf-2.30","commun/html2pdf/_tcpdf_5.0.002/fonts/freefont-20090104","commun/html2pdf/_tcpdf_5.0.002/fonts/utils","commun/html2pdf/_tcpdf_5.0.002/fonts/utils/enc","commun/html2pdf/_tcpdf_5.0.002/fonts/utils/src","commun/html2pdf/_tcpdf_5.0.002/images","commun/html2pdf/locale",
					 "commun/jcrop","commun/jquery","commun/jquery/ui","commun/jquery/ui/i18n","commun/jquery/ui/minified","commun/magpierss","commun/magpierss/extlib","commun/magpierss/htdocs","commun/magpierss/scripts","commun/magpierss/scripts/smarty_plugin","commun/magpierss/scripts/templates","commun/mpdf","commun/mpdf/classes","commun/mpdf/font","commun/mpdf/graph_cache","commun/mpdf/iccprofiles","commun/mpdf/includes","commun/mpdf/mpdfi","commun/mpdf/mpdfi/filters","commun/mpdf/patterns","commun/mpdf/tmp","commun/mpdf/ttfontdata","commun/mpdf/ttfonts","commun/mpdf/utils","commun/pclzip","commun/phplib","commun/phplib/contrib","commun/phplib/doc","commun/phplib/doc/sgml","commun/phplib/pages","commun/phplib/pages/admin","commun/phplib/pages/menu","commun/phplib/php","commun/phplib/stuff","commun/phplib/unsup",
					 "commun/tinymce","commun/tinymce/langs","commun/tinymce/plugins","commun/tinymce/plugins/advlink","commun/tinymce/plugins/advlink/css","commun/tinymce/plugins/advlink/js","commun/tinymce/plugins/advlink/langs","commun/tinymce/plugins/advlist","commun/tinymce/plugins/autolink","commun/tinymce/plugins/autoresize","commun/tinymce/plugins/autosave","commun/tinymce/plugins/autosave/langs","commun/tinymce/plugins/bbcode","commun/tinymce/plugins/contextmenu","commun/tinymce/plugins/directionality","commun/tinymce/plugins/fullpage","commun/tinymce/plugins/fullpage/css","commun/tinymce/plugins/fullpage/js","commun/tinymce/plugins/fullpage/langs",
					 "commun/tinymce/plugins/iespell","commun/tinymce/plugins/inlinepopups","commun/tinymce/plugins/inlinepopups/skins","commun/tinymce/plugins/inlinepopups/skins/clearlooks2","commun/tinymce/plugins/inlinepopups/skins/clearlooks2/img","commun/tinymce/plugins/layer","commun/tinymce/plugins/legacyoutput","commun/tinymce/plugins/lists","commun/tinymce/plugins/nonbreaking","commun/tinymce/plugins/noneditable","commun/tinymce/plugins/paste","commun/tinymce/plugins/paste/js","commun/tinymce/plugins/paste/langs","commun/tinymce/plugins/preview","commun/tinymce/plugins/preview/jscripts","commun/tinymce/plugins/spellchecker","commun/tinymce/plugins/spellchecker/css","commun/tinymce/plugins/spellchecker/img",
					 "commun/tinymce/plugins/style","commun/tinymce/plugins/style/css","commun/tinymce/plugins/style/js","commun/tinymce/plugins/style/langs","commun/tinymce/plugins/tabfocus","commun/tinymce/plugins/template","commun/tinymce/plugins/template/css","commun/tinymce/plugins/template/js","commun/tinymce/plugins/template/langs","commun/tinymce/plugins/visualblocks","commun/tinymce/plugins/visualblocks/css","commun/tinymce/plugins/visualchars","commun/tinymce/plugins/wordcount","commun/tinymce/plugins/xhtmlxtras","commun/tinymce/plugins/xhtmlxtras/css","commun/tinymce/plugins/xhtmlxtras/js","commun/tinymce/plugins/xhtmlxtras/langs",
					 "commun/tinymce/themes","commun/tinymce/themes/advanced","commun/tinymce/themes/advanced/img","commun/tinymce/themes/advanced/js","commun/tinymce/themes/advanced/langs","commun/tinymce/themes/advanced/skins","commun/tinymce/themes/advanced/skins/default","commun/tinymce/themes/advanced/skins/default/img","commun/tinymce/themes/simple","commun/tinymce/themes/simple/img","commun/tinymce/themes/simple/langs","commun/tinymce/themes/simple/skins","commun/tinymce/themes/simple/skins/default","commun/tinymce/themes/simple/skins/o2k7","commun/tinymce/themes/simple/skins/o2k7/img","commun/tinymce/utils",
					 "commun/webcam","commun/webcam/com","commun/webcam/com/adobe","commun/webcam/com/adobe/images","compte","configuration","cooperative","devoirs","eleves","email","ged","images","images/colorpicker","images/extensions","images/ged","langues","langues/fr-FR","livrets","personnels","themes","themes/base","themes/base/images","themes/blitzer","themes/blitzer/images","themes/humanity","themes/humanity/images","themes/images","themes/ligthness","themes/ligthness/images","themes/redmond","themes/redmond/images","themes/smoothness","themes/smoothness/images","themes/southstreet","themes/southstreet/images","users");
   $ligne="odd";	
   $inscriptible="1";
   for ($i=0;$i<count($repertoire);$i++)
   {
     echo '<tr class="'.$ligne.'">';
     echo '<td width="70%" style="padding:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;./'.$repertoire[$i].'</td>';
     echo '<td width="20%" align=center>';
     if (!is_writable("../".$repertoire[$i])) { echo $Langue['LBL_ETAPE3_EXPL3T']; $inscriptible="0"; } else { echo $Langue['LBL_ETAPE3_EXPL3B']; }
	 echo '</td>';
     echo '<td width="10%" align=center>';
     if (is_writable("../".$repertoire[$i])) { echo "<font class=vert>".$Langue['LBL_ETAPE3_OK']."</font>"; } else { echo "<font class=rouge>".$Langue['LBL_ETAPE3_ERREUR']."</font>"; $disabled="true"; } 
     echo '</td></tr>';
	 if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
   }
  ?>
  </tbody>
  </table>
	<p class=explic>&nbsp;</p>
	<div class="textdroite"><input type="button" class="bouton" id="Retour" name="Retour" value="<?php echo $Langue['BTN_RETOUR_ETAPE']; ?>" onClick="document.location.href='install2.php'">&nbsp;<input type="button" class="bouton" id="Suivant" name="Suivant" value="<?php echo $Langue['LBL_ETAPE3_EXPL5']; ?>" onClick="document.location.href='install4.php'"></div>
  </div>	
<script language="Javascript">
$(document).ready(function()
{
  $("#Retour").button();
  $("#Suivant").button({ disabled:<?php echo $disabled; ?> });
});
</script>	  
</body>
</html>
  
