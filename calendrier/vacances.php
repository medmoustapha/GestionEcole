<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_editview name=Detail>
<input type="hidden" id="module" name="module" value="calendrier">
<input type="hidden" id="action" name="action" value="save_vacances">
<input type="hidden" id="id_debut" name="id_debut" value="">
<input type="hidden" id="id_fin" name="id_fin" value="">
<div id="msg_ok"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Button" id="Annuler" name="Annuler" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
  <td class="droite">
	  <button id="aide_fenetre" name="aide_fenetre"><?php echo $Langue['BTN_AIDE']; ?></button>
	</td>
</tr>
</table>

<table cellspacing=0 cellpadding=0 width=100%>
<tr>
  <td class="centre" width=50%><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_VACANCES_EXISTANTS']; ?></div></td>
  <td class="centre marge50_gauche" width=50%><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_VACANCES_CREER_MODIFIER']; ?></div></td>
</tr>
<tr>
  <td width=50% valign=top>
    <table cellspacing=0 cellpadding=0 width=100%>
<?php
  $req=mysql_query("SELECT * FROM `vacances` WHERE (date_debut LIKE '".$_SESSION['annee_en_cours']."%' OR date_fin LIKE '".$_SESSION['annee_en_cours']."%') AND zone='".$gestclasse_config_plus['zone']."' ORDER BY date_debut ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    echo '<tr><td class="textgauche marge10_gauche" width=25%>'.$Langue['LBL_VACANCES_DU'].' '.Date_Convertir(mysql_result($req,$i-1,'date_debut'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_VACANCES_AU'].' '.Date_Convertir(mysql_result($req,$i-1,'date_fin'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_VACANCES_INCLUS'].'</td>';
    echo '<td class="textdroite marge10_droite" width=25%><input type="Button" id="Modifier'.$i.'" name="Modifier'.$i.'" value="'.$Langue['BTN_MODIFIER'].'" onClick="Calendrier_Vacances_Change_Date(\''.Date_Convertir(mysql_result($req,$i-1,'date_debut'),'Y-m-d',$Format_Date_PHP).'\',\''.Date_Convertir(mysql_result($req,$i-1,'date_fin'),'Y-m-d',$Format_Date_PHP).'\')">&nbsp;';
	echo '<input type="Button" id="Supprimer'.$i.'" name="Supprimer'.$i.'" value="'.$Langue['BTN_SUPPRIMER'].'" onClick="Calendrier_Vacances_Supprime_Date(\''.mysql_result($req,$i-1,'date_debut').'\',\''.mysql_result($req,$i-1,'date_fin').'\')"></td>';
	echo '<script language="Javascript">';
    echo '$(document).ready(function() { ';
	echo '$("#Modifier'.$i.'").button();';
	echo '$("#Supprimer'.$i.'").button();';
	echo '})';
	echo '</script>';
    echo '</tr>';
  }
?>
    </table>
  </td>
  <td width=50% valign=top class="marge60_gauche">
<?php
      echo '<input type="Submit" id="Ajouter2" name="Ajouter2" value="'.$Langue['BTN_AJOUTER'].'">&nbsp;';
	  echo '<input type="Button" id="Annuler2" name="Annuler2" value="'.$Langue['BTN_ANNULER2'].'" onClick="Calendrier_Vacances_Vide_Date()"><br><br>';
	  echo $Langue['LBL_VACANCES_DATE'].' <input type=text class="text ui-widget-content ui-corner-all" id="nouvelle_date_debut" name="nouvelle_date_debut" value="" size=10 maxlength=10>';
	  echo ' '.$Langue['LBL_VACANCES_DATE2'].' <input type=text class="text ui-widget-content ui-corner-all" id="nouvelle_date_fin" name="nouvelle_date_fin" value="" size=10 maxlength=10> '.$Langue['LBL_VACANCES_INCLUS'];
	  echo '<script> $(function() { $( "#nouvelle_date_debut" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, minDate:new Date('.$_SESSION['annee_en_cours'].',0,1), maxDate:new Date('.$_SESSION['annee_en_cours'].',11,31) }); });</script>';
	  echo '<script> $(function() { $( "#nouvelle_date_fin" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, minDate:new Date('.$_SESSION['annee_en_cours'].',0,1), maxDate:new Date('.$_SESSION['annee_en_cours'].',11,31) }); });</script>';
