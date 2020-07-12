<?php
// $Id: class/ads.php,v 1.4 2005/07/06 C. Felix AKA the Cat
// ------------------------------------------------------------------------- //
// Catads for Xoops                                                          //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//---------------------------------------------------------------------------//

class CatadsAds extends XoopsObject
{

	var $_catTitle;
	var $_displayPrice;
    var $commentstable;
	
	function CatadsAds()
	{
		$this->XoopsObject();
		$this->initVar('ads_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('cat_id', XOBJ_DTYPE_INT, null, true);
		$this->initVar('ads_title', XOBJ_DTYPE_TXTBOX, null, true, 100);
		$this->initVar('ads_type', XOBJ_DTYPE_TXTBOX, null, true, 40);
		$this->initVar('ads_desc', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('ads_tags', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('ads_video', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('price', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('monnaie', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('price_option', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('email', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('phone', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('pays', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('region', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('departement', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('town', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('codpost', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('published', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('expired', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('expired_mail_send', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('view', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('notify_pub', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('poster_ip', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('contact_mode', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('countpub', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('suspend', XOBJ_DTYPE_INT, 0, false);
		//add v1.30
		$this->initVar('waiting', XOBJ_DTYPE_INT, 0, false);
		//add v1.40
		$this->initVar('photo0', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('photo1', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('photo2', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('photo3', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('photo4', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('photo5', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('thumb', XOBJ_DTYPE_TXTBOX, null, false);
	}
	
	function setCatTitle($value)
	{
		$this->_catTitle = $value;
	}

	function getCatTitle()
	{
		return $this->_catTitle;
	}
	function setDisplayPrice($value)
	{
		$this->_displayPrice = $value;
	}

	function getDisplayPrice()
	{
		return $this->_displayPrice;
	}
}

class CatadsAdsHandler
{
	var $db;

	function CatadsAdsHandler(&$db)
	{
		$this->db =& $db;
	}

	function &getInstance(&$db)
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new CatadsAdsHandler($db);
		}
		return $instance;
	}

	function &create()
	{
		return new CatadsAds();
	}

	function &get($id)
	{
		$id = intval($id);
		if ($id > 0) {
			$sql = 'SELECT * FROM '.$this->db->prefix('catads_ads').' WHERE ads_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$ads = new CatadsAds();
				$ads->assignVars($this->db->fetchArray($result));
				return $ads;
			}
		}
		return false;
	}

	function insert(&$ads){
		if (strtolower(get_class($ads)) != 'catadsads') {
			return false;
		}
		if (!$ads->cleanVars()) {
			return false;
		}
		foreach ($ads->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if (empty($ads_id)) {
			$ads_id = $this->db->genId('catads_ads_ads_id_seq');
			$sql = 'INSERT INTO '.$this->db->prefix('catads_ads').' (
						ads_id, 
						cat_id, 
						ads_title, 
						ads_type, 
						ads_desc,
						ads_tags,
						ads_video,
						price, 
						monnaie, 
						price_option, 
						email, 
						uid, 
						phone,
						pays,
						region,
						departement,
						town, 
						codpost, 
						created, 
						published, 
						expired, 
						expired_mail_send,
						view, 
						notify_pub, 
						poster_ip, 
						contact_mode, 
						countpub, 
						suspend, 
						waiting,
						photo0, 
						photo1, 
						photo2, 
						photo3, 
						photo4, 
						photo5,
						thumb) 
					VALUES (
						'.$ads_id.', 
						'.$cat_id.', 
						'.$this->db->quoteString($ads_title).', 
						'.$this->db->quoteString($ads_type).', 
						'.$this->db->quoteString($ads_desc).',
                        '.$this->db->quoteString($ads_tags).',
						'.$this->db->quoteString($ads_video).',
						'.$this->db->quoteString($price).', 
						'.$this->db->quoteString($monnaie).', 
						'.$this->db->quoteString($price_option).', 
						'.$this->db->quoteString($email).', 
						'.$uid.', '.$this->db->quoteString($phone).',
						'.$this->db->quoteString($pays).', 
						'.$this->db->quoteString($region).', 
						'.$this->db->quoteString($departement).', 
						'.$this->db->quoteString($town).', 
						'.$this->db->quoteString($codpost).', 
						'.$created.', 
						'.$published.', 
						'.$expired.', 
						'.$this->db->quoteString($expired_mail_send).',
						'.$view.', 
						'.$notify_pub.', 
						'.$this->db->quoteString($poster_ip).', 
						'.$contact_mode.', 
						'.$countpub.', 
						'.$suspend.', 
						'.$waiting.',
						'.$this->db->quoteString($photo0).', 
						'.$this->db->quoteString($photo1).', 
						'.$this->db->quoteString($photo2).', 
						'.$this->db->quoteString($photo3).', 
						'.$this->db->quoteString($photo4).', 
						'.$this->db->quoteString($photo5).',
						'.$this->db->quoteString($thumb).')';
		} else {
			$sql = 'UPDATE '.$this->db->prefix('catads_ads').' SET 
				cat_id='.$cat_id.', 
				ads_title='.$this->db->quoteString($ads_title).', 
				ads_type='.$this->db->quoteString($ads_type).', 
				ads_desc='.$this->db->quoteString($ads_desc).', 
				ads_tags='.$this->db->quoteString($ads_tags).',
				ads_video='.$this->db->quoteString($ads_video).',
				price='.$this->db->quoteString($price).', 
				monnaie='.$this->db->quoteString($monnaie).', 
				price_option='.$this->db->quoteString($price_option).', 
				email='.$this->db->quoteString($email).', 
				uid='.$uid.', 
				phone='.$this->db->quoteString($phone).',
				pays='.$this->db->quoteString($pays).',
				region='.$this->db->quoteString($region).',				
				departement='.$this->db->quoteString($departement).',
				town='.$this->db->quoteString($town).',
				codpost='.$this->db->quoteString($codpost).', 
				created='.$created.', published='.$published.', 
				expired='.$expired.', 
				expired_mail_send='.$this->db->quoteString($expired_mail_send).', 
				view='.$view.', 
				notify_pub = '.$notify_pub.', 
				poster_ip='.$this->db->quoteString($poster_ip).', 
				contact_mode='.$contact_mode.', 
				countpub='.$countpub.', 
				suspend='.$suspend.', 
				waiting='.$waiting.' ,
				photo0='.$this->db->quoteString($photo0).', 
				photo1='.$this->db->quoteString($photo1).', 
				photo2='.$this->db->quoteString($photo2).', 
				photo3='.$this->db->quoteString($photo3).', 
				photo4='.$this->db->quoteString($photo4).', 
				photo5='.$this->db->quoteString($photo5).', 
				thumb='.$this->db->quoteString($thumb).'
				WHERE ads_id='.$ads_id;
		}
//echo $sql;
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		if (empty($ads_id)) {
			$ads_id = $this->db->getInsertId();
		}
		$ads->assignVar('ads_id', $ads_id);
		return $ads_id;
	}

	function delete(&$ads){
        global $xoopsModule;
		if (strtolower(get_class($ads)) != 'catadsads') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE ads_id = %u", $this->db->prefix('catads_ads'), $ads->getVar('ads_id'));
       	if (isset($this->commentstable) && $this->commentstable != "") {
            xoops_comment_delete($xoopsModule->getVar('mid'), $ads_id);
		}

		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
	
	function &getObjects($criteria = null)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('catads_ads');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
            $sort = ($criteria->getSort() != '') ? $criteria->getSort() : 'ads_id';
            $sql .= ' ORDER BY '.$sort.' '.$criteria->getOrder();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$ads = new CatadsAds();
			$ads->assignVars($myrow);
			$ret[] =& $ads;
			unset($ads);
		}
//echo $sql;
		return $ret;
	}

	function getAllAds($criteria = null)
	{
		$ret = array();
		$limit = $start = 0;
    	$sql = 'SELECT a.*, c.display_price, c.topic_title FROM '.$this->db->prefix("catads_ads ").'a LEFT JOIN '.$this->db->prefix('catads_cat').' c ON c.topic_id = a.cat_id ';
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
            $sort = ($criteria->getSort() != '') ? $criteria->getSort() : 'ads_id';
            $sql .= ' ORDER BY '.$sort.' '.$criteria->getOrder();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		//echo $sql;exit;
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}

		while ($myrow = $this->db->fetchArray($result)) {
			$ads = new CatadsAds();
			$ads->assignVars($myrow);
            $ads->setCatTitle($myrow['topic_title']);
            $ads->setDisplayPrice($myrow['display_price']);
			$ret[] =& $ads;
			unset($ads);
		}
		return $ret;
	}


	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('catads_ads');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		//if (!$result =& $this->db->query($sql)) {
		if (!$result = $this->db->query($sql)) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	function incView($ads_id)
	{
		$sql = "UPDATE ".$this->db->prefix("catads_ads ")." SET view=view+1 WHERE ads_id = '$ads_id'";
		if (!$result =& $this->db->queryF($sql)) {
			return false;
		}
		return true;
	}

	function getCountAdsByCat($criteria = null)
	{
		$arr = array();
		$sql = "SELECT cat_id, count(*) FROM ".$this->db->prefix("catads_ads ");
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		$sql .= " GROUP BY cat_id";
		//if (!$result =& $this->db->query($sql)) {
		if (!$result = $this->db->query($sql)) {
			return 0;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$arr[$myrow['cat_id']] =  $myrow['count(*)'];
		} 
		return $arr;
	}


}
?>