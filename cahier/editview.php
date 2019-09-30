<?php
// Récupération des informations
  foreach ($tableau_variable['cahier'] AS $cle)
  {
    $tableau_variable['cahier'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `cahierjournal` WHERE id = '" . $_GET['id'] . "'");
    $tableau_variable['cahier']['id_matiere']['idclasse']=mysql_result($req,0,'id_classe');
    foreach ($tableau_variable['cahier'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['cahier'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
  }
  else
  {
    if (isset($_GET['id_classe']))
    {
      $tableau_variable['cahier']['id_classe']['value']=$_GET['id_classe'];
      $tableau_variable['cahier']['id_matiere']['idclasse']=$_GET['id_classe'];
      $tableau_variable['cahier']['id_niveau']['value']=$_GET['id_niveau'];
      $tableau_variable['cahier']['heure_debut']['value']=$_GET['heure_debut'].":00";
      $tableau_variable['cahier']['heure_fin']['value']=$_GET['heure_debut'].":00";
    }
    else
    {
      $req2=mysql_query("SELECT classes.*,classes_profs.* FROM `classes`,`classes_profs` WHERE classes_profs.id_prof='".$_SESSION['id_util']."' AND classes_profs.id_classe=classes.id AND classes.annee='".$_SESSION['annee_scolaire']."' AND (classes_profs.type='T' OR classes_profs.type='E' OR classes_profs.type='D') ORDER BY classes_profs.type DESC");
      $tableau_variable['cahier']['id_classe']['value']=mysql_result($req2,0,'classes.id');
      $tableau_variable['cahier']['id_matiere']['idclasse']=mysql_result($req2,0,'classes.id');
      $jour=date("w",mktime(0,0,0,substr($_SESSION['date_en_cours'],5,2),substr($_SESSION['date_en_cours'],8,2),substr($_SESSION['date_en_cours'],0,4)));
      if ($gestclasse_config_plus['matin'.$jour]=="1")
      {
        $tableau_variable['cahier']['heure_debut']['value']=$gestclasse_config_plus['jour_matin_debut'].":00";
        $tableau_variable['cahier']['heure_fin']['value']=$gestclasse_config_plus['jour_matin_debut'].":00";
      }
      else
      {
        $tableau_variable['cahier']['heure_debut']['value']=$gestclasse_config_plus['jour_am_debut'].":00";
        $tableau_variable['cahier']['heure_fin']['value']=$gestclasse_config_plus['jour_am_debut'].":00";
      }
    }
  }

  $tpl = new template("cahier");
  $tpl->set_file("gform","editview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['cahier'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  
  $contenu_devoir="";
  $date_faire="";
  
  if (isset($_GET['id']))
  {
    $tpl->set_var("ID_CLASSE",'<label class="label_detail">'.Liste_Classes("id_classe","value",$_SESSION['annee_scolaire'],$tableau_variable['cahier']['id_classe']['value'],$_SESSION['id_util'],false).'<input type="hidden" name=id_classe id=id_classe value="'.$tableau_variable['cahier']['id_classe']['value'].'"></label>');
    $req3=mysql_query("SELECT * FROM `listes` WHERE id='".$tableau_variable['cahier']['id_niveau']['value']."'");
    $tpl->set_var("ID_NIVEAU",'<label class="label_detail">'.mysql_result($req3,0,'intitule').'<input type="hidden" name="id_niveau2[]" id="id_niveau2" value="'.$tableau_variable['cahier']['id_niveau']['value'].'"></label>');
    $req_devoirs=mysql_query("SELECT * FROM `devoirs` WHERE id_seance='".$_GET['id']."'");
    if (mysql_num_rows($req_devoirs)!="")
    {
      $date_faire=Date_Convertir(mysql_result($req_devoirs,0,'date_faire'),"Y-m-d",$Format_Date_PHP);
      $contenu_devoir=mysql_result($req_devoirs,0,'contenu');
    }
  }
  else
  {
    $tpl->set_var("ID_CLASSE",Liste_Classes("id_classe","form",$_SESSION['annee_scolaire'],$tableau_variable['cahier']['id_classe']['value'],$_SESSION['id_util'],false));
    $tpl->set_var("ID_NIVEAU",Liste_Niveaux("id_niveau2","form",$tableau_variable['cahier']['id_niveau']['value'],$tableau_variable['cahier']['id_classe']['value'],true,'2'));
  }
  $tpl->set_var("HEURE_DEBUT",Liste_Heures("heure_debut",$tableau_variable['cahier']['id_classe']['value'],$_SESSION['date_en_cours'],$tableau_variable['cahier']['heure_debut']['value'],'4'));
  $tpl->set_var("HEURE_FIN",Liste_Heures("heure_fin",$tableau_variable['cahier']['id_classe']['value'],$_SESSION['date_en_cours'],$tableau_variable['cahier']['heure_fin']['value'],'5'));
  $tpl->set_var("DATE_E",$_SESSION['date_en_cours']);
  $tpl->set_var("ID_PROF_E",$_SESSION['id_util']);
  $tpl->set_var("DATE_FAIRE",'<input tabindex="10" type="text" class="text ui-widget-content ui-corner-all" id="date_faire" name="date_faire" value="'.$date_faire.'" size=10 maxlength=10>');
  $tpl->set_var("DEVOIRS",'<textarea tabindex="11" class="text ui-widget-content ui-corner-all" id="devoirs" name="devoirs" rows=3 cols=100>'.$contenu_devoir.'</textarea>');

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $jour_max=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $jour_min=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $an=$_SESSION['annee_scolaire']+1;
	  $jour_max=$an.$gestclasse_config_plus['fin_annee_scolaire'];
  }
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#EnregistrerNouveau").button();
  $("#Annuler").button();
  $("#Enregistrer2").button();
  $("#EnregistrerNouveau2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  /* Fait fonctionner le champ date_faire en calendrier */
  $("#date_faire").datepicker(
  {
    dateFormat: "<?php echo $Format_Date_Calendar; ?>",
    showOn: "button",
    buttonImage: "images/calendar.gif",
    buttonImageOnly: true,
    minDate:new Date(<?php echo substr($jour_min,0,4); ?>,<?php echo substr($jour_min,5,2); ?>-1,<?php echo substr($jour_min,8,2); ?>),
    maxDate:new Date(<?php echo substr($jour_max,0,4); ?>,<?php echo substr($jour_max,5,2); ?>-1,<?php echo substr($jour_max,8,2); ?>)
  });

  /* Quand on change la classe */
  $("#id_classe").change(function()
  {
    id_classe=$("#id_classe").val();
    id_matiere=$("#id_matiere").val();
    heure_debut=$("#heure_debut").val();
    heure_fin=$("#heure_fin").val();
    if (id_classe!="")
    {
      $("#matiere").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
      $("#niveau").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
      $("#heured").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
      $("#heuref").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
      var request = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=change_niveau&valeur_defaut=<?php echo $tableau_variable['cahier']['id_niveau']['value']; ?>&id_classe="+id_classe });
      var request2 = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=change_matiere&valeur_defaut="+id_matiere+"&id_classe="+id_classe });
      var request3 = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=change_heure&nom_liste=heure_debut&valeur_defaut="+heure_debut+"&id_classe="+id_classe });
      var request4 = $.ajax({type: "POST", url: "index2.php", data: "module=cahier&action=change_heure&nom_liste=heure_fin&valeur_defaut="+heure_fin+"&id_classe="+id_classe });
      request.done(function(msg)
      {
        $("#niveau").html(msg);
      });
      request2.done(function(msg)
      {
        $("#matiere").html(msg);
      });
      request3.done(function(msg)
      {
        $("#heured").html(msg);
      });
      request4.done(function(msg)
      {
        $("#heuref").html(msg);
      });
    }
  });

  $("#id_matiere").change(function()
  {
    Cahier_Change_Matiere();
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="edit";
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		var results = $(this).serialize();
    ed = tinyMCE.get('contenu');
    ed2 = tinyMCE.get('parent');
    if (results.indexOf("id_niveau2",0)==-1) { $("#id_niveau2").addClass( "ui-state-error" );bValid=false; } else { $("#id_niveau2").removeClass( "ui-state-error" ); }
    if (!checkValue($("#id_classe"))) { bValid=false; }
    if ($("#id_matiere").val()!="RECRE")
    {
      if (ed.getContent()=="") { bValid=false; }
    }
		if ($("#heure_debut").val()!=null && $("#heure_fin").val()!=null)
		{
			if (Compare_Heure("01/01/2000 "+$("#heure_debut").val(),"01/01/2000 "+$("#heure_fin").val())<=0)
			{
				$("#heure_debut").addClass( "ui-state-error" );
				$("#heure_fin").addClass( "ui-state-error" );
				bValid=false;
			}
			else
			{
				$("#heure_debut").removeClass( "ui-state-error" );
				$("#heure_fin").removeClass( "ui-state-error" );
			}
		}
		else
		{
			$("#heure_debut").addClass( "ui-state-error" );
			$("#heure_fin").addClass( "ui-state-error" );
			bValid=false;
		}
    /* Si une date pour les devoirs est spécifiée alors on regarde s'il y a un contenu et inversement */
    if ($("#date_faire").val()!="" || $("#devoirs").val()!="")
    {
      if (!checkValue($("#devoirs"))) { bValid=false; }
      if (!checkRegexp($("#date_faire"),<?php echo $date_regexp[$Format_Date_PHP]; ?>))
      {
        bValid=false;
      }
      else
      {
        if (Compare_Date('<?php echo Date_Convertir($_SESSION['date_en_cours'],'Y-m-d',$Format_Date_PHP); ?>',$("#date_faire").val(),'<?php echo $Format_Date_PHP; ?>')<0)
        {
          $("#date_faire").addClass( "ui-state-error" );
          bValid=false;
        }
        else
        {
          $("#date_faire").removeClass( "ui-state-error" );
        }
      }
    }
    
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","cahier","<?php echo $Langue['LBL_MODIFIER_SEANCE']; ?>");
			$('#contenu_e').val(ed.getContent());
			$('#parent_e').val(ed2.getContent());
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          Message_Chargement(1,1);
          decoupe=msg.split('-');
          Charge_Dialog("index2.php?module=cahier&action=editview&id_classe="+decoupe[0]+"&id_niveau="+decoupe[1]+"&heure_debut="+decoupe[2],"<?php echo $Langue['LBL_MODIFIER_SEANCE']; ?>");
        }
        else
        {
          updateTips("success","cahier","<?php echo $Langue['LBL_MODIFIER_SEANCE']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","cahier","<?php echo $Langue['LBL_MODIFIER_SEANCE']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer").click(function()
  {
    action_save="fermer";
  });

  $("#Enregistrer2").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau").click(function()
  {
    action_save="nouveau";
    $("#afaire").val('nouveau');
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau2").click(function()
  {
    action_save="nouveau";
    $("#afaire").val('nouveau');
    $("#form_editview").submit();
  });
  
  Cahier_Change_Matiere();
});

function Cahier_Change_Matiere()
{
  if ($("#id_matiere").val()=="RECRE")
  {
    $("#date_faire").val("");
    $("#devoirs").val("");
    $("#objectifs").val("");
    $("#materiel").val("");
    
    $("#date_faire").attr("disabled", "disabled");
    $("#devoirs").attr("disabled", "disabled");
    $("#objectifs").attr("disabled", "disabled");
    $("#materiel").attr("disabled", "disabled");
    
    $("#date_faire").addClass("input_disabled");
    $("#devoirs").addClass("input_disabled");
    $("#objectifs").addClass("input_disabled");
    $("#materiel").addClass("input_disabled");
  }
  else
  {
    $("#date_faire").removeAttr("disabled");
    $("#devoirs").removeAttr("disabled");
    $("#objectifs").removeAttr("disabled");
    $("#materiel").removeAttr("disabled");
    
    $("#date_faire").removeClass("input_disabled");
    $("#devoirs").removeClass("input_disabled");
    $("#objectifs").removeClass("input_disabled");
    $("#materiel").removeClass("input_disabled");
  }
}
</script>
