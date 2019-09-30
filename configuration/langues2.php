<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
</tr>
</table>
<br />
<p class="explic textgauche"><?php echo $Langue['LBL_LANGUE_EXPL6']; ?></p>
<ol class="explic textgauche">
<li><?php echo $Langue['LBL_LANGUE_EXPL7']; ?></li>
<li><?php echo $Langue['LBL_LANGUE_EXPL8']; ?></li>
</ol>
<br />
<br />
<div id="msg_ok"></div>
<br />
<div id="button_restaurer">
<form target="calcul" enctype="multipart/form-data" name="form_restaure" id="form_restaure" action="index2.php" method="POST">
<input type="hidden" name="module" id="module" value="configuration">
<input type="hidden" name="action" id="action" value="langues2_upload">
<?php echo $Langue['LBL_LANGUE_FICHIER']; ?> : <input type="file" name="fichier" id="fichier" size=75>
<br /><br /><input type="Submit" id="btnValider" name="btnValider" value="<?php echo $Langue['BTN_MAJ_UPLOADER']; ?>"></form></div>
<script language="Javascript">
$(document).ready(function()
{
    $("#btnValider").button();
    $("#form_restaure").submit(function(event)
    {
      if ($("#fichier").val()=="")
      {
        event.preventDefault();
	      $("#msg_ok").fadeIn( 1000 );
 		  $("#msg_ok").html('<div class="ui-widget" style="width:80%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_LANGUE_FICHIER']; ?></strong></div></div>');
   		  setTimeout(function()
          {
            $("#msg_ok").effect("blind",1000);
          }, 3000 );
      }
      else
      {
        fichier=$("#fichier").val();
        extension=fichier.substr(fichier.length-4,4);
        if (extension.toLowerCase()==".zip")
        {
          $("#msg_ok").html('<strong><img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MAJ_CHARGEMENT_FICHIER']; ?></strong>');
        }
        else
        {
          event.preventDefault();
  	      $("#msg_ok").fadeIn( 1000 );
 	  	  $("#msg_ok").html('<div class="ui-widget" style="width:80%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_LANGUE_ERREUR_FICHIER']; ?></strong></div></div>');
   	      setTimeout(function()
          {
            $("#msg_ok").effect("blind",1000);
          }, 3000 );
        }
      }
    });
});

function formUploadCallback (result) {
  switch (result)
  {
    case "move":
      $("#msg_ok").fadeIn( 1000 );
	  $("#msg_ok").html('<div class="ui-widget" style="width:80%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_LANGUE_DEPLACEMENT']; ?></strong></div></div>');
 	  setTimeout(function()
      {
        $("#msg_ok").effect("blind",1000);
      }, 6000 );
      break;
    case "upload":
      $("#msg_ok").fadeIn( 1000 );
	  $("#msg_ok").html('<div class="ui-widget" style="width:80%"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_LANGUE_UPLOAD']; ?></strong></div></div>');
 	  setTimeout(function()
      {
        $("#msg_ok").effect("blind",1000);
      }, 6000 );
      break;
    default:
      $("#msg_ok").html('');
      $("#button_restaurer").html('<strong><?php echo $Langue['MSG_MAJ_UPLOAD_OK']; ?></strong><br /><br /><input type="Button" id="etape4" name="etape4" value="<?php echo $Langue['BTN_LANGUE_ETAPE3']; ?>">');
      $("#etape4").button();
      $("#etape4").click(function()
      {
        Charge_Dialog("index2.php?module=configuration&action=langues3&fichier="+result,"<?php echo $Langue['LBL_LANGUE_TITRE']; ?>");
      });
      break;
  }
}
</script>
