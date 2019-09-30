<?php
  session_start();

  include "config.php";

  if (!isset($_SESSION['language_application']))
  {
    mysql_connect($gestclasse_config['param_connexion']['serveur'],$gestclasse_config['param_connexion']['user'],$gestclasse_config['param_connexion']['passe']);
    @mysql_select_db($gestclasse_config['param_connexion']['base']);
	$req=mysql_query("SELECT * FROM `config` WHERE parametre='langue_defaut'");
	if (mysql_num_rows($req)=="")
	{
      $_SESSION['language_application']="fr-FR";
	}
	else
	{
      $_SESSION['language_application']=mysql_result($req,0,'valeur');
	}
  }

  include "langues/fr-FR/config.php";
  include "langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
	  $Langue[$cle]=$Langue_Application[$cle];
  }
  if ($_SESSION['language_application']!="fr-FR")
  {
	if (file_exists("langues/".$_SESSION['language_application']."/config.php"))
	{
	  include "langues/".$_SESSION['language_application']."/config.php";
	}
	if (file_exists("langues/".$_SESSION['language_application']."/commun.php"))
	{
	  include "langues/".$_SESSION['language_application']."/commun.php";
	  foreach ($Langue_Application AS $cle => $value)
	  {
	    $Langue[$cle]=$Langue_Application[$cle];
	  }
	}
  }

  include "config_parametre.php";
  
  include "commun/fonctions.php";
  include "commun/phplib/php/template.inc";

  Connexion_DB();
  
  if (isset($_SESSION['type_util']))
  {
    if ($_SESSION['type_util']=="D")
    {
      Param_Utilisateur($_SESSION['id_util'],$_SESSION['annee_scolaire']);
    }
    else
    {
      if (isset($_SESSION['titulaire_classe_cours']))
      {
        Param_Utilisateur($_SESSION['titulaire_classe_cours'],$_SESSION['annee_scolaire']);
      }
    }
  }
  else
  {
    $req=mysql_query("SELECT * FROM `config` WHERE parametre='version_de_l_application'");
		$gestclasse_config_plus['version_de_l_application']=mysql_result($req,0,'valeur');
    $req=mysql_query("SELECT * FROM `config` WHERE parametre='message_connexion'");
		$gestclasse_config_plus['message_connexion']=mysql_result($req,0,'valeur');
  }

  // On récupère l'onglet en cours
  if (isset($_GET['tab_en_cours']))
  // Si la personne est identifiée, l'onglet existe
  {
    $tab_en_cours=$_GET['tab_en_cours'];
  }
  else
  // Sinon, on met le premier onglet
  {
    $tab_en_cours=0;
  }
  
  // On récupère le thème choisi (Corrige Bug si la personne non identifiée)
  if (!isset($_SESSION['theme_choisi']))
  {
    $_SESSION['theme_choisi']="redmond";
    $_SESSION['largeur_ecran']=0;
    $_SESSION['largeur_ecran_demi']=0;
  }

  // On initialise la largeur d'écran (Corrige Bug si la personne non identifiée)
  if (!isset($_SESSION['largeur_ecran']))
  {
    $_SESSION['largeur_ecran']=0;
    $_SESSION['largeur_ecran_demi']=0;
  }
?>

