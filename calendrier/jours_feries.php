<a name="haut_formulaire"></a>
<form action="index2.php" method=POST id=form_editview name=Detail>
<input type="hidden" id="module" name="module" value="calendrier">
<input type="hidden" id="action" name="action" value="save_feries">
<input type="hidden" id="id" name="id" value="">
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
  <td class="centre" width=50%><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_FERIES_EXISTANTS']; ?></div></td>
  <td class="centre marge50_gauche" width=50%><div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_FERIES_CREER_MODIFIER']; ?></div></td>
</tr>
<tr>
  <td width=50% valign=top>
    <table cellspacing=0 cellpadding=0 width=100%>
<?php
  $req=mysql_query("SELECT * FROM `dates_speciales` WHERE date LIKE '".$_SESSION['annee_en_cours']."%' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%') ORDER BY date ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    echo '<tr><td class="textgauche marge10_gauche" width=15%>'.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).'</td>';
    echo '<td class="textdroite marge10_droite" width=35%><input type="Button" id="Modifier'.$i.'" name="Modifier'.$i.'" value="'.$Langue['BTN_MODIFIER'].'" onClick="Calendrier_Feries_Change_Date(\''.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).'\')">&nbsp;';
		echo '<input type="Button" id="Supprimer'.$i.'" name="Supprimer'.$i.'" value="'.$Langue['BTN_SUPPRIMER'].'" onClick="Calendrier_Feries_Supprime_Date(\''.mysql_result($req,$i-1,'date').'\')"></td>';
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
	<input type="Submit" id="Ajouter2" name="Ajouter2" value="<?php echo $Langue['BTN_AJOUTER']; ?>">&nbsp;<input type="Button" id="Annuler2" name="Annuler2" value="<?php echo $Langue['BTN_ANNULER2']; ?>" onClick="Calendrier_Feries_Vide_Date()"><br><br>
	<?php echo $Langue['LBL_FERIES_DATE']; ?> : <input type=text class="text ui-widget-content ui-corner-all" id="nouvelle_date" name="nouvelle_date" value="" size=10 maxlength=10>
	<script> $(function() { $( "#nouvelle_date" ).datepicker({ dateFormat: "<?php echo $Format_Date_Calendar; ?>", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, minDate:new Date(<?php echo $_SESSION['annee_en_cours']; ?>,0,1), maxDate:new Date(<?php echo $_SESSION['annee_en_cours']; ?>,11,31) }); });</script>
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
		window.open("http://www.doxconception.com/site/index.php/directeur-agenda/article/173-gerer-les-jours-feries.html","Aide");
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
    if (!checkRegexp($("#nouvelle_date"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
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
				Charge_Dialog("index2.php?module=calendrier&action=jours_feries","<?php echo $Langue['LBL_GESTION_FERIES']; ?>");
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

function Calendrier_Feries_Change_Date(datation)
{
  $("#nouvelle_date").val(datation);
  $("#id").val(datation);
  $("#Ajouter2").val('<?php echo $Langue['BTN_MODIFIER']; ?>');
	$("#dialog-form").scrollTop(0);
}

function Calendrier_Feries_Supprime_Date(datation)
{
  $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_FERIES']; ?></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#dialog-confirm" ).dialog(
	{
		title: "<?php echo $Langue['LBL_SUPPRIMER_FERIES']; ?>",
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
				var request = $.ajax({type: "POST", url: "index2.php", data: "module=calendrier&action=delete_feries&date="+datation});
				request.done(function(msg)
				{
					Message_Chargement(1,1);
					$( "#dialog-confirm" ).dialog( "close" );
					Charge_Dialog("index2.php?module=calendrier&action=jours_feries","<?php echo $Langue['LBL_GESTION_FERIES']; ?>");
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

function Calendrier_Feries_Vide_Date()
{
  $("#nouvelle_date").val('');
  $("#nouvelle_date").removeClass( "ui-state-error" );
  $("#id").val('');
  $("#Ajouter2").val('<?php echo $Langue['BTN_AJOUTER']; ?>');
}
</script>
