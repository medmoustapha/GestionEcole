<?php
  $id=$_POST['id'];

  $req=mysql_query("DELETE FROM `controles_resultats` WHERE id_controle='$id'");

  $req=mysql_query("SELECT * FROM `controles_competences` WHERE id_controle='$id'");
  $req_eleve=mysql_query("SELECT * FROM `eleves_classes` WHERE id_classe='".$_SESSION['id_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."'");

  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    $id_c=mysql_result($req,$i-1,'id_competence');
    for ($j=1;$j<=mysql_num_rows($req_eleve);$j++)
    {
      $id_e=mysql_result($req_eleve,$j-1,'id_eleve');
      if (isset($_POST['req'.$id_e.'-'.$id_c]))
      {
        if ($_POST['req'.$id_e.'-'.$id_c]!="-1")
        {
          $req2=mysql_query("INSERT INTO `controles_resultats` (id_eleve,id_controle,id_competence,resultat) VALUES ('$id_e','$id','$id_c','".$_POST['req'.$id_e.'-'.$id_c]."')");
        }
      }
    }
  }

  echo $id;
?>