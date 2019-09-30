<table cellpadding=0 cellspacing=0 border=0 style="width:100%">
<tr>
  <td class="textgauche" style="width:50%;"><div class="titre_page"><?php echo $Langue['LBL_LICENCE2']; ?></div></td>
  <td class="textdroite" style="width:50%;"><button id="retour"><?php echo $Langue['BTN_RETOUR']; ?></button></td>
</tr>
</table>
<br />
<?php
	$fh = fopen( "licence_".$Langue_Licence.".txt", 'r' ) or die( "License file not found!" );
	$licence = fread( $fh, filesize( "licence_".$Langue_Licence.".txt" ) );
	fclose( $fh );

  $licence = str_replace("\n","<br>",$licence);

	echo '<div style="line-height:24px;direction:ltr">'.$licence.'</div>';
?>
<div class="textdroite"><button id="retour2"><?php echo $Langue['BTN_RETOUR']; ?></button></div>
<script language="Javascript">
$(document).ready(function()
{
  $("#retour").button();
  $("#retour").click(function(event)
  {
    Message_Chargement(1,1);
    $( "#Menu_principal" ).load("index2.php?module=aproposde&action=index#haut_page");
    event.preventDefault();
  });
  $("#retour2").button();
  $("#retour2").click(function(event)
  {
    Message_Chargement(1,1);
    $( "#Menu_principal" ).load("index2.php?module=aproposde&action=index#haut_page");
    event.preventDefault();
  });
});
Message_Chargement(1,0);
</script>
