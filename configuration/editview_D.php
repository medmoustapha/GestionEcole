<?php
  Param_Utilisateur('',$_SESSION['annee_scolaire']);
  
  switch ($_GET['id'])
  {
    case "coordonnees": $titre=$Langue['LBL_COORDONNEES_TITRE']; $lien_site="directeur-configuration/article/245-definir-les-coordonnees-de-votre-etablissement.html"; break;
    case "horaires": $titre=$Langue['LBL_HORAIRES_TITRE']; $lien_site="directeur-configuration/article/243-directeur-definir-les-horaires-de-votre-etablissement.html"; break;
    case "livrets": $titre=$Langue['LBL_LIVRETS_PARAMETRES_LS']; $lien_site="directeur-configuration/article/241-directeur-config-configurer-les-livrets-scolaires.html"; break;
    case "bibliotheque": $titre=$Langue['LBL_BIBLIO_TITRE']; $lien_site="directeur-configuration/article/233-config-configurer-les-bibliotheques-decole-et-de-classe.html"; break;
    case "messagerie": $titre=$Langue['LBL_CONFIG_TITRE']; $lien_site="directeur-configuration/article/238-configuration-generale-de-lapplication.html"; break;
    case "cooperative": $titre=$Langue['LBL_COOPERATIVE_TITRE']; $lien_site="directeur-configuration/article/239-configurer-votre-cooperative-scolaire.html"; break;
    case "signatures": $titre=$Langue['LBL_CONFIG_SIGNATURE']; $lien_site="directeur-configuration/article/235-configurer-les-signatures-electroniques-et-les-appreciations.html"; break;
  }
  
  $tpl = new template("configuration");
  $tpl->set_file("gform","editview_D_".$_GET['id'].".html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  $tpl->set_var("NOM",'<input class="text ui-widget-content ui-corner-all" type="text" name="nom" id="nom" value="'.$gestclasse_config_plus['nom'].'" size=33 maxlength=255>');
  $tpl->set_var("TELEPHONE",'<input class="text ui-widget-content ui-corner-all" type="text" name="tel" id="tel" value="'.$gestclasse_config_plus['tel'].'" size=33 maxlength=30>');
  $tpl->set_var("TELEPHONE2",'<input class="text ui-widget-content ui-corner-all" type="text" name="tel2" id="tel2" value="'.$gestclasse_config_plus['tel2'].'" size=33 maxlength=30>');
  $tpl->set_var("FAX",'<input class="text ui-widget-content ui-corner-all" type="text" name="fax" id="fax" value="'.$gestclasse_config_plus['fax'].'" size=33 maxlength=30>');
  $tpl->set_var("EMAIL",'<input class="text ui-widget-content ui-corner-all" type="text" name="email" id="email" value="'.$gestclasse_config_plus['email'].'" size=33 maxlength=255>');
  $tpl->set_var("SITE_WEB",'<input class="text ui-widget-content ui-corner-all" type="text" name="site_web" id="site_web" value="'.$gestclasse_config_plus['site_web'].'" size=33 maxlength=255>');
  $tpl->set_var("CIRCONSCRIPTION",'<input class="text ui-widget-content ui-corner-all" type="text" name="circonscription" id="circonscription" value="'.$gestclasse_config_plus['circonscription'].'" size=33 maxlength=255>');
  $tpl->set_var("RNE",'<input class="text ui-widget-content ui-corner-all" type="text" name="rne" id="rne" value="'.$gestclasse_config_plus['rne'].'" size=33 maxlength=255>');
  $tpl->set_var("ADRESSE",'<textarea class="text ui-widget-content ui-corner-all" name="adresse" id="adresse" cols=40 rows=5>'.str_replace('<br>',"\r\n",$gestclasse_config_plus['adresse']).'</textarea>');

  if ($gestclasse_config_plus['zone']=="A") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_A",'<input type="radio" id="zone" name="zone" value="A" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="B") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_B",'<input type="radio" id="zone" name="zone" value="B" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="C") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_C",'<input type="radio" id="zone" name="zone" value="C" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="D") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_D",'<input type="radio" id="zone" name="zone" value="D" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="E") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_E",'<input type="radio" id="zone" name="zone" value="E" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="F") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_F",'<input type="radio" id="zone" name="zone" value="F" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="G") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_G",'<input type="radio" id="zone" name="zone" value="G" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="H") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_H",'<input type="radio" id="zone" name="zone" value="H" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="I") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_I",'<input type="radio" id="zone" name="zone" value="I" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="J") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_J",'<input type="radio" id="zone" name="zone" value="J" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="K") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_K",'<input type="radio" id="zone" name="zone" value="K" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="L") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_L",'<input type="radio" id="zone" name="zone" value="L" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="M") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_M",'<input type="radio" id="zone" name="zone" value="M" '.$checked.'>');
  if ($gestclasse_config_plus['zone']=="P") { $checked="checked"; } else { $checked=""; }
  $tpl->set_var("ZONE_P",'<input type="radio" id="zone" name="zone" value="P" '.$checked.'>');

  if ($gestclasse_config_plus['zone']=="P") 
  { 
    $tpl->set_var("VISIBLE_PERSO","visible");
    $tpl->set_var("DISPLAY_PERSO","block");
  } 
  else 
  { 
    $tpl->set_var("VISIBLE_PERSO","hidden"); 
    $tpl->set_var("DISPLAY_PERSO","none");
  }
  $msg='<select class="text ui-widget-content ui-corner-all" name="etendue" id="etendue">';
  $msg .='<option value="1"';
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1") { $msg .=' SELECTED'; }
  $msg .='>'.$Langue['LBL_HORAIRES_PERSON_ANNEE_SCOLAIRE2'].'</option>';
  $msg .='<option value="2"';
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="2") { $msg .=' SELECTED'; }
  $msg .='>'.$Langue['LBL_HORAIRES_PERSON_ANNEE_SCOLAIRE3'].'</option>';
  $msg .='</select>';
  $tpl->set_var("SELECT_DECOUPAGE_ANNEE_SCOLAIRE",$msg);

  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1") { $annee2=$_SESSION['annee_scolaire']; $disabled=true; } else { $annee2=$_SESSION['annee_scolaire']+1; $disabled=false; }
  $debut_as='<input type=text class="text ui-widget-content ui-corner-all" id=debut_as name=debut_as value="'.Date_Convertir($_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'],"Y-m-d",$Format_Date_PHP).'" size=10 maxlength=10 '.$disabled.'>';
  $fin_as='<input type=text class="text ui-widget-content ui-corner-all" id=fin_as name=fin_as value="'.Date_Convertir($annee2.$gestclasse_config_plus['fin_annee_scolaire'],"Y-m-d",$Format_Date_PHP).'" size=10 maxlength=10 '.$disabled.'>';
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1") 
  {
    $tpl->set_var("DEBUT_AS",$debut_as.'<script> $(function() { $( "#debut_as" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true }); });</script>');
    $tpl->set_var("FIN_AS",$fin_as.'<script> $(function() { $( "#fin_as" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true }); });</script>');
  }
  else
  {
    $tpl->set_var("DEBUT_AS",$debut_as.'<script> $(function() { $( "#debut_as" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true }); });</script>');
    $tpl->set_var("FIN_AS",$fin_as.'<script> $(function() { $( "#fin_as" ).datepicker({ dateFormat: "'.$Format_Date_Calendar.'", showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true }); });</script>');
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

  $msg='<select class="text ui-widget-content ui-corner-all" name="biblio_ecole" id="biblio_ecole">';
  $msg2='<select class="text ui-widget-content ui-corner-all" name="biblio_classe" id="biblio_classe">';
  foreach ($liste_choix['ouinon'] AS $cle => $value)
  {
    $msg=$msg.'<option value="'.$cle.'"';
    $msg2=$msg2.'<option value="'.$cle.'"';
    if ($cle==$gestclasse_config_plus['biblio_ecole']) { $msg=$msg.' SELECTED'; }
    if ($cle==$gestclasse_config_plus['biblio_classe']) { $msg2=$msg2.' SELECTED'; }
    $msg=$msg.'>'.$value.'</option>';
    $msg2=$msg2.'>'.$value.'</option>';
  }
  $msg=$msg.'</select>';
  $msg2=$msg2.'</select>';
  $tpl->set_var("BIBLIO_ECOLE",$msg);
  $tpl->set_var("BIBLIO_CLASSE",$msg2);
  
  $msg='<select class="text ui-widget-content ui-corner-all" name="biblio_duree_emprunt" id="biblio_duree_emprunt">';
  $msg2='<select class="text ui-widget-content ui-corner-all" name="biblio_duree_emprunt_classe" id="biblio_duree_emprunt_classe">';
  for ($i=1;$i<=30;$i++)
  {
    $msg=$msg.'<option value="'.$i.'"';
    $msg2=$msg2.'<option value="'.$i.'"';
    if ($i==$gestclasse_config_plus['biblio_duree_emprunt']) { $msg=$msg.' SELECTED'; }
    if ($i==$gestclasse_config_plus['biblio_duree_emprunt_classe']) { $msg2=$msg2.' SELECTED'; }
    $msg=$msg.'>'.$i.' jour';
    $msg2=$msg2.'>'.$i.' jour';
	if ($i!="1") { $msg=$msg.'s';$msg2=$msg2.'s'; }
	$msg=$msg.'</option>';
	$msg2=$msg2.'</option>';
  }
  $msg=$msg.'</select>';
  $msg2=$msg2.'</select>';
  $tpl->set_var("DUREE_EMPRUNT",$msg);
  $tpl->set_var("DUREE_EMPRUNT_CLASSE",$msg2);
  
  $msg='<select class="text ui-widget-content ui-corner-all" name="biblio_nombre_livres" id="biblio_nombre_livres">';
  $msg2='<select class="text ui-widget-content ui-corner-all" name="biblio_nombre_livres_classe" id="biblio_nombre_livres_classe">';
  for ($i=1;$i<=10;$i++)
  {
    $msg=$msg.'<option value="'.$i.'"';
    $msg2=$msg2.'<option value="'.$i.'"';
    if ($i==$gestclasse_config_plus['biblio_nombre_livres']) { $msg=$msg.' SELECTED'; }
    if ($i==$gestclasse_config_plus['biblio_nombre_livres_classe']) { $msg2=$msg2.' SELECTED'; }
    $msg=$msg.'>'.$i.' livre';
    $msg2=$msg2.'>'.$i.' livre';
	if ($i!="1") { $msg=$msg.'s';$msg2=$msg2.'s'; }
	$msg=$msg.'</option>';
	$msg2=$msg2.'</option>';
  }
  $msg=$msg.'</select>';
  $msg2=$msg2.'</select>';
  $tpl->set_var("NB_EMPRUNT",$msg);
  $tpl->set_var("NB_EMPRUNT_CLASSE",$msg2);

  
  // **********************************
  // * Cas de la coopérative scolaire *
  // **********************************

  $msg='<select class="text ui-widget-content ui-corner-all" name="cooperative_presente" id="cooperative_presente">';
  foreach ($liste_choix['ouinon'] AS $cle => $value)
  {
    $msg=$msg.'<option value="'.$cle.'"';
    if ($cle==$gestclasse_config_plus['cooperative_presente']) { $msg=$msg.' SELECTED'; }
    $msg=$msg.'>'.$value.'</option>';
  }
  $msg=$msg.'</select>';
  $tpl->set_var("COOPERATIVE_PRESENTE",$msg);
  $tpl->set_var("COOPERATIVE_MANDATAIRES",Liste_Profs("cooperative_mandataires_liste",'form','',$gestclasse_config_plus['cooperative_mandataires'],'R',true));
  if ($gestclasse_config_plus['cooperative_repartition']=="C") { $tpl->set_var("COOPERATIVE_REPARTITION_C","CHECKED"); } else { $tpl->set_var("COOPERATIVE_REPARTITION_C",""); }
  if ($gestclasse_config_plus['cooperative_repartition']=="E") { $tpl->set_var("COOPERATIVE_REPARTITION_E","CHECKED"); } else { $tpl->set_var("COOPERATIVE_REPARTITION_E",""); }

  // **********************
  // * Cas des signatures *
  // **********************

  $msg='<select class="text ui-widget-content ui-corner-all" name="signature_registre" id="signature_registre">';
  $msg2='<select class="text ui-widget-content ui-corner-all" name="signature_livrets" id="signature_livrets">';
  $msg3='<select class="text ui-widget-content ui-corner-all" name="appreciation_livrets" id="appreciation_livrets">';
  foreach ($liste_choix['ouinon'] AS $cle => $value)
  {
    $msg=$msg.'<option value="'.$cle.'"';
    $msg2=$msg2.'<option value="'.$cle.'"';
    $msg3=$msg3.'<option value="'.$cle.'"';
    if ($cle==$gestclasse_config_plus['signature_registre']) { $msg=$msg.' SELECTED'; }
    if ($cle==$gestclasse_config_plus['signature_livrets']) { $msg2=$msg2.' SELECTED'; }
    if ($cle==$gestclasse_config_plus['appreciation_livrets']) { $msg3=$msg3.' SELECTED'; }
    $msg=$msg.'>'.$value.'</option>';
    $msg2=$msg2.'>'.$value.'</option>';
    $msg3=$msg3.'>'.$value.'</option>';
  }
  $msg=$msg.'</select>';
  $msg2=$msg2.'</select>';
  $msg3=$msg3.'</select>';
  $tpl->set_var("SIGNATURE_REGISTRE",$msg);
  $tpl->set_var("SIGNATURE_LIVRETS",$msg2);
  $tpl->set_var("APPRECIATION_LIVRETS",$msg3);

  // *****************************************************
  // * Cas de la configuration générale de l'application *
  // *****************************************************
  
  $tpl->set_var("URL_GESTECOLE",'<input class="text ui-widget-content ui-corner-all" type="text" name="gestclasse_url" id="gestclasse_url" value="'.$gestclasse_config_plus['gestclasse_url'].'" size=50 maxlength=255>');
  $tpl->set_var("EMAIL_DEFAUT",'<input class="text ui-widget-content ui-corner-all" type="text" name="email_defaut" id="email_defaut" value="'.$gestclasse_config_plus['email_defaut'].'" size=50 maxlength=255>');
  $tpl->set_var("SIGNATURE",'<input class="text ui-widget-content ui-corner-all" type="text" name="signature_messagerie" id="signature_messagerie" value="'.$gestclasse_config_plus['signature_messagerie'].'" size=50 maxlength=255>');
  $tpl->set_var("URL_GESTECOLE2",$gestclasse_config_plus['gestclasse_url']);
  $tpl->set_var("SIGNATURE2",$gestclasse_config_plus['signature_messagerie']);
  $tpl->set_var("MESSAGE_CONNEXION",'<textarea class="text ui-widget-content ui-corner-all" name="messageconnexion" id="messageconnexion" cols=100 rows=20>'.$gestclasse_config_plus['message_connexion'].'</textarea>');

  $msg='<select class="text ui-widget-content ui-corner-all" name="decalage_horaire" id="decalage_horaire">';
	for ($i=-23;$i<0;$i++)
	{
	  for ($j=30;$j>=0;$j=$j-30)
		{
	    if ($j<30) { $heu=$i.':0'.$j; } else { $heu=$i.':'.$j; }
			$msg .='<option class="textdroite" value="'.$heu.'"';
			if ($heu==$gestclasse_config_plus['decalage_horaire']) { $msg .=' SELECTED'; }
			$msg .='>'.$heu.'</option>';
		}
	}
	for ($i=0;$i<=23;$i++)
	{
	  for ($j=0;$j<=55;$j=$j+30)
		{
	    if ($j<30) { $heu=$i.':0'.$j; } else { $heu=$i.':'.$j; }
			$msg .='<option class="textdroite" value="'.$heu.'"';
			if ($heu==$gestclasse_config_plus['decalage_horaire']) { $msg .=' SELECTED'; }
			$msg .='>'.$heu.'</option>';
		}
	}
	$msg .='</select>';
	$tpl->set_var("DECALAGE_HORAIRE",$msg);
	$tpl->set_var("DECALAGE_HORAIRE_DATE_ACTUELLE",date("H:i:s"));

  $msg='<select class="text ui-widget-content ui-corner-all" name="langue_defaut" id="langue_defaut">';
  if ($handle = opendir('langues/')) {
    while (false !== ($entry = readdir($handle))) 
	{
        if ($entry != "." && $entry != "..") 
		{
            if (file_exists("langues/".$entry."/langue.php")) 
			{
			  $fp=fopen("langues/".$entry."/langue.php","r");
			  $texte=fgets($fp);
			  $msg .='<option value="'.$entry.'"';
			  if ($entry==$gestclasse_config_plus['langue_defaut']) { $msg .=" SELECTED"; }
			  $msg .='>'.$texte.'</option>';
			}
        }
    }
    closedir($handle);
  }    
  $msg .="</select>";
  $tpl->set_var("LANGUE_DEFAUT",$msg);
  
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
  
  $("#gestclasse_url").change(function()
  {
    $("#maj_url").html($("#gestclasse_url").val());
  });
  
  $("#signature_messagerie").change(function()
  {
    $("#signataire").html($("#signature_messagerie").val());
  });
  
  $("input[name='zone']").change(function()
  {
	if ($('input[type=radio]:checked').attr('value')=="P") 
	{ 
	  document.getElementById('Personnaliser').style.visibility="visible"; 
	  document.getElementById('Personnaliser').style.display="block"; 
	} 
	else 
	{ 
	  document.getElementById('Personnaliser').style.visibility="hidden"; 
	  document.getElementById('Personnaliser').style.display="none"; 
	}
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		/* On contrôle la saisie du formulaire */
<?php
    switch ($_GET['id'])
    {
      case "coordonnees": ?>
        if (!checkValue($("#nom"))) { bValid=false; }
        if (!checkValue($("#adresse"))) { bValid=false; }
        if ($("#email").val()!="")
        {
          if (!checkRegexp($("#email"),/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i)) { bValid=false; }
        }
<?php      break;
      case "horaires": ?>
	    if ($('input[type=radio]:checked').attr('value')=="P")
		{
		  if ($("#etendue option:selected").val()=="2")
		  {
		    if (!checkRegexp($("#debut_as"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
		    if (!checkRegexp($("#fin_as"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
			if (checkRegexp($("#debut_as"),<?php echo $date_regexp[$Format_Date_PHP]; ?>) && checkRegexp($("#fin_as"),<?php echo $date_regexp[$Format_Date_PHP]; ?>))
			{
			  if (Compare_Date($("#debut_as").val(),$("#fin_as").val(),'<?php echo $Format_Date_PHP; ?>')<0)
			  {
				$("#debut_as").addClass( "ui-state-error" );
				$("#fin_as").addClass( "ui-state-error" );
				bValid=false;
			  }
			  else
			  {
				$("#debut_as").removeClass( "ui-state-error" );
				$("#fin_as").removeClass( "ui-state-error" );
			  }
			}
		  }
		}
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
      case "messagerie": ?>
        if (!checkValue($("#gestclasse_url"))) { bValid=false; }
        if (!checkValue($("#signature_messagerie"))) { bValid=false; }
        if ($("#email_defaut").val()!="")
        {
          if (!checkRegexp($("#email_defaut"),/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i)) { bValid=false; }
        }
<?php      break;
      default: ?>
<?php        break;
  }
?>
    event.preventDefault();
    if ( bValid )
    {
      Message_Chargement(2,1);
      var $form = $( this );
      url = $form.attr( 'action' );
<?php if ($_GET['id']=="messagerie") { ?>
	  ed = tinyMCE.get('messageconnexion');
	  $('#message_c').val(ed.getContent());
<?php } ?>
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

<?php if ($_GET['id']=="messagerie") { ?>
tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
		directionality : "<?php echo $Sens_Ecriture; ?>",
		skin : "default",
		skin_variant :"<?php echo $_SESSION['theme_choisi']; ?>",
<?php if (in_array($Langue_Valeur,$langue_tinymce)) { ?>
		language : "<?php echo $Langue_Valeur; ?>",
<?php } else { ?>
		language : "en",
<?php } ?>
        plugins : "style,layer,advlink,iespell,inlinepopups,preview,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "fontselect,fontsizeselect,forecolor,backcolor,|,bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,undo,redo,|,link,unlink,|,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,
		height:300
});
<?php } ?>
</script>
