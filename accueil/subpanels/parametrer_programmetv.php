<?php
  $parametrage=explode('|',mysql_result($req,0,'parametre'));
?>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_PROGRAMMETV_CHAINES']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <input type="hidden" id="nb_param" name="nb_param" value="1">
    <select id="param1" name="param1" class="text ui-widget-content ui-corner-all">
    <?php
      echo '<option value="1"';
	  if ($parametrage[0]=="1") { echo ' SELECTED'; }
	  echo ">".$Langue['LBL_PROGRAMMETV_HERTZIENNES']."</option>";
      echo '<option value="2"';
	  if ($parametrage[0]=="2") { echo ' SELECTED'; }
	  echo ">".$Langue['LBL_PROGRAMMETV_TNT']."</option>";
    ?>
    </select>
  </td>
</tr>
