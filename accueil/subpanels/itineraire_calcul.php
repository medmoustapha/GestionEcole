<?php
  session_start();

  include "../../langues/fr-FR/commun.php";
  include "../../langues/fr-FR/accueil.php";
  foreach ($Langue_Module AS $cle => $value)
  {
	$Langue[$cle]=$Langue_Module[$cle];
  }
  if ($_SESSION['language_application']!="fr-FR")
  {
	if (file_exists("../../langues/".$_SESSION['language_application']."/accueil.php"))
	{
	  include "../../langues/".$_SESSION['language_application']."/commun.php";
	}
	if (file_exists("../../langues/".$_SESSION['language_application']."/accueil.php"))
	{
	  include "../../langues/".$_SESSION['language_application']."/accueil.php";
	  foreach ($Langue_Module AS $cle => $value)
	  {
		$Langue[$cle]=$Langue_Module[$cle];
	  }
	}
  }
  
  $gauche="left"; $droite="right";
  if ($Sens_Ecriture=="rtl") { $gauche="right"; $droite="left"; }
?>
<!DOCTYPE html>
<head dir="<?php echo $Sens_Ecriture; ?>" lang="<?php echo $Langue_Valeur; ?>">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Gest'Ecole - Calcul d'itinéraire</title>
    <style type="text/css">
      body { font-family: Arial, Verdana, sans serif; font-size: 12px; margin: 2px; }
      table.iti { background-color: #FFFFFF; font-size: 12px; margin: 4px; }
      table.directions th { background-color:#EEEEEE; font-size: 15px; padding:5px; }
      table.directions td { font-size: 12px; padding:0px;}
      img { color: #000000; }
      #map_canvas { width: 446px; height: 400px; border: 1px solid #333333 ; margin: 0px; padding:0px;}
      #directions { text-align: <?php echo $gauche; ?>; font-size: 12px; padding:0px; margin: 0px; }
    </style>
    <style type="text/css" media="print">
      .print { display:none; }
      #directions { width: 600px; height: auto; border: 1px solid #333333; margin: 2px; text-align: <?php echo $gauche; ?>; font-size: 9px; }
    </style>
	<script src=" http://maps.google.com/?file=api&amp;v=2.x&amp;key=ABQIAAAAFw0lDRKsdCra15LPvIxvehRjMgnVhZHmNh95BVtUkp0XgfXLlhSXfnSAP-eiQqfEOGaImc_V_dSQGQ" type="text/javascript"></script>
    <script type="text/javascript">
      var map;
      var gdir;
      var geocoder = null;
      var addressMarker;
      function initialize()
        {
        if (GBrowserIsCompatible())
          {      
          map = new GMap2(document.getElementById("map_canvas"));
          gdir = new GDirections(map, document.getElementById("directions"));
          GEvent.addListener(gdir, "load", onGDirectionsLoad); <!-- Charge la partie pour les distances -->
          GEvent.addListener(gdir, "error", handleErrors); <!-- Charge la partie pour les messages d erreurs -->
          map.setCenter(new GLatLng(46.98025, 3.66943), 6);
          map.addControl(new GMapTypeControl());
          map.addControl(new GLargeMapControl());
          map.addControl(new GOverviewMapControl());
          map.addControl(new GScaleControl());
          map.enableScrollWheelZoom();
                    }
        }
      function setDirections(fromAddress, toAddress, locale)
        {
        gdir.load("from: " + fromAddress + " to: " + toAddress, { "locale": locale });
        }
      function handleErrors()
        {
        if (gdir.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
          alert("<?php echo $Langue['ERR_ITINERAIRE_1']; ?>\n<?php echo $Langue['ERR_ITINERAIRE_2']; ?>\n<?php echo $Langue['ERR_ITINERAIRE_3']; ?>\nError code: " + gdir.getStatus().code);
        else if (gdir.getStatus().code == G_GEO_SERVER_ERROR)
          alert("<?php echo $Langue['ERR_ITINERAIRE_4']; ?>\n Error code: " + gdir.getStatus().code);
        else if (gdir.getStatus().code == G_GEO_MISSING_QUERY)
          alert("<?php echo $Langue['ERR_ITINERAIRE_5']; ?>\n Error code: " + gdir.getStatus().code);
        else if (gdir.getStatus().code == G_GEO_BAD_KEY)
          alert("<?php echo $Langue['ERR_ITINERAIRE_6']; ?>\n Error code: " + gdir.getStatus().code);
        else if (gdir.getStatus().code == G_GEO_BAD_REQUEST)
          alert("<?php echo $Langue['ERR_ITINERAIRE_7']; ?>\n Error code: " + gdir.getStatus().code);
        else alert("<?php echo $Langue['ERR_ITINERAIRE_8']; ?>");
        }
      function onGDirectionsLoad()
	    {
        var reg=new RegExp("&nbsp;", "g");
        }
    </script>
</head>	
<BODY dir="<?php echo $Sens_Ecriture; ?>" BGCOLOR="#FFFFFF" TEXT="#000000" onLoad="initialize();setDirections('<?php echo $_GET['from']; ?>','<?php echo $_GET['to']; ?>','<?php echo $Langue_Valeur; ?>');" onUnload="GUnload()">
<table width="100%" border="0" class="directions" cellspacing="1" cellpadding="0">
<tr>
  <th><?php echo $Langue['LBL_ITINERAIRE_DETAIL']; ?></th>
  <th width="446" class="print"><?php echo $Langue['LBL_ITINERAIRE_CARTE']; ?></th>
</tr>
<tr>
  <td><div id="directions"></div></td>
  <td valign="top" align="center" style="padding-top:10px"><div id="map_canvas" class="print"></div></td>
</tr>
</table>
</body>
</html>