<?php
/**
 * TMD(http://opencartextensions.in/)
 *
 * Copyright (c) 2006 - 2012 TMD
 * This package is Copyright so please us only one domain 
 * 
 */
namespace Opencart\Admin\Model\Extension\tmdseourlcomboofimportexport\Tmd;
class Importseo extends \Opencart\System\Engine\Model {
	
		public function addseokeywordprtoduct($data)
		{


			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'product_id' AND `value` = '" . (int)$data['product_id'] . "' AND `language_id` = '" . (int)$data['language_id']. "'");
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `language_id` = '" . $data['language_id']. "',`key` = 'product_id' , `value` = '" . (int)$data['product_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
			
		}
		
		
		public function getlangaugeid($code)
		{
			$query=$this->db->query("select language_id from  " . DB_PREFIX . "language where code = '" . $code. "'");
			if(!empty($query->row))
			{
				return $query->row['language_id'];
			}
			
		}
		
		public function getproductbymodel($model)
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product where model='".$model."'");
			if($query->row)
			{
			return $query->row['product_id'];
			}
		}
		
		public function addseokeywordCategory($data)
		{

			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'path' AND `value` = '" . (int)$data['category_id'] . "' AND `language_id` = '" . (int)$data['language_id']. "'");
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `key` = 'path' ,`language_id` = '" . (int)$data['language_id'] . "',  `value` = '" . (int)$data['category_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		public function getcategorybyId($category_id)
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category where category_id='".$category_id."'");
			if($query->row)
			{
			return $query->row['category_id'];
			}
		}
		
		public function addseokeywordInfo($data)
		{

			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'information_id' AND `value` = '" . (int)$data['information_id'] . "' AND `language_id` = '" . (int)$data['language_id']. "'");
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `key` = 'information_id' ,`language_id` = '" . (int)$data['language_id'] . "',  `value` = '" . (int)$data['information_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		public function getInfobyId($information_id)
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information where information_id='".$information_id."'");
			if($query->row)
			{
			return $query->row['information_id'];
			}
		}
		public function addseokeywordManufac($data)
		{
			
			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'manufacturer_id' AND `value` = '" . (int)$data['manufacturer_id'] . "' AND `language_id` = '" . (int)$data['language_id']. "'");

			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `key` = 'manufacturer_id' ,`language_id` = '" . (int)$data['language_id'] . "',  `value` = '" . (int)$data['manufacturer_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		public function getManufacbyId($manufacturer_id)
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer where manufacturer_id='".$manufacturer_id."'");
			if($query->row)
			{
			return $query->row['manufacturer_id'];
			}
		}
		
		
	
}
?>