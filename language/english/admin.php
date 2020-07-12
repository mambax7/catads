<?php

/////////////////////Menu/////////////////////
define("_AM_CATADS_GENERALSET","Module Configuration");
define("_AM_CATADS_GOINDEX","Go to the module");
define("_AM_CATADS_UPGRADE","Upgrade");
define("_AM_CATADS_ABOUT","About this module");

define("_AM_CATADS_PAGE","Pages :");
// About.php
define('_AM_CATADS_AUTHOR_NAME', "Supported by");
define('_AM_CATADS_AUTHOR_WEBSITE', "Support Website");
define('_AM_CATADS_AUTHOR_EMAIL', "Support Email");
define('_AM_CATADS_AUTHOR_CREDITS', "Credits");
define('_AM_CATADS_MODULE_INFO', "Module Info");
define('_AM_CATADS_MODULE_STATUS', "Version");
define('_AM_CATADS_MODULE_DEMO', "Demo website");
define('_AM_CATADS_MODULE_SUPPORT', "Support Official Website");
define('_AM_CATADS_MODULE_BUG', "Report a bug");
define('_AM_CATADS_MODULE_FEATURE', "Suggest a new hack or function to this module");
define('_AM_CATADS_MODULE_DISCLAIMER', "Details and Disclaimer");
define('_AM_CATADS_AUTHOR_WORD', "Module History");
define('_AM_CATADS_BY','Updated by :');

/////////////////////Sous-Menu/////////////////////
define("_AM_CATADS_INDEXMANAGE","Index");
define("_AM_CATADS_ADSMANAGE","Ad Management");
define("_AM_CATADS_CATMANAGE","Category Management");
define("_AM_CATADS_OPTMANAGE","List Box Management");
define("_AM_CATADS_PURGEMANAGE","Purge");
define("_AM_CATADS_PERMISSIONSMANAGE","Permissions");
define("_AM_CATADS_IMPORTMANAGE","Import");



/////////////////////Index.php////////////////
define("_AM_CATADS_RECUPADS","Summary");
define("_AM_CATADS_COUNTADSTOTAL","There are <span style='color: #ff0000; font-weight: bold'>%s</span> ad(s) in our database.");
define("_AM_CATADS_COUNTADSAPPROUVE","There are <span style='color: #ff0000; font-weight: bold'>%s</span> approved ads.");
define("_AM_CATADS_COUNTADSLIGNE","There are <span style='color: #ff0000; font-weight: bold'>%s</span> ads online.");
define("_AM_CATADS_COUNTADSWAIT","There are <span style='color: #ff0000; font-weight: bold'>%s</span> ads waiting approval.");
define("_AM_CATADS_COUNTADSSOONEXPIRE","There are <span style='color: #ff0000; font-weight: bold'>%s</span> ads that will expire soon.");
define("_AM_CATADS_COUNTADSEXPIRE","There are <span style='color: #ff0000; font-weight: bold'>%s</span> expired ads");

define("_AM_CATADS_STOCK","Space Used");
define("_AM_CATADS_LENGTH","file size");
define("_AM_CATADS_NBPICTURE_FILE_ORIGINAL","There are <span style='color: #ff0000; font-weight: bold'>%s</span> images in the folder uploads/catads/images/annonces/original");
define("_AM_CATADS_NBPICTURE_FILE_THUMB","There are <span style='color: #ff0000; font-weight: bold'>%s</span> images in the folder uploads/catads/images/annonces/thumb");
define("_AM_CATADS_NBPICTURE_FILE_CAT","There are <span style='color: #ff0000; font-weight: bold'>%s</span> images in the folder uploads/catads/images/categories");
define("_AM_CATADS_NUMBYTES","Kb");

