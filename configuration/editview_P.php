<?php
  Param_Utilisateur($_SESSION['id_util'],$_SESSION['annee_scolaire']);
  
  switch ($_GET['id'])
  {
    case "horaires": $titre=$Langue['LBL_HORAIRES_TITRE_PROFS']; $lien_site="prof-configuration/article/244-prof-definir-les-horaires-de-votre-etablissement.html"; break;
    case "livrets": $titre=$Langue['LBL_LIVRETS_PARAMETRES_LS_PROFS']; $lien_site="prof-configuration/article/240-prof-config-configurer-les-livrets-scolaires.html"; break;
    case "bibliotheque": $titre=$Langue['LBL_BIBLIO_TITRE_PROFS']; $lien_site="prof-configuration/article/234-config-configurer-sa-bibliotheque-de-classe.html"; break;
  }
  
  $tpl = new template("configuration");
  $tpl->set_file("gform","editview_P_".$_GET['id'].".html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  for($i=1;$i<=6;$i++)
  {
    $msg='<select class="text ui-widget-content ui-corner-all" name="matin'.$i.'" id="matin'.$i.'">';
    $msg2='<select class="text ui-widget-content ui-corner-all" name="am'.$i.'" id="matin'.$i.'">';
    foreach ($liste_choix['ouvertferme'] AS $cle => $value)
    {
      $msg=$msg.'<option value="'.$cle.'"';
      $msg2=$msg2.'<option value="'.$cle.'"';
      if ($cle==$gestclasse_config_plus['matin'.$i]) { $msg=$msg.' SELECTED'; }
      if ($cle==$gestclasse_config_plus['am'.$i]) { $msg2=$msg2.' SELECTED'; }
      $msg=$msg.'>'.$value.'</option>';
      $msg2=$msg2.'>'.$value.'</option>';
    }
    $tpl->set_var("MATIN".$i,$msg.'</select>');
    $tpl->set_var("AM".$i,$msg2.'</select>');
  }
  
  $jour_matin_debut='<select class="text ui-widget-content ui-corner-all" name="jour_matin_debut" id="jour_matin_debut">';
  $jour_matin_fin='<select class="text ui-widget-content ui-corner-all" name="jour_matin_fin" id="jour_matin_fin">';
  $jour_am_debut='<select class="text ui-widget-content ui-corner-all" name="jour_am_debut" id="jour_am_debut">';
  $jour_am_fin='<select class="text ui-widget-content ui-corner-all" name="jour_am_fin" id="jour_am_fin">';
  for ($i=8;$i<=12;$i++)
  {
    for ($j=0;$j<=59;$j++)
    {
      $heu=date("H:i",mktime($i,$j,0,date("m"),date("d"),date("Y")));
      $heu2=date("H:i",mktime($i+5,$j,0,date("m"),date("d"),date("Y")));
      $jour_matin_debut=$jour_matin_debut.'<option value="'.$heu.'"';
      $jour_matin_fin=$jour_matin_fin.'<option value="'.$heu.'"';
      $jour_am_debut=$jour_am_debut.'<option value="'.$heu2.'"';
      $jour_am_fin=$jour_am_fin.'<option value="'.$heu2.'"';
      if ($heu==$gestclasse_config_plus['jour_matin_debut']) { $jour_matin_debut=$jour_matin_debut.' SELECTED'; }
      if ($heu==$gestclasse_config_plus['jour_matin_fin']) { $jour_matin_fin=$jour_matin_fin.' SELECTED'; }
      if ($heu2==$gestclasse_config_plus['jour_am_debut']) { $jour_am_debut=$jour_am_debut.' SELECTED'; }
      if ($heu2==$gestclasse_config_plus['jour_am_fin']) { $jour_am_fin=$jour_am_fin.' SELECTED'; }
      $jour_matin_debut=$jour_matin_debut.'>'.$heu.'</option>';
      $jour_matin_fin=$jour_matin_fin.'>'.$heu.'</option>';
      $jour_am_debut=$jour_am_debut.'>'.$heu2.'</option>';
      $jour_am_fin=$jour_am_fin.'>'.$heu2.'</option>';
    }
  }
  $tpl->set_var("HEURE_DEBUT_MATIN",$jour_matin_debut.'</select>');
  $tpl->set_var("HEURE_FIN_MATIN",$jour_matin_fin.'</select>');
  $tpl->set_var("HEURE_DEBUT_AM",$jour_am_debut.'</select>');
  $tpl->set_var("HEURE_FIN_AM",$jour_am_fin.'</select>');

  // **************************
  // * Cas de la bibliothèque *
  // **************************

  $msg2='<select class="text ui-widget-content ui-corner-all" name="biblio_classe" id="biblio_classe">';
  foreach ($liste_choix['ouinon'] AS $cle => $value)
  {
    $msg2=$msg2.'<option value="'.$cle.'"';
    if ($cle==$gestclasse_config_plus['biblio_classe']) { $msg2=$msg2.' SELECTED'; }
    $msg2=$msg2.'>'.$value.'</option>';
  }
  $msg2=$msg2.'</select>';
  $tpl->set_var("BIBLIO_CLASSE",$msg2);
  
  $msg2='<select class="text ui-widget-content ui-corner-all" name="biblio_duree_emprunt_classe" id="biblio_duree_emprunt_classe">';
  for ($i=1;$i<=30;$i++)
  {
    $msg2=$msg2.'<option value="'.$i.'"';
    if ($i==$gestclasse_config_plus['biblio_duree_emprunt_classe']) { $msg2=$msg2.' SELECTED'; }
    $msg2=$msg2.'>'.$i.' jour';
	if ($i!="1") { $msg2=$msg2.'s'; }
	$msg2=$msg2.'</option>';
  }
  $msg2=$msg2.'</select>';
  $tpl->set_var("DUREE_EMPRUNT_CLASSE",$msg2);
  
  $msg2='<select class="text ui-widget-content ui-corner-all" name="biblio_nombre_livres_classe" id="biblio_nombre_livres_classe">';
  for ($i=1;$i<=10;$i++)
  {
    $msg2=$msg2.'<option value="'.$i.'"';
    if ($i==$gestclasse_config_plus['biblio_nombre_livres_classe']) { $msg2=$msg2.' SELECTED'; }
    $msg2=$msg2.'>'.$i.' livre';
	if ($i!="1") { $msg2=$msg2.'s'; }
	$msg2=$msg2.'</option>';
  }
  $msg2=$msg2.'</select>';
  $tpl->set_var("NB_EMPRUNT_CLASSE",$msg2);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/<?php echo $lien_site; ?>","Aide");
  });

  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#EnregistrerFermer").button();
  $("#Annuler").button();
  $("#Enregistrer2").button();
  $("#EnregistrerFermer2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="edit";
  });
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		/* On contrôle la saisie du formulaire */
<?php
    switch ($_GET['id'])
    {
      case "horaires": ?>
        if (Compare_Heure("01/01/2000 "+$("#jour_matin_debut").val(),"01/01/2000 "+$("#jour_matin_fin").val())<0)
        {
          $("#jour_matin_debut").addClass( "ui-state-error" );
          $("#jour_matin_fin").addClass( "ui-state-error" );
          bValid=false;
        }
        else
        {
          $("#jour_matin_debut").removeClass( "ui-state-error" );
          $("#jour_matin_fin").removeClass( "ui-state-error" );
        }
        if (Compare_Heure("01/01/2000 "+$("#jour_am_debut").val(),"01/01/2000 "+$("#jour_am_fin").val())<0)
        {
          $("#jour_am_debut").addClass( "ui-state-error" );
          $("#jour_am_fin").addClass( "ui-state-error" );
          bValid=false;
        }
        else
        {
          $("#jour_am_debut").removeClass( "ui-state-error" );
          $("#jour_am_fin").removeClass( "ui-state-error" );
        }
        /* Voir pour les horaires de début et de fin de matinée / après-midi */
<?php      break;
      default: ?>
<?php        break;
  }
?>
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","configuration","<?php echo $titre; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        updateTips("success","configuration","<?php echo $titre; ?>");
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","configuration","<?php echo $titre; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="edit";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer2").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });
});
</script>
