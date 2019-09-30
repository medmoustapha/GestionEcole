<div class="titre_page"><?php echo $Langue['LBL_GESTION_DOCUMENTAIRE']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<br><br><br><br>
<style type='text/css'>
  .elfinder-dialog { left:<?php echo ($_SESSION['largeur_ecran']-250)/2; ?>px; }
  .elfinder-help-plus { margin-top:-550px; }
</style>

<?php  
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
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-documents.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-documents.html","Aide");
<?php } ?>
  });

	var elf = $('#elfinder').elfinder(
	{
		lang: '<?php echo $language_elfinder; ?>',             // language (OPTIONAL)
		dateFormat : '<?php echo $Format_Date_Heure_PHP; ?>',
		fancyDateFormat : '<?php echo $Format_Date_Heure_PHP_2; ?>',
		resizable : false,
		allowShortcuts : false,
		height : 600,
		//	ui: ['toolbar', 'path', 'stat'],
		uiOptions : 
		{
			// toolbar configuration
			toolbar : [['back', 'forward'],['mkdir', 'upload'],['open', 'download', 'info'],['copy', 'cut', 'paste','rm'],['duplicate', 'rename'],['search'],['view'],['help']],

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
			navbar : ['open', '|', 'copy', 'cut', 'paste', 'duplicate', 'rm', '|', 'info'],

			// current directory menu
			cwd    : ['reload', 'back', '|', 'upload', 'mkdir', 'paste', '|', 'info'],

			// current directory file menu
			files  : ['getfile', '|','open', 'download', '|', 'copy', 'cut', 'paste', 'duplicate', 'rm', '|', 'rename', '|', 'info']
		},
		url : 'commun/elfinder/php/connector.php?repertoire_perso=<?php echo $_SESSION['type_util'].$_SESSION['id_util']; ?>'  // connector URL (REQUIRED)
	}).elfinder('instance');            
});
</script>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>