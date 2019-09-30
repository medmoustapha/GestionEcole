<?php
  if (!isset($_SESSION['tableau_coop_journal']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement
    $_SESSION['tableau_coop_journal']="30|0|0|asc";
  }
  
  if (!isset($_SESSION['recherche_journal']))
  {
		$_SESSION['recherche_journal']='||||||||';
  }

  $tableau_personnalisation=explode("|",$_SESSION['tableau_coop_journal']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_journal']);
  
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_repartition'");
  $gestclasse_config_plus['cooperative_repartition']=mysql_result($req_coop,0,'valeur');

  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  $clos=mysql_result($req_bilan,0,'clos');

  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tableau_variable['cooperative'][$cle['nom']]['value'] = "";
  }
  $tableau_variable['cooperative']['ligne_comptable']['nomliste'] = 'cooperative_ligne_plus';
  
  $tpl = new template("cooperative");
  $tpl->set_file("gliste","listview_journal".$mandataire.$gestclasse_config_plus['cooperative_repartition'].".html");
  $tpl->set_block('gliste','liste_entete','liste_bloc');
  
  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

	$colonne=0;
	foreach ($tableau_recherche['journal'] AS $cle)
	{
	  if (array_key_exists('recherche',$cle))
		{
			$cle['valeur_recherche']=$tableau_recherche2[$colonne];
			$tpl->set_var("RECHERCHE_".strtoupper($cle['nom']), Recherche_Cle($cle));
			$colonne++;
		}
	}
  if ($gestclasse_config_plus['cooperative_repartition']=="E")
  {
		$msg='<select tabindex=108 name="recherche_classe" class="text ui-widget-content ui-corner-all" id="recherche_classe">';
		$msg=$msg.'<option value=""></option>';
		if ($gestclasse_config_plus['cooperative_repartition']=="E")
		{
			$req_classe=mysql_query("SELECT * FROM `classes` WHERE annee='".$_SESSION['cooperative_scolaire']."' ORDER BY nom_classe ASC");
			for ($i=1;$i<=mysql_num_rows($req_classe);$i++)
			{
				$msg=$msg.'<option value="'.mysql_result($req_classe,$i-1,'nom_classe').'"';
				if ($tableau_recherche2[4]==mysql_result($req_classe,$i-1,'nom_classe')) { $msg=$msg.' SELECTED'; }
				$msg=$msg.'>'.mysql_result($req_classe,$i-1,'nom_classe').'</option>';
			}
		}
		$msg=$msg.'<select>';
		$tpl->set_var("RECHERCHE_CLASSE",$msg);
	}

  $msg='<select tabindex=107 name="recherche_tiers" class="text ui-widget-content ui-corner-all" id="recherche_tiers">';
  $msg=$msg.'<option value=""></option>';
  $msg=$msg.'<option value="401"';
	if ($tableau_recherche2[3]=="401") { $msg=$msg.' SELECTED'; }
	$msg=$msg.'>'.$Langue['LBL_RECHERCHE_TOUS_FOURNISSEURS'].'</option>';
  $req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '401-%' ORDER BY nom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		$msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
		if ($tableau_recherche2[3]==mysql_result($req,$i-1,'id')) { $msg=$msg.' SELECTED'; }
		$msg=$msg.'>'.mysql_result($req,$i-1,'nom').'</option>';
  }
  $msg=$msg.'<option value="411"';
	if ($tableau_recherche2[3]=="411") { $msg=$msg.' SELECTED'; }
	$msg=$msg.'>'.$Langue['LBL_RECHERCHE_TOUS_CLIENTS'].'</option>';
  $req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '411-%' ORDER BY nom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		$msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
		if ($tableau_recherche2[3]==mysql_result($req,$i-1,'id')) { $msg=$msg.' SELECTED'; }
		$msg=$msg.'>'.mysql_result($req,$i-1,'nom').'</option>';
  }
  $msg=$msg.'<select>';
  $tpl->set_var("RECHERCHE_TIERS",$msg);
	
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
  
  $tpl->set_file("gliste2","listview_journal".$mandataire.$gestclasse_config_plus['cooperative_repartition'].".html");
  $tpl->set_block('gliste2','liste','liste_bloc2');
  if ($mandataire=="M" || $gestclasse_config_plus['cooperative_repartition']!="E")
  {
    $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE ligne_comptable<>'I' ORDER BY date ASC, ligne_comptable ASC");
  }
  else
  {
    $req52=mysql_query("SELECT classes.*,classes_profs.* FROM `classes`, `classes_profs` WHERE classes_profs.id_prof='".$_SESSION['id_util']."' AND classes.id=classes_profs.id_classe AND classes.annee='".$_SESSION['cooperative_scolaire']."' AND (classes_profs.type='T' OR classes_profs.type='E') ORDER BY classes_profs.type DESC");
    if (mysql_num_rows($req52)!="")
    {
			$id_classe=mysql_result($req52,0,'classes.id');
      $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE id_classe='".$id_classe."' ORDER BY date ASC, ligne_comptable ASC");
		}
  }

  $nbr_lignage = mysql_num_rows($req);
  $total_debit=0;
  $total_credit=0;
  $total=0;
  $total_pointe=0;
  $total_nonpointe=0;
  for ($i=1;$i<=$nbr_lignage;$i++)
  {
    foreach ($tableau_variable['cooperative'] AS $cle)
    {
      $tableau_variable['cooperative'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
    }
    foreach ($tableau_variable['cooperative'] AS $cle)
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
		if (mysql_result($req,$i-1,'id_classe')!="")
		{
			$req_c=mysql_query("SELECT * FROM `classes` WHERE id='".mysql_result($req,$i-1,'id_classe')."'");
			$tpl->set_var("ID_CLASSE", mysql_result($req_c,0,'nom_classe'));
		}
		else
		{
			$tpl->set_var("ID_CLASSE", "&nbsp;");
		}
		
		if (mysql_result($req,$i-1,'tiers')!="")
		{
			$req_liste=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,$i-1,'tiers')."'");
			$msg=mysql_result($req,$i-1,'tiers').' - '.mysql_result($req_liste,0,'nom').'<br />';
			$tpl->set_var("TIERS", mysql_result($req,$i-1,'tiers'));
		}
		else
		{
			$msg="";
		}
		$msg=$msg.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].'<br />';
		$msg=$msg.mysql_result($req,$i-1,'libelle');
		$tpl->set_var("LIBELLE", $msg);
		$tpl->set_var("LIBELLE2", mysql_result($req,$i-1,'libelle'));
		$tpl->set_var("LIGNE_COMPTABLE", mysql_result($req,$i-1,'ligne_comptable'));
	
		if (substr($tableau_variable['cooperative']['ligne_comptable']['value'],0,1)!="5")
		{
			if ($tableau_variable['cooperative']['montant']['value']<0)
			{
				$tpl->set_var("CREDIT", "&nbsp;");
				$tpl->set_var("DEBIT", number_format(Abs($tableau_variable['cooperative']['montant']['value']),2,',',' ').' &euro;');
				$total_debit=$total_debit+Abs($tableau_variable['cooperative']['montant']['value']);
				if ($tableau_variable['cooperative']['banque']['value']!="" || $tableau_variable['cooperative']['ligne_comptable']['value']=="I") { $total=$total-Abs($tableau_variable['cooperative']['montant']['value']); }
			}
			else
			{
				$tpl->set_var("DEBIT", "&nbsp;");
				$tpl->set_var("CREDIT", number_format(Abs($tableau_variable['cooperative']['montant']['value']),2,',',' ').' &euro;');
				$total_credit=$total_credit+Abs($tableau_variable['cooperative']['montant']['value']);
				if ($tableau_variable['cooperative']['banque']['value']!="" || $tableau_variable['cooperative']['ligne_comptable']['value']=="I") { $total=$total+Abs($tableau_variable['cooperative']['montant']['value']); }
			}
			switch (substr($tableau_variable['cooperative']['ligne_comptable']['value'],0,1))
			{
				case "1":
				$tpl->set_var("TYPE","3");
				$typage=3;
					break;
				default:
					$tpl->set_var("TYPE","1");
				$typage=1;
					break;
			}
		}
		else
		{
			$tpl->set_var("LIBELLE", $Langue['LBL_JOURNAL_TRANSFERT']."<br>".$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].' '.$Langue['LBL_JOURNAL_TRANSFERT_VERS'].' '.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')]."<br>".mysql_result($req,$i-1,'libelle'));
			$tpl->set_var("CREDIT", number_format(Abs($tableau_variable['cooperative']['montant']['value']),2,',',' ').' &euro;');
			$tpl->set_var("DEBIT", number_format(Abs($tableau_variable['cooperative']['montant']['value']),2,',',' ').' &euro;');
			$total_debit=$total_debit+Abs($tableau_variable['cooperative']['montant']['value']);
			$total_credit=$total_credit+Abs($tableau_variable['cooperative']['montant']['value']);
			$tpl->set_var("MODE", $Langue['LBL_JOURNAL_MODE']);
			$tpl->set_var("BANQUE", $liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].' > '.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')]);
	//	  if ($tableau_variable['cooperative']['banque']['value']!="") { $total=$total-Abs($tableau_variable['cooperative']['montant']['value']); }
			$tpl->set_var("TYPE","2");
			$typage=2;
		}

		if (mysql_result($req,$i-1,'pointe')=="1")
		{
			$tpl->set_var("EDITER",'<img src="images/vide.png" width=12 height=12 border=0></a>');
			$tpl->set_var("POINTE",'<input type="checkbox" name="r'.mysql_result($req,$i-1,'id').' value="1" disabled checked>');
			if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
			{
				$total_pointe=$total_pointe+$tableau_variable['cooperative']['montant']['value'];
			}
		}
		else
		{
			if ($clos=="0")
			{
				if (substr(mysql_result($req,$i-1,'ligne_comptable'),0,1)!="5")
				{
					$tpl->set_var("EDITER",'<a title="'.$Langue['SPAN_JOURNAL_MODIFIER_FICHE'].'" href="#null" onClick="Coop_Journal_EditView_Util(\''.mysql_result($req,$i-1,'id').'\',\''.$typage.'\');"><img src="images/editer.png" width=12 height=12 border=0></a>');
				}
				else
				{
					$tpl->set_var("EDITER",'<a title="'.$Langue['SPAN_JOURNAL_MODIFIER_FICHE_TRANSFERT'].'" href="#null" onClick="Coop_Journal_EditView_Util(\''.mysql_result($req,$i-1,'id').'\',\'2\');"><img src="images/editer.png" width=12 height=12 border=0></a>');
				}
			}
			else
			{
				$tpl->set_var("EDITER",'<img src="images/vide.png" width=12 height=12 border=0></a>');
			}
			$tpl->set_var("POINTE",'<input type="checkbox" name="r'.mysql_result($req,$i-1,'id').' value="1" disabled>');
		}
		$tpl->set_var("SOLDE", number_format($total,2,',',' ')." &euro;");
	
    $tpl->parse('liste_bloc2','liste',true);
  }
	
  $tpl->pparse("affichage","gliste2");
