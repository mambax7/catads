<?php
// $Id: class.category.php,v 1.1 2005/05/05 C. Felix AKA the Cat
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

include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");

class AdsCategory{

    var $db;
	var $table;
	var $topic_id;
	var $topic_pid;
	var $topic_title;	
	var $topic_desc;
	var $img;
	var $display_cat;
	var $weight;
	var $display_price;
	var $nb_photo; //add v1.4
	
// constructor

	function AdsCategory($topic_id=0){
		$this->db =& Database::getInstance();
        $this->table = $this->db->prefix("catads_cat");

		if ( is_array($topic_id) ) {
			$this->makeCategory($topic_id);
		} elseif ( $topic_id != 0 ) {
			$this->loadCategory($topic_id);
		} else {
			$this->topic_id = $topic_id;
		}
	}

// set

	function setTitle($value){
		$this->topic_title = $value;
	}
	
    function setDesc($value){
		$this->topic_desc = $value;
	}

	function setImg($value){
		$this->img = $value;
	}
	
    function setPid($value){
        $this->topic_pid = $value;
    }
	
	function setWeigth($value){
        $this->weight = $value;
    }
	
	function setDisplayCat($value){
        $this->display_cat = $value;
    }
	
	function setPrice($value){
        $this->display_price = $value;
    }
	
	function setPhoto($value){
        $this->nb_photo = $value;
    }

// database

	function loadCategory($topic_id){
		$sql = "SELECT * FROM ".$this->table." WHERE topic_id=".$topic_id."";
		$array = $this->db->fetchArray($this->db->query($sql));
		$this->makeCategory($array);
	}

	function makeCategory($array){
		foreach($array as $key=>$value){
			$this->$key = $value;
		}
	}

	function store(){
//		global $ts;

        $ts =& MyTextSanitizer::getInstance();
        $title = "";
		$desc = "";
		$img = "";

		if ( isset($this->topic_title) && $this->topic_title != "" ) {
			$title = $ts->addSlashes($this->topic_title);
		}
		if ( isset($this->topic_desc) && $this->topic_desc != "" ) {
			$desc = $ts->addSlashes($this->topic_desc);
		}
        if ( isset($this->img) && $this->img != "" ) {
			$img = $ts->addSlashes($this->img);
		}
 		if ( !isset($this->topic_pid) || !is_numeric($this->topic_pid) ) {
			$this->topic_pid = 0;
		}
 		if ( !isset($this->display_price) || !is_numeric($this->display_price) ) {
			$this->display_price = 1;
		}

		if ( empty($this->topic_id) ) {
			$this->topic_id = $this->db->genId($this->table."_topic_id_seq");
			$sql = "INSERT INTO ".$this->table." (topic_id, topic_pid, topic_title, topic_desc, img, display_cat, weight, display_price, nb_photo) 
				VALUES (".$this->topic_id.", 
						".$this->topic_pid.", 
						'".$title."',
						'".$desc."',						
						'".$img."',
						'".$this->display_cat."', 
						'".$this->weight."',
						'".$this->display_price."', 
						'".$this->nb_photo."')";
		} else {
			$sql = "UPDATE ".$this->table." 
				SET topic_pid=".$this->topic_pid.", 
					img='".$img."', 
					topic_title='".$title."',
					topic_desc='".$desc."',
					display_cat='".$this->display_cat."', 
					weight='".$this->weight."',
					display_price='".$this->display_price."',
					nb_photo='".$this->nb_photo."' 
				WHERE topic_id=".$this->topic_id." ";
		}
//echo $sql;
		if ( !$result = $this->db->query($sql) ) {
			ErrorHandler::show('0022');
		}
		return true;
	}

	function delete(){
		$sql = "DELETE FROM ".$this->table." WHERE topic_id=".$this->topic_id."";
		$this->db->query($sql);
	}

// get

	function topic_id(){
		return $this->topic_id;
	}

	function topic_pid(){
		return $this->topic_pid;
	}

	function topic_title($format="S"){
	 	if (!isset($this->topic_title)) return "";
			$ts =& MyTextSanitizer::getInstance();
			switch($format){
			case "S":
				$title = $ts->htmlSpecialChars($this->topic_title);
				break;
			case "E":
				$title = $ts->htmlSpecialChars($this->topic_title);
				break;
			case "P":
				$title = $ts->htmlSpecialChars( $ts->stripSlashesGPC($this->topic_title) );
				break;
			case "F":
				$title = $ts->htmlSpecialChars( $ts->stripSlashesGPC($this->topic_title) );
				break;
			}
		return $title;
	}
	
