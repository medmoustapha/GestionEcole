<?php
// Connexion à la base de données
function Connexion_DB()
{
  global $gestclasse_config;

  mysql_connect($gestclasse_config['param_connexion']['serveur'],$gestclasse_config['param_connexion']['user'],$gestclasse_config['param_connexion']['passe']);
  @mysql_select_db($gestclasse_config['param_connexion']['base']);
	mysql_query('SET NAMES utf8');
}

// Construction d'un identifiant id
function Construit_Id($nom_base,$longueur)
{

  $Chaine="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";

  do
  {
    $id_cree="";
    $trouve=0;
    for ($i=1;$i<=$longueur;$i++)
    {
      $position = mt_rand(0,61);
      $id_cree = $id_cree . substr($Chaine,$position,1);
    }
    $req = mysql_query("SELECT * FROM `". $nom_base . "` WHERE id = '$id_cree'");
    if (mysql_num_rows($req)!="") { $trouve = 1; }
  } while ($trouve==1);

  return $id_cree;
}

// Récupération des informations sur l'établissement
function Param_Utilisateur($id_prof='',$annee='')
{
  global $gestclasse_config_plus, $gestclasse_config;

  $req=mysql_query("SELECT * FROM `config`");
  for ($i=0;$i<mysql_num_rows($req);$i++)
  {
    $gestclasse_config_plus[mysql_result($req,$i,'parametre')] = mysql_result($req,$i,'valeur');
  }
  
  $req=mysql_query("SELECT * FROM `etablissement`");
  for ($i=0;$i<mysql_num_rows($req);$i++)
  {
    $gestclasse_config_plus[mysql_result($req,$i,'parametre')] = mysql_result($req,$i,'valeur');
  }

  if ($annee!="")
  {
    if (table_ok($gestclasse_config['param_connexion']['base'],"etablissement".$annee)==true)
    {
      $req=mysql_query("SELECT * FROM `etablissement".$annee."`");
      for ($i=0;$i<mysql_num_rows($req);$i++)
      {
        $gestclasse_config_plus[mysql_result($req,$i,'parametre')] = mysql_result($req,$i,'valeur');
      }
	}
  }

  if ($id_prof!="")
  {
    if (table_ok($gestclasse_config['param_connexion']['base'],"param_profs_plus".$annee)==true)
    {
      $req=mysql_query("SELECT * FROM `param_profs_plus".$annee."` WHERE id_prof='$id_prof'");
      if (mysql_num_rows($req)!="")
      {
        for ($i=0;$i<mysql_num_rows($req);$i++)
        {
          $gestclasse_config_plus[mysql_result($req,$i,'parametre')] = mysql_result($req,$i,'valeur');
        }
      }
    }
  }
  
  if ($gestclasse_config_plus['zone']!="P")
  {
	  $gestclasse_config_plus['etendue_annee_scolaire']="2";
	  $gestclasse_config_plus['debut_annee_scolaire']="-08-01";
	  $gestclasse_config_plus['fin_annee_scolaire']="-07-31";
	  $gestclasse_config_plus['mois_annee_scolaire']=7;
	  if ($gestclasse_config_plus['zone']=="I" || $gestclasse_config_plus['zone']=="M")
	  {
			$gestclasse_config_plus['etendue_annee_scolaire']="1";
	    $gestclasse_config_plus['debut_annee_scolaire']="-01-01";
	    $gestclasse_config_plus['fin_annee_scolaire']="-12-31";
	  }
  }
  else
  {
	if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	{
	    $gestclasse_config_plus['debut_annee_scolaire']="-01-01";
	    $gestclasse_config_plus['fin_annee_scolaire']="-12-31";
	}
	else
	{
	  $gestclasse_config_plus['debut_annee_scolaire']=substr($gestclasse_config_plus['debut_annee_scolaire'],4,strlen($gestclasse_config_plus['debut_annee_scolaire']));
	  $gestclasse_config_plus['fin_annee_scolaire']=substr($gestclasse_config_plus['fin_annee_scolaire'],4,strlen($gestclasse_config_plus['fin_annee_scolaire']));
	  $gestclasse_config_plus['mois_annee_scolaire']=substr($gestclasse_config_plus['fin_annee_scolaire'],1,2);
	  if (substr($gestclasse_config_plus['mois_annee_scolaire'],0,1)=="0")
	  {
	    $gestclasse_config_plus['mois_annee_scolaire']=substr($gestclasse_config_plus['mois_annee_scolaire'],1,1);
	  }
	}
  }
}

// Compare deux heures au format HH:ii:ss
function Compare_Heure($heure1,$heure2)
{
  return date("U",mktime(substr($heure1,0,2),substr($heure1,3,2),substr($heure1,6,2),1,1,2010))-date("U",mktime(substr($heure2,0,2),substr($heure2,3,2),substr($heure2,6,2),1,1,2010));
}

// Conversion de la date dans le format demandé
function Date_Convertir($date,$format_avant,$format_apres)
{
 if ($date!="" && $date!="0000-00-00" && $date!="00/00/0000")
 {
  $date=substr($date,0,10);
  switch ($format_avant)
  {
    case "d/m/Y":  list($jour,$mois,$annee) = explode("/",$date); break;
    case "d/Y/m":  list($jour,$annee,$mois) = explode("/",$date); break;
    case "Y/m/d":  list($annee,$mois,$jour) = explode("/",$date); break;
    case "Y/d/m":  list($annee,$jour,$mois) = explode("/",$date); break;
    case "m/d/Y":  list($mois,$jour,$annee) = explode("/",$date); break;
    case "m/Y/d":  list($mois,$annee,$jour) = explode("/",$date); break;
    case "d-m-Y":  list($jour,$mois,$annee) = explode("-",$date); break;
    case "d-Y-m":  list($jour,$annee,$mois) = explode("-",$date); break;
    case "Y-m-d":  list($annee,$mois,$jour) = explode("-",$date); break;
    case "Y-d-m":  list($annee,$jour,$mois) = explode("-",$date); break;
    case "m-d-Y":  list($mois,$jour,$annee) = explode("-",$date); break;
    case "m-Y-d":  list($mois,$annee,$jour) = explode("-",$date); break;
    case "d.m.Y":  list($jour,$mois,$annee) = explode(".",$date); break;
    case "d.Y.m":  list($jour,$annee,$mois) = explode(".",$date); break;
    case "Y.m.d":  list($annee,$mois,$jour) = explode(".",$date); break;
    case "Y.d.m":  list($annee,$jour,$mois) = explode(".",$date); break;
    case "m.d.Y":  list($mois,$jour,$annee) = explode(".",$date); break;
    case "m.Y.d":  list($mois,$annee,$jour) = explode(".",$date); break;
  }
  switch ($format_apres)
  {
    case "d/m/Y": $date = $jour."/".$mois."/".$annee; break;
    case "d/Y/m": $date = $jour."/".$annee."/".$mois; break;
    case "Y/m/d": $date = $annee."/".$mois."/".$jour; break;
    case "Y/d/m": $date = $annee."/".$jour."/".$mois; break;
    case "m/d/Y": $date = $mois."/".$jour."/".$annee; break;
    case "m/Y/d": $date = $mois."/".$annee."/".$jour; break;
    case "d-m-Y": $date = $jour."-".$mois."-".$annee; break;
    case "d-Y-m": $date = $jour."-".$annee."-".$mois; break;
    case "Y-m-d": $date = $annee."-".$mois."-".$jour; break;
    case "Y-d-m": $date = $annee."-".$jour."-".$mois; break;
    case "m-d-Y": $date = $mois."-".$jour."-".$annee; break;
    case "m-Y-d": $date = $mois."-".$annee."-".$jour; break;
    case "d.m.Y": $date = $jour.".".$mois.".".$annee; break;
    case "d.Y.m": $date = $jour.".".$annee.".".$mois; break;
    case "Y.m.d": $date = $annee.".".$mois.".".$jour; break;
    case "Y.d.m": $date = $annee.".".$jour.".".$mois; break;
    case "m.d.Y": $date = $mois.".".$jour.".".$annee; break;
    case "m.Y.d": $date = $mois.".".$annee.".".$jour; break;
    case "Ymd": $date = $annee.$mois.$jour; break;
  }
 }
 else
 {
   $date="";
 }
  return $date;
}

// Vérification qu'une table existe
function table_ok($db, $table){
    $query = "SHOW TABLES FROM `$db`";
    $runQuery = mysql_query($query) or die(mysql_error());

    $tables = array();
    while($row = mysql_fetch_row($runQuery))
    {
        $tables[] = $row[0];
    }

    if(in_array($table, $tables))
    {
        return true;
    }
    else
    {
        return false;
    }
}