define("_AM_CATADS_INFOS_STATUTS_IMAGES_TITLE","informations regarding images status:");
define("_AM_CATADS_INFOS_STATUTS_IMAGES","
<div align='left'>
        <img src='../images/icon/en_ligne.gif'> The image was copied correctly or the thumbnail was created correctly.<br /><br />
        <img src='../images/icon/expiree_bientot.gif'>The thumbnail has already been created.<br /><br />
        <img src='../images/icon/expiree.gif'>The image could not be copied or the thumbnail could not be created.<br /><br />
</div>");

define("_AM_CATADS_NOM_IMAGE","image title");
define("_AM_CATADS_CREATION_VIGNETTE","Creating the thumbnail");
define("_AM_CATADS_COPIE_IMAGE","image copy");

//Importer image
define("_AM_CATADS_IMPORT","Import images");
define("_AM_CATADS_IMPORT_INFO","<i>The process may take some time depending on the number of imported pictures <br />
you'll have a balance when importing images is done .</i>");
define("_AM_CATADS_NO_GD_FOUND","It must the library GD");



/////////////////////Ads.php////////////////
define("_AM_CATADS_ADS","Display ads");

define("_AM_CATADS_ALL","All Ads");
define("_AM_CATADS_DISPLAY","Display");
define("_AM_CATADS_SELECT_SORT","Order");

define("_AM_CATADS_SORT_ASC","Ascending");
define("_AM_CATADS_SORT_DESC","Descending");

define("_AM_CATADS_IMAGE","Images");
define("_AM_CATADS_STATUS","Status");
define("_AM_CATADS_TITLE_ADS","Title");
define("_AM_CATADS_AUTHOR","Author");
define("_AM_CATADS_PRICE","Price");
define("_AM_CATADS_DATE","Published on");
define("_AM_CATADS_IP","IP");
define("_AM_CATADS_TITLE_CAT","Category");
define("_AM_CATADS_DESCR","Description");
define("_AM_CATADS_ACTION","Action");

define("_AM_CATADS_WAIT","Waiting ads");
define("_AM_CATADS_SEND_MAIL","the user has been notified that their ad will expire soon");
define("_AM_CATADS_EXP_SOON","Expired Ads");
define("_AM_CATADS_EXP","Expired Ads");
define("_AM_CATADS_PUB","Ads online");
define("_AM_CATADS_WARNING","Problem with category's ad");

define("_AM_CATADS_EDIT","Edit");
define("_AM_CATADS_APPROUVE","Approve");
define("_AM_CATADS_WAIT1","Waiting");
define("_AM_CATADS_RENEW","Renew");
define("_AM_CATADS_DELETE","Delete");
define("_AM_CATADS_APPROVE","Publish");

define("_AM_CATADS_NOMSG","There are no ads");
define("_AM_CATADS_ERRORDEL","Ads cannot be deleted");
define("_AM_CATADS_MSGDELETED","Ads are now deleted");
define("_AM_CATADS_ERRORVALID","Ad status cannot be modified");
define("_AM_CATADS_VALIDATE","Ad status has been modified");
define("_AM_CATADS_DELETE_ADS_USER","Ads by %s are deleted");

//
define("_AM_CATADS_INFOS_STATUTS_ADS_TITLE","Key to status icons:");
define("_AM_CATADS_INFOS_STATUTS_ADS","
<div align='left'>
<table border='0'>
        <tr><td><img src='../images/icon/en_attente.gif'></td><td> The Ad is waiting for admin approval.</td></tr>
        <tr><td><img src='../images/icon/ic16_clockgreen.gif'></td><td> The user has set a custom date for publication.</td></tr>
        <tr><td><img src='../images/icon/en_ligne.gif'></td><td> The Ad is now online.</td></tr>
        <tr><td><img src='../images/icon/expiree_bientot.gif'></td><td> The Ad is due to expire soon.</td></tr>
        <tr><td><img src='../images/icon/letter.png'></td><td> A renewal notification has been sent.</td></tr>
        <tr><td><img src='../images/icon/expiree.gif'></td><td> The Ad has expired, it will not be shown in the user side.</td></tr>
        <tr><td><img src='../images/icon/attention.gif'></td><td> The Ad is not linked to a category (either you deleted a category, or created a new sub-category).</td></tr>
        <tr><td>&nbsp;</td><td>You must edit the ad and choose a new category.</td></tr>
        <tr><td><img src='../images/icon/stop.gif'></td><td> The Ad has been suspended by the user (suspended ads still count as being online).</td></tr>
</table>
</div>");
//

/////////////////////Category.php////////////////
define("_AM_CATADS_MODIFYCATEGORY","Change a category");
define("_AM_CATADS_ADDCATEGORY","Add a category");
define("_AM_CATADS_CATEGORYWEIGHT","Category's weight");
define("_AM_CATADS_CATEGORYNAME","Category's name");
define("_AM_CATADS_MOVETO","Move to");
define("_AM_CATADS_IN","Make this a sub-category of:");
define("_AM_CATADS_CATEDESC","Category description");
define("_AM_CATADS_CATEGORYIMG","Category image");
define("_AM_CATADS_CATEGORY","Category");
define("_AM_CATADS_CAT_DISP","Show category on the home page");
define("_AM_CATADS_PRICE_DISP","Display the price in this category");
define("_AM_CATADS_DB_UPDATED","Database updated");
define("_AM_CATADS_CANNOT_MOVE_HERE","Category cannot be moved here");
define("_AM_CATADS_MUST_NUMBER","Weight has to be a number");
define("_AM_CATADS_CONFDELCAT","WARNING : Are you sure you want to delete this category and all its subcategories and ads?");
define("_AM_CATADS_CAT_DEL","The selected category has been deleted");
define("_AM_CATADS_NBMAX_PHOTO","Number of allowed photos");

define("_AM_CATADS_INFOS_CATS_TITLE","Category Information:");
define("_AM_CATADS_INFOS_CATS","To add additional category images, upload them to: /uploads/catads/images/categories/.<br />
<br /> <i><b>Attention :</b> Do not use images for sub-categories, only for categories.</i>
");


/////////////////////Options.php////////////////
define("_AM_CATADS_CONFDELOPT","Are you sure you want to delete this option ?<br />This won't change published ads");
define("_AM_CATADS_OPT_DEL","The selected option has been deleted");
define("_AM_CATADS_MODIFYOPTION","Modify an option");
define("_AM_CATADS_OPT1","Currency");
define("_AM_CATADS_OPT2","Price option");
define("_AM_CATADS_OPT3","Type of ad");
define("_AM_CATADS_OPT4","Duration");
define("_AM_CATADS_OPT_ADD","Add an option to the list");
define("_AM_CATADS_OPT_EDIT","Edit an option of the list");
define("_AM_CATADS_OPT_IN","In");
define("_AM_CATADS_OPT_TITLE","Title");
define("_AM_CATADS_OPT_ORDER","Weight");
define("_AM_CATADS_MUST_TEXT","The field is empty");


/////////////////////Purge.php////////////////
define("_AM_CATADS_ADS_PURGE_ALL_USER","Delete All Ads");
define("_AM_CATADS_DELEXP3","Delete ALL ads?");
define("_AM_CATADS_DELETE_ADS_ALL_USER","All ads are deleted");

define("_AM_CATADS_ADS_USER","Delete Ads by User");
define("_AM_CATADS_DELEXP1","Delete the expired ads of:");
define("_AM_CATADS_ADS_DELEXP","Delete this user's online ads too?");
define("_AM_CATADS_PURGER","delete");

define("_AM_CATADS_ADS_PURGE","Delete Ads by Date :");
define("_AM_CATADS_DELEXP2","Delete ALL expired ads older than:");
define("_AM_CATADS_DAYS"," day(s)");

define("_AM_CATADS_ADSAPPROVED","The ad has been published");
define("_AM_CATADS_ADSDELETED","The ad has been deleted");
define("_AM_CATADS_CONF_DEL_ALL","<b>Are you sure you want to delete ALL ads?</b><br />");
define("_AM_CATADS_CONF_DEL","Are you sure you want to delete this ad?<br />");
define("_AM_CATADS_CONF_DELEXP","Are you sure you want to delete these ads?<br />");
define("_AM_CATADS_ADSEXPDELETED","%s expired ads have been deleted");
define("_AM_CATADS_DELETE_ADS_NOUSER","You have not selected a user");



/////////////////////Permissions.php////////////////
define("_AM_CATADS_ACCESSCAT","Categories access permissions");
define("_AM_CATADS_SUBMITCAT","Categories submission permissions");

define("_AM_CATADS_ACCESS","Categories access rights");
define("_AM_CATADS_SUBMIT","Categories submission rights");


/////////////////////Adsmod.php////////////////
define("_AM_CATADS_DATE_CREA","Date of creation");
define("_AM_CATADS_DATE_PUB","Date of publication");
define("_AM_CATADS_DATE_EXP","Date of expiration");
define("_AM_CATADS_WAIT2","Waiting since ");
define("_AM_CATADS_PUB2","Published since ");
define("_AM_CATADS_EXP2","Expired since ");
define("_AM_CATADS_TODAY","Today");
define("_AM_CATADS_IMG","Photo");
define("_AM_CATADS_DELIMG","Delete this photo");
define("_AM_CATADS_REPLACEIMG"," <b>or</b> replace it with <br>");
//define("_AM_CATADS_PRICE","Prix");
define("_AM_CATADS_PHONE","Phone");
define("_AM_CATADS_EMAIL","Email");
define("_AM_CATADS_TOWN","City");
define("_AM_CATADS_REGION","Region");
define("_AM_CATADS_DEPARTEMENT","Country");
define("_AM_CATADS_CODPOST","Post Code");
// pk del duplicate line del img (214)
define("_AM_CATADS_PUBADS"," Publish");
define("_AM_CATADS_NOERROR_UPDATE"," The ad has been modified");
define("_AM_CATADS_ERROR_UPDATE"," Update failed");
define("_AM_CATADS_FORMEDIT","Ad");
define("_AM_CATADS_CONTACT","Contact");
define("_AM_CATADS_ADS_FROM","Ad from");
define("_AM_CATADS_DELAY_PUB","to be shown ");
define("_AM_CATADS_TO","in ");


/////////////////////Autres////////////////
define("_AM_CATADS_CONFIG","Module Configuration");
//define("_AM_CATADS_PURGE","Purge des annonces");
define("_AM_CATADS_ADSMANAGER","Ads Management");
define("_AM_CATADS_NEXT","Ads to appear");

define("_AM_CATADS_NOCHANGE","there is no modification");
define("_AM_CATADS_WARNING_UPGRADE","<font color='#FF0000'><b>Attention : </b>make a backup of your tables before the update </font>");
define("_AM_CATADS_TABLE","Table : ");
define("_AM_CATADS_FIELD","field : ");
define("_AM_CATADS_ADDED","Add; on v ");
define("_AM_CATADS_DELETED","delete; on v ");

define("_AM_CATADS_ERROR","Error : ");
define("_AM_CATADS_NOERROR","No error : ");

/////////////////////Import.php////////////////
define("_AM_CATADS_IMPORT_TYPE_PRICE1","Exact price");
define("_AM_CATADS_IMPORT_TYPE_PRICE2","Surrounding");

define("_AM_CATADS_IMPORT_WARNING","<span style='font-size:16px; font-weight:bold'>Attention!</span>
        <br><br>This feature is designed to import data from the Classifieds module into a new Catads installation.
        <br>Be warned that importing will <b>DELETE</b> any existing ads, categories and options in Catads.");
define("_AM_CATADS_IMPORT_NUMBER","data to be imported");
define("_AM_CATADS_IMPORT_DONT_ADS","there is no data to be imported");
define("_AM_CATADS_IMPORT_DONT_CAT","there is no categories to be imported");
define("_AM_CATADS_IMPORT_DONT_OPTIONS","there is no options to be imported");
define("_AM_CATADS_IMPORT_THERE_IS","there is ");
define("_AM_CATADS_IMPORT_ADS1"," ads to be imported");
define("_AM_CATADS_IMPORT_CAT1"," categories to be imported");
define("_AM_CATADS_IMPORT_OPTIONS1"," options to be imported");
define("_AM_CATADS_IMPORT1","Import");
define("_AM_CATADS_IMPORT_ADS","Step one: Import ad data");
define("_AM_CATADS_IMPORT_PICTURE","Step two: Import ad images");
define("_AM_CATADS_IMPORT_CONF_DATA","Are you sure you want to import data from the Classifieds module to Catads?");
define("_AM_CATADS_IMPORT_CONF_PICTURE","Are you sure you want to import images from the Classifieds module to Catads?");
define("_AM_CATADS_IMPORT_ERROR_DATA","Error while importing data");
define("_AM_CATADS_IMPORT_ERROR_PICTURE","Error while importing images");
define("_AM_CATADS_IMPORT_OK_DATA","Data import has been successfully done");
define("_AM_CATADS_IMPORT_OK_PICTURE","Image import has been successfully done");
// video et tags
define("_AM_CATADS_VIDEO","Add a video");
// pk video embed instructions
define("_AM_CATADS_VIDEO_HELP_TITLE", "Example video links");
define("_AM_CATADS_VIDEO_HELP","YouTube: http://www.youtube.com/watch?v=lvNVjyO1C6M<br />DailyMotion: use permalink");

define("_AM_CATADS_TAGS","Add Tags");
?>