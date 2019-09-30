<script language="Javascript">
/*********************************/
/* Fonctions JavaScript standard */
/*********************************/

    action_save="rien";

    /* Fonction qui convertir une date et une heure au format initial d/m/Y H:i */
    function Convertir_Date_Heure(strdate)
    {
      day = strdate.substring(0,2);
      month = strdate.substring(3,5);
	    year = strdate.substring(6,10);
	    heure = strdate.substring(11,13);
	    minute = strdate.substring(14,16);
	    d = new Date();
	    d.setFullYear(year);
	    d.setDate(day);
	    d.setMonth(month);
	    d.setHours(heure);
	    d.setMinutes(minute);
	    return d;
    }

    /* Fonction qui compare deux heures (les dates doivent être données au format d/m/Y H:i */
    function Compare_Heure(date1,date2)
    {
      d1=Convertir_Date_Heure(date1);
      d2=Convertir_Date_Heure(date2);

      DureeDebut = Date.parse(d1);
      DureeFin = Date.parse(d2);

      iComparaison= DureeFin - DureeDebut;

      return iComparaison;
    }

    /* Fonction qui convertit une date au format initial d/m/Y */
    function Convertir_Date_Anglaise(strdate,format_date)
    {
	  switch (format_date)
	  {
	    case "d/m/Y":
	    case "d-m-Y":
	    case "d.m.Y":
			day = strdate.substring(0,2);
			month = strdate.substring(3,5);
			year = strdate.substring(6,10);
			break;
	    case "d/Y/m":
	    case "d-Y-m":
	    case "d.Y.m":
			day = strdate.substring(0,2);
			month = strdate.substring(8,10);
			year = strdate.substring(3,7);
			break;
	    case "m/d/Y":
	    case "m-d-Y":
	    case "m.d.Y":
			day = strdate.substring(3,5);
			month = strdate.substring(0,2);
			year = strdate.substring(6,10);
			break;
	    case "m/Y/d":
	    case "m-Y-d":
	    case "m.Y.d":
			day = strdate.substring(8,10);
			month = strdate.substring(0,2);
			year = strdate.substring(3,7);
			break;
	    case "Y/d/m":
	    case "Y-d-m":
	    case "Y.d.m":
			day = strdate.substring(5,7);
			month = strdate.substring(8,10);
			year = strdate.substring(0,4);
			break;
	    case "Y/m/d":
	    case "Y-m-d":
	    case "Y.m.d":
			day = strdate.substring(8,10);
			month = strdate.substring(5,7);
			year = strdate.substring(0,4);
			break;
	  }
	    d = new Date(year,month-1,day);
	    return d;
    }

    /* Ajouter un jour à la date sélectionnée */
    function Ajouter_Un_Jour(strdate,format_date)
    {
	  switch (format_date)
	  {
	    case "d/m/Y":
	    case "d-m-Y":
	    case "d.m.Y":
			day = strdate.substring(0,2);
			month = strdate.substring(3,5);
			year = strdate.substring(6,10);
			break;
	    case "d/Y/m":
	    case "d-Y-m":
	    case "d.Y.m":
			day = strdate.substring(0,2);
			month = strdate.substring(8,10);
			year = strdate.substring(3,7);
			break;
	    case "m/d/Y":
	    case "m-d-Y":
	    case "m.d.Y":
			day = strdate.substring(3,5);
			month = strdate.substring(0,2);
			year = strdate.substring(6,10);
			break;
	    case "m/Y/d":
	    case "m-Y-d":
	    case "m.Y.d":
			day = strdate.substring(8,10);
			month = strdate.substring(0,2);
			year = strdate.substring(3,7);
			break;
	    case "Y/d/m":
	    case "Y-d-m":
	    case "Y.d.m":
			day = strdate.substring(5,7);
			month = strdate.substring(8,10);
			year = strdate.substring(0,4);
			break;
	    case "Y/m/d":
	    case "Y-m-d":
	    case "Y.m.d":
			day = strdate.substring(8,10);
			month = strdate.substring(5,7);
			year = strdate.substring(0,4);
			break;
	  }
      d = new Date(year,month-1,eval(day)+1);
	  jour=d.getDate();
	  if (jour<=9) { jour="0"+jour; }
	  mois=d.getMonth()+1;
	  if (mois<=9) { mois="0"+mois; }
	  annee=d.getFullYear();
	  date_nouvelle=format_date.replace('d',jour);
	  date_nouvelle=date_nouvelle.replace('m',mois);
	  date_nouvelle=date_nouvelle.replace('Y',annee);
	  return date_nouvelle;
    }

    /* Fonction qui compare deux dates */
    function Compare_Date(date1,date2,format_date)
    {
      d1=Convertir_Date_Anglaise(date1,format_date);
      d2=Convertir_Date_Anglaise(date2,format_date);
      DureeDebut = Date.parse(d1);
      DureeFin = Date.parse(d2);
      iComparaison= DureeFin - DureeDebut;

      return iComparaison;
    }

    /* Fonction qui permet d'afficher un message dans une fenêtre modale */
	function updateTips(type,module,titre)
    {
  	  switch(type)
  	  {
        case "error":
					$("#dialog-form").scrollTop(0);
					$("#msg_ok").fadeIn( 1000 );
					$("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
					break;
        case "success":
          Message_Chargement(2,1);
          break;
        case "save":
          Message_Chargement(2,1);
          break;
        case "envoi":
          Message_Chargement(10,1);
          break;
      }
      if (type!="save")
      {
        if (type=="error")
        {
					setTimeout(function()
          {
            $("#msg_ok").effect("blind",1000);
          }, 3000 );
        }
        else
        {
          switch (action_save)
          {
            case "nouveau":
              Message_Chargement(1,0);
              action_save="rien";
              Charge_Dialog("index2.php?module="+module+"&action=editview",titre);
              break;
            case "fermer":
              Message_Chargement(1,0);
              action_save="rien";
              $("#dialog-form").dialog( "close" );
              break;
            case "detail":
              action_save="rien";
              Charge_Dialog("index2.php?module="+module+"&action=detailview&id="+$("#id").val(),titre);
              break;
            case "edit":
              action_save="rien";
              Charge_Dialog("index2.php?module="+module+"&action=editview&id="+$("#id").val(),titre);
              break;
						default:
              Charge_Dialog("index2.php?module="+module+"&action="+action_save+"&id="+$("#id").val(),titre);
              action_save="rien";
              break;
          }
				}
  	  }
	};

    /* Fonction qui vérifie qu'une valeur est complétée */
    function checkValue(o)
    {
      if (o.val()=="")
      {
        o.addClass( "ui-state-error" );
				return false;
			}
      else
      {
				o.removeClass( "ui-state-error" );
				return true;
			}
		};

    /* Fonction qui vérifie qu'une valeur est complétée selon un certain format */
		function checkRegexp( o, regexp)
    {
			if ( !( regexp.test( o.val() ) ) )
      {
				o.addClass( "ui-state-error" );
				return false;
			}
      else
      {
				o.removeClass( "ui-state-error" );
				return true;
			}
		};

    /* Fonction qui permet de charger une fenêtre DetailView ou EditView */
    function Charge_Dialog(url,titre)
    {
        Message_Chargement(1,1);
        $("#dialog-form").load(url,function()
        {
	        $(this).dialog(
          {
  	        autoOpen: false,
  		      height: 500,
	  	      width: 950,
		        modal: true,
		        title: titre,
		        draggable: false,
		        resizable: false,
		        zIndex:1000,
		        close: function() { $("#creer-element").button({ disabled: false }); },
          });
	        $(this).dialog( "open" );
          Message_Chargement(1,0);
		    });
    }

    /* Fonction qui permet de charger une fenêtre DetailView ou EditView */
    function Charge_Dialog2(url,titre)
    {
        Message_Chargement(1,1);
        $("#dialog-niveau2").load(url,function()
        {
	        $(this).dialog(
          {
  	        autoOpen: false,
  		      height: 360,
	  	      width: 600,
		        modal: true,
		        title: titre,
		        draggable: false,
		        resizable: false,
		        zIndex:2000,
          });
	        $(this).dialog( "open" );
          Message_Chargement(1,0);
		    });
    }

    /* Fonction qui permet de charger une fenêtre DetailView ou EditView */
    function Charge_Dialog3(url,titre)
    {
        Message_Chargement(1,1);
        $("#dialog-niveau2").load(url,function()
        {
	        $(this).dialog(
          {
  	        autoOpen: false,
  		      height: 500,
	  	      width: 950,
		        modal: true,
		        title: titre,
		        draggable: false,
		        resizable: false,
		        zIndex:2000,
          });
	        $(this).dialog( "open" );
          Message_Chargement(1,0);
		    });
    }

    /* Fonction qui affiche ou cache le message de chargement ou de sauvegarde */
    function Message_Chargement(texte,etat)
    {
      if (etat==1)
      {
        switch (texte)
        {
          case 1: texte2="<?php echo $Langue['MSG_CHARGEMENT']; ?>"; break;
          case 2: texte2="<?php echo $Langue['MSG_SAUVEGARDE']; ?>"; break;
          case 3: texte2="<?php echo $Langue['MSG_IMPRESSION']; ?>"; break;
          case 4: texte2="<?php echo $Langue['MSG_SUPPRESSION']; ?>"; break;
          case 5: texte2="<?php echo $Langue['MSG_CREATION_LISTE']; ?>"; break;
          case 6: texte2="<?php echo $Langue['MSG_MISE_A_JOUR']; ?>"; break;
          case 7: texte2="<?php echo $Langue['MSG_DECONNEXION']; ?>"; break;
          case 8: texte2="<?php echo $Langue['MSG_ENREGISTREMENT']; ?>"; break;
          case 9: texte2="<?php echo $Langue['MSG_VERIFICATION']; ?>"; break;
          case 10: texte2="<?php echo $Langue['MSG_ENVOI']; ?>"; break;
          case 11: texte2="<?php echo $Langue['MSG_VIDAGE_CACHE']; ?>"; break;
          case 12: texte2="<?php echo $Langue['MSG_VIDAGE_CACHE2']; ?>"; break;
        }
        $("#message").text(texte2);
        decalage = (window.pageYOffset)?(window.pageYOffset):(document.documentElement)?document.documentElement.scrollTop:document.body.scrollTop;
        decalage=decalage+15;
        $("#message").css({'visibility':'visible', 'left':'<?php echo $_SESSION['largeur_ecran_demi']; ?>px', 'top':decalage});
      }
      else
      {
        $("#message").css({'visibility':'hidden'});
      }
    }

/********************/
/* Fonctions jQuery */
/********************/

$(document).ready(function()
{
    /* Fonction en cas de changement de thème */
	$( "#theme_choisi" ).change(function()
    {
      <?php if (!isset($_SESSION['id_util'])) { ?>
        var selected = 0;
      <?php } else { ?>
        var selected = $("#tabs").tabs('option', 'selected');
      <?php } ?>
      Message_Chargement(1,1);
      document.location.href="users/change_theme.php?tab_en_cours="+selected+"&theme_choisi="+$("#theme_choisi").val();
    });

	$( "#langue_choisi" ).change(function()
    {
      <?php if (!isset($_SESSION['id_util'])) { ?>
        var selected = 0;
      <?php } else { ?>
        var selected = $("#tabs").tabs('option', 'selected');
      <?php } ?>
      Message_Chargement(1,1);
      document.location.href="users/change_langue.php?tab_en_cours="+selected+"&langue_choisi="+$("#langue_choisi").val();
    });
});


/***************************/
/* Détection du navigateur */
/***************************/

var BrowserDetect = {
	init: function () {
		this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
		this.version = this.searchVersion(navigator.userAgent)
			|| this.searchVersion(navigator.appVersion)
			|| "an unknown version";
		this.OS = this.searchString(this.dataOS) || "an unknown OS";
	},
	searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	searchVersion: function (dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index == -1) return;
		return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
	},
	dataBrowser: [
		{
			string: navigator.userAgent,
			subString: "Chrome",
			identity: "Chrome"
		},
		{ 	string: navigator.userAgent,
			subString: "OmniWeb",
			versionSearch: "OmniWeb/",
			identity: "OmniWeb"
		},
		{
			string: navigator.vendor,
			subString: "Apple",
			identity: "Safari",
			versionSearch: "Version"
		},
		{
			prop: window.opera,
			identity: "Opera",
			versionSearch: "Version"
		},
		{
			string: navigator.vendor,
			subString: "iCab",
			identity: "iCab"
		},
		{
			string: navigator.vendor,
			subString: "KDE",
			identity: "Konqueror"
		},
		{
			string: navigator.userAgent,
			subString: "Firefox",
			identity: "Firefox"
		},
		{
			string: navigator.vendor,
			subString: "Camino",
			identity: "Camino"
		},
		{		// for newer Netscapes (6+)
			string: navigator.userAgent,
			subString: "Netscape",
			identity: "Netscape"
		},
		{
			string: navigator.userAgent,
			subString: "MSIE",
			identity: "Explorer",
			versionSearch: "MSIE"
		},
		{
			string: navigator.userAgent,
			subString: "Gecko",
			identity: "Mozilla",
			versionSearch: "rv"
		},
		{ 		// for older Netscapes (4-)
			string: navigator.userAgent,
			subString: "Mozilla",
			identity: "Netscape",
			versionSearch: "Mozilla"
		}
	],
	dataOS : [
		{
			string: navigator.platform,
			subString: "Win",
			identity: "Windows"
		},
		{
			string: navigator.platform,
			subString: "Mac",
			identity: "Mac"
		},
		{
			   string: navigator.userAgent,
			   subString: "iPhone",
			   identity: "iPhone/iPod"
	    },
		{
			string: navigator.platform,
			subString: "Linux",
			identity: "Linux"
		}
	]

};

</script>