// Construction d'une variable pour un formulaire (Editview)
function Variables_Form($nom_variable)
{
  global $liste_choix, $gestclasse_config_plus, $Format_Date_PHP, $Format_Date_Calendar, $langue_tinymce, $Langue_Valeur, $Sens_Ecriture;

  $msg='';
	$tabindex="";
	if (array_key_exists('tabindex',$nom_variable)) { $tabindex="tabindex=".$nom_variable['tabindex']; }
  switch ($nom_variable['type'])
  {
    case "single":
	    $msg=$nom_variable['value'];
	    break;
    case "dateheure":
	    $msg=$nom_variable['value'];
	    break;
    case "password":
		  
	    $msg='<input type=password class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="" size=20 maxlength=15>';
	    break;
    case "numeraire":
      $msg='<input type=text style="text-align:right" class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="'.number_format($nom_variable['value'],2,',',' ').'" size=15 maxlength=15> &euro;';
      break;
    case "email":
    case "varchar":
      if ($nom_variable['longueur']>33)
      {
        $msg='<input type=text class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="'.str_replace('"','&quot;',$nom_variable['value']).'" size=33 maxlength='.$nom_variable['longueur'].'>';
      }
      else
      {
        $msg='<input type=text class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="'.str_replace('"','&quot;',$nom_variable['value']).'" size='.$nom_variable['longueur'].' maxlength='.$nom_variable['longueur'].'>';
      }
      break;
	case "checkbox":
	  if ($nom_variable['value']=="1")
	  {
	    $msg='<input type="checkbox" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="1" checked>';	
	  }
	  else
	  {
	    $msg='<input type="checkbox" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="1">';	
	  }
      break;	
    case "varchar_long":
      if ($nom_variable['longueur']>100)
      {
        $msg='<input type=text class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="'.str_replace('"','&quot;',$nom_variable['value']).'" size=100 maxlength='.$nom_variable['longueur'].'>';
      }
      else
      {
        $msg='<input type=text class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="'.str_replace('"','&quot;',$nom_variable['value']).'" size='.$nom_variable['longueur'].' maxlength='.$nom_variable['longueur'].'>';
      }
      break;
    case "date":
      $msg='<input type=text class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' name='.$nom_variable['nom'].' '.$tabindex.' value="'.Date_Convertir($nom_variable['value'],"Y-m-d",$Format_Date_PHP).'" size=10 maxlength=10>';
      if (array_key_exists('min',$nom_variable))
      {
        $plus=', minDate:new Date('.substr($nom_variable['min'],0,4).','.substr($nom_variable['min'],4,2).'-1,'.substr($nom_variable['min'],6,2).')';
        $plus=$plus.', maxDate:new Date('.substr($nom_variable['max'],0,4).','.substr($nom_variable['max'],4,2).'-1,'.substr($nom_variable['max'],6,2).')';
      }
      else
      {
        $plus=", changeMonth: true, changeYear: true";
      }
      $msg=$msg.'<script> $(function() { $( "#'.$nom_variable['nom'].'" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true '.$plus.' }); });</script>';
      break;
	case "heure":
	    $msg='<select class="text ui-widget-content ui-corner-all" id='.$nom_variable['nom'].' '.$tabindex.' name='.$nom_variable['nom'].'>';
		for($i=0;$i<=23;$i++)
		{
		  for ($j=0;$j<=59;$j=$j+5)
		  {
		    if ($i<=9) { $m="0".$i; } else { $m=$i; }
			$m=$m.":";
			if ($j<=9) { $m=$m."0".$j; } else { $m=$m.$j; }
			$m=$m.':00';
		    $msg=$msg.'<option value="'.$m.'"';
	        if ($m==$nom_variable['value']) { $msg=$msg." SELECTED"; }
            $msg=$msg.">".substr($m,0,5)."</option>";
		  }
	    }
	    $msg=$msg."</select>";
	    break;
    case "texte":
  	  $msg='<textarea class="text ui-widget-content ui-corner-all" id="'.$nom_variable['nom'].'" name="'.$nom_variable['nom'].'" '.$tabindex.' cols='.$nom_variable['largeur'].' rows='.$nom_variable['hauteur'].'>'.$nom_variable['value']."</textarea>";
	    break;
    case "editeur":
  	  $msg='<textarea id="'.$nom_variable['nom'].'" name="'.$nom_variable['nom'].'" cols=80 rows=10 class="mceEditor">'.$nom_variable['value'].'</textarea><input type="hidden" name="'.$nom_variable['nom'].'_e" id="'.$nom_variable['nom'].'_e" value="">';
	  if ($nom_variable['placer']=="1")
	  {
		  $msg .='<script language="Javacript">
			  tinyMCE.init({
				// General options
				mode : "specific_textareas",
				editor_selector : "mceEditor",
				theme : "advanced",
				skin : "default",
				directionality : "'.$Sens_Ecriture.'",
				skin_variant :"'.$_SESSION['theme_choisi'].'",';
				if (in_array($Langue_Valeur,$langue_tinymce)) 
				{
					$msg .='language : "'.$Langue_Valeur.'",';
				} 
				else 
				{
					$msg .='language : "en",';
				}
				$msg .='plugins : "style,layer,advlink,iespell,inlinepopups,preview,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,template,tabfocus",

				// Theme options
				theme_advanced_buttons1 : "fontselect,fontsizeselect,forecolor,backcolor,|,bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,undo,redo,|,link,unlink,|,code",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : false,
				tabfocus_elements : ":prev,:next",
				height:300
		  });';
			$msg .='</script>';
	  }
	  break;
	case "liste":
	    $msg='<select class="text ui-widget-content ui-corner-all" '.$tabindex.' id='.$nom_variable['nom'].' name='.$nom_variable['nom'].'>';
	    foreach ($liste_choix[$nom_variable['nomliste']] AS $cle => $valeur)
	    {
	      $msg=$msg.'<option value="'.$cle.'"';
	      if ($cle==$nom_variable['value']) { $msg=$msg." SELECTED"; }
        $msg=$msg.">".$valeur."</option>";
	    }
	    $msg=$msg."</select>";
	    break;
	case "liste_bdd":
      $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id_prof='' ORDER BY ordre ASC");
	  $msg='<select class="text ui-widget-content ui-corner-all" '.$tabindex.' id='.$nom_variable['nom'].' name='.$nom_variable['nom'].'>';
  	  for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
	    $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
	    if (mysql_result($req,$i-1,'id')==$nom_variable['value']) { $msg=$msg." SELECTED"; }
        $msg=$msg.">".mysql_result($req,$i-1,'intitule')."</option>";
  	  }
      $msg=$msg."</select>";
	  break;
	case "liste_prof":
	  if (array_key_exists('nom_prof',$nom_variable))
	  {
        $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id_prof='".$nom_variable['nom_prof']."' ORDER BY ordre ASC");
	  }
	  else
	  {
        $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id_prof='".$_SESSION['titulaire_classe_cours']."' ORDER BY ordre ASC");
	  }
	    $msg='<select class="text ui-widget-content ui-corner-all" '.$tabindex.' id='.$nom_variable['nom'].' name='.$nom_variable['nom'].'>';
      if (mysql_num_rows($req)=="")
      {
  	    foreach ($liste_choix[$nom_variable['nomliste']] AS $cle => $valeur)
	      {
          $decoupe=explode("|",$valeur);
	        $msg=$msg.'<option value="'.$cle.'"';
	        if ($cle==$nom_variable['value']) { $msg=$msg." SELECTED"; }
          $msg=$msg.">".$decoupe[$nom_variable['partie']-1]."</option>";
  	    }
	    }
	    else
      {
  	    for ($i=1;$i<=mysql_num_rows($req);$i++)
	      {
          $decoupe=explode("|",mysql_result($req,$i-1,'intitule'));
	        $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
	        if (mysql_result($req,$i-1,'id')==$nom_variable['value']) { $msg=$msg." SELECTED"; }
          $msg=$msg.">".$decoupe[$nom_variable['partie']-1]."</option>";
  	    }
	    }
      $msg=$msg."</select>";
	    break;
	  case "liste_classe":
      $id_classe=$nom_variable['idclasse'];
      $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='$id_classe' AND type='T'");
      $id_prof=mysql_result($req2,0,'id_prof');

      $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id_prof='$id_prof' ORDER BY ordre ASC");
      $msg='<select class="text ui-widget-content ui-corner-all" '.$tabindex.' id="'.$nom_variable['nom'].'" name="'.$nom_variable['nom'].'">';
      if (mysql_num_rows($req)=="")
      {
        foreach ($liste_choix[$nom_variable['nomliste']] AS $cle => $valeur)
        {
          $msg=$msg.'<option value="'.$cle.'"';
          if ($cle==$nom_variable['value']) { $msg=$msg.' SELECTED'; }
          $msg=$msg.'>'.$valeur.'</option>';
        }
      }
      else
      {
        for ($i=1;$i<=mysql_num_rows($req);$i++)
        {
          $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
          if (mysql_result($req,$i-1,'id')==$nom_variable['value']) { $msg=$msg.' SELECTED'; }
          $msg=$msg.'>'.mysql_result($req,$i-1,'intitule').'</option>';
        }
      }
      if (array_key_exists('plus',$nom_variable))
      {
        $decoupe=explode("|",$nom_variable['plus']);
        $msg=$msg.'<option value="'.$decoupe[0].'"';
        if ($decoupe[0]==$nom_variable['value']) { $msg=$msg.' SELECTED'; }
        $msg=$msg.'>'.$decoupe[1].'</option>';
      }
      $msg=$msg."</select>";
      break;
  }
  return $msg;
}

