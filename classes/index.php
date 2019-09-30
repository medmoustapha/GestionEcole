<?php
  if (!isset($_SESSION['tableau_classes']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement
    $_SESSION['tableau_classes']="30|0|1|asc";
  }
  
  if (!isset($_SESSION['colonnes_classes']))
  {
		$_SESSION['colonnes_classes']='{"bSortable": false,"bVisible":true,"aTargets":[0]}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":false,"bVisible":true}';
    $req=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".$_SESSION['id_util']."'");
		if (mysql_num_rows($req)!="")
		{
			if (mysql_result($req,0,'classes')!="")
			{
				$_SESSION['colonnes_classes']=mysql_result($req,0,'classes');
			}
		}
  }
  if (!isset($_SESSION['recherche_classes']))
  {
		$_SESSION['recherche_classes']='||||||||';
  }
  
  $tableau_personnalisation=explode("|",$_SESSION['tableau_classes']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_classes']);
  
// Récupération des informations
  foreach ($tableau_variable['classes'] AS $cle)
  {
    $tableau_variable['classes'][$cle['nom']]['value'] = "";
  }

  $tpl = new template("classes");
  $tpl->set_file("gliste","listview.html");
  $tpl->set_block('gliste','liste_entete','liste_bloc');

  $tpl->set_var("BUTTON",'<button id="creer-element">'.$Langue['BTN_CREER_CLASSE'].'</button>&nbsp;<button id="Rechercher_Liste">'.$Langue['BTN_RECHERCHE_CIBLEE'].'</button>&nbsp;<button id="Imprimer">'.$Langue['BTN_IMPRIMER'].'</button>');
  $tpl->set_var("ANNEE_S",Liste_Annee("annee_s",'form',$_SESSION['annee_scolaire']));

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
	$colonne=0;
	foreach ($tableau_recherche['classes'] AS $cle)
	{
	  if (array_key_exists('recherche',$cle))
		{
			$cle['valeur_recherche']=$tableau_recherche2[$colonne];
			$tpl->set_var("RECHERCHE_".strtoupper($cle['nom']), Recherche_Cle($cle));
			$colonne++;
		}
	}
	
  $tpl->parse('liste_bloc','liste_entete',true);

// Affichage de la liste
  $tpl->set_file("gliste2","listview.html");
  $tpl->set_block('gliste2','liste','liste_bloc2');
  $req = mysql_query("SELECT * FROM `classes` WHERE annee='".$_SESSION['annee_scolaire']."' ORDER BY nom_classe ASC");
  $nbr_lignage = mysql_num_rows($req);
  for ($i=1;$i<=$nbr_lignage;$i++)
  {
	  $tpl->set_var("NUMERO",$i);
    foreach ($tableau_variable['classes'] AS $cle)
    {
      $tableau_variable['classes'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
    }
    foreach ($tableau_variable['classes'] AS $cle)
    {
      if (Variables_Affiche($cle)!="")
      {
        $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
      }
      else
      {
        $tpl->set_var(strtoupper($cle['nom']), "&nbsp;");
      }
    }
    $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req,$i-1,'id')."' AND type='T'");
    $tpl->set_var("TITULAIRE",Liste_Profs("",$type='value','',mysql_result($req2,0,'id_prof'),'',false));
    $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req,$i-1,'id')."' AND type='E'");
    $msg="";
    for ($j=1;$j<=mysql_num_rows($req2);$j++)
    {
      $msg=$msg.'<p class="label_listview">'.Liste_Profs("",$type='value','',mysql_result($req2,$j-1,'id_prof'),'',false)."</p>";
    }
    if ($msg=="") { $msg="&nbsp;"; }
    $tpl->set_var("DECHARGE",$msg);

    $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req,$i-1,'id')."' AND type='D'");
    $msg="";
    for ($j=1;$j<=mysql_num_rows($req2);$j++)
    {
      $msg=$msg.'<p class="label_listview">'.Liste_Profs("",$type='value','',mysql_result($req2,$j-1,'id_prof'),'',false)."</p>";
    }
    if ($msg=="") { $msg="&nbsp;"; }
    $tpl->set_var("DECLOISONNEMENT",$msg);

    $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req,$i-1,'id')."' AND type='S'");
    $msg="";
    for ($j=1;$j<=mysql_num_rows($req2);$j++)
    {
      $msg=$msg.'<p class="label_listview">'.Liste_Profs("",$type='value','',mysql_result($req2,$j-1,'id_prof'),'',false)."</p>";
    }
    if ($msg=="") { $msg="&nbsp;"; }
    $tpl->set_var("ATSEM",$msg);

    $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req,$i-1,'id')."' AND type='I'");
    $msg="";
    for ($j=1;$j<=mysql_num_rows($req2);$j++)
    {
      $msg=$msg.'<p class="label_listview">'.Liste_Profs("",$type='value','',mysql_result($req2,$j-1,'id_prof'),'',false)."</p>";
    }
    if ($msg=="") { $msg="&nbsp;"; }
    $tpl->set_var("INTERVENANTS_EXTERIEURS",$msg);

    $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".mysql_result($req,$i-1,'id')."' AND type='U'");
    $msg="";
    for ($j=1;$j<=mysql_num_rows($req2);$j++)
    {
      $msg=$msg.'<p class="label_listview">'.Liste_Profs("",$type='value','',mysql_result($req2,$j-1,'id_prof'),'',false)."</p>";
    }
    if ($msg=="") { $msg="&nbsp;"; }
    $tpl->set_var("AUTRES_INTERVENANTS",$msg);

    $tpl->set_var("NIVEAUX",str_replace("|",", ",Liste_Niveaux('',$type='value','',mysql_result($req,$i-1,'id'),false)));
    $req2=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_eleve=eleves.id AND eleves_classes.id_classe='".mysql_result($req,$i-1,'id')."' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".date("Y-m-d")."')");
    if (mysql_num_rows($req2)!="") { $tpl->set_var("NB_ELEVE",mysql_num_rows($req2)); } else { $tpl->set_var("NB_ELEVE","&nbsp;"); }

    $tpl->parse('liste_bloc2','liste',true);
  }

  $tpl->pparse("affichage","gliste2");
