<?php
// The name and description of this module
define("_MI_CATADS_NAME"," Small ads");
define("_MI_CATADS_DESC","Small ads management module");

// xoopsversion config
define("_MI_CATADS_MODERATE","Ads are moderated");
define("_MI_CATADS_ANONCANPOST","Anonymous may post");
define("_MI_CATADS_NBPERPAGE","Ads per page :");
define("_MI_CATADS_NBPERPAGE_ADMIN","Ads per page (administration) :");
define("_MI_CATADS_DISPLAYNEW","Number of new ads on homepage :");
define("_MI_CATADS_BBCODE","Form : with bbcodes ");
define("_MI_CATADS_MAXSIZEIMG","Picture upload : max size (bytes)");
define("_MI_CATADS_MAXSIZEIMG_DESC","0 to prevent upload");
define("_MI_CATADS_MAXHEIGHTIMG","Pictures : max height");
define("_MI_CATADS_MAXWIDTHIMG","Pictures : max width");
define("_MI_CATADS_NBDAYSEXPIRED","Number of days before ad expires when expiry message should be sent");
define("_MI_CATADS_RENEW_NBDAYS","Default renewal period (for email and admin renewals)");

define("_MI_CATADS_AUTO","Send automatic expiry message");
define("_MI_CATADS_AUTO_DESC","Default method is email");

define("_MI_CATADS_PUB_FOOTER","Show banner under ads");
define("_MI_CATADS_PUB_FOOTER_DESC","For example, Google Adsense");
define("_MI_CATADS_PUB_FOOTER_SCRIPT","Insert your banner code in this block");
//define("_MI_CATADS_NBDAY_VALID","Dur&eacute;e des annonces (j)");//v1.3
define("_MI_CATADS_NBPUB_AGAIN","Number of ad renewals allowed");
define("_MI_CATADS_NBDAY_NEW","Ads considered new (in days)");
define("_MI_CATADS_TPLTYPE","Categories layout");
define("_MI_CATADS_COL","Columns");
define("_MI_CATADS_LIN","Lines");
define("_MI_CATADS_NBCOL","Number of category columns");
// 2004/10/24
define("_MI_CATADS_MAXLENTXT","Ad text : max number of characters.");

// pk additional
define("_MI_CATADS_TITLE_LENGTH","Max chars for ad title in list");
define("_MI_CATADS_DESC_LENGTH","Max chars for ad description in list");

//add v1.3
define("_MI_CATADS_NBDAYS_BEFORE","Max delay before publication (days)");
//add v1.4
define("_MI_CATADS_CANEDIT","Allow users to edit ads");
define("_MI_CATADS_SHOW_CARD","View France map in the Homepage");
define("_MI_CATADS_SHOW_CAT_DESC","View category description");
define("_MI_CATADS_SHOW_SEO","Activate URL re-writing");
define("_MI_CATADS_CANDELETE","Allow users to delete ads");
define("_MI_CATADS_EMAIL_REQUIRED","Form : email");
define("_MI_CATADS_REGION_REQUIRED","Form : region");
define("_MI_CATADS_DEPARTEMENT_REQUIRED","Form : country");
define("_MI_CATADS_ZIPCODE_REQUIRED","Form : post code");
define("_MI_CATADS_REQUIRED","Required");
define("_MI_CATADS_OPTIONAL","Optional");
define("_MI_CATADS_NOASK","Not asked");
define("_MI_CATADS_NBCOLS_IMG","Ad : # of images per row");
define("_MI_CATADS_DISP_PSEUDO","Display submitter nickname under ad");
define("_MI_THUMB_WIDTH","Thumbnails width (shown in listings)");
define("_MI_THUMB_METHOD","Resize thumbnails method");

// pk additions
define("_MI_CATADS_ALLOW_CUSTOM_TAGS","Allow custom tags");
define("_MI_CATADS_ALLOW_CUSTOM_TAGS_DESC","If 'no' then tags are auto-created from title");
define("_MI_CATADS_SHOW_AD_TYPE","Show ad-type (e.g. For Sale)");
define("_MI_CATADS_SHOW_AD_TYPE_DESC","Note: to display price or not is a category pref");
define("_MI_CATADS_SHOW_VIDEO_FIELD","Show video field");
define("_MI_CATADS_ALLOW_PUBLISH_DATE","Show publication date options");
define("_MI_CATADS_ALLOW_PUBLISH_DATE_DESC","Allow user to choose a publication date");
define("_MI_CATADS_SHOW_NOTIFICATION_OPTIONS","Show expiry message options");
define("_MI_CATADS_SHOW_NOTIFICATION_OPTIONS_DESC","Allow user to choose email, PM or none");
define("_MI_CLICK_IMAGE_WIDTH","Pop-up image width (shown in advert)");
define("_MI_CATADS_SHOW_CARD_DESC","Important. This will not work without customised region and departement tables");
define("_MI_CATADS_ANONCANPOST_DESC","Note: If you allow anonymous posts in any category this will automatically change to yes, however category permissions are respected");
define("_MI_CATADS_SEO_DESC","This does not yet work in this version of Catads");