function Recherche_Cle($nom_variable)
{
  global $liste_choix, $gestclasse_config_plus, $Format_Date_PHP, $Format_Date_Calendar, $langue_tinymce, $Langue_Valeur, $Sens_Ecriture, $Langue;

  $msg='';
	if (array_key_exists('recherche',$nom_variable))
	{
		$tabindex="";
		if (array_key_exists('tabindex',$nom_variable)) { $tab=$nom_variable['tabindex']+100;$tabindex="tabindex=".$tab; }
		switch ($nom_variable['type'])
		{
			case "email":
			case "varchar":
				if ($nom_variable['longueur']>33)
				{
					$msg='<input '.$tabindex.' type=text class="text ui-widget-content ui-corner-all" id="recherche_'.$nom_variable['nom'].'" name="recherche_'.$nom_variable['nom'].'" value="'.$nom_variable['valeur_recherche'].'" size=33 maxlength='.$nom_variable['longueur'].'>';
				}
				else
				{
					$msg='<input '.$tabindex.' type=text class="text ui-widget-content ui-corner-all" id="recherche_'.$nom_variable['nom'].'" name="recherche_'.$nom_variable['nom'].'" value="'.$nom_variable['valeur_recherche'].'" size='.$nom_variable['longueur'].' maxlength='.$nom_variable['longueur'].'>';
				}
				break;
			case "monetaire":
				if ($nom_variable['longueur']>33)
				{
					$msg='<input '.$tabindex.' type=text class="text ui-widget-content ui-corner-all" id="recherche_'.$nom_variable['nom'].'" name="recherche_'.$nom_variable['nom'].'" value="'.$nom_variable['valeur_recherche'].'" size=33 maxlength='.$nom_variable['longueur'].'>';
				}
				else
				{
					$msg='<input '.$tabindex.' type=text class="text ui-widget-content ui-corner-all" id="recherche_'.$nom_variable['nom'].'" name="recherche_'.$nom_variable['nom'].'" value="'.$nom_variable['valeur_recherche'].'" size='.$nom_variable['longueur'].' maxlength='.$nom_variable['longueur'].'>';
				}
				$msg .='<script language="Javascript">
								$(document).ready(function()
								{
									$("#recherche_'.$nom_variable['nom'].'").blur(function()
									{
									  if ($("#recherche_'.$nom_variable['nom'].'").val()!="")
										{
									    $("#recherche_'.$nom_variable['nom'].'").val(number_format($("#recherche_'.$nom_variable['nom'].'").val(),2,","," "));
										}
									});
								});
								</script>';
				break;
			case "liste":
				$msg='<select '.$tabindex.' class="text ui-widget-content ui-corner-all" id="recherche_'.$nom_variable['nom'].'" name="recherche_'.$nom_variable['nom'].'">';
				if ($nom_variable['option_vide']=="1") { $msg=$msg.'<option value=""></option>'; }
				foreach ($liste_choix[$nom_variable['nomliste']] AS $cle => $valeur)
				{
				  if ($nom_variable['option']=="valeur")
					{
					  $msg=$msg.'<option value="'.$valeur.'"';
						if ($valeur==$nom_variable['valeur_recherche']) { $msg=$msg.' SELECTED'; }
						$msg=$msg.'>'.$valeur.'</option>';
					}
					else
					{
					  $msg=$msg.'<option value="'.$cle.'"';
						if ($cle==$nom_variable['valeur_recherche']) { $msg=$msg.' SELECTED'; }
						$msg=$msg.'>'.$valeur.'</option>';
					}
				}
				$msg=$msg."</select>";
				break;
			case "liste_bdd":
					$req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id_prof='' ORDER BY ordre ASC");
					$msg='<select '.$tabindex.' class="text ui-widget-content ui-corner-all" id="recherche_'.$nom_variable['nom'].'" name="recherche_'.$nom_variable['nom'].'">';
					if ($nom_variable['option_vide']=="1") { $msg=$msg.'<option value=""></option>'; }
					for ($i=1;$i<=mysql_num_rows($req);$i++)
					{
						if ($nom_variable['option']=="valeur")
						{
							$msg=$msg.'<option value="'.mysql_result($req,$i-1,'intitule').'"';
							if (mysql_result($req,$i-1,'intitule')==$nom_variable['valeur_recherche']) { $msg=$msg.' SELECTED'; }
						}
						else
						{
							$msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
							if (mysql_result($req,$i-1,'id')==$nom_variable['valeur_recherche']) { $msg=$msg.' SELECTED'; }
						}
						$msg=$msg.">".mysql_result($req,$i-1,'intitule')."</option>";
					}
					$msg=$msg."</select>";
				break;
			case "date":
				$msg='<input '.$tabindex.' type=text class="text ui-widget-content ui-corner-all" id="recherche_'.$nom_variable['nom'].'" name="recherche_'.$nom_variable['nom'].'" value="'.$nom_variable['valeur_recherche'].'" size=10 maxlength=10> ';
				if (array_key_exists('min',$nom_variable))
				{
					$plus=', minDate:new Date('.substr($nom_variable['min'],0,4).','.substr($nom_variable['min'],4,2).'-1,'.substr($nom_variable['min'],6,2).')';
					$plus=$plus.', maxDate:new Date('.substr($nom_variable['max'],0,4).','.substr($nom_variable['max'],4,2).'-1,'.substr($nom_variable['max'],6,2).')';
				}
				else
				{
					$plus=", changeMonth: true, changeYear: true";
				}
				$msg=$msg.'<script> $(function() 
				{ 
					$( "#recherche_'.$nom_variable['nom'].'" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true '.$plus.' }); 
				});
				</script>';
				break;
		}
	}
  return $msg;
}

// Construction d'une variable à afficher (Detailview et Listview)
function Variables_Affiche($nom_variable)
{
  global $liste_choix, $Format_Date_PHP;

  $msg='';
  switch ($nom_variable['type'])
  {
    case "hidden":
	    $msg='<input type=hidden name='.$nom_variable['nom'].' value="'.$nom_variable['value'].'">';
	    break;
    case "dateheure":
	    $msg=Date_Convertir($nom_variable['value'],"Y-m-d",$Format_Date_PHP)." à ".substr(11,8,$nom_variable['value']);
	    break;
    case "date":
      if ($nom_variable['value']=='0000-00-00') { $msg='&nbsp;'; } else { $msg=Date_Convertir($nom_variable['value'],"Y-m-d",$Format_Date_PHP); }
	    break;
    case "heure":
        $msg=substr($nom_variable['value'],0,5);
	    break;
    case "numeraire":
        $msg=number_format($nom_variable['value'],2,',',' ')." &euro;";
	    break;
    case "texte":
  	  $msg=str_replace("\n","<br>",$nom_variable['value']);
	    break;
    case "editeur":
  	  $msg=$nom_variable['value'];
	    break;
    case "checkbox":
  	  if ($nom_variable['value']=="1")
	    {
	      $msg="<input type=checkbox class=textbox name=".$nom_variable['nom']." value=1 checked disabled>";
	    }
	    else
	    {
	      $msg="<input type=checkbox class=textbox name=".$nom_variable['nom']." value=1 disabled>";
	    }
	    break;
	  case "liste":
	    $msg=$liste_choix[$nom_variable['nomliste']][$nom_variable['value']];
	    break;
    case "email":
  	  $msg='<a href="mailto:'.$nom_variable['value'].'">'.$nom_variable['value'].'</a>';
	    break;
	case "liste_prof":
	  if (array_key_exists('nom_prof',$nom_variable))
	  {
        $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id='".$nom_variable['value']."'");
	  }
	  else
	  {
        $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id='".$nom_variable['value']."'");
	  }
      if (mysql_num_rows($req)=="")
      {
        $valeur=$liste_choix[$nom_variable['nomliste']][$nom_variable['value']];
      }
      else
      {
        $valeur=mysql_result($req,0,'intitule');
      }
      $decoupe=explode("|",$valeur);
      $msg=$decoupe[$nom_variable['partie']-1];
      break;
	case "liste_bdd":
      $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$nom_variable['nomliste']."' AND id='".$nom_variable['value']."'");
	  $msg=mysql_result($req,0,'intitule');
      break;
    default:
	    $msg=$nom_variable['value'];
	    break;
  }
  return $msg;
}

