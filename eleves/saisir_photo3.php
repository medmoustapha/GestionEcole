<form target="calcul" action="index2.php" method=POST id=form_editview2 name=Detail2>
<input type="hidden" name="module" id="module" value="eleves">
<input type="hidden" name="action" id="action" value="saisir_photo4">
<input type="hidden" id="id_personne" name="id_personne" value="<?php echo $_GET['id_personne']; ?>">
<input type="hidden" id="x" name="x" />
<input type="hidden" id="y" name="y" />
<input type="hidden" id="w" name="w" />
<input type="hidden" id="h" name="h" />
<div class="font12"><?php echo $Langue['EXPL_PHOTO_RECADRER']; ?></div><br>
<div id="msg_photo"></div>
<?php
  $id_personne=$_GET['id_personne'];
    $dimensions=getimagesize('cache/photos/'.$id_personne.'_temp.jpg');
    if ($dimensions[0]<$dimensions[1])
    {
	  if ($dimensions[1]>450) { $largeur=450*$dimensions[0]/$dimensions[1]; $hauteur=450; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
    }
    else
    {
	  if ($dimensions[0]>600) { $hauteur=600*$dimensions[1]/$dimensions[0]; $largeur=600; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
    }
    echo '<img src="cache/photos/'.$id_personne.'_temp.jpg?'.time().'" height="'.$hauteur.'" width="'.$largeur.'" border=0 id="target">';
	$image="cache/photos/'.$id_personne.'_temp.jpg";
  
  $x_haut=($largeur-190)/2;
  $y_haut=($hauteur-250)/2;
  $x_bas=$x_haut+190;
  $y_bas=$y_haut+250;
?>
<br>
<div id="chargement_cours"></div>
<input type="submit" id="soumettre_photo" name="soumettre_photo" value="<?php echo $Langue['BTN_PHOTO_ENREGISTRER']; ?>">
</form>
<script language="Javascript">
$(document).ready(function()
{
  $("#soumettre_photo").button();
  $('#target').Jcrop(
  {
    setSelect: [ <?php echo $x_haut; ?>,<?php echo $y_haut; ?>,<?php echo $x_bas; ?>,<?php echo $y_bas; ?>],
	aspectRatio: 0.76,
	onSelect: updateCoords
  });

  $("#form_editview2").submit(function(event)
  {
    $("#chargement_cours").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_PHOTO_SAUVEGARDE_FICHIER']; ?></strong><br><br>');
  });
});

function updateCoords(c)
{
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
}

function formUploadCallback (result) {
  switch (result)
  {
    case "move":
      $("#chargement_cours").html('');
      $("#msg_photo").fadeIn( 1000 );
	  $("#msg_photo").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_PHOTO_SAUVEGARDE']; ?></strong></div></div>');
 	  setTimeout(function()
      {
        $("#msg_photo").effect("blind",1000);
      }, 6000 );
      break;
    default:
      $("#dialog-niveau2").dialog( "close" );
      Charge_Dialog("index2.php?module=eleves&action=detailview&id=<?php echo $_GET['id_personne']; ?>","<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
      break;
  }
}
</script>
