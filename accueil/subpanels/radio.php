<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="listItem2_<?php echo $i_panneau; ?>" style="min-width:300px">
  <div class="portlet-header ui-widget-header ui-corner-all"><?php echo $titre; ?>
    <div class="floatdroite">
          <a href="#null" title="<?php echo $Langue['LBL_PARAMETRER_PANNEAU2']; ?>" onClick="Parametrer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/parametrer.png" width=12 height=12 border=0></a>&nbsp;
          <a href="#null" title="<?php echo $Langue['LBL_SUPPRIMER_PANNEAU2']; ?>" onClick="Supprimer_Panneau('<?php echo $id_panneau; ?>')"><img src="images/supprimer.png" width=12 height=12 border=0></a>
    </div>
  </div>
  <div class="portlet-content" style="padding:5px;margin:0px;padding-top:10px;padding-bottom:10px">
<?php 
		echo $Langue['LBL_RADIO_SELECTION']." : <b>".$liste_choix['radio_nom'][$param[0]]."</b>";
		if ($param[1]=="1")
		{
			echo '<br><div id="bouton_radio'.$id_panneau.'" class="textcentre marge10_haut"><img title="'.$Langue['BTN_RADIO_DEMARRER'].'" id="bouton_play'.$id_panneau.'" onClick="lancerlecteur'.$id_panneau.'()" onMouseOver="playover'.$id_panneau.'()" onMouseOut="playout'.$id_panneau.'()" src="images/play_off2.png" width=29 height=28 border=0>&nbsp;&nbsp;<img title="'.$Langue['BTN_RADIO_PAUSE'].'" id="bouton_pause'.$id_panneau.'" onMouseOver="pauseover'.$id_panneau.'()" onMouseOut="pauseout'.$id_panneau.'()" onClick="mettrepause'.$id_panneau.'()" src="images/pause_off.png" width=29 height=28 border=0></a></div>';
			echo '<script language="Javascript">';
			echo 'var radio_on=1;';
			echo 'parent.radio.location.href="accueil/subpanels/radio_frame.php?radio='.$param[0].'&radio_on=1";';
			echo '</script>';
		}
		else
		{
			echo '<br><div id="bouton_radio'.$id_panneau.'" class="textcentre marge10_haut"><img title="'.$Langue['BTN_RADIO_DEMARRER'].'" id="bouton_play'.$id_panneau.'" onClick="lancerlecteur'.$id_panneau.'()" onMouseOver="playover'.$id_panneau.'()" onMouseOut="playout'.$id_panneau.'()" src="images/play_off.png" width=29 height=28 border=0>&nbsp;&nbsp;<img title="'.$Langue['BTN_RADIO_PAUSE'].'" id="bouton_pause'.$id_panneau.'" onMouseOver="pauseover'.$id_panneau.'()" onMouseOut="pauseout'.$id_panneau.'()" onClick="mettrepause'.$id_panneau.'()" src="images/pause_off2.png" width=29 height=28 border=0></a></div>';
			echo '<script language="Javascript">';
			echo 'var radio_on=0;';
			echo 'parent.radio.location.href="accueil/subpanels/radio_frame.php?radio='.$param[0].'&radio_on=0";';
			echo '</script>';
		}
?>		
  </div>
</div>
<script language="Javascript">
function pauseover<?php echo $id_panneau; ?>()
{
  if (radio_on==1)
	{
	  document.getElementById('bouton_pause<?php echo $id_panneau; ?>').src="images/pause_on.png";
	}
}
function pauseout<?php echo $id_panneau; ?>()
{
  if (radio_on==1)
	{
	  document.getElementById('bouton_pause<?php echo $id_panneau; ?>').src="images/pause_off.png";
	}
}

function playover<?php echo $id_panneau; ?>()
{
  if (radio_on==0)
	{
	  document.getElementById('bouton_play<?php echo $id_panneau; ?>').src="images/play_on.png";
	}
}
function playout<?php echo $id_panneau; ?>()
{
  if (radio_on==0)
	{
	  document.getElementById('bouton_play<?php echo $id_panneau; ?>').src="images/play_off.png";
	}
}

function mettrepause<?php echo $id_panneau; ?>()
{
  if (radio_on==1)
	{
	  parent.radio.location.href="accueil/subpanels/radio_frame.php?radio=<?php echo $param[0]; ?>&radio_on=0";
		radio_on=0;
	  document.getElementById('bouton_pause<?php echo $id_panneau; ?>').src="images/pause_off2.png";
	  document.getElementById('bouton_play<?php echo $id_panneau; ?>').src="images/play_off.png";
	}
}
function lancerlecteur<?php echo $id_panneau; ?>()
{
  if (radio_on==0)
	{
	  parent.radio.location.href="accueil/subpanels/radio_frame.php?radio=<?php echo $param[0]; ?>&radio_on=1";
		radio_on=1;
	  document.getElementById('bouton_pause<?php echo $id_panneau; ?>').src="images/pause_off.png";
	  document.getElementById('bouton_play<?php echo $id_panneau; ?>').src="images/play_off2.png";
	}
}
</script>
