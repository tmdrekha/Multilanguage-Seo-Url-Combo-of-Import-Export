<?php 
namespace Opencart\Admin\Controller\Extension\tmdseourlcomboofimportexport\Tmd;

require_once(DIR_EXTENSION.'/tmdseourlcomboofimportexport/system/library/tmd/Psr/autoloader.php');
require_once(DIR_EXTENSION.'/tmdseourlcomboofimportexport/system/library/tmd/myclabs/Enum.php');
require_once(DIR_EXTENSION.'/tmdseourlcomboofimportexport/system/library/tmd/ZipStream/autoloader.php');
require_once(DIR_EXTENSION.'/tmdseourlcomboofimportexport/system/library/tmd/ZipStream/ZipStream.php');
require_once(DIR_EXTENSION.'/tmdseourlcomboofimportexport/system/library/tmd/PhpSpreadsheet/autoloader.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class Importseo extends \Opencart\System\Engine\Controller {


	public function index() {
		
		$this->language->load('extension/tmdseourlcomboofimportexport/tmd/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		
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
		$filter_data = [];
		
		$data['VERSION2'] = VERSION;

		$results = $this->model_localisation_language->getLanguages($filter_data);
		
		$data['languages'] = [];
		
		foreach ($results as $result) {
			$data['languages'][] = [
				'language_id' => $result['language_id'],
				'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null),
				'code'        => $result['code'],
				];
		}
		
		$this->load->model('catalog/category');
			$data['categories'] = [];
			
		$data1 = [];
		$results = $this->model_catalog_category->getCategories($data1);
	
		foreach ($results as $result) {
		
		$data['categories'][] = [
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected'])
				
			];
			
		}

		if(VERSION>='4.0.2.0'){
			$data['importproduct'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.importproduct', 'user_token=' . $this->session->data['user_token'], true);
			$data['importcategory'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.importcategory', 'user_token=' . $this->session->data['user_token'], true);
			$data['importinfo'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.importinfo', 'user_token=' . $this->session->data['user_token'], true);
			$data['importmanuf'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.importmanuf', 'user_token=' . $this->session->data['user_token'], true);

			$data['exportproduct'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.exportproduct', 'user_token=' . $this->session->data['user_token'], true);
			$data['exportcategory'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.exportcategory', 'user_token=' . $this->session->data['user_token'], true);
			$data['exportinfo'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.exportinfo', 'user_token=' . $this->session->data['user_token'], true);
			$data['exportmanuf'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo.exportmanuf', 'user_token=' . $this->session->data['user_token'], true);
		}
		else{
			$data['importproduct'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|importproduct', 'user_token=' . $this->session->data['user_token'], true);
			$data['importcategory'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|importcategory', 'user_token=' . $this->session->data['user_token'], true);
			$data['importinfo'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|importinfo', 'user_token=' . $this->session->data['user_token'], true);
			$data['importmanuf'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|importmanuf', 'user_token=' . $this->session->data['user_token'], true);

			$data['exportproduct'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|exportproduct', 'user_token=' . $this->session->data['user_token'], true);
			$data['exportcategory'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|exportcategory', 'user_token=' . $this->session->data['user_token'], true);
			$data['exportinfo'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|exportinfo', 'user_token=' . $this->session->data['user_token'], true);
			$data['exportmanuf'] = $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo|exportmanuf', 'user_token=' . $this->session->data['user_token'], true);
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
  		$data['breadcrumbs'] = [];

   		$data['breadcrumbs'][] = [
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),     		
      		'separator' => false
   		];

   		$data['breadcrumbs'][] = [
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('extension/tmdseourlcomboofimportexport/tmd/importseo', $data));
	}
	
	public function importproduct(){
		$totalnewproduct=0;
		
		$this->language->load('extension/tmdseourlcomboofimportexport/tmd/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/tmdseourlcomboofimportexport/tmd/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
						
			if (is_uploaded_file($this->request->files['importseoproduct']['tmp_name'])) {
				$content = file_get_contents($this->request->files['importseoproduct']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content){

				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				$spreadsheet = $reader->load($_FILES['importseoproduct']['tmp_name']);
				$spreadsheet->setActiveSheetIndex(0);
				$sheetDatas = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);	

			$i=0;

			foreach($sheetDatas as $sheetData){
				if($i!=0)
				{
				$product_id=$sheetData['A'];
				$model=$sheetData['B'];


					if(!empty($product_id) || !empty($model))
					{
						
						if(empty($product_id))
						{
							 $product_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getproductbymodel($model);
						}
						
						$language_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getlangaugeid($sheetData['C']);
						$keyword=$sheetData['D'];
						if(!empty($language_id) && !empty($keyword)  && !empty($product_id))
						{
						
						$file=[
							'product_id'=>$product_id,
							'language_id'=>$language_id,
							'keyword'=>$keyword,
						];
						
						$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->addseokeywordprtoduct($file);
						$totalnewproduct++;
						}
						
					}
				}
				$i++;
			}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewproduct;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}
		}
	}
		
	public function importcategory(){
		
		$totalnewcategory=0;
		
		$this->language->load('extension/tmdseourlcomboofimportexport/tmd/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/tmdseourlcomboofimportexport/tmd/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'extension/tmdseourlcomboofimportexport/tmd/importseo')) {
			
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
					 $category_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getcategorybyId($sheetData['A']);
					 $language_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getlangaugeid($sheetData['B']); 
					 $keyword=$sheetData['C'];
						
						if(!empty($language_id) && !empty($keyword)  && !empty($category_id))
						{
						$file=[
							'category_id'=>$category_id,
							'language_id'=>$language_id,
							'keyword'=>$keyword,
						];
						
						$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->addseokeywordCategory($file);
						$totalnewcategory++;
					}										
				}
				$i++;
				}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewcategory;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}			
		}
	}
	
	public function importinfo(){
	
		$totalnewinformation=0;
		
		$this->language->load('extension/tmdseourlcomboofimportexport/tmd/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/tmdseourlcomboofimportexport/tmd/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'extension/tmdseourlcomboofimportexport/tmd/importseo')) {
						
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
					 $information_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getInfobyId($sheetData['A']);
					 $language_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getlangaugeid($sheetData['B']); 
					 $keyword=$sheetData['C'];
						
						if(!empty($language_id) && !empty($keyword)  && !empty($information_id))
						{
						$file=[
							'information_id'=>$information_id,
							'language_id'=>$language_id,
							'keyword'=>$keyword,
						];
						
						$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->addseokeywordInfo($file);
						$totalnewinformation++;
						}					
				}
				$i++;
				}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewinformation;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				 $this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}			
		}		
	}
	
	public function importmanuf(){
		
		$totalnewmanufacturer =0;
		$this->language->load('extension/tmdseourlcomboofimportexport/tmd/importseo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/tmdseourlcomboofimportexport/tmd/importseo');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'extension/tmdseourlcomboofimportexport/tmd/importseo')) {			
			
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
					 $manufacturer_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getManufacbyId($sheetData['A']);
					 $language_id=$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->getlangaugeid($sheetData['B']); 
					 $keyword=$sheetData['C'];
						
						if(!empty($language_id) && !empty($keyword)  && !empty($manufacturer_id))
						{
						$file=[
							'manufacturer_id'=>$manufacturer_id,
							'language_id'=>$language_id,
							'keyword'=>$keyword,
						];
						
						$this->model_extension_tmdseourlcomboofimportexport_tmd_importseo->addseokeywordManufac($file);
						$totalnewmanufacturer++;
					}					
				}
				$i++;
				}
				 $this->session->data['success']='Total SEO URL Edit : '.$totalnewmanufacturer;
				
				////////////////////////// Started Import SEO work  //////////////
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->session->data['warning'] = $this->language->get('error_empty');
				$this->response->redirect($this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token'], true));
			}			
		}		
	}
	
	
	public function exportproduct(){
		
		$data=[];
		if(!empty($this->request->get['language_id']))
		{
			$language_id=$this->request->get['language_id'];
		}else{
			$language_id=$this->config->get('config_language_id');
		}
		
		
		if(!empty($this->request->get['category_id']))
		{
			$category_id=$this->request->get['category_id'];
		}
		else
		{
			$category_id='';
		}
		
		$this->load->model('localisation/language');
	
		
		$spreadsheet = new Spreadsheet();

		// Set properties
		
		$spreadsheet->getProperties()->setCreator("TMD ProductSeoExport");
		$spreadsheet->getProperties()->setLastModifiedBy("TMD ProductSeoExport");
		$spreadsheet->getProperties()->setTitle("Office Excel");
		$spreadsheet->getProperties()->setSubject("Office Excel");
		$spreadsheet->getProperties()->setDescription("Office Excel");
		$spreadsheet->setActiveSheetIndex(0);
						$i=1;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, 'Product ID');
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, 'Model');
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, 'Language Code');
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, 'Keyword');
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$i, 'Name');
		$spreadsheet->getActiveSheet()->SetCellValue('F'.$i, 'Manufacturer');
			$i++;


		if(!empty($category_id))	
		{
			$sql="SELECT p.product_id,p.model,p.manufacturer_id FROM `".DB_PREFIX."product` p";
			$sql .=" left join ".DB_PREFIX."product_to_category as pc on pc.`product_id`= p.`product_id` where pc.category_id='".$category_id."'";
		}
		else
		{			
		$sql="SELECT product_id,model,manufacturer_id FROM `".DB_PREFIX."product`";
		}

		$query=$this->db->query($sql);
		
		if($query->row)
		{
		foreach($query->rows as $rowproduct){
		
		$sql1 = $this->db->query("SELECT keyword,language_id FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'product_id' AND `value` = '" . (int)$rowproduct['product_id'] . "' AND language_id='".(int)$language_id."' ");

				if(!empty($sql1->row))
					{
						foreach($sql1->rows as $row)
						{
		
						$sql2="SELECT name FROM `".DB_PREFIX."product_description` where product_id='".$rowproduct['product_id']."' and language_id='".$row['language_id']."'";
						$query2=$this->db->query($sql2);
						
						$sql3="SELECT name FROM `".DB_PREFIX."manufacturer` where manufacturer_id='".$rowproduct['manufacturer_id']."'";
						$query3=$this->db->query($sql3);
						
						$manufacturer='';
						if(isset($query3->row['name']))
						{
							$manufacturer=$query3->row['name'];
						}
							
						$languages = $this->model_localisation_language->getLanguage($row['language_id']);
						$language_code=$languages['code'];
					
						$spreadsheet->getActiveSheet()->SetCellValue('A'.$i, $rowproduct['product_id']);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$i, $rowproduct['model']);
						$spreadsheet->getActiveSheet()->SetCellValue('C'.$i, $language_code);
						$spreadsheet->getActiveSheet()->SetCellValue('D'.$i, $row['keyword']);
						$spreadsheet->getActiveSheet()->SetCellValue('E'.$i, $query2->row['name']);
						$spreadsheet->getActiveSheet()->SetCellValue('F'.$i, $manufacturer);
						$i++;
			
						}
					}
					else
					{
						$sql2="SELECT name FROM `".DB_PREFIX."product_description` where product_id='".$rowproduct['product_id']."' and language_id='".$this->config->get('config_language_id') ."'";
						$query2=$this->db->query($sql2);
						$sql3="SELECT name FROM `".DB_PREFIX."manufacturer` where manufacturer_id='".$rowproduct['manufacturer_id']."'";
						$query3=$this->db->query($sql3);
						
						$manufacturer='';
						if(isset($query3->row['name']))
						{
							$manufacturer=$query3->row['name'];
						}
						
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
		/* color setup */
				for($col = 'A'; $col != 'H'; $col++) {
			   $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(20);
			 	}
				$spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
				$spreadsheet->getActiveSheet()
				->getStyle('A1:G1')
				->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('FF4F81BD');
				
				$styleArray = [
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				)];
				
				$spreadsheet->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);

				/* color setup */  				
				
		$filename = 'SeoProduct-Export' . time() . '.xls';
		$spreadsheet->getActiveSheet()->setTitle('All product');
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet, 'Excel5');
		$writer->save($filename );
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
		$writer->save('php://output');
		unlink($filename);
		
	}
	
	public function exportcategory(){
	
		$data=[];
		if(!empty($this->request->get['language_id']))
		{
			$language_id=$this->request->get['language_id'];
		}else{
			$language_id=$this->config->get('config_language_id');
		}
	
		$this->load->model('localisation/language');		
		
		$spreadsheet = new Spreadsheet();

		// Set properties
		
		$spreadsheet->getProperties()->setCreator("TMD SeoCategoryExport");
		$spreadsheet->getProperties()->setLastModifiedBy("TMD SeoCategoryExport");
		$spreadsheet->getProperties()->setTitle("Office Excel");
		$spreadsheet->getProperties()->setSubject("Office Excel");
		$spreadsheet->getProperties()->setDescription("Office Excel");
		$spreadsheet->setActiveSheetIndex(0);
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
		

		$sql1 = $this->db->query("SELECT keyword,language_id FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'path' AND `value` = '" . (int)$rowproduct['category_id'] . "' AND language_id='".(int)$language_id."' ");
			
				if(!empty($sql1->row))
					{
						foreach($sql1->rows as $row)
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
						$sql2="SELECT name FROM `".DB_PREFIX."category_description` where category_id='".$rowproduct['category_id']."' and language_id='".$language_id."'";
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
				
				$styleArray = [
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				)];
				
				$spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

				/* color setup */ 
		$filename = 'SeoCategory-Export' . time() . '.xls';
		$spreadsheet->getActiveSheet()->setTitle('All Category');
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet, 'Excel5');
		$writer->save($filename );
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
		$writer->save('php://output');
		unlink($filename);
	
	}
	public function exportinfo(){
	
		$data=[];

		if(!empty($this->request->get['language_id']))
		{
			$language_id=$this->request->get['language_id'];
		}else{
			$language_id=$this->config->get('config_language_id');
		}	
		
		$this->load->model('localisation/language');
				
		$spreadsheet = new Spreadsheet();

		// Set properties
		
		$spreadsheet->getProperties()->setCreator("TMD SeoInfoExport");
		$spreadsheet->getProperties()->setLastModifiedBy("TMD SeoInfoExport");
		$spreadsheet->getProperties()->setTitle("Office Excel");
		$spreadsheet->getProperties()->setSubject("Office Excel");
		$spreadsheet->getProperties()->setDescription("Office Excel");
		$spreadsheet->setActiveSheetIndex(0);
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

			$sql1 = $this->db->query("SELECT keyword,language_id FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'information_id' AND `value` = '" . (int)$rowproduct['information_id'] . "' AND `language_id`='".(int)$language_id."' ");

				if(!empty($sql1->row)){
						foreach($sql1->rows as $row)
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
				
				$styleArray = [
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				)];
				
				$spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);

				/* color setup */  
				
		$filename = 'SeoInfo-Export' . time() . '.xls';
		$spreadsheet->getActiveSheet()->setTitle('All Information');
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet, 'Excel5');
		$writer->save($filename );
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
		$writer->save('php://output');
		unlink($filename);
	}
	public function exportmanuf(){
	
		$data=[];
		if(!empty($this->request->get['language_id']))
		{
			$language_id=$this->request->get['language_id'];
		}else{
			$language_id=$this->config->get('config_language_id');
		}
				
		$this->load->model('localisation/language');		
		
		$spreadsheet = new Spreadsheet();

		// Set properties
		
		$spreadsheet->getProperties()->setCreator("TMD SeoManufacExport");
		$spreadsheet->getProperties()->setLastModifiedBy("TMD SeoManufacExport");
		$spreadsheet->getProperties()->setTitle("Office Excel");
		$spreadsheet->getProperties()->setSubject("Office Excel");
		$spreadsheet->getProperties()->setDescription("Office Excel");
		$spreadsheet->setActiveSheetIndex(0);
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
			
			$sql1 = $this->db->query("SELECT keyword,language_id FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'manufacturer_id' AND `value` = '" . (int)$rowproduct['manufacturer_id'] . "' AND language_id='".(int)$language_id."' ");

		
				if(!empty($sql1->row))
				{
					foreach($sql1->rows as $row)
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
				
				$styleArray = [
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				)];
				
				$spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

				/* color setup */ 
		$filename = 'SeoManufac-Export' . time() . '.xls';
		$spreadsheet->getActiveSheet()->setTitle('All Manufacturer');
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet, 'Excel5');
		$writer->save($filename );
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
		$writer->save('php://output');
		unlink($filename);
	}	
}
?>