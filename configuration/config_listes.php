<?php
  $id_liste="";
  if (isset($_GET['id_liste']))
  {
    $id_liste=$_GET['id_liste'];
  }
?>
<p class="aide2"><button id="aide_fenetre"><?php echo $Langue['BTN_AIDE']; ?></button></p>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_LISTES_TITRE']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_LISTES_LISTES_DISPO']; ?> :</label></td>
  <td class="gauche" width=85%><select name="id_liste" id="id_liste" class="text ui-widget-content ui-corner-all">
<?php
  foreach ($listes_personnalisables[$_SESSION['type_util']] AS $cle => $value)
  {
    echo '<option value="'.$cle.'"';
    if ($cle==$id_liste) { echo ' SELECTED'; }
    echo '>'.$cle.' : '.$value.'</option>';
  }
?>
  </select>
  </td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_LISTES_PERSONNALISATION']; ?></div>
<div id="personnalisation"></div>

<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();		
<?php if ($_SESSION['type_util']=="D") { ?>
    window.open("http://www.doxconception.com/site/index.php/directeur-configuration/article/228-directeur-configurer-les-listes-de-choix.html","Aide");
<?php } else { ?>
    window.open("http://www.doxconception.com/site/index.php/prof-configuration/article/229-prof-configurer-les-listes-de-choix.html","Aide");
<?php } ?>
  });

  $("#id_liste").change(function()
  {
    Message_Chargement(1,1);
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=config_listes_liste&id_liste="+$("#id_liste").val()});
    request.done(function(msg)
    {
      Message_Chargement(1,0);
      $("#personnalisation").html(msg);
    });
  });
  
  $("#id_liste").change();
});
</script>
