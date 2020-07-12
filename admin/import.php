<?php


include_once( "admin_header.php" );
include_once '../include/functions.php';
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/admin/functions.php";
//Class
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");

xoops_cp_header();
catads_admin_menu(6, _AM_CATADS_IMPORTMANAGE);
$myts =& MyTextSanitizer::getInstance();

//Action dans switch
        if (isset($_GET['op']))
                $op = $_GET['op'];
        elseif (isset($_POST['op']))
                $op = $_POST['op'];
        else
                $op = 'show_form_import';

/*
 *Importer des données
 */
 function ImportData()
 {
         $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

        global $xoopsDB, $myts;

        if ( $ok == 1 )
        {

                //Vider les 3 tables
                $query=$xoopsDB->queryF("truncate table ".$xoopsDB->prefix("catads_ads"));
                $query=$xoopsDB->queryF("truncate table ".$xoopsDB->prefix("catads_cat"));
                $query=$xoopsDB->queryF("truncate table ".$xoopsDB->prefix("catads_options"));

                //Inserer les donnees des annonces
                $query_ads=$xoopsDB->query("SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, date, email, submitter, usid, town, country, contactby, photo, photo2, photo3, hits FROM ".$xoopsDB->prefix("classifieds_listing"));
                //$fecha = time()-1;
                while ($donnees=$xoopsDB->fetchArray($query_ads))
                {
                        //Type
                        //Statuts
                        // pk replace with data from the record.
                        //if ( $donnees['typeprice'] == _AM_CATADS_IMPORT_TYPE_PRICE1 ){
                        //        $typeprice = _AM_CATADS_IMPORT_TYPE_PRICE1;
                        //} else {
                        //        $typeprice = _AM_CATADS_IMPORT_TYPE_PRICE2;
                        //}

                        $typeprice = $donnees['typeprice'] ;

                        //Images
                        $i = 0;
                        $photo = array();
                        $query = $xoopsDB->query("SELECT url FROM ".$xoopsDB->prefix("classifieds_pictures")." WHERE lid = ".$donnees['lid']."");
                        while(list($image) = $xoopsDB->fetchRow($query))
                        {
                                $image = explode('_',$image);
                                $photo[$i] = "img_".$image[2];
                                $i++;
                                //echo "".$donnees['lid']." = ".$i."<br>";
                        }

                        isset($photo[0]) ? $photo0 = $photo[0] : $photo0 = '';
                        isset($photo[1]) ? $photo1 = $photo[1] : $photo1 = '';
                        isset($photo[2]) ? $photo2 = $photo[2] : $photo2 = '';
                        isset($photo[3]) ? $photo3 = $photo[3] : $photo3 = '';
                        isset($photo[4]) ? $photo4 = $photo[4] : $photo4 = '';
                        isset($photo[5]) ? $photo5 = $photo[5] : $photo5 = '';
                        //echo "Type Price : ".$typeprice."<br>";
                        //Expired
                        $expire = $donnees['date'] + ( $donnees['expire'] * 86400);
                        $title = $myts->htmlSpecialChars($myts->stripSlashesGPC($donnees['title']));
                        $desctext = $myts->htmlSpecialChars($myts->stripSlashesGPC($donnees['desctext']));
                        //$desctext = utf8_decode($donnees['desctext']);

                        $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_ads")." (
                        ads_id, cat_id, ads_title, ads_type, ads_desc, price, monnaie, price_option, email, uid, phone, pays, region, departement,  town,  codpost, created, published, expired, expired_mail_send, view, notify_pub, poster_ip, contact_mode, countpub, suspend, waiting, photo0, photo1, photo2, photo3, photo4, photo5, thumb) VALUES ('".$donnees['lid']."','".$donnees['cid']."','".$title."','".$donnees['type']."','".$desctext."','".$donnees['price']."','Euros', '$typeprice','".$donnees['email']."','".$donnees['usid']."','".$donnees['tel']."','FRANCE', '00', '00','".$donnees['town']."','','".$donnees['date']."','".$donnees['date']."','".$expire."', '1', '".$donnees['hits']."', '1', '0', '0', '0', '0', '".$donnees['status']."', '".$photo0."','".$photo1."','".$photo2."', '".$photo3."','".$photo4."','".$photo5."','')");

                        if (!$insert) {
                                echo "<font color='red'>Error: #".$donnees['lid']."</font><br>".$donnees['title']."<br>";
                                $messagesent = _AM_CATADS_IMPORT_ERROR_DATA;
                        }
                }



                //Inserer les donnees des categories - imported :-)
                $query_cat=$xoopsDB->query("SELECT cid, pid, title, cat_desc, img, ordre, affprice FROM ".$xoopsDB->prefix("classifieds_categories"));

                while ($donnees=$xoopsDB->fetchArray($query_cat))
                {
                        if ( $donnees['img'] == "default.gif" )
                        {
                                $img = "blank.gif";
                        }
                        $title = $myts->htmlSpecialChars($myts->stripSlashesGPC($donnees['title']));

                        $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_cat")." (topic_id, topic_pid, topic_title, img, display_cat, weight, display_price, nb_photo ) VALUES ('".$donnees['cid']."','".$donnees['pid']."','".$title."','".$img."','1','".$donnees['ordre']."','".$donnees['affprice']."','6')");
                        if (!$insert) {
                                echo "<font color='red'>Error: #".$donnees['cid']."</font><br>".$donnees['title']."<br>";
                                $messagesent = _AM_CATADS_IMPORT_ERROR_DATA;
                        }
                }


                //Inserer les donnees des options

                // SET DEFAULT CURRENCY HERE - why? - because Classifieds simply uses a currency symbol, Catads needs a currency definition. Set Euros as default. Change here if required.
                $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('1','1','Euros','0')");


                // SET DEFAULT AD-DURATION HERE - why? - because it's not an option in Classifieds the same way as Catads. Set 30 days as default. Change here if required.
                $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('6','4','30','0')");


                //Option prix
                //$insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('2','2','Minimum','0')");
                //$insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('3','2','Maximum','0')");
                //$insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('4','2','Environ','0')");
                //$insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('5','2','Ferme','0')");


                // pk price options - now imported
                $query_type=$xoopsDB->query("SELECT id_price, nom_price FROM ".$xoopsDB->prefix("classifieds_price"));
                while ($donnees=$xoopsDB->fetchArray($query_type))
                {
                        $price_type = $myts->htmlSpecialChars($myts->stripSlashesGPC($donnees['nom_price']));
                        $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('','2','".$price_type."','0')");

                        if (!$insert)
                        {
                                echo "<font color='red'>Error: #".$donnees['cid']."</font><br>".$donnees['title']."<br>";
                                $messagesent = _AM_CATADS_IMPORT_ERROR_DATA;
                        } else {
                                $messagesent = _AM_CATADS_IMPORT_OK_DATA;
                        }
                }



                //Type d'annonce catads
                /*$insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('7','3','A louer','0')");
                $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('8','3','A vendre','0')");
                $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('9','3','Acheter','0')");
                $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('10','3','Echange','0')");
                $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('11','3','Recherche','0')");*/


                //Insertion de differents types d'annonces - imported :-)
                $query_type=$xoopsDB->query("SELECT id_type, nom_type FROM ".$xoopsDB->prefix("classifieds_type"));

                while ($donnees=$xoopsDB->fetchArray($query_type))
                {
                        $nom_type = $myts->htmlSpecialChars($myts->stripSlashesGPC($donnees['nom_type']));
                        $insert = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_options")." (option_id, option_type, option_desc, option_order) VALUES ('','3','".$nom_type."','0')");

                        if (!$insert)
                        {
                                echo "<font color='red'>Error: #".$donnees['cid']."</font><br>".$donnees['title']."<br>";
                                $messagesent = _AM_CATADS_IMPORT_ERROR_DATA;
                        } else {
                                $messagesent = _AM_CATADS_IMPORT_OK_DATA;
                        }
                }
        redirect_header("import.php", 2, $messagesent);

        } else {
                xoops_cp_header();
                xoops_confirm(array('op' => 'import_data', 'ok' => 1), 'import.php', _AM_CATADS_IMPORT_CONF_DATA);
                xoops_cp_footer();
        }
}

