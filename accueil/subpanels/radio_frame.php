<?php
  session_start();
  include "../../langues/fr-FR/config.php";
  if ($_SESSION['language_application']!="fr-FR")
  {
		if (file_exists("../../langues/".$_SESSION['language_application']."/config.php"))
		{
			include "../../langues/".$_SESSION['language_application']."/config.php";
		}
  }
?>

<OBJECT id="lecteur_radio" classid="CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6" id="WindowsMediaPlayer1" width="260" height="60" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Chargement..." type="application/x-oleobject">
<PARAM name="URL" value="<?php echo $liste_choix['radio_stream'][$_GET['radio']]; ?>">
<PARAM name="rate" value="1">
<PARAM name="autoStart" value="<?php echo $_GET['radio_on']; ?>">
<PARAM name="volume" value="50">
<embed src="<?php echo $liste_choix['radio_stream'][$_GET['radio']]; ?>" width="260" height="60" autostart="<?php echo $_GET['radio_on']; ?>" type="application/x-mplayer2" showcontrols="1" showstatusbar="0" showpositioncontrols="0" showaudiocontrols="1" border="0"> </embed>
</object>
