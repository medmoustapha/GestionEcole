<?php
  if (!isset($_SESSION['tableau_coop_balance']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement		4 : Recherche
    $_SESSION['tableau_coop_balance']="30|0|0|asc";
  }
  
  if (!isset($_SESSION['recherche_balance']))
  {
		$_SESSION['recherche_balance']='||||';
  }

  $tableau_personnalisation=explode("|",$_SESSION['tableau_coop_balance']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_balance']);
  
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_repartition'");
  $gestclasse_config_plus['cooperative_repartition']=mysql_result($req_coop,0,'valeur');

  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  $clos=mysql_result($req_bilan,0,'clos');

  $tpl = new template("cooperative");
  $tpl->set_file("gliste","listview_balance".$mandataire.$gestclasse_config_plus['cooperative_repartition'].".html");
  $tpl->set_block('gliste','liste_entete','liste_bloc');
  
  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

	$colonne=0;
	foreach ($tableau_recherche['balance'] AS $cle)
	{
	  if (array_key_exists('recherche',$cle))
		{
			$cle['valeur_recherche']=$tableau_recherche2[$colonne];
			$tpl->set_var("RECHERCHE_".strtoupper($cle['nom']), Recherche_Cle($cle));
			$colonne++;
		}
	}
  $msg='<select tabindex=1 class="text ui-widget-content ui-corner-all" id="recherche_libelle" name="recherche_libelle">';
	foreach ($liste_choix['cooperative_ligne_plus'] AS $cle => $value)
	{
	  if ($cle=="5")
		{
			$msg=$msg.'<option value="401"';
			if ($tableau_recherche2[0]=="401") { $msg=$msg.' SELECTED'; }
			$msg=$msg.'>'.$Langue['LBL_RECHERCHE_TOUS_FOURNISSEURS'].'</option>';
			$req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '401-%' ORDER BY id ASC");
			for ($i=1;$i<=mysql_num_rows($req);$i++)
			{
				$msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
				if ($tableau_recherche2[0]==mysql_result($req,$i-1,'id')) { $msg=$msg.' SELECTED'; }
				$msg=$msg.'>'.mysql_result($req,$i-1,'id').' - '.mysql_result($req,$i-1,'nom').'</option>';
			}
			$msg=$msg.'<option value="411"';
			if ($tableau_recherche2[0]=="411") { $msg=$msg.' SELECTED'; }
			$msg=$msg.'>'.$Langue['LBL_RECHERCHE_TOUS_CLIENTS'].'</option>';
			$req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '411-%' ORDER BY id ASC");
			for ($i=1;$i<=mysql_num_rows($req);$i++)
			{
				$msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
				if ($tableau_recherche2[0]==mysql_result($req,$i-1,'id')) { $msg=$msg.' SELECTED'; }
				$msg=$msg.'>'.mysql_result($req,$i-1,'id').' - '.mysql_result($req,$i-1,'nom').'</option>';
			}
		}
	  $msg .='<option value="'.$cle.'"';
		if ($tableau_recherche2[0]==$cle) { $msg .=' SELECTED'; }
		$msg .='>'.$value.'</option>';
	}
	$msg .='</select>';
  $tpl->set_var("RECHERCHE_LIBELLE",$msg);

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
  $tpl->set_var("ANNEE_S",$msg);

  $msg='<select class="text ui-widget-content ui-corner-all" id="cooperative_visuel" name="cooperative_visuel">';
  $msg=$msg.'<option value="journal_general"';
  if ($_SESSION['cooperative_visuel']=='journal_general') { $msg=$msg."SELECTED"; } 
  $msg=$msg.'>'.$Langue['LBL_JOURNAL'].'</option>';
  if ($gestclasse_config_plus['cooperative_repartition']=="E")
  {
    $msg=$msg.'<option value="journal_general_classe"';
    if ($_SESSION['cooperative_visuel']=='journal_general_classe') { $msg=$msg."SELECTED"; } 
    $msg=$msg.'>'.$Langue['LBL_JOURNAL_CLASSE'].'</option>';
  }
  $msg=$msg.'<option value="grand_livre"';
  if ($_SESSION['cooperative_visuel']=='grand_livre') { $msg=$msg."SELECTED"; } 
  $msg=$msg.'>'.$Langue['LBL_GRAND_LIVRE'].'</option>';
  $msg=$msg.'<option value="balance"';
  if ($_SESSION['cooperative_visuel']=='balance') { $msg=$msg."SELECTED"; } 
  $msg=$msg.'>'.$Langue['LBL_BALANCE'].'</option>';
  $msg=$msg.'</select>';
  $tpl->set_var("DOCUMENTS",$msg);

  $tpl->parse('liste_bloc','liste_entete',true);
  
  $tpl->set_file("gliste2","listview_balance".$mandataire.$gestclasse_config_plus['cooperative_repartition'].".html");
  $tpl->set_block('gliste2','liste','liste_bloc2');
  
  $grand_total_debit=0;	
  $grand_total_credit=0;
  $total_debit=0;
  $total_credit=0;
  $tableau=Array();
  $tableau_credit=Array();
  $tableau_debit=Array();
  $req=mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable<>'I'");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if (!array_key_exists(mysql_result($req,$i-1,'ligne_comptable'),$tableau))
	{
	  $tableau[mysql_result($req,$i-1,'ligne_comptable')]=$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')];
	  $tableau_credit[mysql_result($req,$i-1,'ligne_comptable')]=0;
	  $tableau_debit[mysql_result($req,$i-1,'ligne_comptable')]=0;
	}
	if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
	{
		if (mysql_result($req,$i-1,'tiers')!="")
		{
			if (!array_key_exists(mysql_result($req,$i-1,'tiers'),$tableau))
			{
			  $req2=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,$i-1,'tiers')."'");
			  $tableau[mysql_result($req,$i-1,'tiers')]=mysql_result($req,$i-1,'tiers').' - '.mysql_result($req2,0,'nom');
			  $tableau_credit[mysql_result($req,$i-1,'tiers')]=0;
			  $tableau_debit[mysql_result($req,$i-1,'tiers')]=0;
			}
		}
		if (mysql_result($req,$i-1,'montant')<0)
		{
		  $tableau_debit[mysql_result($req,$i-1,'ligne_comptable')]=$tableau_debit[mysql_result($req,$i-1,'ligne_comptable')]+Abs(mysql_result($req,$i-1,'montant'));
		  if (mysql_result($req,$i-1,'tiers')!="") { $tableau_credit[mysql_result($req,$i-1,'tiers')]=$tableau_credit[mysql_result($req,$i-1,'tiers')]+Abs(mysql_result($req,$i-1,'montant')); }
		}
		else
		{
		  $tableau_credit[mysql_result($req,$i-1,'ligne_comptable')]=$tableau_credit[mysql_result($req,$i-1,'ligne_comptable')]+Abs(mysql_result($req,$i-1,'montant'));
		  if (mysql_result($req,$i-1,'tiers')!="") { $tableau_debit[mysql_result($req,$i-1,'tiers')]=$tableau_debit[mysql_result($req,$i-1,'tiers')]+Abs(mysql_result($req,$i-1,'montant')); }
		}
		if (mysql_result($req,$i-1,'banque')!="")
		{
			if (!array_key_exists(mysql_result($req,$i-1,'banque'),$tableau))
			{
			  $tableau[mysql_result($req,$i-1,'banque')]=$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')];
			  $tableau_credit[mysql_result($req,$i-1,'banque')]=0;
			  $tableau_debit[mysql_result($req,$i-1,'banque')]=0;
			}
			if (mysql_result($req,$i-1,'montant')<0)
			{
			  $tableau_credit[mysql_result($req,$i-1,'banque')]=$tableau_credit[mysql_result($req,$i-1,'banque')]+Abs(mysql_result($req,$i-1,'montant'));
			  if (mysql_result($req,$i-1,'tiers')!="") { $tableau_debit[mysql_result($req,$i-1,'tiers')]=$tableau_debit[mysql_result($req,$i-1,'tiers')]+Abs(mysql_result($req,$i-1,'montant')); }
			}
			else
			{
			  $tableau_debit[mysql_result($req,$i-1,'banque')]=$tableau_debit[mysql_result($req,$i-1,'banque')]+Abs(mysql_result($req,$i-1,'montant'));
			  if (mysql_result($req,$i-1,'tiers')!="") { $tableau_credit[mysql_result($req,$i-1,'tiers')]=$tableau_credit[mysql_result($req,$i-1,'tiers')]+Abs(mysql_result($req,$i-1,'montant')); }
			}
		}
	}
	else
	{
	    $tableau_credit[mysql_result($req,$i-1,'ligne_comptable')]=$tableau_credit[mysql_result($req,$i-1,'ligne_comptable')]+Abs(mysql_result($req,$i-1,'montant'));
		if (!array_key_exists(mysql_result($req,$i-1,'banque'),$tableau))
		{
		  $tableau[mysql_result($req,$i-1,'banque')]=$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')];
		  $tableau_credit[mysql_result($req,$i-1,'banque')]=0;
		  $tableau_debit[mysql_result($req,$i-1,'banque')]=0;
		}
	    $tableau_debit[mysql_result($req,$i-1,'banque')]=$tableau_debit[mysql_result($req,$i-1,'banque')]+Abs(mysql_result($req,$i-1,'montant'));
	}
  }
  
  array_multisort($tableau,SORT_ASC, SORT_STRING,$tableau_debit,$tableau_credit);

  foreach ($tableau AS $cle => $value)
  {
	$tpl->set_var("LIBELLE", $value);
	if ($tableau_debit[$cle]!=0)
	{
	  $tpl->set_var("DEBIT", number_format(Abs($tableau_debit[$cle]),2,',',' ').' &euro;');
	  $total_debit=$total_debit+Abs($tableau_debit[$cle]);
	}
	else
	{
	  $tpl->set_var("DEBIT", '0,00 &euro;');
	}
	if ($tableau_credit[$cle]!=0)
	{
	  $tpl->set_var("CREDIT", number_format(Abs($tableau_credit[$cle]),2,',',' ').' &euro;');
	  $total_credit=$total_credit+Abs($tableau_credit[$cle]);
	}
	else
	{
	  $tpl->set_var("CREDIT", '0,00 &euro;');
	}
	
	if ($tableau_debit[$cle]-$tableau_credit[$cle]<0)
	{
	  $tpl->set_var("SOLDE_DEBIT",'0,00 &euro;');
	  $tpl->set_var("SOLDE_CREDIT",number_format(Abs($tableau_debit[$cle]-$tableau_credit[$cle]),2,',',' ').' &euro;');
	  $grand_total_credit=$grand_total_credit+Abs($tableau_debit[$cle]-$tableau_credit[$cle]);
	}
	else
	{
	  $tpl->set_var("SOLDE_CREDIT",'0,00 &euro;');
	  $tpl->set_var("SOLDE_DEBIT",number_format(Abs($tableau_debit[$cle]-$tableau_credit[$cle]),2,',',' ').' &euro;');
	  $grand_total_debit=$grand_total_debit+Abs($tableau_debit[$cle]-$tableau_credit[$cle]);
	}
	$tpl->parse('liste_bloc2','liste',true);
  }

  $tpl->set_var("LIBELLE", '<b>'.$Langue['LBL_BALANCE'].'</b>');
  $tpl->set_var("CREDIT", '<b>'.number_format(Abs($total_credit),2,',',' ').' &euro;</b>');
  $tpl->set_var("DEBIT", '<b>'.number_format(Abs($total_debit),2,',',' ').' &euro;</b>');
  $tpl->set_var("SOLDE_DEBIT", '<b>'.number_format(Abs($grand_total_debit),2,',',' ').' &euro;</b>');
  $tpl->set_var("SOLDE_CREDIT", '<b>'.number_format(Abs($grand_total_credit),2,',',' ').' &euro;</b>');
  $tpl->parse('liste_bloc2','liste',true);

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
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/162-la-balance-des-comptes.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/162-la-balance-des-comptes.html","Aide");
<?php } ?>
  });

  $("#bilan_coop").button();
  $("#imprimer_doc").button();

  $("#imprimer_doc").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#bilan_coop").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=bilan_comptable&id_a_imprimer=3","<?php echo $Langue['LBL_BILAN_COMPTABLE']; ?>");
  });

  /* Création du tableau de données */
	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
  oTable_balance=$('#listing_donnees_balance').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "bAutoWidth": false,
			"sDom": '<"H"lr>t<"F"ip>',
      "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
			"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
			"fnDrawCallback": function( oSettings ) 
			{
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
					data = "module=users&action=save_perso&module_session=coop_balance&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
					var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
      "bSort" : false,
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
	  if (document.getElementById('affiche_recherche_balance').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_balance').style.visibility="visible";
		  document.getElementById('affiche_recherche_balance').style.display="block";
			$("#recherche_libelle").focus();
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_balance').style.visibility="hidden";
		  document.getElementById('affiche_recherche_balance').style.display="none";
		}
	});

  $("#rechercher_balance").button();
	$("#rechercher_balance").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['balance'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_balance.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=balance&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_balance").button();
	$("#vider_balance").click(function()
	{
<?php
		foreach ($tableau_recherche['balance'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_balance.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=balance&recherche=|||||";
		var request = $.ajax({type: "POST", url: url, data: data});
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

  $('#cooperative_visuel').change(function()
  {
     Message_Chargement(1,1);
     var url="cooperative/change_visuel.php";
     var data="visuel_choisi="+$("#cooperative_visuel").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function()
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
     });
  });

	$("#rechercher_balance").click();
<?php if ($_SESSION['recherche_balance']!='||||') { echo '$("#Rechercher_Liste").click();'; } ?>
});
</script>
