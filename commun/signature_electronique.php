<?php
  $req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
  
  if (mysql_num_rows($req_signature)=="")
  {
?>
    <div class="ui-state-highlight ui-corner-all marge10_tout textgauche" style="width:400px;">
	<font style="font-variant:small-caps;font-weight:bold;font-size:16px"><?php echo $texte; ?></font><br /><br />
	<div id="msg_signature<?php echo $complement; ?>"></div>
	<form method=POST name="form_signature<?php echo $complement; ?>" id="form_signature<?php echo $complement; ?>" action="index2.php">
	<input type="hidden" id="module" name="module" value="users">
	<input type="hidden" id="action" name="action" value="save_signature">
	<input type="hidden" id="parametre" name="parametre" value="<?php echo $parametre; ?>">
	<?php
	  $code=Construit_Id("signatures",20);
	?>
	<input type="hidden" id="captcha<?php echo $complement; ?>" name="captcha" value="<?php echo substr($code,0,7); ?>">
	<input type="hidden" id="id_signature<?php echo $complement; ?>" name="id_signature" value="<?php echo $code; ?>">
	<table cellspacing=0 cellpadding=0 class="tableau_editview2">
	<tr>
	  <td class="droite" width=50%><label class="label_class"><?php echo $Langue['LBL_SIGNATURE_CODE']; ?> :</label></td><td class="gauche" width=50%><img src="commun/create_captcha.php?code=<?php echo substr($code,0,7); ?>"></td>
    </tr>
	<tr>
	  <td class="droite" width=50%><label class="label_class" style="width:180px"><?php echo $Langue['LBL_SIGNATURE_CODE2']; ?> :</label></td><td class="gauche" width=50%><input type=text class="text ui-widget-content ui-corner-all" name="code" id="code<?php echo $complement; ?>" value="" size=10 maxlength=10></td>
    </tr>
	<tr>
	  <td class="droite" width=50%><label class="label_class"><?php echo $Langue['LBL_SIGNATURE_MDP']; ?> :</label></td><td class="gauche" width=50%><input type=password class="text ui-widget-content ui-corner-all" name="mdp" id="mdp<?php echo $complement; ?>" value="" size=10 maxlength=20></td>
    </tr>
	<tr>
	  <td style="text-align:center !important" colspan=2><input type="Submit" id="Signature<?php echo $complement; ?>" name="Signature<?php echo $complement; ?>" value="<?php echo $texte; ?>"></td>
    </tr>
	</table>
	</form>
	</div>
	<script language="Javascript">
	$(document).ready(function()
	{
	  $("#Signature<?php echo $complement; ?>").button({disabled:false});
	  
	  $("#form_signature<?php echo $complement; ?>").submit(function(event)
      {
		var bValid = true;
		if (!checkValue($("#code<?php echo $complement; ?>"))) 
		{ 
		  bValid=false; 
		}
		else
		{
		  if ($("#code<?php echo $complement; ?>").val()!=$("#captcha<?php echo $complement; ?>").val())
		  {
		    $("#code<?php echo $complement; ?>").addClass("ui-state-error");
			bValid=false;
		  }
		  else
		  {
		    $("#code<?php echo $complement; ?>").removeClass("ui-state-error");
		  }
		}
		if (!checkValue($("#mdp<?php echo $complement; ?>"))) { bValid=false; }
		event.preventDefault();
		if ( bValid )
		{
		  Message_Chargement(9,1);
		  var $form = $( this );
		  url = $form.attr( 'action' );
		  data = $form.serialize();
		  var request534 = $.ajax({type: "POST", url: url, data: data});
		  request534.done(function(msg)
		  {
		    if (msg=="ok")
			{
		      Message_Chargement(1,1);
			  <?php if ($adresse_plus!="") { echo $adresse_plus; } ?>
			  $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
			}
			else
			{
		      Message_Chargement(9,0);
			  $("#msg_signature<?php echo $complement; ?>").fadeIn( 1000 );
			  $("#msg_signature<?php echo $complement; ?>").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong><?php echo $Langue['MSG_SIGNATURE_ERREUR_MDP']; ?></strong></div></div>');
			  setTimeout(function()
			  {
				$("#msg_signature<?php echo $complement; ?>").effect("blind",1000);
			  }, 3000 );
			}
		  });
		}
		else
		{
	      $("#msg_signature<?php echo $complement; ?>").fadeIn( 1000 );
  		  $("#msg_signature<?php echo $complement; ?>").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
    	  setTimeout(function()
          {
            $("#msg_signature<?php echo $complement; ?>").effect("blind",1000);
          }, 3000 );
		}
	  });	  
	});
	</script>
<?php
	$est_sans_signature=true;
  }
  else
  {
	switch (mysql_result($req_signature,0,'type_util'))
	{
	  case "D":
	    $req_p=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req_signature,0,'id_util')."'");
		$par=mysql_result($req_p,0,'nom').' '.mysql_result($req_p,0,'prenom');
		break;
	  case "P":
	    $req_p=mysql_query("SELECT * FROM `profs` WHERE id='".mysql_result($req_signature,0,'id_util')."'");
		$par=mysql_result($req_p,0,'nom').' '.mysql_result($req_p,0,'prenom');
		break;
	  case "E":
	    $req_p=mysql_query("SELECT * FROM `eleves` WHERE id='".mysql_result($req_signature,0,'id_util')."'");
		$par=$Langue['LBL_SIGNATURE_PARENTS']." ".mysql_result($req_p,0,'nom').' '.mysql_result($req_p,0,'prenom');
		break;
	}
    echo '<div align="right"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout textcentre" style="width:400px;">'.$Langue['MSG_SIGNATURE_SIGNE_PAR'].' <strong>'.$par.'</strong> '.$Langue['MSG_SIGNATURE_LE'].' <strong>'.Date_Convertir(mysql_result($req_signature,0,'date'),'Y-m-d',$Format_Date_PHP).'</strong>.<br />'.$Langue['MSG_SIGNATURE_CLE'].' : <strong>'.mysql_result($req_signature,0,'id').'</strong></div></div>';
	$est_sans_signature=false;
  }
?>