?>
<table cellspacing=0 cellpadding=0 border=0 style="padding-top:10px" width=100%>
<tr>
  <td width=10%>&nbsp;</td>
  <td width=35%><div class="ui-widget ui-state-default ui-corner-all marge5_tout marge10_gauche marge10_droite font16 textcentre"><?php echo $Langue['LBL_JOURNAL_SOLDE_POINTE']; ?> : <?php echo number_format($total_pointe,2,',',' ')." &euro;"; ?></div></td>
  <td width=10%>&nbsp;</td>
  <td width=35%><div class="ui-widget ui-state-default ui-corner-all marge5_tout marge10_gauche marge10_droite font16 textcentre"><?php echo $Langue['LBL_JOURNAL_SOLDE_COURANT']; ?> : <?php echo number_format($total,2,',',' ')." &euro;"; ?></div></td>
  <td width=10%>&nbsp;</td>
</tr>
</table>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
<?php if ($mandataire=="M") { ?>
  $("#aide").click(function(event)
  {
		event.preventDefault();		
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative.html","Aide");
<?php } ?>
  });
<?php } else { ?>
  $("#aide").click(function(event)
  {
		event.preventDefault();		
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative/article/165-vue-pour-les-non-mandataires.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative/article/165-vue-pour-les-non-mandataires.html","Aide");
<?php } ?>
  });
