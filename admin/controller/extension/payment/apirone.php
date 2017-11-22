<?php
class ControllerExtensionPaymentApirone extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/apirone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_apirone', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_merchant'] = $this->language->get('entry_merchant');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_canceled_status'] = $this->language->get('entry_canceled_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');

		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_test_mode'] = $this->language->get('entry_test_mode');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/apirone', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/apirone', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$secret = $this->config->get('payment_apirone_secret');


		if ($this->config->get('payment_apirone_secret') == null) {
			$data['payment_apirone_secret'] = $secret = md5(time().$this->session->data['user_token']);
		} else {
			$data['payment_apirone_secret'] = $this->config->get('payment_apirone_secret');
		}

		if (isset($this->request->post['payment_apirone_merchant'])) {
			$data['payment_apirone_merchant'] = $this->request->post['payment_apirone_merchant'];
		} else {
			$data['payment_apirone_merchant'] = $this->config->get('payment_apirone_merchant');
		}

		if (isset($this->request->post['payment_apirone_order_status_id'])) {
			$data['payment_apirone_order_status_id'] = $this->request->post['payment_apirone_order_status_id'];
		} else {
			$data['payment_apirone_order_status_id'] = $this->config->get('payment_apirone_order_status_id');
		}
		
		if (isset($this->request->post['payment_apirone_canceled_status_id'])) {
			$data['payment_apirone_canceled_status_id'] = $this->request->post['payment_apirone_canceled_status_id'];
		} else {
			$data['payment_apirone_canceled_status_id'] = $this->config->get('payment_apirone_canceled_status_id');
		}
		
		if (isset($this->request->post['payment_apirone_pending_status_id'])) {
			$data['payment_apirone_pending_status_id'] = $this->request->post['payment_apirone_pending_status_id'];
		} else {
			$data['payment_apirone_pending_status_id'] = $this->config->get('payment_apirone_pending_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payment_apirone_geo_zone_id'])) {
			$data['payment_apirone_geo_zone_id'] = $this->request->post['payment_apirone_geo_zone_id'];
		} else {
			$data['payment_apirone_geo_zone_id'] = $this->config->get('payment_apirone_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_apirone_test_mode'])) {
			$data['payment_apirone_test_mode'] = $this->request->post['payment_apirone_test_mode'];
		} else {
			$data['payment_apirone_test_mode'] = $this->config->get('payment_apirone_test_mode');
		}

		if (isset($this->request->post['payment_apirone_status'])) {
			$data['payment_apirone_status'] = $this->request->post['payment_apirone_status'];
		} else {
			$data['payment_apirone_status'] = $this->config->get('payment_apirone_status');
		}

		if (isset($this->request->post['payment_apirone_sort_order'])) {
			$data['payment_apirone_sort_order'] = $this->request->post['payment_apirone_sort_order'];
		} else {
			$data['payment_apirone_sort_order'] = $this->config->get('payment_apirone_sort_order');
		}

		if (isset($this->request->post['payment_apirone_sort_canceled'])) {
			$data['payment_apirone_sort_canceled'] = $this->request->post['payment_apirone_sort_canceled'];
		} else {
			$data['payment_apirone_sort_canceled'] = $this->config->get('payment_apirone_sort_canceled');
		}
		
		if (isset($this->request->post['payment_apirone_sort_pending'])) {
			$data['payment_apirone_sort_pending'] = $this->request->post['payment_apirone_sort_pending'];
		} else {
			$data['payment_apirone_sort_pending'] = $this->config->get('payment_apirone_sort_pending');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/apirone', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/apirone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_apirone_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		return !$this->error;
	}
}