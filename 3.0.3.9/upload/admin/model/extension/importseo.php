<?php
/**
 * TMD(http://opencartextensions.in/)
 *
 * Copyright (c) 2006 - 2012 TMD
 * This package is Copyright so please us only one domain 
 * 
 */
class ModelExtensionImportseo extends Model {
	
		public function addseokeywordprtoduct($data)
		{
			$query=$this->db->query("delete from  " . DB_PREFIX . "seo_url where language_id = '" . (int)$data['language_id']. "' and query ='product_id=" . (int)$data['product_id'].  "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'product_id=" . (int)$data['product_id']. "', language_id = '" . $data['language_id']. "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			
		}
		
		
		public function getlangaugeid($code)
		{
			$query=$this->db->query("select language_id from  " . DB_PREFIX . "language where code = '" . $code. "'");
			if(isset($query->row))
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
			$query=$this->db->query("delete from  " . DB_PREFIX . "seo_url where language_id = '" . (int)$data['language_id']. "' and query ='category_id=" . (int)$data['category_id'].  "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'category_id=" . (int)$data['category_id']. "', language_id = '" . $data['language_id']. "', keyword = '" . $this->db->escape($data['keyword']) . "'");
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
			$query=$this->db->query("delete from  " . DB_PREFIX . "seo_url where language_id = '" . (int)$data['language_id']. "' and query ='information_id=" . (int)$data['information_id'].  "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'information_id=" . (int)$data['information_id']. "', language_id = '" . $data['language_id']. "', keyword = '" . $this->db->escape($data['keyword']) . "'");
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
			$query=$this->db->query("delete from  " . DB_PREFIX . "seo_url where language_id = '" . (int)$data['language_id']. "' and query ='manufacturer_id=" . (int)$data['manufacturer_id'].  "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'manufacturer_id=" . (int)$data['manufacturer_id']. "', language_id = '" . $data['language_id']. "', keyword = '" . $this->db->escape($data['keyword']) . "'");
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