<?php } ?>
  $("#ajouter_ticket").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  <?php if ($gestclasse_config_plus['cooperative_repartition']=="E") { ?>
  $("#repartir_classes").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  <?php } ?>
  $("#bilan_coop").button();
  $("#imprimer_doc").button();
  $("#transfert_compte").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  $("#rapprochement_bancaire").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  $("#ajouter_ticket").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview","<?php echo $Langue['LBL_JOURNAL_SAISIR_DEPENSE']; ?>");
  });

  $("#transfert_compte").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview_transfert","<?php echo $Langue['LBL_JOURNAL_SAISIR_TRANSFERT']; ?>");
  });

  $("#repartir_classes").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview_repartition","<?php echo $Langue['LBL_JOURNAL_SAISIR_REPARTITION']; ?>");
  });

  $("#imprimer_doc").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=detailview_imprimer&id_a_imprimer=1","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#bilan_coop").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=bilan_comptable&id_a_imprimer=3","<?php echo $Langue['LBL_BILAN_COMPTABLE']; ?>");
  });

  $("#rapprochement_bancaire").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=rapprochement","<?php echo $Langue['LBL_SAISIE_RAPPROCHEMENT_BANCAIRE']; ?>");
  });

  /* Création du tableau de données */
	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
  oTable_journal=$('#listing_donnees_journal').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "bAutoWidth": false,
			"sDom": '<"H"lr>t<"F"ip>',
