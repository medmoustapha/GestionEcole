<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE3']; ?></div></td>
  <td width="25%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE4']; ?></div></td>
</tr>
</table>
<br />
<div class="textgauche">
<?php
  $file_name=$_GET['fichier'];
  $zip_dir='cache/update/'.$file_name;

    echo "<b>".$Langue['LBL_MAJ_COPIE_FICHIERS']."</b> :<br><ul>";
    function Liste_Dossier($repertoire)
    {
      global $file_name,$Langue;
      $repertoire_transit = 'cache/update/'.$file_name;
      $handle = opendir($repertoire_transit ."/". $repertoire);
      while ( $file = readdir($handle) )
      {
        if ($repertoire!="")
        {
          $rep = $repertoire_transit ."/". $repertoire."/".$file;
          $rep2 = $repertoire."/".$file;
        }
        else
        {
          $rep = $repertoire_transit ."/". $repertoire .$file;
          $rep2 = $file;
        }
        if ($file!="." && $file!=".." && $file!="install.txt")
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

    echo "</ul><br><b>".$Langue['LBL_LANGUE_TERMINEE']."</b>";
	
?>
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
