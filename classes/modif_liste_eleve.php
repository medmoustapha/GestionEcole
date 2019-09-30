<?php
  $id_classe=$_GET['id_classe'];
?>
<!-- BEGIN formulaire -->
<a name="haut_formulaire2"></a>
<form action="index2.php" method=POST id="form_editview2" name=form_editview2>
<input type="hidden" id="module" name="module" value="classes">
<input type="hidden" id="action" name="action" value="save_eleves">
<input type="hidden" id="id_classe" name="id_classe" value="<?php echo $id_classe; ?>">
<div id="message2"></div>
<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
<tr>
  <td class="gauche">
    <input type="Submit" id="Enregistrer_Eleve" name="Enregistrer_Eleve" value="<?php echo $Langue['BTN_ENREGISTRER']; ?>">&nbsp;
    <input type="Button" id="Annuler_Eleve" name="Annuler_Eleve" value="<?php echo $Langue['BTN_ANNULER']; ?>">
  </td>
</tr>
</table>
<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form"><?php echo $Langue['LBL_AJOUT_ELEVES']; ?></div>
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_AJOUT_ELEVES']; ?> :</td>
<?php
  $id_classe=$_GET['id_classe'];
  $req=mysql_query("SELECT * FROM `eleves` WHERE date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."' ORDER BY nom ASC, prenom ASC");
  $msg='<select tabindex=1 name=id_eleve[] id=id_eleve multiple="multiple" size="5">';
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $req2=mysql_query("SELECT eleves_classes.*, classes.* FROM `eleves_classes`,`classes` WHERE eleves_classes.id_eleve='".mysql_result($req,$i-1,'id')."' AND eleves_classes.id_classe=classes.id AND classes.annee='".$_SESSION["annee_scolaire"]."'");
    if (mysql_num_rows($req2)=="")
    {
      $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'">'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'prenom').'</option>';
    }
  }
  $msg=$msg.'</select>';
  $msg=$msg.'<script language="Javascript">
  $(document).ready( function()
  {
    $("#id_eleve").multiselect(
    {
      header:false,
      selectAll: false,
      noneSelectedText: "'.$Langue['LBL_SELECT_ELEVES'].'",
      selectedText: "# '.$Langue['LBL_SELECTED_ELEVES'].'"
    });
  });
  </script>';
  echo '<td class="gauche" width=80%>'.$msg.'</td></tr>';
?>
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LBL_NIVEAU']; ?> :</td>
<td class="gauche" width="80%"><?php echo Liste_Niveaux("id_niveau","form","",$id_classe,false,'2'); ?></td></tr>
<tr><td class="droite" width=20%><label class="label_class"><?php echo $Langue['LST_CLASSE_REDOUBLEMENT']; ?> :</td>
<td class="gauche" width="80%">
<?php
  $msg='<select tabindex=3 id=redoublement name=redoublement class="text ui-widget-content ui-corner-all">';
  foreach ($liste_choix['ouinon'] AS $cle => $value)
  {
    $msg=$msg.'<option value="'.$cle.'">'.$value.'</option>';
  }
  $msg=$msg.'</select>';
  echo $msg;
?>
</td></tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer_Eleve").button();
  $("#Annuler_Eleve").button();
  $("#Annuler_Eleve").click(function()
  {
    $("#modif_liste").button({ disabled: false });
    $("#modif_liste2").button({ disabled: false });
    $("#dialog-niveau2").dialog( "close" );
  });
  
  $("#form_editview2").submit(function(event)
  {
		var results = $(this).serialize();
    event.preventDefault();
    if (results.indexOf("id_eleve",0)==-1)
    {
      $("#message2").fadeIn( 1000 );
  	  $("#message2").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all marge10_tout margin10_haut"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
      $("#id_eleve2").addClass( "ui-state-error" );
			setTimeout(function()
      {
        $("#message2").effect("blind",1000);
		  }, 3000 );
    }
    else
    {
      Message_Chargement(2,1);
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        Charge_Dialog("index2.php?module=classes&action=detailview&id="+msg,"<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
        $("#tabs").tabs("load",2);
        $("#dialog-niveau2").dialog( "close" );
        Message_Chargement(1,0);
      });
    }
  });
});
</script>