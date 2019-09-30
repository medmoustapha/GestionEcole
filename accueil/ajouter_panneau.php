<?php
  include "accueil/ajouter_panneau_".$_SESSION['type_util'].".php";
?>
<script language="Javascript">
function Accueil_Ajout_Panneau(panneau,param,titre,colonne)
{
  Message_Chargement(2,1);
  var request = $.ajax({type: "POST", url: "index2.php", data: "module=accueil&action=save_ajout_panneau&panneau="+panneau+"&param="+param+"&titre="+titre+"&colonne="+colonne});
  request.done(function()
  {
    Message_Chargement(1,1);
    $( "#dialog-form" ).dialog( "close" );
    $("#tabs").tabs("load",0);
    Message_Chargement(1,0);
  });
}
</script>
