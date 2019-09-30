<?php
  if (!isset($_SESSION['tableau_personnels']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement
    $_SESSION['tableau_personnels']="30|0|1|asc";
  }
  
  if (!isset($_SESSION['colonnes_personnels']))
  {
		$_SESSION['colonnes_personnels']='{"bSortable": false,"bVisible":true,"aTargets":[0]}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":true,"bVisible":false}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":false,"bVisible":true}|{"bSortable":true,"bVisible":true}|{"bSortable":false,"bVisible":true}';
    $req=mysql_query("SELECT * FROM `param_persos` WHERE id_prof='".$_SESSION['id_util']."'");
		if (mysql_num_rows($req)!="")
		{
			if (mysql_result($req,0,'personnels')!="")
			{
				$_SESSION['colonnes_personnels']=mysql_result($req,0,'personnels');
			}
		}
  }
  if (!isset($_SESSION['recherche_personnels']))
  {
		$_SESSION['recherche_personnels']='||||||||||||';
  }
  
  $tableau_personnalisation=explode("|",$_SESSION['tableau_personnels']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_personnels']);
  
// Récupération des informations
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tableau_variable['personnels'][$cle['nom']]['value'] = "";
  }

  $tpl = new template("personnels");
  $tpl->set_file("gliste","listview.html");
  $tpl->set_block('gliste','liste_entete','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

	$colonne=0;
	foreach ($tableau_recherche['personnels'] AS $cle)
	{
	  if (array_key_exists('recherche',$cle))
		{
			$cle['valeur_recherche']=$tableau_recherche2[$colonne];
			$tpl->set_var("RECHERCHE_".strtoupper($cle['nom']), Recherche_Cle($cle));
			$colonne++;
		}
	}
	
  $tpl->set_var("BUTTON",'<button id="creer-element">'.$Langue['BTN_CREER_PERSONNEL'].'</button>&nbsp;<button id="Rechercher_Liste">'.$Langue['BTN_RECHERCHE_CIBLEE'].'</button>&nbsp;<button id="Imprimer">'.$Langue['BTN_IMPRIMER'].'</button>');

  if ($_SESSION['affiche_presents']=="0")
  {
    $req = mysql_query("SELECT * FROM `profs` ORDER BY nom ASC, prenom ASC");
    $tpl->set_var("PRESENTS",'<input type="checkbox" id="affiche_presents" name="affiche_presents" value="1"> '.$Langue['LBL_UNIQUEMENT_PRESENTS']);
  }
  else
  {
    $req = mysql_query("SELECT * FROM `profs` WHERE date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."' ORDER BY nom ASC, prenom ASC");
    $tpl->set_var("PRESENTS",'<input type="checkbox" id="affiche_presents" name="affiche_presents" checked value="0"> '.$Langue['LBL_UNIQUEMENT_PRESENTS']);
  }

  $tpl->parse('liste_bloc','liste_entete',true);

// Affichage de la liste
  $tpl->set_file("gliste2","listview.html");
  $tpl->set_block('gliste2','liste','liste_bloc2');
  $nbr_lignage = mysql_num_rows($req);
	for ($i=1;$i<=$nbr_lignage;$i++)
	{
		$tpl->set_var("NUMERO",$i);
		foreach ($tableau_variable['personnels'] AS $cle)
		{
			$tableau_variable['personnels'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
		}
		foreach ($tableau_variable['personnels'] AS $cle)
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
		if ((mysql_result($req,$i-1,"identifiant")!="" && mysql_result($req,$i-1,"passe")!="") && (mysql_result($req,$i-1,"date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"date_sortie")>=date("Y-m-d")))
		{
			$tpl->set_var("ACCES",mysql_result($req,$i-1,"identifiant"));
		}
		else
		{
			$tpl->set_var("ACCES","&nbsp;");
		}
		if (mysql_result($req,$i-1,"date_sortie")=="0000-00-00" || mysql_result($req,$i-1,"date_sortie")>=date("Y-m-d"))
		{
			$tpl->set_var("SORTI","&nbsp;");
		}
		else
		{
			$tpl->set_var("SORTI",'class="sorti"');
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
    window.open("http://www.doxconception.com/site/index.php/directeur-personnels.html","Aide");
  });

	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
  /* Création du tableau de données */
	oTable_personnels=$('#listing_donnees_personnels').dataTable
  (
    {
      "bJQueryUI": true,
			"bProcessing": true,
      "sPaginationType": "full_numbers",
      "bAutoWidth": true,
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
			"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
			"sDom": 'C<"clear"><"H"lr>t<"F"ip>',
			"oColVis": 
			{
				"buttonText": "<?php echo $Langue['LBL_TABLEAU_MONTRER_CACHER']; ?>",
				"aiExclude": [ 0,1,14 ],
				"fnStateChange": function(iColumn, bVisible)
				{
					url = "index2.php";
				  if (bVisible==false) { bVisible2=0; } else { bVisible2=1; }
  				data = "module=users&action=save_perso2&longueur=14&module_session=personnels&colonne="+iColumn+"&visible="+bVisible2;
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
				if (faire==true)
				{
  				url = "index2.php";
					data = "module=users&action=save_perso&module_session=personnels&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
					var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
      "aoColumns" : [ <?php echo str_replace('|',',',$_SESSION['colonnes_personnels']); ?> ],
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
	  if (document.getElementById('affiche_recherche_personnels').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_personnels').style.visibility="visible";
		  document.getElementById('affiche_recherche_personnels').style.display="block";
			$("#recherche_nom").focus();
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_personnels').style.visibility="hidden";
		  document.getElementById('affiche_recherche_personnels').style.display="none";
		}
	});

  /* EditView : Création des boutons et des actions associées */
  $("#creer-element").button();
	$("#creer-element").click(function()
  {
    Charge_Dialog("index2.php?module=personnels&action=editview","<?php echo $Langue['LBL_CREER_PERSONNEL']; ?>");
	});

  $("#Imprimer").button();
  $("#Imprimer").click(function()
  {
    Charge_Dialog("index2.php?module=personnels&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
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

  $("#rechercher_personnels").button();
	$("#rechercher_personnels").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['personnels'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_personnels.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=personnels&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_personnels").button();
	$("#vider_personnels").click(function()
	{
<?php
		foreach ($tableau_recherche['personnels'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_personnels.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=personnels&recherche=|||||||||||||";
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$("#rechercher_personnels").click();
<?php if ($_SESSION['recherche_personnels']!='||||||||||||') { echo '$("#Rechercher_Liste").click();'; } ?>
});

  /* Fonction pour charger de DetailView d'un utilisateur */
  function Personnels_L_DetailView_Util(id)
  {
    Charge_Dialog("index2.php?module=personnels&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
  }

  /* Fonction pour charger l'EditView d'un utilisateur */
  function Personnels_L_EditView_Util(id)
  {
    Charge_Dialog("index2.php?module=personnels&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_FICHE_PERSONNEL']; ?>");
  }
</script>