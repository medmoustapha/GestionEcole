<?php
  if ($_SESSION['type_util']=="D" && $listes_auteurs[$_POST['id_liste']]=="D")
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$_POST['id_liste']."' AND id_prof='' ORDER BY ordre ASC");
  }
  else
  {
    $req=mysql_query("SELECT * FROM `listes` WHERE nom_liste='".$_POST['id_liste']."' AND id_prof='".$_SESSION['id_util']."' ORDER BY ordre ASC");
  }
  
  if (mysql_num_rows($req)=="")
  {
    $msg='<div class="textgauche"><input type="Button" id="Personnaliser" name="Personnaliser" value="'.$Langue['BTN_LISTES_PERSONNALISER'].'"></div>';
    $msg .='<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_LISTES_PERSONNALISATION'].'</strong></div></div>';
    $msg .='<script language="Javascript">
            $(document).ready(function()
            {
              $("#Personnaliser").button();
              $("#Personnaliser").click(function()
              {
                Message_Chargement(5,1);
                var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=save_config_listes_perso&id_liste='.$_POST['id_liste'].'"});
                request.done(function(msg)
                {
                  Message_Chargement(1,1);
                  Charge_Dialog("index2.php?module=configuration&action=config_listes&id_liste="+msg,"'.$Langue['LBL_LISTES_PERSONNALISER_LISTES'].'");
                });
              });
            });
            </script>';
  }
  else
  {
    $msg='<div class="textgauche"><input type="Button" id="Creer" name="Creer" value="'.$Langue['BTN_LISTES_AJOUTER_INTITULE'].'">';
    $msg .='<div class="listesortable" style="width:50%">';
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      $msg .='<div id="listItem_'.$i.'"><div class="ui-state-default" style="margin: 0 3px 3px 3px; padding: 0.4em; height: 18px;"><img src="images/deplacer.png" width=15 height=15 border=0>&nbsp;';
      if ($listes_colonne[$_POST['id_liste']]=="2")
      {
        $intitule=explode("|",mysql_result($req,$i-1,'intitule'));
        $msg .=$intitule[1].' <font style="font-weight:normal;">(<u>'.$Langue['LBL_LISTES_ABREVIATION_COURT'].'</u> : <strong>'.$intitule[0].'</strong>)</font>';
      }
      else
      {
        $msg .=mysql_result($req,$i-1,'intitule');
      }
      $msg .='<div class="floatdroite"><a title="'.$Langue['SPAN_LISTES_MODIFIER_INTITULE'].'" href="#null" onClick="Charge_Intitule(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_LISTES_SUPPRIMER_INTITULE'].'" href="#null" onClick="Supprime_Intitule(\''.mysql_result($req,$i-1,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a></div></div></div>';
    }
    $msg .='</div>';
    $msg .='<div class="textgauche"><input type="Button" id="Creer2" name="Creer2" value="'.$Langue['BTN_LISTES_AJOUTER_INTITULE'].'"></div></div>';
    $msg .='<script language="Javascript">
            $(document).ready(function()
            {
              $("#Creer").button();
              $("#Creer2").button();

              $("#Creer").click(function() { Charge_Intitule(""); });
              $("#Creer2").click(function() { Charge_Intitule(""); });

              $( ".listesortable" ).sortable(
              {
                zIndex: 3000,
                update: function()
                {
                  var ordre=$(".listesortable").sortable("serialize");
                  Message_Chargement(2,1);
                  var request = $.ajax({type: "POST", url: "index2.php", data: ordre+"&module=configuration&action=save_listes_ordre&id_liste='.$_POST['id_liste'].'"});
                  request.done(function()
                  {
                    Message_Chargement(1,1);
                    $("#id_liste").change();
 			            });
                }
              });

	            $( ".listesortable" ).disableSelection();
            });
            
            function Charge_Intitule(id)
            {
              Charge_Dialog2("index2.php?module=configuration&action=editview_intitule&id="+id+"&id_liste='.$_POST['id_liste'].'","'.$Langue['LBL_LISTES_MODIFIER_INTITULE'].'");
            }
            
            function Supprime_Intitule(id)
            {
              $( "#dialog-confirm" ).html(\'<div class="textgauche" style="line-height:24px;">'.$Langue['MSG_LISTES_SUPPRIMER_INTITULE'].'</div><div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout">'.$Langue['MSG_LISTES_SUPPRIMER_INTITULE2'].'</strong></div></div>\');
		          $( "#dialog:ui-dialog" ).dialog( "destroy" );

		          $( "#dialog-confirm" ).dialog(
              {
                title: "'.$Langue['LBL_LISTES_SUPPRIMER_INTITULE'].'",
			          resizable: false,
			          draggable: false,
			          height:200,
			          width:450,
			          modal: true,
			          buttons:[
                {
                  text: "'.$Langue['BTN_SUPPRIMER'].'",
				          click: function()
                  {
                    Message_Chargement(4,1);
                    var request = $.ajax({type: "POST", url: "index2.php", data: "module=configuration&action=delete_intitule&id="+id});
                    request.done(function(msg)
                    {
					            $( "#dialog-confirm" ).dialog( "close" );
                      Charge_Dialog("index2.php?module=configuration&action=config_listes&id_liste='.$_POST['id_liste'].'","'.$Langue['LBL_LISTES_PERSONNALISER_LISTES'].'");
                      $("#tabs").tabs("load",$("#tabs").tabs("option", "selected"));
                      Message_Chargement(1,0);
		                });
                  }
			          },
				        {
                  text: "'.$Langue['BTN_ANNULER2'].'",
				          click: function()
                  {
					          $( this ).dialog( "close" );
			            }
		            }]
              });
            }
            </script>';
  }
  echo $msg;
?>