<?php
namespace Opencart\Admin\Controller\Extension\tmdseourlcomboofimportexport\Module;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdseourlcomboofimportexport/system/library/tmd/system.php');
// Lib Include 
class Tmdseoimportexport extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->registry->set('tmd', new  \Tmdseourlcomboofimportexport\System\Library\Tmd\System($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmdseoimportexport',
		'eid'=>'MjUzMTk=',
		'route'=>'extension/tmdseourlcomboofimportexport/module/tmdseoimportexport',
		);
		$tmdseoimportexport=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');

		$this->document->setTitle($this->language->get('heading_title1'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];
		
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport', 'user_token=' . $this->session->data['user_token'])
		];

		if(VERSION>='4.0.2.0'){
			$data['save'] = $this->url->link('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport.save', 'user_token=' . $this->session->data['user_token']);
		}
		else{
			$data['save'] = $this->url->link('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport|save', 'user_token=' . $this->session->data['user_token']);
		}

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['module_tmdseoimportexport_status'] = $this->config->get('module_tmdseoimportexport_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport', $data));
	}

	public function install(): void{
		$this->load->language('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');
		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdseourlcomboofimportexport/tmd/importseo');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlcomboofimportexport/tmd/importseo');
	
		// Register events
		if(VERSION>='4.0.2.0')
		{
			$eventaction='extension/tmdseourlcomboofimportexport/module/tmdseoimportexport.menu';
		}
		else{
			$eventaction='extension/tmdseourlcomboofimportexport/module/tmdseoimportexport|menu';
		}
		$this->model_setting_event->deleteEventByCode('tmd_customerimportexport');
		$eventrequest=[
					'code'=>'tmd_customerimportexport',
					'description'=>'TMD Seo combo Import Export',
					'trigger'=>'admin/view/common/column_left/before',
					'action'=>$eventaction,
					'status'=>'1',
					'sort_order'=>'1',
				];
		if(VERSION=='4.0.0.0')
		{
		$this->model_setting_event->addEvent('tmd_customerimportexport', 'TMD Seo combo Import Export', 'admin/view/common/column_left/before','extension/tmdseourlcomboofimportexport/module/tmdseoimportexport|menu', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}	
	}

	public function uninstall(): void{
		$this->load->language('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');
		// Register events
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('tmd_customerimportexport');
		
		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');
		
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdseourlcomboofimportexport/tmd/importseo');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlcomboofimportexport/tmd/importseo');
		
	}

	public function menu(string &$route, array &$args, mixed &$output): void{	
		$this->load->language('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');

		$modulestatus=$this->config->get('module_tmdseoimportexport_status');
		if(!empty($modulestatus)){
			$this->load->language('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');
			
			$tmdseourlcomboofimportexport = [];
				
			if ($this->user->hasPermission('access', 'extension/tmdseourlcomboofimportexport/tmd/importseo')) {
				$tmdseourlcomboofimportexport[] = [
					'name'	   => $this->language->get('text_importseo'),
					'href'     => $this->url->link('extension/tmdseourlcomboofimportexport/tmd/importseo', 'user_token=' . $this->session->data['user_token']),
					'children' => []		
				];
			}
				
			if ($tmdseourlcomboofimportexport) {					
				$args['menus'][] = [
					'id'       => 'menu-extension',
					'icon'	   => 'far fa-file-excel', 
					'name'	   => $this->language->get('text_importseo'),
					'href'     => '',
					'children' => $tmdseourlcomboofimportexport
				];	
			}
		}
	}


	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/tmdseourlcomboofimportexport/module/tmdseoimportexport');
		
		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdseourlcomboofimportexport/module/tmdseoimportexport')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		$tmdseoimportexport=$this->config->get('tmdkey_tmdseoimportexport');
		if (empty(trim($tmdseoimportexport))) {			
		$json['error'] ='Module will Work after add License key!';
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('module_tmdseoimportexport', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmdseoimportexport',
			'eid'=>'MjUzMTk=',
			'route'=>'extension/tmdseourlcomboofimportexport/module/tmdseoimportexport',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new  \Tmdseourlcomboofimportexport\System\Library\Tmd\System($this->registry));
		
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}