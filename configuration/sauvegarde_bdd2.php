<?php
  $dater=date("YmdHis");
  $filename = "export_bdd";
  $fp = fopen("cache/upload/".$filename.".sql","w");
  if (!is_resource($fp))
  return false;

  $res = mysql_list_tables($gestclasse_config['param_connexion']['base']);
  $num_rows = mysql_num_rows($res);
  $i = 0;
  while ($i < $num_rows)
  {
    $tablename = mysql_tablename($res, $i);

    fwrite($fp,"--\n-- Table `$tablename`;\n--\n");
    fwrite($fp,"DROP TABLE IF EXISTS `$tablename`;\n");

    $query = "SHOW CREATE TABLE $tablename";
    $resCreate = mysql_query($query);
    $row = mysql_fetch_array($resCreate);
    $schema = $row[1].";";
    fwrite($fp,"$schema\n\n");

    $query = "SELECT * FROM $tablename";
    $resData = mysql_query($query);
    if (mysql_num_rows($resData) > 0)
    {
      $sFieldnames = "";
      $num_fields = mysql_num_fields($resData);
      for($j=0; $j < $num_fields; $j++)
      {
        $sFieldnames .= "`".mysql_field_name($resData, $j)."`,";
      }
      $sFieldnames = "(".substr($sFieldnames,0,-1).")";
      $sInsert = "INSERT INTO `$tablename` $sFieldnames values ";

      while($rowdata = mysql_fetch_assoc($resData))
      {
        $lesDonnees = "<guillemet>".implode("<guillemet>,<guillemet>",$rowdata)."<guillemet>";
        $lesDonnees = str_replace("<guillemet>","'",addslashes($lesDonnees));

        $lesDonnees = "$sInsert($lesDonnees);";

        fwrite($fp,"$lesDonnees\n");
      }
    }
    fwrite($fp,"\n\n\n");
    $i++;
  }
  fclose($fp);

  $afaire=true;
  $filename = "backup_".$dater;
  include_once('commun/pclzip/pclzip.lib.php');
  $archive = new PclZip('cache/upload/'.$filename.'.zip');
  $v_list = $archive->create('cache/upload/export_bdd.sql',PCLZIP_OPT_REMOVE_PATH, 'cache/upload');
  if ($v_list == 0)
  {
    die("Erreur : ".$archive->errorInfo(true));
    $afaire=false;
  }

  if ($afaire==true)
  {
    $req=mysql_query("UPDATE `config` SET valeur='".date("Y-m-d")."' WHERE parametre='sauve_bdd'");
    if (!isset($_POST['etape']))
    {
      echo '<p><strong>'.$Langue['MSG_SAUVEGARDE_GENERE'].'</strong><br /><br /><input type="button" name="Telecharger" id="Telecharger" value="'.$Langue['BTN_SAUVEGARDE_TELECHARGER'].'" onClick="window.open(\'configuration/telecharger.php?fichier='.$filename.'.zip\',\'Download\')"></p><script language="Javascript">$("#Telecharger").button();$("#tabs").tabs("load",$("#tabs").tabs("option", "selected"));</script>';
    }
    else
    {
      $msg ='<p><strong>'.$Langue['MSG_SAUVEGARDE_GENERE'].'</strong><br /><br /><input type="button" name="Telecharger" id="Telecharger" value="'.$Langue['BTN_SAUVEGARDE_TELECHARGER'].'" onClick="window.open(\'configuration/telecharger.php?fichier='.$filename.'.zip\',\'Download\')"></p><br /><br /><br /><div style="text-align:right">';
	  if ($_POST['decoupe']!="3")
	  {
	    $msg .='<input type="button" id="etape3" name="etape3" value="'.$Langue['BTN_RESTAURATION_ETAPE3'].'"></div>';
	  }
	  else
	  {
	    $msg .='<input type="button" id="etape3" name="etape3" value="'.$Langue['BTN_NETTOYAGE_ETAPE3'].'"></div>';
	  }
      $msg .='<script language="Javascript">$("#Telecharger").button();$("#etape3").button({disabled:true});';
	  $msg .='$("#tabs").tabs("load",$("#tabs").tabs("option", "selected"));';
      $msg .='$("#Telecharger").click(function() {$("#etape3").button({disabled:false}); });';
	  switch ($_POST['decoupe'])
	  {
	    case "3":
          $msg .='$("#etape3").click(function() { Charge_Dialog("index2.php?module=configuration&action=nettoyage_bdd3","'.$Langue['BTN_NETTOYAGE_BDD'].'"); });</script>';
		  break;
	    case "4":
          $msg .='$("#etape3").click(function() { Charge_Dialog("index2.php?module=configuration&action=restauration_bdd3","'.$Langue['LBL_RESTAURATION_TITRE2'].'"); });</script>';
		  break;
		case "5":
		  $msg .='$("#etape3").click(function() { Charge_Dialog("index2.php?module=configuration&action=mise_a_jour3","'.$Langue['LBL_MAJ_TITRE2'].'"); });</script>';
		  break;
	  }
      echo $msg;
    }
  }
  else
  {
    echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 10px;"><strong>'.$Langue['MSG_SAUVEGARDE_ERREUR_GENERATION'].'</strong></div></div>';
  }
?>