?>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/directeur-classes.html","Aide");
  });

  /* Création du tableau de données */
	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
	oTable_classes=$('#listing_donnees_classes').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "bAutoWidth": true,
      "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
			"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
			"fnDrawCallback": function( oSettings ) 
			{
			  var that = this;
				if ( oSettings.bSorted || oSettings.bFiltered )
				{
					this.$('td:first-child', {"filter":"applied"}).each( function (i) 
					{
						that.fnUpdate( i+1, this.parentNode, 0, false, false );
					} );
				}
			  faire=false;
				colonne_index=oSettings.aaSorting[0][0];
				colonne_ordre=oSettings.aaSorting[0][1];
				if (longueur_tableau!=oSettings._iDisplayLength) { faire=true;longueur_tableau=oSettings._iDisplayLength; }
//				if (page_tableau!=oSettings._iDisplayStart) { faire=true;page_tableau=oSettings._iDisplayStart; }
				if (colonne_tableau!=colonne_index) { faire=true;colonne_tableau=colonne_index; }
				if (ordre_tableau!=colonne_ordre) { faire=true;ordre_tableau=colonne_ordre; }
				if (faire==true)
				{
					url = "index2.php";
					data = "module=users&action=save_perso&module_session=classes&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
					var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
			"sDom": 'C<"clear"><"H"lr>t<"F"ip>',
			"oColVis": 
			{
				"buttonText": "<?php echo $Langue['LBL_TABLEAU_MONTRER_CACHER']; ?>",
				"aiExclude": [ 0,1,10 ],
				"fnStateChange": function(iColumn, bVisible)
				{
					url = "index2.php";
				  if (bVisible==false) { bVisible2=0; } else { bVisible2=1; }
  				data = "module=users&action=save_perso2&longueur=10&module_session=classes&colonne="+iColumn+"&visible="+bVisible2;
	  			var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
      "aoColumns" : [ <?php echo str_replace('|',',',$_SESSION['colonnes_classes']); ?> ],
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_ELEMENT_AFFICHES']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_NO_DATA']; ?>",
        "sInfo": "<?php echo $Langue['LBL_ELEMENT_AFFICHES2']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_RESULT_RECHERCHE']; ?>",
        "sSearch": "<?php echo $Langue['LBL_RECHERCHER_DATA']; ?>",
        "oPaginate":
        {
          "sFirst":    "<?php echo $Langue['LBL_DEBUT']; ?>",
          "sPrevious": "<?php echo $Langue['LBL_PRECEDENT']; ?>",
          "sNext":     "<?php echo $Langue['LBL_SUIVANT']; ?>",
          "sLast":     "<?php echo $Langue['LBL_FIN']; ?>"
        }
      },
    }
  );

  $("#Rechercher_Liste").button();
	$("#Rechercher_Liste").click(function()
	{
	  if (document.getElementById('affiche_recherche_classes').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_classes').style.visibility="visible";
		  document.getElementById('affiche_recherche_classes').style.display="block";
			$("#recherche_nom_classe").focus();
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_classes').style.visibility="hidden";
		  document.getElementById('affiche_recherche_classes').style.display="none";
		}
	});

  /* EditView : Création des boutons et des actions associées */
  $("#creer-element").button();
	$("#creer-element").click(function()
  {
    Charge_Dialog("index2.php?module=classes&action=editview","<?php echo $Langue['BTN_CREER_CLASSE']; ?>");
	});

  $("#Imprimer").button();
  $("#Imprimer").click(function()
  {
    Charge_Dialog("index2.php?module=classes&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  /* Choix de l'année en cours */
	$("#annee_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_annee.php";
     var data="annee_choisi="+$("#annee_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});

  $("#rechercher_classes").button();
	$("#rechercher_classes").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['classes'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_classes.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=classes&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_classes").button();
	$("#vider_classes").click(function()
	{
<?php
		foreach ($tableau_recherche['classes'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_classes.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=classes&recherche=|||||||||";
		var request = $.ajax({type: "POST", url: url, data: data});
	});

	$("#rechercher_classes").click();
<?php if ($_SESSION['recherche_classes']!='||||||||') { echo '$("#Rechercher_Liste").click();'; } ?>

  $( "#dialog:ui-dialog" ).dialog( "destroy" );

});

  /* Fonction pour charger de DetailView d'un utilisateur */
  function Classes_L_DetailView_Util(id)
  {
    Charge_Dialog("index2.php?module=classes&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
  }

  /* Fonction pour charger l'EditView d'un utilisateur */
  function Classes_L_EditView_Util(id)
  {
    Charge_Dialog("index2.php?module=classes&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_CLASSE']; ?>");
  }

</script>