// Names of blocks for this module
define("_MI_CATADS_BNAME1","Latest ads");
define("_MI_CATADS_BNAME2","Submit an ad");
define("_MI_CATADS_BNAME3","My ads");
define("_MI_CATADS_BNAME4","Send an email if the ad is due to expire");
define("_MI_CATADS_BNAME5","Search Ads");
// pk contextual ads block
define("_MI_CATADS_BNAME6","Contextual Ads");

//submenu
define("_MI_CATADS_SMENU1","My ads");
define("_MI_CATADS_SMENU2","Submit");
define("_MI_CATADS_SMENU3","Advanced Search");


// Popup
define("_MI_CATADS_ADMENU1", "Index");
define("_MI_CATADS_ADMENU2", "Ad Management");
define("_MI_CATADS_ADMENU3", "Categories management");
define("_MI_CATADS_ADMENU4", "Options management");
define("_MI_CATADS_ADMENU5", "Purge");
define("_MI_CATADS_ADMENU6", "Permissions");

// Text for notifications

define("_MI_CATADS_GLOBAL_NOTIFY", "Global");
define("_MI_CATADS_GLOBAL_NOTIFYDSC", "Global ads notification option.");

define("_MI_CATADS_CATEGORY_NOTIFY", "Category");
define("_MI_CATADS_CATEGORY_NOTIFYDSC", "Notification option for the category.");

define("_MI_CATADS_ADS_NOTIFY", "Ads");
define("_MI_CATADS_ADS_NOTIFYDSC", "Notification option for the current ad.");
// Event 1
define("_MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFY", "Ad submitted");
define("_MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFYCAP", "Notification when there is a new ad submitted (waiting).");
define("_MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFYDSC", "Be notified when a new ad is submitted");
define("_MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New ad");
// Event 2
define("_MI_CATADS_GLOBAL_NEWADS_NOTIFY", "New ad");
define("_MI_CATADS_GLOBAL_NEWADS_NOTIFYCAP", "Notification when there is a new ad published.");
define("_MI_CATADS_GLOBAL_NEWADS_NOTIFYDSC", "Be notified when a new ad is published.");
define("_MI_CATADS_GLOBAL_NEWADS_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New ad");
// Event 2//
define("_MI_CATADS_GLOBAL_EDIT_NOTIFY", "Edit ad");
define("_MI_CATADS_GLOBAL_EDIT_NOTIFYCAP", "Notification when there is a new edit to an ad.");
define("_MI_CATADS_GLOBAL_EDIT_NOTIFYDSC", "Be notified when a new ad is modified.");
define("_MI_CATADS_GLOBAL_EDIT_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : Ad modification");
// Event 3
define("_MI_CATADS_CATEGORY_SUBMIT_NOTIFY", "New ad submitted");
define("_MI_CATADS_CATEGORY_SUBMIT_NOTIFYCAP", "Notification when there is a new ad submitted (waiting).");
define("_MI_CATADS_CATEGORY_SUBMIT_NOTIFYDSC", "Be notified when a new ad is submitted.");
define("_MI_CATADS_CATEGORY_SUBMIT_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New ad");
// Event 4
define("_MI_CATADS_CATEGORY_NEWADS_NOTIFY", "New ad submitted");
define("_MI_CATADS_CATEGORY_NEWADS_NOTIFYCAP", "Notification when there is a new ad in this category.");
define("_MI_CATADS_CATEGORY_NEWADS_NOTIFYDSC", "Be notified when a new ad is published in this category.");
define("_MI_CATADS_CATEGORY_NEWADS_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New ad published");
// Event 5
define("_MI_CATADS_ADS_APPROVE_NOTIFY", "Ad published");
define("_MI_CATADS_ADS_APPROVE_NOTIFYCAP", "Notification when my ad published.");
define("_MI_CATADS_ADS_APPROVE_NOTIFYDSC", "Be notified when my ad is published.");
define("_MI_CATADS_ADS_APPROVE_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : Ad published");
// pk bugfix missing defs
define("_MI_CATADS_ADS_EDIT_NOTIFY", "Ad published");
define("_MI_CATADS_ADS_EDIT_NOTIFYCAP", "Notification when my ad edited.");
define("_MI_CATADS_ADS_EDIT_NOTIFYDSC", "Be notified when my ad is edited.");
define("_MI_CATADS_ADS_EDIT_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : Ad edited");



?>