<?php
  ini_set('error_reporting',E_ALL ^ E_DEPRECATED);
?>
<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
  <td width="20%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE5']; ?></div></td>
</tr>
</table>
<br />
<div class="textgauche">
<?php
  $file_name=$_GET['fichier'];
  $zip_dir='cache/update/'.$file_name;

function split_sql($sql) {
	$sql = trim($sql);
	$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);

	$buffer = array();
	$ret = array();
	$in_string = false;

	for($i=0; $i<strlen($sql)-1; $i++) {
		if($sql[$i] == ";" && !$in_string) {
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}

		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
			$in_string = false;
		}
		elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
			$in_string = $sql[$i];
		}
		if(isset($buffer[1])) {
			$buffer[0] = $buffer[1];
		}
		$buffer[1] = $sql[$i];
	}

	if(!empty($sql)) {
		$ret[] = $sql;
	}
	return($ret);
}

function Table($fichier)
{
	$mqr = @get_magic_quotes_runtime();
	@set_magic_quotes_runtime(0);
	$query = fread( fopen( $fichier, 'r' ), filesize( $fichier ) );
	@set_magic_quotes_runtime($mqr);
	$pieces  = split_sql($query);

	for ($i=0; $i<count($pieces); $i++)
  {
		$pieces[$i] = trim($pieces[$i]);
		if(!empty($pieces[$i]) && $pieces[$i] != "#")
    {
      $req=mysql_query($pieces[$i]);
		}
	}
}

    if (file_exists($zip_dir."/mysql_update.sql"))
    {
      Table($zip_dir."/mysql_update.sql");
      echo "<b>".$Langue['LBL_MAJ_BDD']."</b><br><br><br>";
    }
    
    if (file_exists($zip_dir."/mysql_update.php"))
    {
      include $zip_dir."/mysql_update.php";
      echo "<b>".$Langue['LBL_MAJ_AUTRES_DONNEES']."</b><br><br><br>";
    }

    echo "<b>".$Langue['LBL_MAJ_COPIE_FICHIERS']."</b> :<br><ul>";
    function Liste_Dossier($repertoire)
    {
      global $file_name,$Langue;
      $repertoire_transit = 'cache/update/'.$file_name;
      $handle = opendir($repertoire_transit . "/update/" . $repertoire);
      while ( $file = readdir($handle) )
      {
        if ($repertoire!="")
        {
          $rep = $repertoire_transit . "/update/" . $repertoire."/".$file;
          $rep2 = $repertoire."/".$file;
        }
        else
        {
          $rep = $repertoire_transit . "/update/" . $repertoire .$file;
          $rep2 = $file;
        }
        if ($file!="." && $file!="..")
        {
          if (is_dir($rep))
          {
            if (!is_dir($rep2))
            {
              if (mkdir($rep2))
			  {
                echo '<li>'.$Langue['LBL_MAJ_CREATION_REPERTOIRE'].' <strong>'.$rep2.'</strong> : <font color="#009900">OK</font></li>';
			  }
			  else
		  	  {
                echo '<li>'.$Langue['LBL_MAJ_CREATION_REPERTOIRE'].' <strong>'.$rep2.'</strong> : <font color="#FF0000">'.$Langue['ERR_MAJ_ECRITURE_REPERTOIRE'].'</font></li>';
			  }
            }
            Liste_Dossier($rep2);
          }
          else
          {
            if (copy($rep,$rep2))
			{
              echo '<li>'.$Langue['LBL_MAJ_COPIE_FICHIER'].' <strong>'.$rep2.'</strong> : <font color="#009900">OK</font></li>';
			}
			else	
			{
              echo '<li>'.$Langue['LBL_MAJ_COPIE_FICHIER'].' <strong>'.$rep2.'</strong> : <font color="#FF0000">'.$Langue['ERR_MAJ_ECRITURE_FICHIER'].'</font></li>';
			}
          }
        }
      }
      closedir($handle);
    }
    Liste_Dossier('');

    echo '</ul><br><b>'.$Langue['LBL_MAJ_TERMINEE'].'</b><br><br>';
	
	  echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;line-height:18px;"><strong><u>'.$Langue['LBL_MAJ_TERMINEE2'].'</u><br>';
    if (file_exists($zip_dir."/message_fin.php"))
    {
      include $zip_dir."/message_fin.php";
			echo $message_fin."<br>";
    }

    echo $Langue['LBL_MAJ_TERMINEE3'].'</strong></div></div>';

		/* Recherche de la version finale */
  $fichier=$zip_dir.'/install.txt';

  $fp = @fopen( $fichier, 'r' );
  $type=fgets($fp);
  $decoupe=explode("|",$type);
  fclose($fp);
  $req = mysql_query("UPDATE `config` SET valeur='".$decoupe[2]."' WHERE parametre='version_de_l_application'");

  /**************************/  
  /* Envoi des statistiques */
  /**************************/  

  /* Vérification que la clé de stat existe */	
  $req=mysql_query("SELECT * FROM `config` WHERE parametre='cle_stat'");
  if (mysql_num_rows($req)=="")
  {
    $cle=md5($gestclasse_config_plus['nom']);
	$req=mysql_query("INSERT INTO `config` (parametre,valeur) VALUES ('cle_stat','$cle')");
  }
  else
  {
    $cle=mysql_result($req,0,'valeur');
  }
?>
<script language="Javascript">
  parent.calcul.location.href="statistiques.php?cle=<?php echo $cle; ?>&type=update&version=<?php echo $decoupe[2]; ?>&zone=<?php echo $gestclasse_config_plus['zone']; ?>";
</script>
</div>
<br /><br />
<div class="textcentre"><input type="Button" id="fermer" name="fermer" value="Fermer"></div>
<script language="Javascript">
$(document).ready(function()
{
  $("#fermer").button();
  $("#fermer").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
});
</script>
