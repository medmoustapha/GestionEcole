<div class="titre_page"><?php echo $Langue['LBL_COOP_TITRE']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<br><br><br>
<?php
  $msg='<select class="text ui-widget-content ui-corner-all" id="cooperative_scolaire" name="cooperative_scolaire">';
  for ($i=2010;$i<=$_SESSION['cooperative_scolaire']+2;$i++)
  {
    if (table_ok($gestclasse_config['param_connexion']['base'],"cooperative".$i)==true)
    {
	  $msg=$msg.'<option value="'.$i.'"';
	  if ($i==$_SESSION['cooperative_scolaire']) { $msg=$msg.' SELECTED'; }
	  $j=$i+1;
	  $msg=$msg.'>'.$i.'-'.$j.'</option>';
	}
  }
  $msg=$msg.'</select>';
?>
<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
  <td class="droite" nowrap>
    <div class="ui-widget ui-state-default ui-corner-all floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_SAISIE_EXERCICE'].' : '.$msg; ?></div>
  </td>
</tr>
</table>
<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong><?php echo $Langue['MSG_PAS_COOP']; ?></strong></div></div>

<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/gerer-la-cooperative-scolaire","Aide");
  });
  
  $('#cooperative_scolaire').change(function()
  {
     Message_Chargement(1,1);
     var url="cooperative/change_annee.php";
     var data="annee_choisi="+$("#cooperative_scolaire").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function()
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
     });
  });
});
</script>