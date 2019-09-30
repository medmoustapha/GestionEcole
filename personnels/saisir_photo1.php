<!-- BEGIN formulaire -->
<form target="calcul" enctype="multipart/form-data" action="index2.php" method=POST id=form_editview2 name=Detail2>
<input type="hidden" name="module" id="module" value="personnels">
<input type="hidden" name="action" id="action" value="saisir_photo2">
<input type="hidden" id="id_personne" name="id_personne" value="<?php echo $_GET['id_personne']; ?>">
<div id="msg_photo"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Button" id="Retour_Saisie" name="Retour_Saisie" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite">
	  <button id="aide_fenetre" name="aide_fenetre"><?php echo $Langue['BTN_AIDE']; ?></button>
	</td>
</tr>
</table>
<table cellspacing=0 cellpadding=0 class="tableau_editview2" style="width:100%">
<tr>
  <td class="gauche marge10_droite" style="width:25%" valign=top><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_PHOTO_ACTUELLE']; ?></div></td>
  <td class="gauche" style="width:75%" valign=top><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_PHOTO_SAISIE']; ?></div></td>
</tr>
<tr>
  <td class="centre marge10_droite" style="width:25%" valign=top>
<?php
  $id_personne=$_GET['id_personne'];
  if (file_exists("cache/photos/".$_GET['id_personne'].".jpg"))
  {
    echo '<img src="cache/photos/'.$_GET['id_personne'].'.jpg?'.time().'" height=250 width=190 border=0><br><input type="button" id="Supprimer_Photo" name="Supprimer_Photo" value="'.$Langue['BTN_SUPPRIMER'].'">';
  }
  else
  {
    $req=mysql_query("SELECT * FROM `profs` WHERE id='$id_personne'");
	if (mysql_result($req,0,'civilite')=="" || mysql_result($req,0,'civilite')=="1")
	{
	  echo '<img src="images/homme.png" height=250 width=190 border=0>';
	}
	else
	{
	  echo '<img src="images/femme.png" height=250 width=190 border=0>';
	}
  }
?>
  </td>
  <td class="centre" style="width:75%" valign=top>
    <div id="chargement_cours"></div><input type="file" id="fichier" name="fichier" size=70 maxlength=255><br />
	<div class="font10"><?php echo $Langue['EXPL_PHOTO_TAILLE']; ?></div><br />
	<input type="button" id="Saisie_Webcam" name="Saisie_Webcam" value="<?php echo $Langue['BTN_PHOTO_WEBCAM']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" id="Valider_Saisie" name="Valider_Saisie" value="<?php echo $Langue['BTN_PHOTO_TELECHARGER']; ?>">
  </td>
</tr>
</table>
</form>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton_bas">
<tr>
  <td class="gauche">
    <input type="Button" id="Retour_Saisie2" name="Retour_Saisie2" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
		window.open("http://www.doxconception.com/site/index.php/directeur-personnels.html","Aide");
  });

  $("#Supprimer_Photo").button();
  $("#Saisie_Webcam").button();
  $("#Retour_Saisie").button();
  $("#Retour_Saisie2").button();
  $("#Valider_Saisie").button();
  $("#Retour_Saisie").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Retour_Saisie2").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Saisie_Webcam").click(function()
  {
    Charge_Dialog3("index2.php?module=personnels&action=saisir_photo_webcam&id_personne=<?php echo $_GET['id_personne']; ?>","<?php echo $Langue['BTN_PHOTO_WEBCAM']; ?>");
  });
  
    $("#form_editview2").submit(function(event)
    {
      if ($("#fichier").val()=="")
      {
        event.preventDefault();
	      $("#msg_photo").fadeIn( 1000 );
 		  $("#msg_photo").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_PHOTO_MANQUE_FICHIER']; ?></strong></div></div>');
   		  setTimeout(function()
          {
            $("#msg_photo").effect("blind",1000);
          }, 3000 );
      }
      else
      {
        fichier=$("#fichier").val();
        extension=fichier.substr(fichier.length-4,4);
        if (extension.toLowerCase()==".jpg")
        {
          $("#chargement_cours").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_PHOTO_CHARGEMENT_FICHIER']; ?></strong><br><br>');
        }
        else
        {
          event.preventDefault();
  	      $("#msg_photo").fadeIn( 1000 );
 	  	  $("#msg_photo").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_PHOTO_ERREUR_FICHIER']; ?></strong></div></div>');
   	      setTimeout(function()
          {
            $("#msg_photo").effect("blind",1000);
          }, 3000 );
        }
      }
    });
	
  $("#Supprimer_Photo").click(function()
  {  
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_PHOTO_SUPPRIMER']; ?></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_PHOTO_SUPPRIMER']; ?>",
	  resizable: false,
	  draggable: false,
	  height:200,
	  width:450,
	  modal: true,
	  buttons:[
      {
        text: "<?php echo $Langue['BTN_VALIDER']; ?>",
		click: function()
        {
          Message_Chargement(4,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: "module=personnels&action=delete_photo&id=<?php echo $_GET['id_personne']; ?>"});
          request.done(function(msg)
          {
		    Message_Chargement(1,1);
		    $( "#dialog-confirm" ).dialog( "close" );
			Charge_Dialog3("index2.php?module=personnels&action=saisir_photo1&id_personne=<?php echo $_GET['id_personne']; ?>","<?php echo $Langue['LBL_PHOTO_TITRE']; ?>");
			Charge_Dialog("index2.php?module=personnels&action=detailview&id=<?php echo $_GET['id_personne']; ?>","<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
		  });
        }
	  },
	  {
        text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
		click: function()
        {
		  $( this ).dialog( "close" );
		}
	  }]
	});
  });	
});

function formUploadCallback (result) {
  switch (result)
  {
    case "move":
      $("#chargement_cours").html('');
      $("#msg_photo").fadeIn( 1000 );
	  $("#msg_photo").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_PHOTO_MAJ_DEPLACEMENT']; ?></strong></div></div>');
 	  setTimeout(function()
      {
        $("#msg_photo").effect("blind",1000);
      }, 6000 );
      break;
    case "upload":
      $("#chargement_cours").html('');
      $("#msg_photo").fadeIn( 1000 );
	  $("#msg_photo").html('<div class="ui-widget" style="width:100%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_PHOTO_MAJ_UPLOAD']; ?></strong></div></div>');
 	  setTimeout(function()
      {
        $("#msg_photo").effect("blind",1000);
      }, 6000 );
      break;
    default:
      Charge_Dialog3("index2.php?module=personnels&action=saisir_photo3&id_personne=<?php echo $_GET['id_personne']; ?>","<?php echo $Langue['LBL_PHOTO_TITRE']; ?>");
      break;
  }
}
</script>