<?php if ($mandataire=="M") { ?>
	<?php if ($gestclasse_config_plus['cooperative_repartition']=="E") { ?>
			"aoColumns" : [ null,null,{"bVisible":false},{"bVisible":false},{"bVisible":false},null,null,null,null,null,null,null ],
	<?php } else { ?>
			"aoColumns" : [ null,null,{"bVisible":false},{"bVisible":false},{"bVisible":false},{"bVisible":false},null,null,null,null,null,null ],
	<?php } ?>
<?php } ?>
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
					data = "module=users&action=save_perso&module_session=coop_journal&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
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

<?php if ($mandataire=="M") { ?>
  $("#Rechercher_Liste").button();
	$("#Rechercher_Liste").click(function()
	{
	  if (document.getElementById('affiche_recherche_journal').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_journal').style.visibility="visible";
		  document.getElementById('affiche_recherche_journal').style.display="block";
			$("#recherche_date").focus();
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_journal').style.visibility="hidden";
		  document.getElementById('affiche_recherche_journal').style.display="none";
		}
	});

  $("#rechercher_journal").button();
	$("#rechercher_journal").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['journal'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_journal.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=journal&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_journal").button();
	$("#vider_journal").click(function()
	{
<?php
		foreach ($tableau_recherche['journal'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_journal.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=journal&recherche=|||||||||";
		var request = $.ajax({type: "POST", url: url, data: data});
	});

	$("#rechercher_journal").click();
<?php if ($_SESSION['recherche_journal']!='||||||||') { echo '$("#Rechercher_Liste").click();'; } ?>
<?php } ?>
});

  function Coop_Journal_DetailView_Util(id,type)
  {
    switch (type)
		{
			case "1":
				Charge_Dialog("index2.php?module=cooperative&action=detailview&id="+id,"<?php echo $Langue['LBL_SAISIE_FICHE']; ?>");
				break;
			case "2":
				Charge_Dialog("index2.php?module=cooperative&action=detailview_transfert&id="+id,"<?php echo $Langue['LBL_SAISIE_FICHE_TRANSFERT']; ?>");
				break;
			case "3":
				Charge_Dialog("index2.php?module=cooperative&action=detailview_ouverture&id="+id,"<?php echo $Langue['LBL_SAISIE_FICHE_OUVERTURE']; ?>");
				break;
		}
  }

  function Coop_Journal_EditView_Util(id,type)
  {
    switch (type)
		{
			case "1":
				Charge_Dialog("index2.php?module=cooperative&action=editview&id="+id,"<?php echo $Langue['LBL_JOURNAL_MODIFIER_DEPENSE']; ?>");
				break;
			case "2":
				Charge_Dialog("index2.php?module=cooperative&action=editview_transfert&id="+id,"<?php echo $Langue['LBL_JOURNAL_MODIFIER_TRANSFERT']; ?>");
				break;
			case "3":
				Charge_Dialog("index2.php?module=cooperative&action=editview_ouverture&id="+id,"<?php echo $Langue['LBL_SAISIE_FICHE_OUVERTURE']; ?>");
				break;
		}
  }

</script>