//Importation des images des annonces du dossier modules/classifieds/photo vers uploads/catads/images/annonces/original
function ImportImages()
{
        global $xoopsDB, $xoopsModuleConfig, $xoopsModule;

                //Copie des images des annonces et creation de thumb de photo0
                $dirup = XOOPS_ROOT_PATH . "/modules/classifieds/photo/";
                $racine=opendir($dirup);

                echo "<table width='100%' cellspacing='1' class='outer'>
                                <tr class='bg3'>
                                        <td align='center'>"._AM_CATADS_NOM_IMAGE."</td>
                                        <td align='center'>"._AM_CATADS_CREATION_VIGNETTE."</td>
                                        <td align='center'>"._AM_CATADS_COPIE_IMAGE."</td>
                                </tr>";

                 while($dossier=@readdir($racine))
                        {
                                if(!in_array($dossier, Array("..", ".")))
                                {
                                        $fileCopy = XOOPS_ROOT_PATH . "/modules/classifieds/photo/".$dossier;
                                        $query = "SELECT thumb FROM ".$xoopsDB->prefix("catads_ads")." WHERE photo0='".$dossier."'";
                                        //echo "fichier source = ".$fileCopy."<br />";
                                        $result = $xoopsDB->queryF($query);
                                        $data = $xoopsDB->fetchArray($result);

                                        $image = explode('_',$dossier);

                                        //Insertion dans le fichier uploads/catads/images/thumb
                                        if(copy($fileCopy, XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original/".$dossier."")) $copy = 1;
                                        $img = "img_".$image[2];
                                        rename( XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original/".$dossier."" , XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original/".$img."" ) ;

                                        if ( $data['thumb'] == '' )
                                        {
                                                $thumb = "thumb_".$image[2];
                                                $thumb_dir = XOOPS_ROOT_PATH . "/uploads/catads/images/annonces/thumb/".$thumb;

                                                if (!file_exists($thumb_dir))
                                                {
                                                        if( resize_image($fileCopy, $thumb_dir, $xoopsModuleConfig['thumb_width'], $xoopsModuleConfig['thumb_method']) )
                                                        {
                                                                $resize = 1;
                                                        } else {
                                                                $resize = 0;
                                                        }
                                                }
                                                /*echo "Thumb = ".$thumb."<br>";
                                                echo "Dossier = ".$dossier."<br>";*/
                                                $sql = "UPDATE ". $xoopsDB->prefix('catads_ads')." SET thumb = '".$thumb."' WHERE photo0 = '".$img."'";
                                                $result = $xoopsDB->queryF($sql);
                                        }
                                        else
                                        {
                                                $resize = 2;
                                        }

                                        if ( $copy == 1 ){
                                                //Copie reussie
                                                $copy = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/en_ligne.gif'>";
                                        } else {
                                                //Copie echoué
                                                $copy = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree.gif'>";
                                        }
                                        if ( $resize == 1 ){
                                                //Creation reussie
                                                $resize = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/en_ligne.gif'>";
                                        } elseif ( $resize == 2 ) {
                                                //Thumb deja existant dans la db
                                                $resize = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree_bientot.gif'>";
                                        } else {
                                                //Thumb echoué
                                                $resize = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree.gif'>";
                                        }

                                        echo "<tr>
                                                        <td align='center' class = 'odd'>".$fileCopy."</td>
                                                        <td align='center' class = 'odd'>".$resize."</td>
                                                        <td align='center' class = 'odd'>".$copy."</td>
                                                  </tr>";
                                        //unlink("".$fileCopy."");

                                }
                        }
                        @closedir($racine);
                echo "</table>";

                echo "<br><br>
                <fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_CATADS_INFOS_STATUTS_IMAGES_TITLE . "</legend>
                        <div style='padding: 8px;'>" . _AM_CATADS_INFOS_STATUTS_IMAGES . "</div>
                </fieldset>";

                //$dir = XOOPS_ROOT_PATH . "/modules/catads/images/ads";
                //chmod ($dir, 0777);
                //rmdir($dir);
}


// pk check to see if classifieds module is installed

         function is_module_installed()
         {
         global $xoopsDB, $is_installed;
         $query = $xoopsDB->query("SELECT COUNT(lid) as count FROM ".$xoopsDB->prefix("classifieds_listing"));
         $is_installed = $xoopsDB->fetchRow( $query ) ;
         }

/*
 *Compte le nombre d'annonces a importer de Classifield
 */
        function AdsCount()
        {
                global $xoopsDB, $count_ads;
                $query = $xoopsDB->query("SELECT COUNT(lid) as count FROM ".$xoopsDB->prefix("classifieds_listing"));
                list( $count_ads ) = $xoopsDB->fetchRow( $query ) ;
                if( $count_ads < 1 ) {
                        echo ""._AM_CATADS_IMPORT_DONT_ADS."<br>";
                } else {
                        echo ""._AM_CATADS_IMPORT_THERE_IS."".$count_ads.""._AM_CATADS_IMPORT_ADS1."<br>";
                }
        }
/*
 *Compte le nombre de categories a importer de Classifield
 */
        function CatCount()
        {
                global $xoopsDB, $count_cat;
                $query = $xoopsDB->query("SELECT COUNT(cid) as count FROM ".$xoopsDB->prefix("classifieds_categories"));
                list( $count_cat ) = $xoopsDB->fetchRow( $query ) ;
                if( $count_cat < 1 ) {
                        echo ""._AM_CATADS_IMPORT_DONT_CAT."<br>";
                } else {
                        echo ""._AM_CATADS_IMPORT_THERE_IS."".$count_cat.""._AM_CATADS_IMPORT_CAT1."<br>";
                }
        }

/*
 *Compte le nombre d'options a  importer de Classifield
 */
        function OptionsCount()
        {
                global $xoopsDB, $count_options;
                $query = $xoopsDB->query("SELECT COUNT(id_type) as count FROM ".$xoopsDB->prefix("classifieds_type"));
                list( $count_options ) = $xoopsDB->fetchRow( $query ) ;
                if( $count_options < 1 ) {
                        echo ""._AM_CATADS_IMPORT_DONT_OPTIONS."<br>";
                } else {
                        echo ""._AM_CATADS_IMPORT_THERE_IS."".$count_options.""._AM_CATADS_IMPORT_OPTIONS1."<br>";
                }
        }

/*
 *Afficher le nombres d'elements a importer les annonces, les categories et les options
 */
        function CountData()
        {
                echo "<fieldset>
                                <legend style='font-weight: bold; color: #900;'>" ._AM_CATADS_IMPORT_NUMBER. "</legend>";
                                        AdsCount();
                                        CatCount() ;
                                        OptionsCount();
                echo "</fieldset>";

        }


//Affichage des l'administration des annonces
        switch ($op)
        {
                case "import_data":
                                $errorcounter = 0;
                                ImportData();
                break;

                case "import_images":
                                $errorcounter = 0;
                                ImportImages();
                break;

                case "show_form_import":
                default:
                                echo "<br><br>";
                                echo "<div class='errorMsg'>";
                                echo ""._AM_CATADS_IMPORT_WARNING."";
                                echo "</div>";
                                echo "<br><br>";
                                CountData();
                                // pk verify installation
                                is_module_installed();
                                echo "<br><br>";
                                if($is_installed !=''){
                                echo "<table width='100%' border='0'>
                                                  <tr>
                                                        <form action='import.php?op=import_data' method=POST>
                                                        <td  class='even'>"._AM_CATADS_IMPORT_ADS."</td>
                                                        <td  class='odd'><input type='submit' name='button' id='import_data' value='"._AM_CATADS_IMPORT1."'></td>
                                                        </form>
                                                  </tr>
                                                  <tr>
                                                        <form action='import.php?op=import_images' method=POST>
                                                        <td  class='even'>"._AM_CATADS_IMPORT_PICTURE."</td>
                                                        <td  class='odd'><input type='submit' name='button' id='import_images' value='"._AM_CATADS_IMPORT1."'></td>
                                                        </form>
                                                  </tr>
                                        </table>";
                                } else {
                                echo "The Classifieds module is not installed" ;
                                }
                break;
        }

xoops_cp_footer();
?>