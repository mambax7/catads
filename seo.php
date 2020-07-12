<?php
/*
 * $Id: seo.php,v 1.5 2006/08/15 19:52:08 malanciault Exp $
 * Module: catads
 * Author: Sudhaker Raj <http://xoops.biz>
 * Licence: GNU
 */

$seoOp = $_GET['seoOp'];
$seoArg = $_GET['seoArg'];
$seoOther = $_GET['seoOther'];

if (!empty($seoOther))
{
	$seoOther = explode("/",$seoOther);
}

$seoMap = array(
	'categorie' => 'adslist.php',
	'annonce' => 'adsitem.php'
);

if (! empty($seoOp) && ! empty($seoMap[$seoOp]))
{
	// module specific dispatching logic, other module must implement as
	// per their requirements.
	$newUrl = '/modules/catads/' . $seoMap[$seoOp];

	$_ENV['PHP_SELF'] = $newUrl;
	$_SERVER['SCRIPT_NAME'] = $newUrl;
	$_SERVER['PHP_SELF'] = $newUrl;
	switch ($seoOp) {
		case 'categorie':
			$_SERVER['REQUEST_URI'] = $newUrl . '?topic_id=' . $seoArg;
			$_GET['topic_id'] = $seoArg;
			break;
		case 'annonce':
			$_SERVER['REQUEST_URI'] = $newUrl . '?ads_id=' . $seoArg;
			$_GET['ads_id'] = $seoArg;
			break;
	}

	include( $seoMap[$seoOp]);
}

exit;

?>