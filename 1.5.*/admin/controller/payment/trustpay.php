<?php 
class ControllerPaymenttrustpay extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('payment/trustpay');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('trustpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
				
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_vendor_id'] = $this->language->get('entry_vendor_id');
		$this->data['entry_notification_url'] = $this->language->get('entry_notification_url');
		$this->data['entry_shared_secret'] = $this->language->get('entry_shared_secret');		
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['help_total'] = $this->language->get('help_total');
                $this->data['help_vendor_id'] = $this->language->get('help_vendor_id');
                $this->data['help_notification_url'] = $this->language->get('help_notification_url');
                $this->data['help_shared_secret'] = $this->language->get('help_shared_secret');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

	$this->data['breadcrumbs'] = array();

                $this->data['breadcrumbs'][] = array(
                        'text'      => $this->language->get('text_home'),
                        'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
'separator' => false
                );

                $this->data['breadcrumbs'][] = array(
                        'text'      => $this->language->get('text_payment'),
                        'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
                        'separator' => ' :: '
                );

                $this->data['breadcrumbs'][] = array(
                        'text'      => $this->language->get('heading_title'),
                        'href'      => $this->url->link('payment/trustpay', 'token=' . $this->session->data['token'], 'SSL'),
                        'separator' => ' :: '
                );	
		
		$this->data['action'] = $this->url->link('payment/trustpay', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	
		
		if (isset($this->request->post['trustpay_vendor_id'])) {
			$this->data['trustpay_vendor_id'] = $this->request->post['trustpay_vendor_id'];
		} else {
			$this->data['trustpay_vendor_id'] = $this->config->get('trustpay_vendor_id'); 
		}
		if (isset($this->request->post['trustpay_notification_url'])) {
			$this->data['trustpay_notification_url'] = $this->request->post['trustpay_notification_url'];
		} else {
			$this->data['trustpay_notification_url'] = $this->config->get('trustpay_notification_url'); 
		}
		if (isset($this->request->post['trustpay_shared_secret'])) {
			$this->data['trustpay_shared_secret'] = $this->request->post['trustpay_shared_secret'];
		} else {
			$this->data['trustpay_shared_secret'] = $this->config->get('trustpay_shared_secret'); 
		}
		
		if (isset($this->request->post['trustpay_total'])) {
			$this->data['trustpay_total'] = $this->request->post['trustpay_total'];
		} else {
			$this->data['trustpay_total'] = $this->config->get('trustpay_total'); 
		}
				
		if (isset($this->request->post['trustpay_order_status_id'])) {
			$this->data['trustpay_order_status_id'] = $this->request->post['trustpay_order_status_id'];
		} else {
			$this->data['trustpay_order_status_id'] = $this->config->get('trustpay_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['trustpay_geo_zone_id'])) {
			$this->data['trustpay_geo_zone_id'] = $this->request->post['trustpay_geo_zone_id'];
		} else {
			$this->data['trustpay_geo_zone_id'] = $this->config->get('trustpay_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');						
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['trustpay_status'])) {
			$this->data['trustpay_status'] = $this->request->post['trustpay_status'];
		} else {
			$this->data['trustpay_status'] = $this->config->get('trustpay_status');
		}
		
		if (isset($this->request->post['trustpay_sort_order'])) {
			$this->data['trustpay_sort_order'] = $this->request->post['trustpay_sort_order'];
		} else {
			$this->data['trustpay_sort_order'] = $this->config->get('trustpay_sort_order');
		}
		
		$this->template = 'payment/trustpay.tpl';
                $this->children = array(
                        'common/header',
                        'common/footer'
                );

                $this->response->setOutput($this->render());	
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/trustpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		return !$this->error;
	}
}
