<?php 
// *
//  * TMD(http://opencartextensions.in/)
//  *
//  * Copyright (c) 2006 - 2012 TMD
//  * This package is Copyright so please us only one domain 
//  * 
 
require_once(DIR_SYSTEM.'/library/tmd/Psr/autoloader.php');
require_once(DIR_SYSTEM.'/library/tmd/myclabs/Enum.php');
require_once(DIR_SYSTEM.'/library/tmd/ZipStream/autoloader.php');
require_once(DIR_SYSTEM.'/library/tmd/ZipStream/ZipStream.php');
require_once(DIR_SYSTEM.'/library/tmd/PhpSpreadsheet/autoloader.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class ControllerExtensionImportseo extends Controller { 
	private $error = array();
	
	public function index() {	
		
		$this->language->load('extension/importseo');

		$this->document->setTitle($this->language->get('heading_title1'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title1'] = $this->language->get('heading_title1');
		
		$data['help_productimport'] = $this->language->get('help_productimport');
		$data['entry_forexp'] = $this->language->get('entry_forexp');
		$data['entry_forexp1'] = $this->language->get('entry_forexp1');
		$data['entry_forexp2'] = $this->language->get('entry_forexp2');
		$data['entry_forexp3'] = $this->language->get('entry_forexp3');
		
		$data['entry_productimport'] = $this->language->get('entry_productimport');
		$data['entry_categoryimport'] = $this->language->get('entry_categoryimport');
		$data['entry_informationimport'] = $this->language->get('entry_informationimport');
		$data['entry_manufaimport'] = $this->language->get('entry_manufaimport');
				
		$data['button_productimport'] = $this->language->get('button_productimport');
		$data['button_categoryimport'] = $this->language->get('button_categoryimport');
		$data['button_informationimport'] = $this->language->get('button_informationimport');
		$data['button_manufaimport'] = $this->language->get('button_manufaimport');
		
		$data['button_productexport'] = $this->language->get('button_productexport');
		$data['button_categoryexport'] = $this->language->get('button_categoryexport');
		$data['button_informationexport'] = $this->language->get('button_informationexport');
		$data['button_manufaexport'] = $this->language->get('button_manufaexport');
		$data['text_select'] = $this->language->get('text_select');
		$data['user_token'] = $this->session->data['user_token'];	
		$data['text_all_category'] = $this->language->get('text_all_category');
		$this->load->model('localisation/language');
		$filter_data = array();
		
		$results = $this->model_localisation_language->getLanguages($filter_data);
		
		$data['languages'] = array();
		
		foreach ($results as $result) {
			$data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null),
				'code'        => $result['code'],
				);
		}
		
		$this->load->model('catalog/category');
			$data['categories'] = array();
			
		$data1 = array(
		);
		$results = $this->model_catalog_category->getCategories($data1);
	
		foreach ($results as $result) {
		
		$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected'])
				
			);
			
		}
		
		$data['importproduct'] = $this->url->link('extension/importseo/importproduct', 'user_token=' . $this->session->data['user_token'], true);
		$data['importcategory'] = $this->url->link('extension/importseo/importcategory', 'user_token=' . $this->session->data['user_token'], true);
		$data['importinfo'] = $this->url->link('extension/importseo/importinfo', 'user_token=' . $this->session->data['user_token'], true);
		$data['importmanuf'] = $this->url->link('extension/importseo/importmanuf', 'user_token=' . $this->session->data['user_token'], true);

		$data['exportproduct'] = $this->url->link('extension/importseo/exportproduct', 'user_token=' . $this->session->data['user_token'], true);
		$data['exportcategory'] = $this->url->link('extension/importseo/exportcategory', 'user_token=' . $this->session->data['user_token'], true);
		$data['exportinfo'] = $this->url->link('extension/importseo/exportinfo', 'user_token=' . $this->session->data['user_token'], true);
		$data['exportmanuf'] = $this->url->link('extension/importseo/exportmanuf', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->session->data['error'])) {
    		$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
 		}elseif (isset($this->session->data['warning'])) {
    		$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
 		}  elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),     		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		);
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('extension/importseo', $data));
	}
		public function importproduct(){
		$totalnewproduct=0;
		
		$this->language->load('extension/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'extension/importseo')) {
			
		
	
			
			if (is_uploaded_file($this->request->files['importseoproduct']['tmp_name'])) {
				$content = file_get_contents($this->request->files['importseoproduct']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content) {
				////////////////////////// Started Import Seo work  //////////////
				// try {
				// 	$spreadsheet = PHPExcel_IOFactory::load($this->request->files['importseoproduct']['tmp_name']);
				// } catch(Exception $e) {
				// 	die('Error loading file "'.pathinfo($this->path.$files,PATHINFO_BASENAME).'": '.$e->getMessage());
				// }
				/*	@ get a file data into $sheetDatas variable */
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				$spreadsheet = $reader->load($_FILES['importseoproduct']['tmp_name']);
				$spreadsheet->setActiveSheetIndex(0);
				$sheetDatas = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
				
				/*	@ $i variable for getting data. in first iteration of loop we get size and color name of product */
				$i=0;
				
				/*
				@ arranging the data according to our need
				*/
				foreach($sheetDatas as $sheetData){
				if($i!=0)
				{
				$product_id=$sheetData['A'];
				$model=$sheetData['B'];
					if(!empty($product_id) || !empty($model))
					{
						
						if(empty($product_id))
						{
							 $product_id=$this->model_extension_importseo->getproductbymodel($model);
						}
						
						$language_id=$this->model_extension_importseo->getlangaugeid($sheetData['C']);
						$keyword=$sheetData['D'];
						if(!empty($language_id) && !empty($keyword)  && !empty($product_id))
						{
						
						$file=array(
						'product_id'=>$product_id,
						'language_id'=>$language_id,
						'keyword'=>$keyword,
						);
						
						$this->model_extension_importseo->addseokeywordprtoduct($file);
						$totalnewproduct++;
						}
						
					}
				}
				$i++;
				}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewproduct;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}
			
			
		}

	}
	
	
		
	public function importcategory(){
		
		$totalnewcategory=0;
		
		$this->language->load('extension/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'extension/importseo')) {
			
			
			
	
			
			if (is_uploaded_file($this->request->files['importseocategory']['tmp_name'])) {
				$content = file_get_contents($this->request->files['importseocategory']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				$spreadsheet = $reader->load($_FILES['importseocategory']['tmp_name']);
				$spreadsheet->setActiveSheetIndex(0);
				$sheetDatas = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
				$i=0;
				
				/*
				@ arranging the data according to our need
				*/
				foreach($sheetDatas as $sheetData){
				if($i!=0)
				{
					 $category_id=$this->model_extension_importseo->getcategorybyId($sheetData['A']);
					 $language_id=$this->model_extension_importseo->getlangaugeid($sheetData['B']); 
					 $keyword=$sheetData['C'];
						
						if(!empty($language_id) && !empty($keyword)  && !empty($category_id))
						{
						$file=array(
						'category_id'=>$category_id,
						'language_id'=>$language_id,
						'keyword'=>$keyword,
						);
						
						$this->model_extension_importseo->addseokeywordCategory($file);
						$totalnewcategory++;
						}
						
					
				}
				$i++;
				}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewcategory;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}
			
			
		}

	}
	
	public function importinfo(){
	
		$totalnewinformation=0;
		
		$this->language->load('extension/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'extension/importseo')) {
			
		
	
			
			if (is_uploaded_file($this->request->files['importinformation']['tmp_name'])) {
				$content = file_get_contents($this->request->files['importinformation']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content) {

				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				$spreadsheet = $reader->load($_FILES['importinformation']['tmp_name']);
				$spreadsheet->setActiveSheetIndex(0);
				$sheetDatas = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

				$i=0;
				
				/*
				@ arranging the data according to our need
				*/
				foreach($sheetDatas as $sheetData){
				if($i!=0)
				{
					 $information_id=$this->model_extension_importseo->getInfobyId($sheetData['A']);
					 $language_id=$this->model_extension_importseo->getlangaugeid($sheetData['B']); 
					 $keyword=$sheetData['C'];
						
						if(!empty($language_id) && !empty($keyword)  && !empty($information_id))
						{
						$file=array(
						'information_id'=>$information_id,
						'language_id'=>$language_id,
						'keyword'=>$keyword,
						);
						
						$this->model_extension_importseo->addseokeywordInfo($file);
						$totalnewinformation++;
						}
						
					
				}
				$i++;
				}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewinformation;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				 $this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}
			
			
		}

		
	}
	
	public function importmanuf(){
		
		$totalnewmanufacturer =0;
		$this->language->load('extension/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'extension/importseo')) {
			
			
			
			if (isset($this->request->files['importmanufac']['tmp_name']) && is_uploaded_file($this->request->files['importmanufac']['tmp_name'])) {
				$content = file_get_contents($this->request->files['importmanufac']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content) {

				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				$spreadsheet = $reader->load($_FILES['importmanufac']['tmp_name']);
				$spreadsheet->setActiveSheetIndex(0);
				$sheetDatas = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);	
				

				$i=0;
				
				/*
				@ arranging the data according to our need
				*/
				foreach($sheetDatas as $sheetData){
				if($i!=0)
				{
					 $manufacturer_id=$this->model_extension_importseo->getManufacbyId($sheetData['A']);
					 $language_id=$this->model_extension_importseo->getlangaugeid($sheetData['B']); 
					 $keyword=$sheetData['C'];
						
						if(!empty($language_id) && !empty($keyword)  && !empty($manufacturer_id))
						{
						$file=array(
						'manufacturer_id'=>$manufacturer_id,
						'language_id'=>$language_id,
						'keyword'=>$keyword,
						);
						
						$this->model_extension_importseo->addseokeywordManufac($file);
						$totalnewmanufacturer++;
						}
						
					
				}
				$i++;
				}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewmanufacturer;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}
			
			
		}

		
	}
	
	
	public function exportproduct() {
	    $data = array();
	    $language_id = !empty($this->request->get['language_id']) ? $this->request->get['language_id'] : $this->config->get('config_language_id');
	    $category_id = !empty($this->request->get['category_id']) ? $this->request->get['category_id'] : '';

	    $this->load->model('localisation/language');
	    $spreadsheet = new Spreadsheet();
	    $i = 1;

	    // Set header values
	    $spreadsheet->getActiveSheet()->SetCellValue('A'.$i, 'Product ID');
	    $spreadsheet->getActiveSheet()->SetCellValue('B'.$i, 'Model');
	    $spreadsheet->getActiveSheet()->SetCellValue('C'.$i, 'Language Code');
	    $spreadsheet->getActiveSheet()->SetCellValue('D'.$i, 'Keyword');
	    $spreadsheet->getActiveSheet()->SetCellValue('E'.$i, 'Name');
	    $spreadsheet->getActiveSheet()->SetCellValue('F'.$i, 'Manufacturer');
	    $i++;

	    // SQL query to fetch products
	    if (!empty($category_id)) {
	        $sql = "SELECT p.product_id, p.model, p.manufacturer_id FROM `".DB_PREFIX."product` p LEFT JOIN ".DB_PREFIX."product_to_category as pc ON pc.`product_id`= p.`product_id` WHERE pc.category_id='".$category_id."'";
	    } else {
	        $sql = "SELECT product_id, model, manufacturer_id FROM `".DB_PREFIX."product`";
	    }

	    $query = $this->db->query($sql);

	    if ($query->rows) {
	        foreach ($query->rows as $rowproduct) {
	            $sql1 = "SELECT keyword, language_id FROM `".DB_PREFIX."seo_url` WHERE query='product_id=".$rowproduct['product_id']."'";
	            if (!empty($language_id)) {
	                $sql1 .= " AND language_id='".$language_id."'";
	            }
	            $query1 = $this->db->query($sql1);

	            if ($query1->rows) {
	                foreach ($query1->rows as $row) {
	                    $sql2 = "SELECT name FROM `".DB_PREFIX."product_description` WHERE product_id='".$rowproduct['product_id']."' AND language_id='".$row['language_id']."'";
	                    $query2 = $this->db->query($sql2);

	                    $sql3 = "SELECT name FROM `".DB_PREFIX."manufacturer` WHERE manufacturer_id='".$rowproduct['manufacturer_id']."'";
	                    $query3 = $this->db->query($sql3);

	                    $manufacturer = isset($query3->row['name']) ? $query3->row['name'] : '';
	                    $languages = $this->model_localisation_language->getLanguage($row['language_id']);
	                    $language_code = $languages['code'];

	                    // Set values in spreadsheet
	                    $spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['product_id']);
	                    $spreadsheet->getActiveSheet()->SetCellValue('B'.$i, $rowproduct['model']);
	                    $spreadsheet->getActiveSheet()->SetCellValue('C'.$i, $language_code);
	                    $spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $row['keyword']);
	                    $spreadsheet->getActiveSheet()->SetCellValue('E'.$i, $query2->row['name']);
	                    $spreadsheet->getActiveSheet()->SetCellValue('F'.$i, $manufacturer);
	                    $i++;
	                }
	            } else {
	                // Handle case where no SEO URL is found
	                $sql2 = "SELECT name FROM `".DB_PREFIX."product_description` WHERE product_id='".$rowproduct['product_id']."' AND language_id='".$this->config->get('config_language_id')."'";
	                $query2 = $this->db->query($sql2);
	                $sql3 = "SELECT name FROM `".DB_PREFIX."manufacturer` WHERE manufacturer_id='".$rowproduct['manufacturer_id']."'";
	                $query3 = $this->db->query($sql3);

	                $manufacturer = isset($query3->row['name']) ? $query3->row['name'] : '';

	                // Set values in spreadsheet
	                $spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['product_id']);
	                $spreadsheet->getActiveSheet()->SetCellValue('B'.$i, $rowproduct['model']);
	                $spreadsheet->getActiveSheet()->SetCellValue('C'.$i, '');
	                $spreadsheet->getActiveSheet()->SetCellValue('D'.$i, '');
	                $spreadsheet->getActiveSheet()->SetCellValue('E'.$i, $query2->row['name']);
	                $spreadsheet->getActiveSheet()->SetCellValue('F'.$i, $manufacturer);
	                $i++;
	            }
	        }
	    }

	    // Set column widths and styles
	    for ($col = 'A'; $col != 'G'; $col++) {
	        $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(20);
	    }
	    $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
	    $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');

	    $styleArray = array(
	        'font' => array(
	            'bold' => true,
	            'color' => array('rgb' => 'FFFFFF'),
	            'size' => 9,
	            'name' => 'Verdana'
	        )
	    );

	    $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);

	    // Output to browser
	    $filename = 'SeoProduct-Export-' . time() . '.xls';
	    $spreadsheet->getActiveSheet()->setTitle('All product');
	    $writer = new Xls($spreadsheet);
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'. urlencode($filename) .'"');
	    $writer->save('php://output');
	    exit; // Ensure no further output is sent
	}
	
	public function exportcategory(){
	
		$data=array();
		if(!empty($this->request->get['language_id']))
		{
			$language_id=$this->request->get['language_id'];
		}else{
			$language_id=$this->config->get('config_language_id');
		}
		
		
		$this->load->model('localisation/language');
		
		
		$spreadsheet = new Spreadsheet();

		
		$i=1;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, 'Category Id');
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, 'Language Code');
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, 'Keyword');
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, 'Name');
			$i++;
			
			
		$sql="SELECT category_id FROM `".DB_PREFIX."category`";
		$query=$this->db->query($sql);
		if($query->row)
		{
		foreach($query->rows as $rowproduct){
		
		$sql1="SELECT keyword,language_id FROM `".DB_PREFIX."seo_url` where query='category_id=".$rowproduct['category_id']."'";
		if(!empty($language_id))
			{
				$sql1.=" and language_id='".$language_id."'";
			}
			$query1=$this->db->query($sql1);
					if(!empty($query1->row))
					{
						foreach($query1->rows as $row)
						{
		
						$sql2="SELECT name FROM `".DB_PREFIX."category_description` where category_id='".$rowproduct['category_id']."' and language_id='".$row['language_id']."'";
						$query2=$this->db->query($sql2);
						
						$languages = $this->model_localisation_language->getLanguage($row['language_id']);
						$language_code=$languages['code'];
					
						
						$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['category_id']);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, $language_code);
						$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, $row['keyword']);
						$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $query2->row['name']);
						$i++;
						}
					}
					else
					{
						$sql2="SELECT name FROM `".DB_PREFIX."category_description` where category_id='".$rowproduct['category_id']."' and language_id='".$this->config->get('config_language_id')."'";
						$query2=$this->db->query($sql2);
						
						$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['category_id']);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, '');
						$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, '');
						$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $query2->row['name']);
						$i++;
					}
		
		}
		}
			/* color setup */
				for($col = 'A'; $col != 'E'; $col++) {
			   $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(20);
			 	}
				$spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
				$spreadsheet->getActiveSheet()
				->getStyle('A1:D1')
				->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('FF4F81BD');
				
				$styleArray = array(
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				));
				
				$spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

				/* color setup */ 
		// Output to browser
	    $filename = 'SeoProduct-Export-' . time() . '.xls';
	    $spreadsheet->getActiveSheet()->setTitle('All product');
	    $writer = new Xls($spreadsheet);
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'. urlencode($filename) .'"');
	    $writer->save('php://output');
	    exit; // Ensure no further output is sent
	
	}
	public function exportinfo(){
	
		$data=array();
		if(!empty($this->request->get['language_id']))
		{
			$language_id=$this->request->get['language_id'];
		}else{
			$language_id=$this->config->get('config_language_id');
		}
	
		
		$this->load->model('localisation/language');
		
		
		$spreadsheet = new Spreadsheet();

		$i=1;

		$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, 'Information Id');
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, 'Language Code');
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, 'Keyword');
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, 'Name');
			$i++;
			
			
		$sql="SELECT information_id FROM `".DB_PREFIX."information`";
		$query=$this->db->query($sql);
		
		if($query->row)
		{
			foreach($query->rows as $rowproduct){
			
			$sql1="SELECT keyword,language_id FROM `".DB_PREFIX."seo_url` where query='information_id=".$rowproduct['information_id']."'";
			if(!empty($language_id))
			{
				$sql1.=" and language_id='".$language_id."'";
			}
			$query1=$this->db->query($sql1);
					if(!empty($query1->row))
					{
						foreach($query1->rows as $row)
						{
			
						$sql2="SELECT title FROM `".DB_PREFIX."information_description` where information_id='".$rowproduct['information_id']."' and language_id='".$row['language_id']."'";
						
						$query2=$this->db->query($sql2);
						
						$languages = $this->model_localisation_language->getLanguage($row['language_id']);
						$language_code=$languages['code'];
						
						$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['information_id']);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, $language_code);
						$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, $row['keyword']);
						$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $query2->row['title']);
						$i++;
					}
				}
				
				else
					{
						$sql2="SELECT title FROM `".DB_PREFIX."information_description` where information_id='".$rowproduct['information_id']."' and language_id='".$this->config->get('config_language_id')."'";
						
						$query2=$this->db->query($sql2);
						
						$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['information_id']);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, '');
						$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, '');
						$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $query2->row['title']);
						$i++;
					}
			}
		}
		
			/* color setup */
				for($col = 'A'; $col != 'E'; $col++) {
			   $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(20);
			 	}
				$spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
				$spreadsheet->getActiveSheet()
				->getStyle('A1:E1')
				->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('FF4F81BD');
				
				$styleArray = array(
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				));
				
				$spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);

				/* color setup */  
				
				
				
		// Output to browser
	    $filename = 'SeoProduct-Export-' . time() . '.xls';
	    $spreadsheet->getActiveSheet()->setTitle('All product');
	    $writer = new Xls($spreadsheet);
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'. urlencode($filename) .'"');
	    $writer->save('php://output');
	    exit; // Ensure no further output is sent
	}
	public function exportmanuf(){
	
		$data=array();
		if(!empty($this->request->get['language_id']))
		{
			$language_id=$this->request->get['language_id'];
		}else{
			$language_id=$this->config->get('config_language_id');
		}
		
		
		$this->load->model('localisation/language');
		
		
		$spreadsheet = new Spreadsheet();

		$i=1;

		$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, 'Manufacturer Id');
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, 'Language Code');
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, 'Keyword');
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, 'Name');
			$i++;
			
			
		$sql="SELECT manufacturer_id,name FROM `".DB_PREFIX."manufacturer`";
		$query=$this->db->query($sql);
		
		if($query->row)
		{
			foreach($query->rows as $rowproduct){
			
			$sql1="SELECT keyword,language_id FROM `".DB_PREFIX."seo_url` where query='manufacturer_id=".$rowproduct['manufacturer_id']."'";
			if(!empty($language_id))
			{
			$sql1 .="and language_id='".$language_id."'";
			}
			$query1=$this->db->query($sql1);
				if(!empty($query1->row))
				{
					foreach($query1->rows as $row)
					{
					
						$languages = $this->model_localisation_language->getLanguage($row['language_id']);
						$language_code=$languages['code'];
						
						$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['manufacturer_id']);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, $language_code);
						$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, $row['keyword']);
						$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $rowproduct['name']);
						$i++;
					
					}
				}
				else
					{
						$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['manufacturer_id']);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, '');
						$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, '');
						$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $rowproduct['name']);
						$i++;
					}
			}
		}
	
		/* color setup */
				for($col = 'A'; $col != 'E'; $col++) {
			   $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(20);
			 	}
				$spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
				$spreadsheet->getActiveSheet()
				->getStyle('A1:D1')
				->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('FF4F81BD');
				
				$styleArray = array(
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				));
				
				$spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

				/* color setup */ 
		// Output to browser
	    $filename = 'SeoProduct-Export-' . time() . '.xls';
	    $spreadsheet->getActiveSheet()->setTitle('All product');
	    $writer = new Xls($spreadsheet);
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'. urlencode($filename) .'"');
	    $writer->save('php://output');
	    exit; // Ensure no further output is sent
	}
	
	
}
?>