<!DOCTYPE html>
<head dir="<?php echo $Sens_Ecriture; ?>" lang="<?php echo $Langue_Valeur; ?>">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Gest'Ecole</title>
      
  <!-- **************** -->
  <!-- * Fichiers CSS * -->
  <!-- **************** -->

  <!-- Fichiers CSS jQuery -->
    <link rel="stylesheet" href="themes/<?php echo $_SESSION['theme_choisi']; ?>/jquery.ui.all_<?php echo $Sens_Ecriture; ?>.css">
    <link rel="stylesheet" href="themes/<?php echo $_SESSION['theme_choisi']; ?>/personnel_<?php echo $Sens_Ecriture; ?>.css">

  <!-- Fichiers CSS Autres -->
  <link rel="stylesheet" href="themes/jquery.contextmenu_<?php echo $Sens_Ecriture; ?>.css">
	<link rel="stylesheet" href="themes/jquery.datatables_<?php echo $Sens_Ecriture; ?>.css">
	<link rel="stylesheet" href="themes/jquery.colvis_<?php echo $Sens_Ecriture; ?>.css">
	<link rel="stylesheet" href="themes/jquery.multiselect_<?php echo $Sens_Ecriture; ?>.css">
	<link rel="stylesheet" href="themes/personnel_<?php echo $Sens_Ecriture; ?>.css">
	<link rel="stylesheet" href="themes/colorpicker.css">
	<link rel="stylesheet" href="themes/tiptip_<?php echo $Sens_Ecriture; ?>.css">
	<link rel="stylesheet" href="themes/jcrop.min.css">

  <!-- *********** -->
  <!-- * Scripts * -->
  <!-- *********** -->

  <!-- Scripts jQuery fondamentaux -->
	  <script src="commun/jquery/jquery.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.core.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.widget.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.mouse.js"></script>

  <!-- Scripts jQuery UI -->
	  <script src="commun/jquery/ui/jquery.ui.accordion.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.button.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.datepicker.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.dialog.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.draggable.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.droppable.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.position.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.progressbar.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.resizable.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.selectable.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.slider.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.sortable.js"></script>
	  <script src="commun/jquery/ui/jquery.ui.tabs.js"></script>

  <!-- Scripts jQuery Effets -->
	  <script src="commun/jquery/ui/jquery.effects.core.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.blind.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.bounce.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.clip.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.drop.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.explode.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.fade.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.fold.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.highlight.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.pulsate.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.scale.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.shake.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.slide.js"></script>
	  <script src="commun/jquery/ui/jquery.effects.transfer.js"></script>

  <!-- Scripts jQuery Autres -->
	 <script src="commun/jquery/jquery.contextmenu.js"></script>
   <script src="commun/jquery/jquery.datatables.min.js"></script>
   <script src="commun/jquery/jquery.datatables.rowgrouping.js"></script>
	 <script src="commun/jquery/jquery.colvis.js"></script>
	 <script src="commun/jquery/jquery.fixedcolumns.js"></script>
	 <script src="commun/jquery/jquery.multiselect.js"></script>
	 <script src="commun/jquery/jquery.printelement.js"></script>
	 <script src="commun/jquery/jquery.tiptip.js"></script>
	 <script src="commun/colorpicker/colorpicker.js"></script>
	 <script src="commun/tinymce/tiny_mce.js"></script>
	 <script src="commun/jcrop/jquery.jcrop.min.js"></script>
	 <script src="commun/php.full.min.js"></script>

  <!-- Scripts ElFinder -->
	  <link rel="stylesheet" type="text/css" href="commun/elfinder/css/elfinder.min.css">
	  <link rel="stylesheet" type="text/css" media="screen" href="commun/elfinder/css/theme.css">
	  <script type="text/javascript" src="commun/elfinder/js/elfinder.min.js"></script>

  <!-- Scripts jQuery et elfinder Traduction en français -->
<?php
      if (file_exists("langues/".$_SESSION['language_application']."/jquery.ui.datepicker-".$_SESSION['language_application'].".js"))
      {
?>
	    <script src="langues/<?php echo $_SESSION['language_application']; ?>/jquery.ui.datepicker-<?php echo $_SESSION['language_application']; ?>.js"></script>
<?php
      }
	  else
	  {
?>
	    <script src="langues/fr-FR/jquery.ui.datepicker-fr-FR.js"></script>
<?php
	  }
      if (file_exists("langues/".$_SESSION['language_application']."/elfinder.".$_SESSION['language_application'].".js"))
      {
?>
	    <script type="text/javascript" src="langues/<?php echo $_SESSION['language_application']; ?>/elfinder.<?php echo $_SESSION['language_application']; ?>.js"></script>
<?php 
	  }
	  else
	  {
?>
	    <script type="text/javascript" src="langues/fr-FR/elfinder.fr-FR.js"></script>
<?php
	  }
?>

  <!-- Fonctions Javascript communes -->
  <?php include "commun/fonctions_js.js"; ?>

  
  <!-- ************************************* -->
  <!-- * Initialisation des parties jQuery * -->
  <!-- ************************************* -->
	<script language="Javascript">
    BrowserDetect.init();

	  $(document).ready(function()
    {
      /************************************/
      /* Création des différents éléments */
      /************************************/

      /* Boutons */
      $( "#accordion" ).accordion();
      $( "#deconnexion" ).button();
      $( "#deconnexion" ).click(function() { Message_Chargement(7,1); document.location.href="users/deconnexion.php"; });

      /* Onglets */
	    $( "#tabs" ).tabs(
      {
        selected: <?php echo $tab_en_cours; ?>,
        select: function()
        {
          Message_Chargement(1,1);
        },
        load: function() { Message_Chargement(1,0); },
			  ajaxOptions:
        {
	  			error: function( xhr, status, index, anchor )
          {
			  		$( anchor.hash ).html(
				  		"<?php echo $Langue['ERR_CHARGEMENT_ONGLET']; ?>" );
  				}
	  		},
		  });
	  });

		function Charge_MDP_Perdu()
		{
        url="users/mdp_perdu1.php";
        $("#dialog-mdp").load(url,function()
        {
	        $(this).dialog(
          {
  	        autoOpen: false,
  		      height: 500,
	  	      width: 950,
		        modal: true,
		        title: '<?php echo $Langue['LBL_MDP_PERDU_RECUPERATION']; ?>',
		        draggable: false,
		        resizable: false,
		        zIndex:1000,
		        close: function() { $("#creer-element").button({ disabled: false }); },
          });
	        $(this).dialog( "open" );
		    });
		}
	</script>