?>
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
		window.open("http://www.doxconception.com/site/index.php/directeur-agenda/article/172-gerer-les-vacances-scolaires.html","Aide");
  });

  $("#Annuler").button();
  $("#Ajouter2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
    if (!checkRegexp($("#nouvelle_date_debut"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    if (!checkRegexp($("#nouvelle_date_fin"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
		if (checkRegexp($("#nouvelle_date_debut"),<?php echo $date_regexp[$Format_Date_PHP]; ?>) && checkRegexp($("#nouvelle_date_fin"),<?php echo $date_regexp[$Format_Date_PHP]; ?>))
		{
			if (Compare_Date($("#nouvelle_date_debut").val(),$("#nouvelle_date_fin").val(),'<?php echo $Format_Date_PHP; ?>')<0)
			{
				$("#nouvelle_date_debut").addClass( "ui-state-error" );
				$("#nouvelle_date_fin").addClass( "ui-state-error" );
				bValid=false;
			}
			else
			{
				$("#nouvelle_date_debut").removeClass( "ui-state-error" );
				$("#nouvelle_date_fin").removeClass( "ui-state-error" );
			}
		}
    event.preventDefault();
    if (bValid)
		{
			Message_Chargement(2,1);
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function()
      {
				Message_Chargement(1,1);
				Charge_Dialog("index2.php?module=calendrier&action=vacances","<?php echo $Langue['LBL_GESTION_VACANCES']; ?>");
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
			$("#dialog-form").scrollTop(0);
			$("#msg_ok").fadeIn( 1000 );
			$("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
			setTimeout(function()
			{
				$("#msg_ok").effect("blind",1000);
			}, 3000 );
    }
  });  
});

function Calendrier_Vacances_Change_Date(datation,datation2)
{
  $("#nouvelle_date_debut").val(datation);
  $("#nouvelle_date_fin").val(datation2);
  $("#id_debut").val(datation);
  $("#id_fin").val(datation2);
  $("#Ajouter2").val('<?php echo $Langue['BTN_MODIFIER']; ?>');
	$("#dialog-form").scrollTop(0);
}

function Calendrier_Vacances_Supprime_Date(datation,datation2)
{
	$( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_VACANCES']; ?></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
	{
		title: "<?php echo $Langue['LBL_SUPPRIMER_VACANCES']; ?>",
	  resizable: false,
	  draggable: false,
	  height:200,
	  width:450,
	  modal: true,
	  buttons:[
		{
			text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
			click: function()
			{
				Message_Chargement(4,1);
				var request = $.ajax({type: "POST", url: "index2.php", data: "module=calendrier&action=delete_vacances&date_debut="+datation+"&date_fin="+datation2});
				request.done(function(msg)
				{
					Message_Chargement(1,1);
					$( "#dialog-confirm" ).dialog( "close" );
					Charge_Dialog("index2.php?module=calendrier&action=vacances","<?php echo $Langue['LBL_GESTION_VACANCES']; ?>");
					$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
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
}

function Calendrier_Vacances_Vide_Date()
{
  $("#nouvelle_date_debut").val('');
  $("#nouvelle_date_fin").val('');
  $("#nouvelle_date_debut").removeClass( "ui-state-error" );
  $("#nouvelle_date_fin").removeClass( "ui-state-error" );
  $("#id_debut").val('');
  $("#id_fin").val('');
  $("#Ajouter2").val('<?php echo $Langue['BTN_AJOUTER']; ?>');
}
</script>
