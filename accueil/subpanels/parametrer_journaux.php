<?php
  $parametrage=explode('|',mysql_result($req,0,'parametre'));
?>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_JOURNAUX_LISTE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <input type="hidden" id="nb_param" name="nb_param" value="3">
    <select id="param2" name="param2" class="text ui-widget-content ui-corner-all">
    <?php
    foreach ($liste_choix["journaux_actualites"] AS $cle => $value)
    {
      echo '<option value="'.$cle.'"';
      if ($cle==$parametrage[1]) { echo ' SELECTED'; }
      echo '>'.$value.'</option>';
    }
    ?>
    </select>
  </td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_JOURNAUX_AFFICHER']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <select id="param3" name="param3" class="text ui-widget-content ui-corner-all">
    <?php
    foreach ($liste_choix["journaux_categories"] AS $cle => $value)
    {
      echo '<option value="'.$cle.'"';
      if ($cle==$parametrage[2]) { echo ' SELECTED'; }
      echo '>'.$value.'</option>';
    }
    ?>
    </select>
  </td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_JOURNAUX_NB_AFFICHE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <select id="param1" name="param1" class="text ui-widget-content ui-corner-all">
    <?php
    for ($p=1;$p<=10;$p++)
    {
      echo '<option value="'.$p.'"';
      if ($p==$parametrage[0]) { echo ' SELECTED'; }
      echo '>'.$p.'</option>';
    }
    ?>
    </select>
  </td>
</tr>