	function topic_desc($format="S"){
	 	if (!isset($this->topic_desc)) return "";
			$ts =& MyTextSanitizer::getInstance();
			switch($format){
			case "S":
				$desc = $ts->htmlSpecialChars($this->topic_desc);
				break;
			case "E":
				$desc = $ts->htmlSpecialChars($this->topic_desc);
				break;
			case "P":
				$desc = $ts->htmlSpecialChars( $ts->stripSlashesGPC($this->topic_desc) );
				break;
			case "F":
				$desc = $ts->htmlSpecialChars( $ts->stripSlashesGPC($this->topic_desc) );
				break;
			}
		return $desc;
	}

	function img($format="S"){
		$ts =& MyTextSanitizer::getInstance();
		switch($format){
			case "S":
				$img= $ts->htmlSpecialChars($this->img);
				break;
			case "E":
				$img = $ts->htmlSpecialChars($this->img);
				break;
			case "P":
				$img = $ts->htmlSpecialChars( $ts->stripSlashesGPC($this->img) );
				break;
			case "F":
				$img = $ts->htmlSpecialChars( $ts->stripSlashesGPC($this->img) );
				break;
			}
		return $img;
	}

	function weight(){
		return $this->weight;
	}

	function display_price(){
		return $this->display_price;
	}
	

    function countCat($main = 0){
    	$db =& Database::getInstance();
         $sql = "SELECT COUNT(*) FROM ".$db->prefix("catads_cat")."";
         if ( $main!= 0 ) {
             $sql .= " WHERE topic_pid = 0";
         }
         $result = $db->query($sql);
         list($count) = $db->fetchRow($result);
         return $count;
     }

    function getCatWithPid($sel_pid = 0){
		$db =& Database::getInstance();
		$sql ="SELECT topic_id, topic_title, topic_desc, img FROM ".$db->prefix('catads_cat')." WHERE topic_pid = ".$sel_pid." ORDER BY weight";
		$result = $db->query($sql);		
		$ret = array();
		while ($myrow = $db->fetchArray($result)) {
			$ret[] = new AdsCategory($myrow);
		}
		return $ret;
     }

	function getAllChild(){
		$ret = array();
		$xt = new XoopsTree($this->table, "topic_id", "topic_pid");
		$category_arr = $xt->getAllChild($this->topic_id);
		if ( is_array($category_arr) && count($category_arr) ) {
			foreach($category_arr as $category){
				$ret[] = new AdsCategory($category);
			}
		}
		return $ret;
	}

	function getFirstChildArr($sel_id, $order=""){
		$db =& Database::getInstance();
		$arr =array();
		$sql = "SELECT * FROM ".$db->prefix("catads_cat")." WHERE topic_pid =".$sel_id." AND display_cat = 1 ";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result = $db->query($sql);
		$count = $db->getRowsNum($result);
		if ( $count==0 ) {
			return $arr;
		}
		while ( $myrow=$db->fetchArray($result) ) {
			array_push($arr, $myrow);
		}
		return $arr;
	}
	
	function getFirstChildArr2($sel_id, $order=""){
		$db =& Database::getInstance();
		$arr =array();
		$sql = "SELECT * FROM ".$db->prefix("catads_cat")." WHERE topic_pid =".$sel_id."";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result = $db->query($sql);
		$count = $db->getRowsNum($result);
		if ( $count==0 ) {
			return $arr;
		}
		while ( $myrow=$db->fetchArray($result) ) {
			array_push($arr, $myrow);
		}
		return $arr;
	}

// array des cid dernier enfant
	function getAllLastChild(){
	    $db =& Database::getInstance();
        $sql = "SELECT topic_id, topic_pid FROM ".$db->prefix("catads_cat");
		$result = $db->query($sql);
        $i = 0;
		while($myrow = $db->fetchArray($result)) {
			$arr1_cat[$i]['cid'] = $myrow['topic_id'];
			$arr1_cat[$i]['pid'] = $myrow['topic_pid'];
			$arr1_cat[$i]['last'] = true;
			$i++;
		}
		for ( $j = 0; $j < $i; $j++ ) {
			$cat = $arr1_cat[$j]['cid'];
			for ( $k = 0; $k < $i; $k++ ) {
				if ($cat == $arr1_cat[$k]['pid']) $arr1_cat[$j]['last'] = false;
			}
		}
		$arr2_cat = array();
		for ( $j = 0; $j < $i; $j++ ) {
		if ($arr1_cat[$j]['last']) $arr2_cat[]=$arr1_cat[$j]['cid'];
		}
		return $arr2_cat;
	}

// array 
	function getAllCat(){
	    $db =& Database::getInstance();
        $sql = "SELECT * FROM ".$db->prefix("catads_cat")." ORDER BY weight";
		$result = $db->query($sql);		
		$arr = array();
		while($myrow = $db->fetchArray($result)) {
			array_push($arr, $myrow);
			}
		return $arr;
	}
	


}
?>
