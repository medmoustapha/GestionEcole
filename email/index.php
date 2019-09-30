<?php
  if (!isset($_SESSION['tableau_email']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement		4 : Recherche
    $_SESSION['tableau_email']="30|0|1|desc";
  }
  
  if (!isset($_SESSION['recherche_email']))
  {
		$_SESSION['recherche_email']='|||';
  }
  
  $tableau_personnalisation=explode("|",$_SESSION['tableau_email']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_email']);
  
  if (!isset($_SESSION['id_dossier_mail']))
  {
    $_SESSION['id_dossier_mail']="R";
  }	

  $tpl = new template("email");
  $tpl->set_file("gliste","listview.html");
  $tpl->set_block('gliste','liste_entete','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

	$colonne=0;
	foreach ($tableau_recherche['email'] AS $cle)
	{
	  if (array_key_exists('recherche',$cle))
		{
			$cle['valeur_recherche']=$tableau_recherche2[$colonne];
			$tpl->set_var("RECHERCHE_".strtoupper($cle['nom']), Recherche_Cle($cle));
			$colonne++;
		}
	}
	
  $msg ='<select class="text ui-widget-content ui-corner-all" name="dossier" id="dossier">';
  foreach ($liste_choix['dossiers'] AS $cle => $value)
  {
    $msg .='<option value="'.$cle.'"';
		if ($cle==$_SESSION['id_dossier_mail']) { $msg .=' SELECTED'; }
		$msg .='>'.$value.'</option>';
  }
  $msg .='</select>';
  $tpl->set_var("DOSSIER",$msg);
  $tpl->parse('liste_bloc','liste_entete',true);
  
  switch ($_SESSION['id_dossier_mail'])
  {
    case "R" : 
			$req=mysql_query("SELECT * FROM `email` WHERE (id_destinataire LIKE '".$_SESSION['type_util'].$_SESSION['id_util']."' OR id_destinataire LIKE '".$_SESSION['type_util'].$_SESSION['id_util'].";%' OR id_destinataire LIKE '%;".$_SESSION['type_util'].$_SESSION['id_util']."' OR id_destinataire LIKE '%;".$_SESSION['type_util'].$_SESSION['id_util'].";%') ORDER BY date DESC");
			break;
    case "E" : 
			$req=mysql_query("SELECT * FROM `email` WHERE id_expediteur='".$_SESSION['id_util']."' AND type_expediteur='".$_SESSION['type_util']."' AND id_dossier_exp='E' ORDER BY date DESC");
			break;
  }
  
  $tpl->set_file("gliste2","listview.html");
  $tpl->set_block('gliste2','liste','liste_bloc2');
  $nbr_lignage = mysql_num_rows($req);
	$numero_ligne=1;
  for ($i=1;$i<=$nbr_lignage;$i++)
  {
    $faire=true;
    if ($_SESSION['id_dossier_mail']=="R")
		{
			$destinataire=explode(';',mysql_result($req,$i-1,'id_destinataire'));
			$key=array_search($_SESSION['type_util'].$_SESSION['id_util'],$destinataire);
			$etat=explode(";",mysql_result($req,$i-1,'id_dossier_dest'));
			if ($etat[$key]=="S") { $faire=false; }
		}
		$decaler=explode(':',$gestclasse_config_plus['decalage_horaire']);
		if (substr($decaler[0],0,1)=="-") { $decaler[1]=-$decaler[1]; }
		if ($faire==true)
		{
		  $tpl->set_var("NUMERO",$numero_ligne);
			$numero_ligne++;
			$tpl->set_var("DATE",$Langue['LBL_LE'].' '.Date_Convertir(mysql_result($req,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req,$i-1,'date'),11,2)-$decaler[0],substr(mysql_result($req,$i-1,'date'),14,2)-$decaler[1],substr(mysql_result($req,$i-1,'date'),17,2),01,01,2010)));
			$type_e=mysql_result($req,$i-1,'type_expediteur');
			$id_e=mysql_result($req,$i-1,'id_expediteur');
			if ($type_e=="E") { $req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id_e'"); } else { $req2=mysql_query("SELECT * FROM `profs` WHERE id='$id_e'"); }
			$tpl->set_var("EXPEDITEUR",mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom'));
			$id_e=mysql_result($req,$i-1,'id_destinataire');
			if (strpos($id_e,';')===false)
			{
				$type_e=substr($id_e,0,1);
				$id_e=substr($id_e,1,strlen($id_e));
				if ($type_e=="E") { $req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id_e'"); } else { $req2=mysql_query("SELECT * FROM `profs` WHERE id='$id_e'"); }
				$tpl->set_var("DESTINATAIRE",mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom'));
				$tpl->set_var("DESTINATAIRE2",mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom'));
				if (mysql_result($req,$i-1,'id_destinataire')==$_SESSION['type_util'].$_SESSION['id_util'] && mysql_result($req,$i-1,'etat')=="N")
				{
					$tpl->set_var("GRAS","<b>"); $tpl->set_var("GRAS_FIN","</b>");
				}
				else
				{
					$tpl->set_var("GRAS",""); $tpl->set_var("GRAS_FIN","");
				}
			}
			else
			{
				$destinataire=explode(';',mysql_result($req,$i-1,'id_destinataire'));
				if (in_array($_SESSION['type_util'].$_SESSION['id_util'],$destinataire))
				{
					$total=count($destinataire)-1;
					$key=array_search($_SESSION['type_util'].$_SESSION['id_util'],$destinataire);
					$tpl->set_var("DESTINATAIRE",$_SESSION['nom_util'].' + '.$total.' '.$Langue['LBL_DESTINATAIRES_PERSONNES2']);
					$etat=explode(";",mysql_result($req,$i-1,'etat'));
					if ($etat[$key]=="N")
					{
						$tpl->set_var("GRAS","<b>"); $tpl->set_var("GRAS_FIN","</b>");
					}
					else
					{
						$tpl->set_var("GRAS",""); $tpl->set_var("GRAS_FIN","");
					}
				}
				else
				{
					$tpl->set_var("DESTINATAIRE",count($destinataire).' '.$Langue['LBL_DESTINATAIRES_PERSONNES']);
				}
				$msg="";
				for ($hj=0;$hj<count($destinataire);$hj++)
				{
					$type=substr($destinataire[$hj],0,1);
					$id_d=substr($destinataire[$hj],1,strlen($destinataire[$hj]));
					if ($type=="E") { $req52=mysql_query("SELECT * FROM `eleves` WHERE id='$id_d'"); } else { $req52=mysql_query("SELECT * FROM `profs` WHERE id='$id_d'"); }
					$msg=$msg.mysql_result($req52,0,'nom').' '.mysql_result($req52,0,'prenom').',';
				}
				$tpl->set_var("DESTINATAIRE2",$msg);
			}
			$tpl->set_var("TITRE",mysql_result($req,$i-1,'titre'));
			$tpl->set_var("ID",mysql_result($req,$i-1,'id'));
			$tpl->parse('liste_bloc2','liste',true);
		}
  }

  $tpl->pparse("affichage","gliste2");
?>
<script language="Javascript">
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "date-euro-pre": function ( a ) 
		{
		    a = a.replace("<b>","");
		    a = a.replace("</b>","");
		    a = a.replace("<?php echo $Langue['LBL_LE']; ?> ","");
		    a = a.replace("<?php echo $Langue['LBL_A']; ?> ","");
        if ($.trim(a) != '') 
				{
            var frDatea = $.trim(a).split(' ');
            var frTimea = frDatea[1].split(':');
<?php		switch ($Format_Date_PHP)
				{
					case "d/m/Y":
					case "d/Y/m":
					case "Y/m/d":
					case "Y/d/m":
					case "m/d/Y":
					case "m/Y/d":
?>
            var frDatea2 = frDatea[0].split('/');
<?php
						break;
					case "d-m-Y":
					case "d-Y-m":
					case "Y-m-d":
					case "Y-d-m":
					case "m-d-Y":
					case "m-Y-d":
?>
            var frDatea2 = frDatea[0].split('-');
<?php
						break;
					case "d.m.Y":
					case "d.Y.m":
					case "Y.m.d":
					case "Y.d.m":
					case "m.d.Y":
					case "m.Y.d":
?>
            var frDatea2 = frDatea[0].split('.');
<?php
						break;
				}
				switch ($Format_Date_PHP)
				{
					case "d/m/Y":
					case "d-m-Y":
					case "d.m.Y":
?>
            var x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "d/Y/m":
					case "d-Y-m":
					case "d.Y.m":
?>
            var x = (frDatea2[1] + frDatea2[2] + frDatea2[0] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "Y/m/d":
					case "Y-m-d":
					case "Y.m.d":
?>
            var x = (frDatea2[0] + frDatea2[1] + frDatea2[2] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "Y/d/m":
					case "Y-d-m":
					case "Y.d.m":
?>
            var x = (frDatea2[0] + frDatea2[2] + frDatea2[1] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "m/d/Y":
					case "m-d-Y":
					case "m.d.Y":
?>
            var x = (frDatea2[2] + frDatea2[0] + frDatea2[1] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					case "m/Y/d":
					case "m-Y-d":
					case "m.Y.d":
?>
            var x = (frDatea2[1] + frDatea2[0] + frDatea2[2] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
<?php
						break;
					}
?>
				} else {
            var x = 10000000000000; // = l'an 1000 ...
        }
        return x;
    },
 
    "date-euro-asc": function ( a, b ) 
		{
			return a - b;
    },
 
    "date-euro-desc": function ( a, b ) 
		{
			return b - a;
    }
} );

$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
<?php
		switch ($_SESSION['type_util'])
		{
			case "D":
?>
				window.open("http://www.doxconception.com/site/index.php/directeur-messagerie.html","Aide");
<?php
				break;
			case "P":
?>
				window.open("http://www.doxconception.com/site/index.php/prof-messagerie.html","Aide");
<?php
				break;
			case "E":
?>
				window.open("http://www.doxconception.com/site/index.php/parent-messagerie.html","Aide");
<?php
				break;
		}
?>
  });

  /* Création du tableau de données */
	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
	oTable_email=$('#listing_donnees_email').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "bAutoWidth": false,
      "aoColumns" : [ null,{ "sType": "date-euro" },null,null,{"bVisible":false},null ],
      "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
			"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
			"sDom": '<"H"lr>t<"F"ip>',
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
					data = "module=users&action=save_perso&module_session=email&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
					var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_EMAIL_AFFICHER_PAGE']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_EMAIL_NO_DATA']; ?>",
        "sInfo": "<?php echo $Langue['LBL_EMAIL_A_AFFICHER']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_EMAIL_NO_DATA']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_EMAIL_RECHERCHE']; ?>",
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
	  if (document.getElementById('affiche_recherche_email').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_email').style.visibility="visible";
		  document.getElementById('affiche_recherche_email').style.display="block";
			$("#recherche_date").focus();
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_email').style.visibility="hidden";
		  document.getElementById('affiche_recherche_email').style.display="none";
		}
	});

  $("#rechercher_email").button();
	$("#rechercher_email").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['email'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_email.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=email&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_email").button();
	$("#vider_email").click(function()
	{
<?php
		foreach ($tableau_recherche['email'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_email.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=email&recherche=||||";
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#creer-element").button();
  $("#creer-element").click(function()
  {
    Charge_Dialog("index2.php?module=email&action=editview","<?php echo $Langue['LBL_ENVOYER_MESSAGE']; ?>");
  });
  
  $("#dossier").change(function()
  {
    Message_Chargement(1,1);
    var url="email/change_dossier.php";
    var data="dossier_choisi="+$("#dossier").val();
    var request = $.ajax({type: "POST", url: url, data: data});
    request.done(function(msg)
    {
      $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
    });
  });

	$("#rechercher_email").click();
<?php if ($_SESSION['recherche_email']!='|||') { echo '$("#Rechercher_Liste").click();'; } ?>

  $( "#dialog:ui-dialog" ).dialog( "destroy" );

});

function Emails_L_DetailView_Util(id)
{
  Charge_Dialog("index2.php?module=email&action=detailview&id="+id,"<?php echo $Langue['LBL_LIRE_MESSAGE']; ?>");
}
</script>