</head>


<!-- ******************** -->
<!-- * Corps de la page * -->
<!-- ******************** -->

<body dir="<?php echo $Sens_Ecriture; ?>">
<a name="haut_page"></a>
<!-- Entête de la page -->
<div align=center id="message" class="message_chargement ui-corner-all" style="visibility:hidden;z-index:5000;"></div>
<div class="ui-widget ui-widget-content ui-corner-all espacement_bas">
  <div class="ui-widget ui-widget-header ui-corner-all entete" style="height:40px;">
    <div class="floatgauche"><img src="themes/images/logo_petit.png"> Gest'&Eacute;cole <font style="font-size:10px;font-weight:normal;"><?php echo $Langue['LBL_VERSION']; ?> <?php echo $gestclasse_config_plus['version_de_l_application']; ?></font></div>
    <div class="floatdroite"><?php if (isset($_SESSION['id_util'])) { ?><button id="deconnexion" style="font-size:12px;"><?php echo $Langue['BTN_DECONNEXION']; ?></button><?php } ?></div>
  </div>
</div>

<!-- Partie centrale -->
<?php
// On vérifie si la personne est identifiée
if (!isset($_SESSION['id_util']))
// Si non, on construit la page de connexion
{
?>
  <div id="dialog-mdp" align=center></div>
  <div class="ui-widget ui-widget-content ui-corner-all" style="min-height:630px;" align=center>
  <?php if ($gestclasse_config_plus["message_connexion"]!='') { ?>
    <div class="ui-widget ui-widget-content ui-corner-all textgauche marge10_tout" style="width:500px; margin-top:50px;">
	  <?php echo str_replace("</li><br>","</li>",str_replace("</ul><br>","</ul>",$gestclasse_config_plus["message_connexion"])); ?>
	</div>
    <div class="ui-widget ui-widget-content ui-corner-all textgauche" style="width:400px; height:250px;margin-top:25px;">
  <?php } else { ?>
    <div class="ui-widget ui-widget-content ui-corner-all textgauche" style="width:400px; height:250px;margin-top:175px;">
  <?php } ?>
      <div class="ui-widget ui-widget-header ui-state-default ui-corner-all marge10_tout"><?php echo $Langue['LBL_IDENTIFIEZ_VOUS']; ?></div>
      <div class="ui-accordion-content-active">
        <form id=identification action="users/identification.php" method=POST>
          <script language="Javascript">
            /* On recherche la largeur de la page */
            if (BrowserDetect.browser=="Explorer")
            {
              largeur=document.body.offsetWidth;
            }
            else
            {
              largeur=window.innerWidth;
            }
            document.write("<input type=hidden id=largeur_ecran name=largeur_ecran value="+largeur+">");
						parent.radio.location.href="vide.html";
          </script>
<?php
		  if (isset($_GET['erreur_connexion']))
			{
			  switch ($_GET['erreur_connexion'])
				{
				  case "1":
					  echo '<div id="msg_connexion_erreur"><div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong>'.$Langue['ERR_CONNEXION_PASCLASSE'].'</strong></div></div></div><br>';
						echo '<script language="Javascript"> $(document).ready(function() { setTimeout(function() { $("#msg_connexion_erreur").effect("blind",1000); }, 3000 ); }); </script>';
					  break;
					case "2":
					  echo '<div id="msg_connexion_erreur"><div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong>'.$Langue['ERR_CONNEXION_INCONNU'].'</strong></div></div></div><br>';
						echo '<script language="Javascript"> $(document).ready(function() { setTimeout(function() { $("#msg_connexion_erreur").effect("blind",1000); }, 3000 ); }); </script>';
					  break;
					default:
					  echo '<br />';
					  break;
				}
			}
			else
			{
			  echo '<br />';
			}
?>
		  <table cellspacing=0 cellpadding=0 border=0 width=100%>
		  <tr>
          <td><label class="label_class"><?php echo $Langue['LBL_IDENTIFIANT']; ?> :</label></td><td><input type="text" name="identifiant" id="identifiant" class="text ui-widget-content ui-corner-all" size=30 maxlength=255></td></tr>
          <tr><td colspan=2><br /><br /></td></tr>
          <td><label class="label_class"><?php echo $Langue['LBL_MOT_PASSE']; ?> :</label></td><td><input type="password" name="motdepasse" id="motdepasse" class="text ui-widget-content ui-corner-all" size=30 maxlength=20></td></tr>
          <tr><td colspan=2><br /><br /></td></tr>
          <tr><td colspan=2 style="text-align:center"><input type="Submit" id="valider" name="valider" value="<?php echo $Langue['BTN_S_IDENTIFIER']; ?>"><br><br>
					<a href="#null" onClick="Charge_MDP_Perdu()"><?php echo $Langue['LNK_MDP_PERDU']; ?></a></td></tr>
		  </table>
        </form>
        <script language="Javascript">
	      $(document).ready(function()
        {
          $( "#valider" ).button();
          $( "#valider" ).click(function()
          {
            $("#message").text("<?php echo $Langue['LBL_IDENTIFICATION_COURS']; ?>");
            largeurdemi=($("#largeur_ecran").val()-350)/2;
            $("#message").css({'visibility':'visible', 'left':largeurdemi, 'top':'15px'});
          });
        });
        document.getElementById('identifiant').focus();
        </script>
      </div>
    </div>
  </div>
<?php
}
else
// Si oui, on construit les onglets
{
?>
  <div id="dialog-form" align=center></div>
  <div id="dialog-niveau2" align=center></div>
  <div id="dialog-confirm" align=center></div>
  <div id="tabs">
    <div class="scroller" style="overflow:hidden;overflow-x:auto;overflow-y:hidden;">
	  <ul id="liste_des_tabs">
	  <?php
	  $width=0;
      $onglet_ok=explode(",",$gestclasse_config_plus['onglet_'.$_SESSION['type_util']]);
      foreach ($onglet[$_SESSION['type_util']] AS $cle => $value)
      {
  		  $config=true;
  		  if (!in_array($cle,$onglet_ok))
  		  {
          $config=false;
        }
        else
        {
  		  if ($cle=="configuration" && $_SESSION['type_util']=="P")
	  	  {
            $req=mysql_query("SELECT classes.*, classes_profs.* FROM `classes`,`classes_profs` WHERE classes.annee='".$_SESSION['annee_scolaire']."' AND classes.id=classes_profs.id_classe AND classes_profs.id_prof='".$_SESSION['id_util']."' AND type='T'");
            if (mysql_num_rows($req)=="") { $config=false; } else { $config=true; }
          }
        }
        if ($config==true)
		{
  		    echo '<li style="width:9em;text-align:center;"><a style="text-align:center;width:7em" href="index2.php?module='.$cle.'&action=index" title="Menu principal">'.$value.'</a></li>';
			$width=$width+9.4;
        }
	  }
      ?>
  	  </ul>
	  <script language="Javascript">
	    document.getElementById("liste_des_tabs").style.width="<?php echo $width; ?>em";
	  </script>
	</div>
    <div id="Menu_principal" style="margin:0px;"></div>
  </div>
<?php
}
?>

