<?php
  $parametrage=explode('|',mysql_result($req,0,'parametre'));
?>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_REUNIONS_AFFICHER']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <input type="hidden" id="nb_param" name="nb_param" value="2">
    <select id="param1" name="param1" class="text ui-widget-content ui-corner-all">
    <?php
	    echo '<option value="D"';
		if ($parametrage[0]=="D") { echo ' SELECTED'; }
		echo ">".$Langue['LBL_REUNIONS_JOUR']."</option>";
	    echo '<option value="L"';
		if ($parametrage[0]=="L") { echo ' SELECTED'; }
		echo ">".$Langue['LBL_REUNIONS_A_VENIR']."</option>";
    ?>
    </select>
  </td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_REUNIONS_NB_AFFICHE']; ?> <font color="#FF0000">*</font> :</label></td>
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
