<div>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="715" height="375" id="videoPlayer" align="middle">
		<param name="allowScriptAccess" value="always" />
		<param name="allowFullScreen" value="true" />
		<param name="flashvars" value="id_personne=<?php echo $_GET['id_personne']; ?>&module=compte&titre=<?php echo $Langue['LBL_PHOTO_TITRE']; ?>" />
		<param name="movie" value="commun/webcam/webcam.swf" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#FFFFFF" />
		<embed src="commun/webcam/webcam.swf" quality="high" bgcolor="#ffffff" width="715" height="375" flashvars="id_personne=<?php echo $_GET['id_personne']; ?>&module=compte&titre=<?php echo $Langue['LBL_PHOTO_TITRE']; ?>&bouton1=<?php echo $Langue['BTN_PHOTO_WEBCAM_CAPTURER']; ?>&bouton2=<?php echo $Langue['BTN_PHOTO_WEBCAM_SAUVEGARDER']; ?>" name="videoPlayer" align="middle" allowScriptAccess="always" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
	</object>
</div>
<br>
<p class="marge60_gauche explic textgauche"><?php echo $Langue['EXPL_PHOTO1']; ?></p>
<p class="marge60_gauche explic textgauche"><?php echo $Langue['EXPL_PHOTO2']; ?></p>
<p class="marge60_gauche explic textgauche"><?php echo $Langue['EXPL_PHOTO3']; ?></p>

