<?php
  $id_classe=$_POST['id_classe'];
  $req2=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='$id_classe' AND type='T'");
  $id_prof=mysql_result($req2,0,'id_prof');

  $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='matieres_cj' AND id_prof='$id_prof' ORDER BY ordre ASC");
  $msg='<select class="text ui-widget-content ui-corner-all" id="id_matiere" name="id_matiere" onChange="Change_Matiere()">';
  if (mysql_num_rows($req)=="")
  {
    foreach ($liste_choix['matieres_cj'] AS $cle => $valeur)
    {
      $msg=$msg.'<option value="'.$cle.'"';
      if ($cle==$_POST['valeur_defaut']) { $msg=$msg.' SELECTED'; }
      $msg=$msg.'>'.$valeur.'</option>';
    }
  }
  else
  {
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      $msg=$msg.'<option value="'.mysql_result($req,$i-1,'id').'"';
      if (mysql_result($req,$i-1,'id')==$_POST['valeur_defaut']) { $msg=$msg.' SELECTED'; }
      $msg=$msg.'>'.mysql_result($req,$i-1,'intitule').'</option>';
    }
  }
  $msg=$msg.'<option value="RECRE"';
  if ($_POST['valeur_defaut']=="RECRE") { $msg=$msg.' SELECTED'; }
  $msg=$msg.'>'.$Langue['LBL_RECREATION'].'</option>';
  $msg=$msg."</select>";
  
  echo $msg;
?>