// Création des tables etablissement et param_profs_plus après un changement d'année scolaire
function Change_Annee_Session($annee_a)
{
  global $gestclasse_config_plus, $gestclasse_config;

  if (table_ok($gestclasse_config['param_connexion']['base'],"etablissement".$annee_a)==false)
  {
    $annee_avant=$annee_a-1;
    $req18=mysql_query("CREATE TABLE `".$gestclasse_config['param_connexion']['base']."`.`etablissement".$annee_a."` (`parametre` varchar( 50 ) NOT NULL ,`valeur` text NOT NULL) ENGINE = MYISAM DEFAULT CHARSET = utf8");
    if (table_ok($gestclasse_config['param_connexion']['base'],"etablissement".$annee_avant)==false)
    {
      $req18=mysql_query("INSERT INTO `".$gestclasse_config['param_connexion']['base']."`.`etablissement".$annee_a."` SELECT * FROM `".$gestclasse_config['param_connexion']['base']."`.`etablissement`");
    }
    else
    {
      $req18=mysql_query("INSERT INTO `".$gestclasse_config['param_connexion']['base']."`.`etablissement".$annee_a."` SELECT * FROM `".$gestclasse_config['param_connexion']['base']."`.`etablissement".$annee_avant."`");
    }
  }

  if (table_ok($gestclasse_config['param_connexion']['base'],"param_profs_plus".$annee_a)==false)
  {
    $annee_avant=$annee_a-1;
    $req18=mysql_query("CREATE TABLE `".$gestclasse_config['param_connexion']['base']."`.`param_profs_plus".$annee_a."` (`id_prof` varchar( 64 ) NOT NULL , `parametre` varchar( 50 ) NOT NULL , `valeur` text NOT NULL ) ENGINE = MYISAM DEFAULT CHARSET = utf8");
    if (table_ok($gestclasse_config['param_connexion']['base'],"param_profs_plus".$annee_avant)==true)
    {
      $req18=mysql_query("INSERT INTO `".$gestclasse_config['param_connexion']['base']."`.`param_profs_plus".$annee_a."` SELECT * FROM `".$gestclasse_config['param_connexion']['base']."`.`param_profs_plus".$annee_avant."`");
    }
  }

}

// Créer la liste des élèves
function Liste_Eleve($nom_liste,$type='form',$valeur_defaut,$id_classe="",$date_limite_entree,$date_limite_sortie,$tabindex="1")
{
  if ($type=="form")
  {
    $msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
    if ($id_classe=="")
    {
      if ($date_limite_entree=="")
      {
        $req=mysql_query("SELECT * FROM `eleves` WHERE date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."' ORDER BY nom ASC, prenom ASC");
      }
      else
      {
        $req=mysql_query("SELECT * FROM `eleves` WHERE date_entree<'$date_limite_entree' AND (date_sortie='0000-00-00' OR date_sortie>='$date_limite_sortie') ORDER BY nom ASC, prenom ASC");
      }
    }
    else
    {
      if ($date_limite_entree=="")
      {
        $req=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves.id=eleves_classes.id_eleve AND eleves_classes.id_classe='$id_classe' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".date("Y-m-d")."') ORDER BY eleves.nom ASC, eleves.prenom ASC");
      }
      else
      {
        $req=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves.id=eleves_classes.id_eleve AND eleves_classes.id_classe='$id_classe' AND eleves.date_entree<'$date_limite_entree' AND (eleves.date_sortie='0000-00-00' OR date_sortie>='$date_limite_sortie') ORDER BY eleves.nom ASC, eleves.prenom ASC");
      }
    }
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      $msg=$msg.'<option value="'.mysql_result($req,$i-1,'eleves.id').'"';
      if (mysql_result($req,$i-1,'eleves.id')==$valeur_defaut) { $msg=$msg.' SELECTED'; }
      $msg=$msg.'>'.mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom').'</option>';
    }
    $msg=$msg.'</select>';
  }
  else
  {
    $req=mysql_query("SELECT * FROM `eleves` WHERE id='$valeur_defaut'");
    $msg=mysql_result($req,$i-1,'eleves.nom').' '.mysql_result($req,$i-1,'eleves.prenom');
  }
  return $msg;
}

// Créer la liste des années scolaires
function Liste_Annee($nom_liste,$type='form',$valeur_defaut,$tabindex='1')
{
  global $gestclasse_config_plus;
  
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  if ($type=="form")
	  {
	    $annee_plus=date("Y")+1;
			$req=mysql_query("SELECT * FROM `classes` ORDER BY annee ASC");
			$annee_cours="0000";
			$msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
			if (mysql_num_rows($req)!="")
			{
				for ($i=1;$i<=mysql_num_rows($req);$i++)
				{
					if (mysql_result($req,$i-1,'annee')!=$annee_cours)
					{
						$msg .='<option value="'.mysql_result($req,$i-1,'annee').'"';
						if (mysql_result($req,$i-1,'annee')==$valeur_defaut) { $msg .=' SELECTED'; }
						$msg .='>'.mysql_result($req,$i-1,'annee').'</option>';
						$annee_cours=mysql_result($req,$i-1,'annee');
					}
				}
				if ($annee_cours!=$annee_plus)
				{
					$msg .='<option value="'.$annee_plus.'"';
					if ($annee_plus==$valeur_defaut) { $msg .=' SELECTED'; }
					$msg .='>'.$annee_plus.'</option>';
					$msg .='</select>';
				}
			}
			else
			{
				$annee=date("Y");
				$msg .='<option value="'.$annee.'"';
				if ($annee==$valeur_defaut) { $msg .=' SELECTED'; }
				$msg .='>'.$annee.'</option>';
				$annee=$annee+1;
				$msg .='<option value="'.$annee.'"';
				if ($annee==$valeur_defaut) { $msg .=' SELECTED'; }
				$msg .='>'.$annee.'</option>';
				$msg .='</select>';
			}
	  }
	  else
	  {
			$msg=$valeur_defaut;
	  }
  }
  else
  {
	  if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire'])
	  {
			$annee_plus=date("Y");
	  }
	  else
	  {
			$annee_plus=date("Y")+1;
	  }
	  if ($type=="form")
	  {
			$req=mysql_query("SELECT * FROM `classes` ORDER BY annee ASC");
			$annee_cours="0000";
			$msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
			if (mysql_num_rows($req)!="")
			{
				for ($i=1;$i<=mysql_num_rows($req);$i++)
				{
					if (mysql_result($req,$i-1,'annee')!=$annee_cours)
					{
						$msg .='<option value="'.mysql_result($req,$i-1,'annee').'"';
						if (mysql_result($req,$i-1,'annee')==$valeur_defaut) { $msg .=' SELECTED'; }
						$annee_p=mysql_result($req,$i-1,'annee')+1;
						$msg .='>'.mysql_result($req,$i-1,'annee').'-'.$annee_p.'</option>';
						$annee_cours=mysql_result($req,$i-1,'annee');
					}
				}
				if ($annee_cours!=$annee_plus)
				{
					$msg .='<option value="'.$annee_plus.'"';
					if ($annee_plus==$valeur_defaut) { $msg .=' SELECTED'; }
					$annee=$annee_plus+1;
					$msg .='>'.$annee_plus.'-'.$annee.'</option>';
					$msg .='</select>';
				}
			}
			else
			{
				if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire'])
				{
					$annee=date("Y")-1;
				}
				else
				{
					$annee=date("Y");
				}
				$annee_p=$annee+1;
				$msg .='<option value="'.$annee.'"';
				if ($annee==$valeur_defaut) { $msg .=' SELECTED'; }
				$msg .='>'.$annee.'-'.$annee_p.'</option>';
				$msg .='<option value="'.$annee_p.'"';
				if ($annee_p==$valeur_defaut) { $msg .=' SELECTED'; }
				$annee=$annee_p+1;
				$msg .='>'.$annee_p.'-'.$annee.'</option>';
				$msg .='</select>';
			}
	  }
	  else
	  {
			$annee1=$valeur_defaut+1;
			$msg=$valeur_defaut."-".$annee1;
	  }
  }
  return $msg;
}

