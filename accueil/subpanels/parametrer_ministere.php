<?php
  $parametrage=explode('|',mysql_result($req,0,'parametre'));
?>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_MINISTERE_PROVENANCE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <input type="hidden" id="nb_param" name="nb_param" value="2">
    <select id="param1" name="param1" class="text ui-widget-content ui-corner-all">
    <?php
    foreach ($liste_choix['news_ministere'] AS $cle => $value)
    {
      echo '<option value="'.$cle.'"';
      if ($cle==$parametrage[0]) { echo ' SELECTED'; }
      echo '>'.$value.'</option>';
    }
    ?>
    </select>
  </td>
</tr>
<tr>
  <td class="droite" width=15%><label class="label_class"><?php echo $Langue['LBL_MINISTERE_NB_AFFICHE']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=85%>
    <select id="param2" name="param2" class="text ui-widget-content ui-corner-all">
    <?php
    for ($p=1;$p<=10;$p++)
    {
      echo '<option value="'.$p.'"';
      if ($p==$parametrage[1]) { echo ' SELECTED'; }
      echo '>'.$p.'</option>';
    }
    ?>
    </select>
  </td>
</tr>
