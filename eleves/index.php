<?php
  if (!isset($_SESSION['tableau_eleves']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement
    $_SESSION['tableau_eleves']="30|0|1|asc";
  }

  if (!isset($_SESSION['colonnes_eleves']))
  {
		$_SESSION['colonnes_eleves']='{"bSortable": false,"bVisible":true,"aTargets":[0]}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":true}|{"bSortable":false,"bVisible":true}';
    $req=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".$_SESSION['id_util']."'");
		if (mysql_num_rows($req)!="")
		{
			if (mysql_result($req,0,'eleves')!="")
			{
				$_SESSION['colonnes_eleves']=mysql_result($req,0,'eleves');
			}
		}
  }
  if (!isset($_SESSION['recherche_eleves']))
  {
		$_SESSION['recherche_eleves']='||||||||||||||';
  }
  
  $tableau_personnalisation=explode("|",$_SESSION['tableau_eleves']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_eleves']);
  
// Récupération des informations
  foreach ($tableau_variable['eleves'] AS $cle)
  {
    $tableau_variable['eleves'][$cle['nom']]['value'] = "";
  }

  $tpl = new template("eleves");
  $tpl->set_file("gliste","listview".$_SESSION['type_util'].".html");
  $tpl->set_block('gliste','liste_entete','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

	$colonne=0;
	foreach ($tableau_recherche['eleves'] AS $cle)
	{
	  if (array_key_exists('recherche',$cle))
		{
			$cle['valeur_recherche']=$tableau_recherche2[$colonne];
			$tpl->set_var("RECHERCHE_".strtoupper($cle['nom']), Recherche_Cle($cle));
			$colonne++;
		}
	}

  $tpl->set_var("BUTTON",'<button id="creer-element">'.$Langue['BTN_CREER_ELEVE'].'</button>');
  $tpl->set_var("ANNEE_S",Liste_Annee("annee_s",'form',$_SESSION['annee_scolaire']));
  if ($_SESSION['type_util']=="D")
  {
    $tpl->set_var("CLASSES_S",Liste_Classes("classes_s",'form',$_SESSION['annee_scolaire'],$_SESSION['id_classe_cours'],'',true));
  }
  else
  {
    $tpl->set_var("CLASSES_S",Liste_Classes("classes_s",'form',$_SESSION['annee_scolaire'],$_SESSION['id_classe_cours'],$_SESSION['id_util'],false));
  }
  
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $debut_annee=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
    $debut_annee=date("Y-m-d",mktime(0,0,0,substr($gestclasse_config_plus['fin_annee_scolaire'],1,2),substr($gestclasse_config_plus['fin_annee_scolaire'],4,2),$_SESSION['annee_scolaire']+1));
  }
  if ($_SESSION['affiche_presents']=="0")
  {
    if ($_SESSION['id_classe_cours']=="")
    {
      $req = mysql_query("SELECT * FROM `eleves` WHERE date_entree<='$debut_annee' ORDER BY nom ASC, prenom ASC");
    }
    else
    {
      $req = mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$debut_annee' ORDER BY eleves.nom ASC, eleves.prenom ASC");
    }
    $tpl->set_var("PRESENTS",'<input type="checkbox" id="affiche_presents" name="affiche_presents" value="1"> '.$Langue['LBL_UNIQUEMENT_INSCRITS']);
  }
  else
  {
    if ($_SESSION['id_classe_cours']=="")
    {
      $req = mysql_query("SELECT * FROM `eleves` WHERE date_entree<='$debut_annee' AND (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."') ORDER BY nom ASC, prenom ASC");
    }
    else
    {
      $req = mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$debut_annee' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".date("Y-m-d")."') ORDER BY eleves.nom ASC, eleves.prenom ASC");
    }
    $tpl->set_var("PRESENTS",'<input type="checkbox" id="affiche_presents" name="affiche_presents" checked value="0"> '.$Langue['LBL_UNIQUEMENT_INSCRITS']);
  }

  $tpl->parse('liste_bloc','liste_entete',true);

// Affichage de la liste
  $tpl->set_file("gliste2","listview".$_SESSION['type_util'].".html");
  $tpl->set_block('gliste2','liste','liste_bloc2');
  $nbr_lignage = mysql_num_rows($req);
  for ($i=1;$i<=$nbr_lignage;$i++)
  {
	  $tpl->set_var("NUMERO",$i);
    foreach ($tableau_variable['eleves'] AS $cle)
    {
      $tableau_variable['eleves'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
    }
    foreach ($tableau_variable['eleves'] AS $cle)
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
		$acces="";
		if (mysql_result($req,$i-1,"date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"date_sortie")>=date("Y-m-d"))
    {
      if (mysql_result($req,$i-1,"identifiant")!="" && mysql_result($req,$i-1,"passe")!="")
      {
				$acces=mysql_result($req,$i-1,"identifiant");
			}
    }
    $tpl->set_var("ACCES",$acces);
    
    if (mysql_result($req,$i-1,"date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"date_sortie")>=date("Y-m-d"))
    {
      $tpl->set_var("SORTI","");
    }
    else
    {
      $tpl->set_var("SORTI",'class="sorti"');
    }

    $req2=mysql_query("SELECT eleves_classes.*, classes.*, listes.* FROM `eleves_classes`,`classes`,`listes` WHERE eleves_classes.id_eleve='".mysql_result($req,$i-1,"id")."' AND eleves_classes.id_classe=classes.id AND classes.annee='".$_SESSION['annee_scolaire']."' AND eleves_classes.id_niveau=listes.id");
    if (mysql_num_rows($req2)!='')
    {
      if ($_SESSION['type_util']=="D")
      {
  	    $tpl->set_var("CLASSE",'<a title="'.$Langue['SPAN_VOIR_CLASSE'].'" href=#null onClick="Eleves_L_Charge_Classe(\''.mysql_result($req2,0,'classes.id').'\')">'.mysql_result($req2,0,'classes.nom_classe').'</a>');
			}
			else
      {
        $tpl->set_var("CLASSE",mysql_result($req2,0,'classes.nom_classe'));
			}
      $tpl->set_var("NIVEAU",mysql_result($req2,0,'listes.intitule'));
    }
    else
    {
      $tpl->set_var("CLASSE","");
      $tpl->set_var("NIVEAU","");
    }
    if ($_SESSION['type_util']=="D")
    {
      $tpl->set_var("MODIFIER",'&nbsp;<a title="'.$Langue['SPAN_MODIFIER_ELEVE'].'" href="#null" onClick="Eleves_L_EditView_Util(\''.mysql_result($req,$i-1,'id').'\');"><img src="images/editer.png" width=12 height=12 border=0></a>');
    }
    else
    {
      if ($_SESSION['titulaire_classe_cours']==$_SESSION['id_util'])
      {
        $tpl->set_var("MODIFIER",'&nbsp;<a title="'.$Langue['SPAN_MODIFIER_ELEVE'].'" href="#null" onClick="Eleves_L_EditView_Util(\''.mysql_result($req,$i-1,'id').'\');"><img src="images/editer.png" width=12 height=12 border=0></a>');
      }
      else
      {
        $tpl->set_var("MODIFIER",'');
      }
    }
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
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-eleves.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-eleves.html","Aide");
<?php } ?>
  });

	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	dessine=0;
	
  /* Création du tableau de données */
	oTable_eleves=$('#listing_donnees_eleves').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "bAutoWidth": true,
      "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
			"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
			"sDom": 'C<"clear"><"H"lr>t<"F"ip>',
			"oColVis": 
			{
				"buttonText": "<?php echo $Langue['LBL_TABLEAU_MONTRER_CACHER']; ?>",
				"aiExclude": [ 0,1,17 ],
				"fnStateChange": function(iColumn, bVisible)
				{
					url = "index2.php";
				  if (bVisible==false) { bVisible2=0; } else { bVisible2=1; }
  				data = "module=users&action=save_perso2&longueur=17&module_session=eleves&colonne="+iColumn+"&visible="+bVisible2;
	  			var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
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
				if (faire!=false)
				{
					url = "index2.php";
					data = "module=users&action=save_perso&module_session=eleves&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
					var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
      "aoColumns" : [ <?php echo str_replace("|",",",$_SESSION['colonnes_eleves']); ?> ],
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
      }
    }
  );

  $("#Rechercher_Liste").button();
	$("#Rechercher_Liste").click(function()
	{
	  if (document.getElementById('affiche_recherche_eleves').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_eleves').style.visibility="visible";
		  document.getElementById('affiche_recherche_eleves').style.display="block";
			$("#recherche_nom").focus();
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_eleves').style.visibility="hidden";
		  document.getElementById('affiche_recherche_eleves').style.display="none";
		}
	});

  $("#rechercher_eleves").button();
	$("#rechercher_eleves").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['eleves'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_eleves.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=eleves&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_eleves").button();
	$("#vider_eleves").click(function()
	{
<?php
		foreach ($tableau_recherche['eleves'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_eleves.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=eleves&recherche=|||||||||||||||";
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  /* EditView : Création des boutons et des actions associées */
  $("#creer-element").button();
	$("#creer-element").click(function()
  {
    Charge_Dialog("index2.php?module=eleves&action=editview","<?php echo $Langue['BTN_CREER_ELEVE']; ?>");
	});

  $("#Imprimer").button();
  $("#Imprimer").click(function()
  {
    Charge_Dialog("index2.php?module=eleves&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
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

  /* Choix si on affiche tout le monde ou uniquement les présents */
	$("#affiche_presents").click(function()
  {
     Message_Chargement(1,1);
     var url="users/change_affiche.php";
     var data="affiche_choisi="+$("#affiche_presents").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});

  /* Choix de la classe en cours */
<?php if ($_SESSION['type_util']=="D") { ?>
	$("#classes_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_classe.php";
     var data="classe_choisie="+$("#classes_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});
<?php } else { ?>
	$("#classes_s").change(function()
  {
    if ($("#classes_s").val()!="")
    {
      Message_Chargement(1,1);
      var url="users/change_classe.php";
      var data="classe_choisie="+$("#classes_s").val();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
        $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
      });
    }
	});
<?php } ?>

	$("#rechercher_eleves").click();
<?php if ($_SESSION['recherche_eleves']!='||||||||||||||') { echo '$("#Rechercher_Liste").click();'; } ?>

  $( "#dialog:ui-dialog" ).dialog( "destroy" );

});

  /* Fonction pour charger de DetailView d'un utilisateur */
  function Eleves_L_DetailView_Util(id)
  {
    Charge_Dialog("index2.php?module=eleves&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
  }

  /* Fonction pour charger l'EditView d'un utilisateur */
  function Eleves_L_EditView_Util(id)
  {
    Charge_Dialog("index2.php?module=eleves&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_ELEVE']; ?>");
  }

  function Eleves_L_Charge_Classe(id)
  {
    Charge_Dialog("index2.php?module=classes&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
  }
</script>