// Créer une liste des profs
// nom_liste : nom du select et de id
// type : form pour un select, value pour la valeur
// valeur_defaut : valeur par défaut du select
// id_classe : id de la classe
// type_user : sélectionne uniquement les personnes d'un certain type
// Remarque : type_user=T (titulaire), E (décharge), D (décloisonnement), I (intervenant extérieur), A (titulaire, décharge et décloisonnement), S (ATSEM), U (Autre) si id_classe est déclarée
//            type_user=D (directeur), P (enseignant), R (enseignant et directeur), I (intervenant extérieur), S (ATSEM), U (Autre) si id_classe est non déclarée
function Liste_Profs($nom_liste,$type='form',$id_classe='',$valeur_defaut,$type_user='',$multiple=false,$exclusion=false,$id_titulaire='',$tabindex='1')
{
  global $liste_choix, $Langue;

  $tab_valeur_defaut=explode("|",$valeur_defaut);
  $msg="";
  $where="";
  if ($type=="form")
  {
    if ($multiple==false)
    {
      $msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'[]" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
    }
    else
    {
      $msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'[]" id="'.$nom_liste.'" multiple="multiple" size="5">';
    }

    if ($id_classe!="")
    {
      if ($type_user!="")
      {
        if ($type_user=="A")
        {
          $where="AND (classes_profs.type='T' OR classes_profs.type='D' OR classes_profs.type='E')";
        }
        else
        {
          $where="AND classes_profs.type='$type_user'";
        }
      }
      $req=mysql_query("SELECT profs.*, classes_profs.* FROM `profs`, `classes_profs` WHERE profs.id=classes_profs.id_prof AND classes_profs.id_classe='$id_classe' $where ORDER BY classes_profs.type DESC, profs.nom ASC, profs.prenom ASC");
      $type="45";
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
         if (mysql_result($req,$i-1,'classes_profs.type')!=$type)
         {
           $msg=$msg.'<option class=option_gras value="">'.$liste_choix['type_user_classe'][mysql_result($req,$i-1,'classes_profs.type')].'</option>';
           $type=mysql_result($req,$i-1,'classes_profs.type');
         }
         $msg=$msg.'<option value="'.mysql_result($req,$i-1,'profs.id').'"';
         for ($k=1;$k<=count($tab_valeur_defaut);$k++)
         {
           if (mysql_result($req,$i-1,'profs.id')==$tab_valeur_defaut[$k-1]) { $msg=$msg.' selected="selected"'; }
         }
         $msg=$msg.'>'.mysql_result($req,$i-1,'profs.nom').' '.mysql_result($req,$i-1,'profs.prenom').'</option>';
      }
    }
    else
    {
      if ($type_user!='')
      {
        if ($type_user=="R") { $where="AND (type='D' OR type='P')"; } else { $where="AND type='$type_user'"; }
      }
	  if ($id_titulaire!="") { $where .=" AND id!='".$id_titulaire."'"; }
      $req=mysql_query("SELECT * FROM `profs` WHERE (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."') $where ORDER BY nom ASC, prenom ASC");
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
	    $ajoute=true;
	    if ($exclusion==true)
		{
		  $id_p=mysql_result($req,$i-1,'id');
		  $req2=mysql_query("SELECT * FROM `classes`, `classes_profs` WHERE classes.annee='".$_SESSION['annee_scolaire']."' AND classes.id=classes_profs.id_classe AND classes_profs.id_prof='$id_p' AND classes_profs.type='T'");
		  if (mysql_num_rows($req2)!="") { $ajoute=false; }
          for ($k=1;$k<=count($tab_valeur_defaut);$k++)
          {
		    if ($id_p==$tab_valeur_defaut[$k-1]) { $ajoute=true; }
		  }
		}
		if ($ajoute==true)
		{
          $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
          for ($k=1;$k<=count($tab_valeur_defaut);$k++)
          {
            if (mysql_result($req,$i-1,'id')==$tab_valeur_defaut[$k-1]) { $msg=$msg.' selected="selected"'; }
          }
          $msg=$msg.'>'.mysql_result($req,$i-1,'nom').' '.mysql_result($req,$i-1,'profs.prenom').'</option>';
		}
      }
    }
    $msg=$msg.'</select>';
    if ($multiple==true)
    {
      $msg=$msg.'<script language="Javascript">
      $(document).ready( function()
      {
        $("#'.$nom_liste.'").multiselect(
        {
          header:false,
          selectAll: false,
          noneSelectedText: "'.$Langue['MSG_SELECT_PERSONNE'].'",
          selectedText: "# '.$Langue['MSG_SELECTED_PERSONNE'].'"
        });
      });
      </script>';
    }
  }
  else
  {
    if ($id_classe=="")
    {
      $req=mysql_query("SELECT * FROM `profs` WHERE id='$valeur_defaut'");
      $msg=mysql_result($req,0,'nom')." ".mysql_result($req,0,'prenom');
    }
    else
    {
      $req=mysql_query("SELECT profs.*, classes_profs.* FROM `profs`, `classes_profs` WHERE classes_profs.id_classe='$id_classe' AND classes_profs.id_prof=profs.id AND classes_profs.type='$type_user' ORDER BY profs.nom ASC, profs.prenom ASC");
      $msg="";
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        $msg=$msg.mysql_result($req,$i-1,'nom')." ".mysql_result($req,$i-1,'prenom')."|";
      }
      $msg=substr($msg,0,strlen($msg)-1);
    }
  }
  return $msg;
}

// Liste des classes
function Liste_Classes($nom_liste,$type='form',$annee,$valeur_defaut='',$id_prof='',$tous=false,$option_plus='',$tabindex="1")
{
  global $liste_choix, $Langue;

  $msg="";

  if ($type=="form")
  {
    $msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
    if ($id_prof=="")
    {
      $req=mysql_query("SELECT * FROM `classes` WHERE annee='$annee' ORDER BY nom_classe ASC");
      if ($tous==true) { if ($option_plus=="") {$msg=$msg.'<option value="">'.$Langue['LBL_TOUTES_CLASSES'].'</option>'; } else {$msg=$msg.'<option value=""></option>'; } }
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
			  if ($option_plus=="")
				{
          $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
          if (mysql_result($req,$i-1,'id')==$valeur_defaut) { $msg=$msg.' SELECTED'; }
          $msg=$msg.'>'.mysql_result($req,$i-1,"nom_classe").'</option>';
				}
				else
				{
          $msg=$msg.'<option value="'.mysql_result($req,$i-1,"nom_classe").'">'.mysql_result($req,$i-1,"nom_classe").'</option>';
				}
      }
    }
    else
    {
      $req=mysql_query("SELECT classes.*, classes_profs.* FROM `classes`, `classes_profs` WHERE classes_profs.id_classe=classes.id AND classes_profs.id_prof='$id_prof' AND classes.annee='$annee' ORDER BY classes_profs.type DESC, nom_classe ASC");
      $type="8547";
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        if (mysql_result($req,$i-1,'classes_profs.type')!=$type)
        {
          $msg=$msg.'<option class=option_gras value="">'.$liste_choix['type_user_classe'][mysql_result($req,$i-1,'classes_profs.type')].'</option>';
          $type=mysql_result($req,$i-1,'classes_profs.type');
        }
			  if ($option_plus=="")
				{
					$msg=$msg.'<option value="'.mysql_result($req,$i-1,'classes.id').'"';
					if (mysql_result($req,$i-1,'classes.id')==$valeur_defaut) { $msg=$msg.' SELECTED'; }
					$msg=$msg.'>'.mysql_result($req,$i-1,"classes.nom_classe").'</option>';
				}
				else
				{
					$msg=$msg.'<option value="'.mysql_result($req,$i-1,'classes.nom_classe').'">'.mysql_result($req,$i-1,"classes.nom_classe").'</option>';
				}
      }
    }
    $msg=$msg.'</select>';
  }
  else
  {
    $req=mysql_query("SELECT * FROM `classes` WHERE id='$valeur_defaut'");
    $msg=mysql_result($req,0,"nom_classe");
  }
  return $msg;
}

