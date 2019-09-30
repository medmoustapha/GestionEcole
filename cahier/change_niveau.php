<?php
  $id_classe=$_POST['id_classe'];
  $req=mysql_query("SELECT classes_niveaux.*, listes.* FROM `classes_niveaux`, `listes` WHERE classes_niveaux.id_classe='$id_classe' AND classes_niveaux.id_niveau=listes.id ORDER BY listes.ordre ASC");

  $msg='<select class="text ui-widget-content ui-corner-all" id="id_niveau2" name="id_niveau2[]" multiple="multiple" size="5">';
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $msg=$msg.'<option value="'.mysql_result($req,$i-1,'listes.id').'"';
    if (mysql_result($req,$i-1,'listes.id')==$_POST['valeur_defaut']) { $msg=$msg.' SELECTED'; }
    $msg=$msg.'>'.mysql_result($req,$i-1,'listes.intitule').'</option>';
  }
  $msg=$msg."</select>";
      $msg=$msg.'<script language="Javascript">
      $(document).ready( function()
      {
        $("#id_niveau2").multiselect(
        {
          header:false,
          selectAll: false,
          noneSelectedText: "'.$Langue['MSG_SELECT_NIVEAU'].'",
          selectedText: "# '.$Langue['MSG_SELECTED_NIVEAU'].'"
        });
      });
      </script>';

  echo $msg;
?>