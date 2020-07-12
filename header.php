<?php
include "../../mainfile.php";

global $xoopsModuleConfig;

if ( $xoopsModuleConfig['show_seo'] > 0 )
{
	include "seo_url.php";
}

?>