// Liste des niveaux
function Liste_Niveaux($nom_liste,$type='form',$valeur_defaut='',$id_classe='',$multiple=false,$tabindex="1")
{
  global $Langue; 
  
  $tab_valeur_defaut=explode("|",$valeur_defaut);
  $msg="";

  if ($type=="form")
  {
    if ($multiple==false)
    {
      $msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'[]" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
    }
    else
    {
      $msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'[]" id="'.$nom_liste.'" multiple="multiple" size="5">';
    }

    if ($id_classe=="")
    {
      $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='niveaux' ORDER BY ordre ASC");
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
        for ($k=1;$k<=count($tab_valeur_defaut);$k++)
        {
          if (mysql_result($req,$i-1,'id')==$tab_valeur_defaut[$k-1]) { $msg=$msg.' selected="selected"'; }
        }
        $msg=$msg.'>'.mysql_result($req,$i-1,'intitule').'</option>';
      }
    }
    else
    {
      $req=mysql_query("SELECT listes.*, classes_niveaux.* FROM `listes`,`classes_niveaux` WHERE classes_niveaux.id_classe='".$id_classe."' AND classes_niveaux.id_niveau=listes.id AND listes.nom_liste='niveaux' ORDER BY ordre ASC");
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        $msg=$msg.'<option value="'.mysql_result($req,$i-1,'listes.id').'"';
        for ($k=1;$k<=count($tab_valeur_defaut);$k++)
        {
          if (mysql_result($req,$i-1,'listes.id')==$tab_valeur_defaut[$k-1]) { $msg=$msg.' selected="selected"'; }
        }
        $msg=$msg.'>'.mysql_result($req,$i-1,'listes.intitule').'</option>';
      }
    }
    $msg=$msg.'</select>';
    if ($multiple==true)
    {
      $msg=$msg.'<script language="Javascript">
      $(document).ready( function()
      {
        $("#'.$nom_liste.'").multiselect(
        {
          header:false,
          selectAll: false,
          noneSelectedText: "'.$Langue['MSG_SELECT_NIVEAU'].'",
          selectedText: "# '.$Langue['MSG_SELECTED_NIVEAU'].'"
        });
      });
      </script>';
    }
  }
  else
  {
    if ($id_classe=="")
    {
      $req=mysql_query("SELECT * FROM `listes` WHERE id='$valeur_defaut' ORDER BY ordre ASC");
      $msg=mysql_result($req,0,'intitule');
    }
    else
    {
      $req=mysql_query("SELECT listes.*, classes_niveaux.* FROM `listes`,`classes_niveaux` WHERE classes_niveaux.id_classe='".$id_classe."' AND classes_niveaux.id_niveau=listes.id ORDER BY ordre ASC");
      for ($i=1;$i<=mysql_num_rows($req);$i++)
      {
        $msg=$msg.mysql_result($req,$i-1,'listes.intitule').'|';
      }
      $msg=substr($msg,0,strlen($msg)-1);
    }
  }
  return $msg;
}

// Construit la liste des heures de travail
function Liste_Heures($nom_liste,$id_classe,$date,$valeur_defaut='',$tabindex="1")
{
  global $gestclasse_config_plus, $Langue;
  
  $valeur_defaut=substr($valeur_defaut,0,5);
  $req=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='$id_classe' AND type='T'");
  $id_prof=mysql_result($req,0,'id_prof');
  Param_Utilisateur($id_prof,$_SESSION['annee_scolaire']);
  $msg='<select tabindex='.$tabindex.' name="'.$nom_liste.'" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
  $jour=date("w",mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4)));
  if ($gestclasse_config_plus['matin'.$jour]=="1")
  {
    $msg .='<option value="" class="option_gras">'.$Langue['LBL_MATIN'].'</option>';
    $heure_debut=$gestclasse_config_plus['jour_matin_debut'];
    if ($gestclasse_config_plus['am'.$jour]=="0")
    {
      $option=false;
      $heure_fin=$gestclasse_config_plus['jour_matin_fin'];
    }
    else
    {
      $option=true;
      $heure_fin=$gestclasse_config_plus['jour_am_fin'];
    }
  }
  else
  {
    $option=false;
    $msg .='<option value="" class="option_gras">'.$Langue['LBL_AM'].'</option>';
    $heure_debut=$gestclasse_config_plus['jour_am_debut'];
    $heure_fin=$gestclasse_config_plus['jour_am_fin'];
  }
  
  for ($i=8;$i<=18;$i++)
  {
    for ($j=0;$j<=55;$j=$j+5)
    {
      $heure=date("H:i",mktime($i,$j,0,date("m"),date("d"),date("Y")));
      if ($heure>=$heure_debut && $heure<=$heure_fin)
      {
        if ($heure<=$gestclasse_config_plus['jour_matin_fin'] || $heure>=$gestclasse_config_plus['jour_am_debut'])
        {
          if ($heure==$gestclasse_config_plus['jour_am_debut'] && $option==true) { $msg .='<option value="" class="option_gras">'.$Langue['LBL_AM'].'</option>'; }
          $msg .='<option value="'.$heure.'"';
          if ($heure==$valeur_defaut) { $msg .=' SELECTED'; }
          $msg .='>'.$heure.'</option>';
        }
      }
    }
  }
  $msg .='</select>';
  return $msg;
}