<!-- Affichage de la liste des thèmes -->
<div class="ui-widget ui-widget-content ui-corner-all espacement_haut">
  <div class="ui-widget ui-widget-header ui-corner-all theme textcentre"><table cellspacing=0 cellpadding=0 border=0 width=100%><tr><td width=50% class="textdroite">
    <?php echo $Langue['LBL_THEME']; ?> : <select id="theme_choisi" name="theme_choisi" class="text ui-widget-content ui-corner-all">
    <option value="blitzer" <?php if ($_SESSION['theme_choisi']=="blitzer") { echo 'SELECTED'; } ?>>Blitzer</option>
    <option value="humanity" <?php if ($_SESSION['theme_choisi']=="humanity") { echo 'SELECTED'; } ?>>Humanity</option>
    <option value="ligthness" <?php if ($_SESSION['theme_choisi']=="ligthness") { echo 'SELECTED'; } ?>>Ligthness</option>
    <option value="redmond" <?php if ($_SESSION['theme_choisi']=="redmond") { echo 'SELECTED'; } ?>>Redmond</option>
    <option value="smoothness" <?php if ($_SESSION['theme_choisi']=="smoothness") { echo 'SELECTED'; } ?>>Smoothness</option>
    <option value="southstreet" <?php if ($_SESSION['theme_choisi']=="southstreet") { echo 'SELECTED'; } ?>>South Street</option>
    </select></td><td width=50% class="textgauche">&nbsp;-&nbsp;<?php echo $Langue['LBL_LANGUE']; ?> : <select id="langue_choisi" name="langue_choisi" class="text ui-widget-content ui-corner-all">
<?php
  if ($handle = opendir('langues/')) {
    while (false !== ($entry = readdir($handle))) 
	{
        if ($entry != "." && $entry != "..") 
		{
            if (file_exists("langues/".$entry."/langue.php")) 
			{
			  $fp=fopen("langues/".$entry."/langue.php","r");
			  $texte=fgets($fp);
			  echo '<option value="'.$entry.'"';
			  if ($entry==$_SESSION['language_application']) { echo " SELECTED"; }
			  echo '>'.$texte.'</option>';
			}
        }
    }
    closedir($handle);
  }  
?>
    </select></td></tr></table>
  </div>
</div>
</body>
</html>