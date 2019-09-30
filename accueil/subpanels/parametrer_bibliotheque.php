<?php
  $parametrage=explode('|',mysql_result($req,0,'parametre'));
?>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_BIBLIO_AFFICHER']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <input type="hidden" id="nb_param" name="nb_param" value="2">
    <select id="param1" name="param1" class="text ui-widget-content ui-corner-all">
    <?php
      if ($gestclasse_config_plus['biblio_ecole']=="1")
	  {
	    echo '<option value="E"';
		if ($parametrage[0]=="E") { echo ' SELECTED'; }
		echo ">".$Langue['LBL_BIBLIO_ECOLE']."</option>";
	  }
	  if ($gestclasse_config_plus['biblio_classe']=="1")
      {
	    echo '<option value="C"';
		if ($parametrage[0]=="C") { echo ' SELECTED'; }
		echo ">".$Langue['LBL_BIBLIO_CLASSE']."</option>";
	  }
    ?>
    </select>
  </td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_BIBLIO_NB_AFFICHE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <select id="param2" name="param2" class="text ui-widget-content ui-corner-all">
    <?php
    for ($p=1;$p<=5;$p++)
    {
      echo '<option value="'.$p.'"';
      if ($p==$parametrage[1]) { echo ' SELECTED'; }
      echo '>'.$p.'</option>';
    }
    ?>
    </select>
  </td>
</tr>