// Retourne une selectbox avec les trimestres ou les périodes
function Liste_Periode($nom_liste,$valeurdefaut)
{
  global $gestclasse_config_plus, $liste_choix, $Langue;
  
  if ($gestclasse_config_plus['decoupage_livret']=="T") { $msg=$Langue['LBL_TRIMESTRE']." : "; } else { $msg=$Langue['LBL_PERIODE']." : "; }
  $msg .='<select name="'.$nom_liste.'" id="'.$nom_liste.'" class="text ui-widget-content ui-corner-all">';
  foreach ($liste_choix['livret_decoupage_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    $msg .='<option value="'.$cle.'"';
    if ($cle==$valeurdefaut) { $msg .=' SELECTED'; }
    $msg .='>'.$value.'</option>';
  }
  $msg .='</select>';
  return $msg;
}

// Fonction affichant la selectbox pour les résultats
function Liste_Resultats($nom_liste,$valeurdefaut)
{
  global $gestclasse_config_plus;
  $msg='<select class="text ui-widget-content ui-corner-all" name="'.$nom_liste.'" id="'.$nom_liste.'">';
  $msg .='<option value="-1"></option>';
  for ($i=9;$i>=0;$i--)
  {
    if ($gestclasse_config_plus['c'.$i]!="")
    {
      $msg .='<option value="'.$i.'"';
      if ($i==$valeurdefaut) { $msg .=' SELECTED'; }
      $msg .='>'.$gestclasse_config_plus['c'.$i].'</option>';
    }
  }
  $msg .='</select>';
  return $msg;
}

function FeedParser($url_feed, $nb_items_affiches=10, $id_panneau, $readmore=true, $date=true)
{
  global $liste_choix, $Langue, $Format_Date_PHP;

  // lecture du fichier distant (flux XML)
  $rss = fetch_rss($url_feed);

  // si la lecture s'est bien passee,
  // on lit les elements
  if (is_array($rss->items))
  {
   // on ne recupere que les elements les + recents
   $items = array_slice($rss->items,0);

   // debut de la liste
   // (vous pouvez indiquer un style CSS
   // pour la formater)
   $html ='<input type="hidden" id="nb_par_page_'.$id_panneau.'" name="nb_par_page_'.$id_panneau.'" value="'.$nb_items_affiches.'">';
   $html .='<input type="hidden" id="page_'.$id_panneau.'" name="page_'.$id_panneau.'" value="1">';
   $html .='<input type="hidden" id="max_'.$id_panneau.'" name="max_'.$id_panneau.'" value="'.count($items).'">';

   // boucle sur tous les elements
   $i=1;
   foreach ($items as $item)
   {
    if ($i<=$nb_items_affiches) { $style='style="visibility:visible;display:block;"'; } else { $style='style="visibility:hidden;display:none;"'; }
    $html .= '<div '.$style.' id="ministere_'.$id_panneau.'_'.$i.'"><div class="ui-widget ui-widget-content ui-corner-all"><div class="ui-widget ui-widget-header ui-state-default ui-corner-all" style="float:none;padding:5px;text-align:left;margin-bottom:7px">'.utf8_encode($item['title']).'</div>';
    $html .= '<div style="padding:5px">'.utf8_encode($item['description']).'</div>';
	if ($readmore==true || $date==true) { $html .= '<div style="padding:5px;text-align:right;font-size:10px">'; }
	if ($date==true) { $html .= $Langue['LBL_LE']." ".Date_Convertir(date('Y-m-d',strtotime($item['pubdate'])),'Y-m-d',$Format_Date_PHP); }
	if ($readmore==true && $date==true) { $html .= ' - '; }
	if ($readmore==true) { $html .= '<a href='.$item['link'].' target=_new>'.$Langue['LBL_LIRE_SUITE'].'</a>'; }
	if ($readmore==true || $date==true) { $html .= '</div>'; }
    $html .= '</div><br /></div>';
    $i++;
   }
 }
 $nb_page=ceil(count($items)/$nb_items_affiches);
 $html .='<div style="text-align:center">';
 for($i=1;$i<=$nb_page;$i++)
 {
   if ($i=="1")
   {
     $html .= '<a id="lien_'.$id_panneau.'_'.$i.'" href="#null" class="fg-button ui-button ui-state-default ui-state-disabled" onClick="Accueil_Modif_Page_'.$id_panneau.'('.$i.')">&nbsp;'.$i.'&nbsp;</a>';
   }
   else
   {
     $html .= '<a id="lien_'.$id_panneau.'_'.$i.'" href="#null" class="fg-button ui-button ui-state-default" onClick="Accueil_Modif_Page_'.$id_panneau.'('.$i.')">&nbsp;'.$i.'&nbsp;</a>';
   }
 }
 $html .= '</div>';

 // retourne le code HTML à inclure dans la page
 return $html;
}

// Fonction qui convertit une chaîne en majuscule
function Convertir_En_Majuscule($str)
{
$liste=Array("â","ä","à","é","è","ê","ë","ô","ö","û","ü","ù","ç","î","ï","ÿ","°","ã","õ","ñ");
$liste2=Array("&acirc;","&auml;","&agrave;","&eacute;","&egrave;","&ecirc;","&euml;","&ocirc;","&ouml;","&ucirc;","&uuml;","&ugrave;","&ccedil;","&icirc;","&iuml;","&yuml;","&deg;","&atilde;","&otilde;","&ntilde;");
  for ($i=0;$i<count($liste);$i++)
  {
    $str=str_replace($liste[$i],$liste2[$i],$str);
  }
  $str=htmlentities($str, ENT_QUOTES);
  $str = strtoupper($str);
  $str = str_replace("&AMP;","&",$str);
  $pattern = '/&([A-Z])(UML|ACUTE|CIRC|TILDE|RING|';
  $pattern .= 'ELIG|GRAVE|SLASH|HORN|CEDIL|TH);/e';
  $replace = "'&'.'\\1'.strtolower('\\2').';'";
  $str = preg_replace($pattern, $replace, $str);
  return $str;
}

// Retourne le trimestre ou la période en cours
function Trouve_Trimestre()
{
  global $gestclasse_config_plus;
  if ($gestclasse_config_plus['decoupage_livret']=="T")
  {
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_T1'] && date("Y-m-d")<=$gestclasse_config_plus['fin_T1']) { return "1"; }
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_T2'] && date("Y-m-d")<=$gestclasse_config_plus['fin_T2']) { return "2"; }
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_T3'] && date("Y-m-d")<=$gestclasse_config_plus['fin_T3']) { return "3"; }
  }
  else
  {
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_P1'] && date("Y-m-d")<=$gestclasse_config_plus['fin_P1']) { return "1"; }
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_P2'] && date("Y-m-d")<=$gestclasse_config_plus['fin_P2']) { return "2"; }
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_P3'] && date("Y-m-d")<=$gestclasse_config_plus['fin_P3']) { return "3"; }
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_P4'] && date("Y-m-d")<=$gestclasse_config_plus['fin_P4']) { return "4"; }
	if (date("Y-m-d")>=$gestclasse_config_plus['debut_P5'] && date("Y-m-d")<=$gestclasse_config_plus['fin_P5']) { return "5"; }
  }
}

// Fonction qui calcule le nombre de demi-journées dans le mois
function Demi_Journee($mois,$annee)
{
  global $gestclasse_config_plus;

  $total=0;
  $nb_jour=date('t',mktime(0,0,0,$mois,1,$annee));
  for ($i=1;$i<=$nb_jour;$i++)
  {
    $date=date("Y-m-d",mktime(0,0,0,$mois,$i,$annee));
    // Est-ce un jour de vacances ?
    $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='$date' AND date_fin>='$date' AND zone='".$gestclasse_config_plus['zone']."'");
    if (mysql_num_rows($req)=="")
    // Si ce n'est pas un jour de vacances, on regarde si c'est un jour férié
    {
      $req=mysql_query("SELECT * FROM `dates_speciales` WHERE date='$date' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
      if (mysql_num_rows($req)=="")
      // Si ce n'est pas un jour férié, on regarde quand on travaille
      {
        $date=date("w",mktime(0,0,0,$mois,$i,$annee));
        if ($date!="0")
        // Si ce n'est pas un dimanche
        {
          // Travaille-t-on le matin
          if ($gestclasse_config_plus['matin'.$date]=="1") { $total=$total+1; }
          // Travaille-t-on l'après-midi
          if ($gestclasse_config_plus['am'.$date]=="1") { $total=$total+1; }
        }
      }
    }
  }
  return $total;
}

// Fonction qui recherche les élèves qui sont entrés ou sortis durant le mois et donc qui enlèvent des 1/2 journées de présence
function Demi_Journee_en_moins($mois,$annee,$premier_jour_du_mois,$dernier_jour_du_mois,$id_classe)
{
  global $gestclasse_config_plus;
  $total=0;
  // On cherche les élèves qui font leur entrée dans le mois
  $req_eleve=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_classe='$id_classe' AND eleves_classes.id_eleve=eleves.id AND (eleves.date_entree<='$dernier_jour_du_mois' AND eleves.date_entree>='$premier_jour_du_mois')");
  for ($i=1;$i<=mysql_num_rows($req_eleve);$i++)
  {
    $fin=date('j',mktime(0,0,0,substr(mysql_result($req_eleve,$i-1,'eleves.date_entree'),5,2),substr(mysql_result($req_eleve,$i-1,'eleves.date_entree'),8,2),substr(mysql_result($req_eleve,$i-1,'eleves.date_entree'),0,4)));
    for ($j=1;$j<$fin;$j++)
    {
      $date=date("Y-m-d",mktime(0,0,0,$mois,$j,$annee));
      // Est-ce un jour de vacances ?
      $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='$date' AND date_fin>='$date' AND zone='".$gestclasse_config_plus['zone']."'");
      if (mysql_num_rows($req)=="")
      // Si ce n'est pas un jour de vacances, on regarde si c'est un jour férié
      {
        $req=mysql_query("SELECT * FROM `dates_speciales` WHERE date='$date' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
        if (mysql_num_rows($req)=="")
        // Si ce n'est pas un jour férié, on regarde quand on travaille
        {
          $date=date("w",mktime(0,0,0,$mois,$j,$annee));
          if ($date!="0")
          // Si ce n'est pas un dimanche
          {
            // Travaille-t-on le matin
            if ($gestclasse_config_plus['matin'.$date]=="1") { $total=$total+1; }
            // Travaille-t-on l'après-midi
            if ($gestclasse_config_plus['am'.$date]=="1") { $total=$total+1; }
          }
        }
      }
    }
  }

  // On cherche les élèves qui sortent dans le mois
  $req_eleve=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_classe='$id_classe' AND eleves_classes.id_eleve=eleves.id AND (eleves.date_sortie<='$dernier_jour_du_mois' AND eleves.date_sortie>='$premier_jour_du_mois')");
  for ($i=1;$i<=mysql_num_rows($req_eleve);$i++)
  {
    $debut=date('j',mktime(0,0,0,substr(mysql_result($req_eleve,$i-1,'eleves.date_sortie'),5,2),substr(mysql_result($req_eleve,$i-1,'eleves.date_sortie'),8,2),substr(mysql_result($req_eleve,$i-1,'eleves.date_sortie'),0,4)));
    $fin=substr($dernier_jour_du_mois,8,2);
    for ($j=$debut;$j<=$fin;$j++)
    {
      $date=date("Y-m-d",mktime(0,0,0,$mois,$j,$annee));
      // Est-ce un jour de vacances ?
      $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='$date' AND date_fin>='$date' AND zone='".$gestclasse_config_plus['zone']."'");
      if (mysql_num_rows($req)=="")
      // Si ce n'est pas un jour de vacances, on regarde si c'est un jour férié
      {
        $req=mysql_query("SELECT * FROM `dates_speciales` WHERE date='$date' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
        if (mysql_num_rows($req)=="")
        // Si ce n'est pas un jour férié, on regarde quand on travaille
        {
          $date=date("w",mktime(0,0,0,$mois,$j,$annee));
          if ($date!="0")
          // Si ce n'est pas un dimanche
          {
            // Travaille-t-on le matin
            if ($gestclasse_config_plus['matin'.$date]=="1") { $total=$total+1; }
            // Travaille-t-on l'après-midi
            if ($gestclasse_config_plus['am'.$date]=="1") { $total=$total+1; }
          }
        }
      }
    }
  }
  return $total;
}

// Fonction qui calcule les absences du mois et qui retourne le nombre d'élèves absents plus de trois 1/2 journées et le nombre d'absences qui leur sont imputables
function Absences_Mois($mois,$annee,$id_classe)
{
  global $gestclasse_config_plus;
  $total_eleve=0;
  $total_absence=0;
  $total_general=0;
  // On recherche les élèves du mois
  $dernier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$mois,date('t',mktime(0,0,0,$mois,1,$annee)),$annee));
  $premier_jour_du_mois=date("Y-m-d",mktime(0,0,0,$mois,1,$annee));
  $borne=date("Y-m-",mktime(0,0,0,$mois,1,$annee));
  $req_eleve=mysql_query("SELECT eleves.*, eleves_classes.* FROM `eleves`,`eleves_classes` WHERE eleves_classes.id_classe='$id_classe' AND eleves_classes.id_eleve=eleves.id AND eleves.date_entree<='$dernier_jour_du_mois' AND (eleves.date_sortie='0000-00-00' OR date_sortie>='$premier_jour_du_mois')");
  for ($i=1;$i<=mysql_num_rows($req_eleve);$i++)
  {
    $total=0;
    // On récupère les absences de l'élève
    $req_absence=mysql_query("SELECT * FROM `absences` WHERE id_eleve='".mysql_result($req_eleve,$i-1,'eleves.id')."' AND date LIKE '".$borne."%'");
    for ($j=1;$j<=mysql_num_rows($req_absence);$j++)
    {
      $date=mysql_result($req_absence,$j-1,'date');
      // Est-ce un jour de vacances ?
      $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='$date' AND date_fin>='$date' AND zone='".$gestclasse_config_plus['zone']."'");
      if (mysql_num_rows($req)=="")
      // Si ce n'est pas un jour de vacances, on regarde si c'est un jour férié
      {
        $req=mysql_query("SELECT * FROM `dates_speciales` WHERE date='$date' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
        if (mysql_num_rows($req)=="")
        // Si ce n'est pas un jour férié, on regarde quand on travaille et s'il y a une absence
        {
          $date2=date("w",mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4)));
          if ($date2!="0")
          // Si ce n'est pas un dimanche
          {
            // Travaille-t-on le matin
            if ($gestclasse_config_plus['matin'.$date2]=="1" && mysql_result($req_absence,$j-1,'matin')=="1") { $total=$total+1; }
            // Travaille-t-on l'après-midi
            if ($gestclasse_config_plus['am'.$date2]=="1" && mysql_result($req_absence,$j-1,'apres_midi')=="1") { $total=$total+1; }
          }
        }
      }
    }
    $total_general=$total_general+$total;
    if ($total>3)
    {
      $total_absence=$total_absence+$total;
      $total_eleve=$total_eleve+1;
    }
  }
  return $total_general.'-'.$total_eleve.'-'.$total_absence;
}

function Affiche_Calendrier($mois,$annee,$hauteur,$extension)
{
global $liste_choix, $gestclasse_config_plus, $Format_Date_PHP;

  echo '<div class="ui-widget ui-widget-content ui-corner-all">';
  echo '<a href=#null onClick="Calendrier_L_Charge_Mois(\''.$mois.'\')" style="text-decoration:none"><div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all" style="padding:7px;text-align:center">';
  echo $liste_choix['mois'][$mois].' '.$annee;
  echo '</div></a>';
  echo '<table class="ui-datepicker-calendar" width=100%><thead><tr>';
  for ($i=1;$i<=7;$i++)
  {
    echo '<th style="padding:5px;width:14%"><span title="'.$liste_choix['jour_long'][$i].'">'.$liste_choix['jour_'.$extension][$i].'</span></th>';
  }
  echo '</tr></thead><tbody>';
  $premier_jour_mois=date("N",mktime(0,0,0,$mois,1,$annee));
  for ($i=1;$i<$premier_jour_mois;$i++)
  {
    if ($i==1) { echo '<tr>'; }
    echo '<td>&nbsp;</td>';
  }
  $nb_jour=date("t",mktime(0,0,0,$mois,1,$annee));
  for ($i=1;$i<=$nb_jour;$i++)
  {
    $jour=date("w",mktime(0,0,0,$mois,$i,$annee));
	$class="";
	$plus="";
	$plus_apres="";
	$style="";
	if (date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))==date("Y-m-d")) { $class=" ui-state-highlight"; }
	switch ($jour)
	{
	  case "0":
	    $plus_apres='</tr>';
	    $class="ui-state-default".$class;
		break;
	  case "1":
	    $plus="<tr>";
		if ($gestclasse_config_plus['matin'.$jour]=="0" && $gestclasse_config_plus['am'.$jour]=="0")
		{
  	      $class="ui-state-default".$class;
		}
		else
		{
		  $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND date_fin>='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND zone='".$gestclasse_config_plus['zone']."'");
		  $req2=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
		  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="") 
		  { 
		    $class="ui-widget-content".$class; 
			$style='font-weight:bold;text-decoration:none;';
		  } 
		  else 
		  { 
		    $class="ui-state-default".$class; 
		  }		
		}
		break;
	  default:
		if ($gestclasse_config_plus['matin'.$jour]=="0" && $gestclasse_config_plus['am'.$jour]=="0")
		{
  	      $class="ui-state-default".$class;
		}
		else
		{
		  $req=mysql_query("SELECT * FROM `vacances` WHERE date_debut<='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND date_fin>='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND zone='".$gestclasse_config_plus['zone']."'");
		  $req2=mysql_query("SELECT * FROM `dates_speciales` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (type LIKE '".$gestclasse_config_plus['zone']."' OR type LIKE '".$gestclasse_config_plus['zone']."%' OR type LIKE '%".$gestclasse_config_plus['zone']."' OR type LIKE '%".$gestclasse_config_plus['zone']."%')");
		  if (mysql_num_rows($req)=="" && mysql_num_rows($req2)=="") 
		  { 
		    $class="ui-widget-content".$class; 
			$style='font-weight:bold;text-decoration:none;';
		  } 
		  else 
		  { 
		    $class="ui-state-default".$class; 
		  }		
		}
		break;
	}
	echo $plus.'<td class="'.$class.'" style="height:'.$hauteur.'px;vertical-align:top;'.$style.'"><p class="textdroite"><a style="text-decoration:underline;" href=#null onClick="Calendrier_L_Modif_Date_Calendrier(\''.date($Format_Date_PHP,mktime(0,0,0,$mois,$i,$annee)).'\')">'.$i.'</a></p>';
	$id_util=$_SESSION['type_util'].$_SESSION['id_util'];
	$req=mysql_query("SELECT * FROM `reunions` WHERE date='".date("Y-m-d",mktime(0,0,0,$mois,$i,$annee))."' AND (id_util LIKE '".$id_util.",%' OR id_util LIKE '%,".$id_util."' OR id_util='".$id_util."' OR id_util LIKE '%,".$id_util.",%') ORDER BY heure_debut ASC");
	for ($o=1;$o<=mysql_num_rows($req);$o++)
	{
	  echo '<p style="border-left:3px solid '.$liste_choix['couleur_reunion'][mysql_result($req,$o-1,'type')].'">';
 	  if ($extension=="court")
	  {
	    echo '&nbsp;<a style="text-decoration:none;" class="someClass" onMouseOver="Calendrier_L_Voir_Tooltil(\''.substr(mysql_result($req,$o-1,'heure_debut'),0,5).'-'.substr(mysql_result($req,$o-1,'heure_fin'),0,5).'\',\''.str_replace("'","\'",$liste_choix['type_reunion'][mysql_result($req,$o-1,'type')]).'\',\''.str_replace('"','&quot;',str_replace("'","\'",mysql_result($req,$o-1,'resume'))).'\')" href=#null onClick="Calendrier_L_Charge_Reunion(\''.mysql_result($req,$o-1,'id').'\')">'.$liste_choix['type_reunion_court'][mysql_result($req,$o-1,'type')].'</a>';
	  }
	  else
	  {
	    echo '&nbsp;<a style="text-decoration:none;" class="someClass" onMouseOver="Calendrier_L_Voir_Tooltil(\''.substr(mysql_result($req,$o-1,'heure_debut'),0,5).'-'.substr(mysql_result($req,$o-1,'heure_fin'),0,5).'\',\''.str_replace("'","\'",$liste_choix['type_reunion'][mysql_result($req,$o-1,'type')]).'\',\''.str_replace('"','&quot;',str_replace("'","\'",mysql_result($req,$o-1,'resume'))).'\')" href=#null onClick="Calendrier_L_Charge_Reunion(\''.mysql_result($req,$o-1,'id').'\')">'.substr(mysql_result($req,$o-1,'heure_debut'),0,5).'-'.substr(mysql_result($req,$o-1,'heure_fin'),0,5).' : '.$liste_choix['type_reunion'][mysql_result($req,$o-1,'type')].'</a>';
	  }
	  echo '</p>';
	}
	echo '</td>'.$plus_apres;
  }
  echo '</tbody></table></div>';
}

function Supprimer_Caractere($chaine)
{
  $search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_]@');
  $replace = array ('e','a','i','u','o','c','_','');
  return strtolower(preg_replace($search, $replace, $chaine));
}
?>