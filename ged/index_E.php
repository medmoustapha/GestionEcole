<div class="titre_page"><?php echo $Langue['LBL_GESTION_DOCUMENTAIRE']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<br><br><br><br>

<style type='text/css'>
  .elfinder-dialog { left:<?php echo ($_SESSION['largeur_ecran']-250)/2; ?>px; }
</style>

<?php
  $req=mysql_query("SELECT * FROM `profs` WHERE id='".$_SESSION['titulaire_classe_cours']."'");
  $type_util=mysql_result($req,0,'type');
  
  if (substr($_SESSION['language_application'],0,2)==strtolower(substr($_SESSION['language_application'],3,2)))
  {
    $language_elfinder=substr($_SESSION['language_application'],0,2);
  }
  else
  {
    $language_elfinder=str_replace('-','_',$_SESSION['language_application']);
  }
?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() 
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/parent-documents.html","Aide");
  });

  var elf = $('#elfinder').elfinder(
	{
		lang: '<?php echo $language_elfinder; ?>',             // language (OPTIONAL)
		dateFormat : '<?php echo $Format_Date_Heure_PHP; ?>',
		fancyDateFormat : '<?php echo $Format_Date_Heure_PHP_2; ?>',
		resizable : false,
		allowShortcuts : false,
		height : 600,
		uiOptions : 
		{
			// toolbar configuration
			toolbar : [['back', 'forward'],['open', 'download', 'info'],['search'],['view'],['help']],

			// directories tree options
			tree : 
			{
				openRootOnLoad : true,
				syncTree : true
			},

			// navbar options
			navbar : 
			{
				minWidth : 150,
				maxWidth : 500
			}
		},
		contextmenu : 
		{
			// navbarfolder menu
			navbar : ['open', '|', 'info'],

			// current directory menu
			cwd    : ['reload', 'back', '|', 'info'],

			// current directory file menu
			files  : ['getfile', '|','open', 'download', '|', 'info']
		},
		url : 'commun/elfinder/php/connector_E.php?repertoire_perso=<?php echo $type_util.$_SESSION['titulaire_classe_cours']; ?>'  // connector URL (REQUIRED)
	}).elfinder('instance');            
});
</script>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>