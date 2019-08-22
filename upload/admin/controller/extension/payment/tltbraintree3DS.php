<?php
class ControllerExtensionPaymentTltBraintree3DS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/tltbraintree3DS');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if ($this->checkLibrary()) {
            $data['braintree'] = '';
        } else {
            $data['braintree'] = $this->language->get('text_braintree');
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('tltbraintree3DS', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_charge'] = $this->language->get('text_charge');
		$data['text_default_currency'] = $this->language->get('text_default_currency');
		$data['text_copyright'] = '&copy; ' . date('Y') . ', <a href="https://taiwanleaftea.com" target="_blank" class="alert-link" title="Authentic tea from Taiwan">Taiwanleaftea.com</a>';
		$data['text_donation'] = 'If you find this software usefull and to support further development please buy me a cup of <a href="https://taiwanleaftea.com" class="alert-link" target="_blank" title="Authentic tea from Taiwan">tea</a> using this <a href="https://www.paypal.me/AMamykin/10" class="alert-link" target="_blank" title="Paypal me">link</a>.';

		$data['tab_general'] = $this->language->get('tab_general');
        $data['tab_sandbox'] = $this->language->get('tab_sandbox');
        $data['tab_production'] = $this->language->get('tab_production');
        $data['tab_paypal'] = $this->language->get('tab_paypal');
        $data['tab_googlepay'] = $this->language->get('tab_googlepay');

        $data['help_total'] = $this->language->get('help_total');
		$data['help_merchant_account'] = $this->language->get('help_merchant_account');		
		$data['help_use_default'] = $this->language->get('help_use_default');		
		$data['help_debug'] = $this->language->get('help_debug');
        $data['help_tls12'] = $this->language->get('help_tls12');

        $data['entry_sdk'] = $this->language->get('entry_sdk');
        $data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_use_default'] = $this->language->get('entry_use_default');
		$data['entry_default_account'] = $this->language->get('entry_default_account');
		$data['entry_merchant_account'] = $this->language->get('entry_merchant_account');
        $data['entry_public'] = $this->language->get('entry_public');
		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_mode'] = $this->language->get('entry_mode');
		$data['entry_method'] = $this->language->get('entry_method');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_tls12'] = $this->language->get('entry_tls12');
        $data['entry_3d'] = $this->language->get('entry_3d');
        $data['entry_3d_version'] = $this->language->get('entry_3d_version');
        $data['entry_paypal'] = $this->language->get('entry_paypal');
        $data['entry_googlepay'] = $this->language->get('entry_googlepay');
        $data['entry_googlepay_mid'] = $this->language->get('entry_googlepay_mid');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['public'])) {
			$data['error_public'] = $this->error['public'];
		} else {
			$data['error_public'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}
        if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}
		
        if (isset($this->error['default_account'])) {
			$data['error_default_account'] = $this->error['default_account'];
		} else {
			$data['error_default_account'] = '';
		}

        if (isset($this->error['merchant_account'])) {
			$data['error_merchant_account'] = $this->error['merchant_account'];
		} else {
			$data['error_merchant_account'] = array();
		}

        if (isset($this->error['public_sandbox'])) {
            $data['error_public_sandbox'] = $this->error['public_sandbox'];
        } else {
            $data['error_public_sandbox'] = '';
        }

        if (isset($this->error['key_sandbox'])) {
            $data['error_key_sandbox'] = $this->error['key_sandbox'];
        } else {
            $data['error_key_sandbox'] = '';
        }
        if (isset($this->error['merchant_sandbox'])) {
            $data['error_merchant_sandbox'] = $this->error['merchant_sandbox'];
        } else {
            $data['error_merchant_sandbox'] = '';
        }

        if (isset($this->error['default_account_sandbox'])) {
            $data['error_default_account_sandbox'] = $this->error['default_account_sandbox'];
        } else {
            $data['error_default_account_sandbox'] = '';
        }

        if (isset($this->error['merchant_account_sandbox'])) {
            $data['error_merchant_account_sandbox'] = $this->error['merchant_account_sandbox'];
        } else {
            $data['error_merchant_account_sandbox'] = array();
        }

        if (isset($this->error['sdk'])) {
            $data['error_sdk'] = $this->error['sdk'];
        } else {
            $data['error_sdk'] = '';
        }

        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/tltbraintree3DS', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/tltbraintree3DS', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['tltbraintree3DS_merchant'])) {
			$data['tltbraintree3DS_merchant'] = $this->request->post['tltbraintree3DS_merchant'];
		} else {
			$data['tltbraintree3DS_merchant'] = $this->config->get('tltbraintree3DS_merchant');
		}

		if (isset($this->request->post['tltbraintree3DS_use_default'])) {
			$data['tltbraintree3DS_use_default'] = $this->request->post['tltbraintree3DS_use_default'];
		} elseif ($this->config->has('tltbraintree3DS_use_default')) {
			$data['tltbraintree3DS_use_default'] = $this->config->get('tltbraintree3DS_use_default');
		} else {
			$data['tltbraintree3DS_use_default'] = '1';
		}

		if (isset($this->request->post['tltbraintree3DS_default_account'])) {
			$data['tltbraintree3DS_default_account'] = $this->request->post['tltbraintree3DS_default_account'];
		} else {
			$data['tltbraintree3DS_default_account'] = $this->config->get('tltbraintree3DS_default_account');
		}

        if (isset($this->request->post['tltbraintree3DS_merchant_sandbox'])) {
            $data['tltbraintree3DS_merchant_sandbox'] = $this->request->post['tltbraintree3DS_merchant_sandbox'];
        } else {
            $data['tltbraintree3DS_merchant_sandbox'] = $this->config->get('tltbraintree3DS_merchant_sandbox');
        }

        if (isset($this->request->post['tltbraintree3DS_use_default_sandbox'])) {
            $data['tltbraintree3DS_use_default_sandbox'] = $this->request->post['tltbraintree3DS_use_default_sandbox'];
        } elseif ($this->config->has('tltbraintree3DS_use_default_sandbox')) {
            $data['tltbraintree3DS_use_default_sandbox'] = $this->config->get('tltbraintree3DS_use_default_sandbox');
        } else {
            $data['tltbraintree3DS_use_default_sandbox'] = '1';
        }

        if (isset($this->request->post['tltbraintree3DS_default_account_sandbox'])) {
            $data['tltbraintree3DS_default_account_sandbox'] = $this->request->post['tltbraintree3DS_default_account_sandbox'];
        } else {
            $data['tltbraintree3DS_default_account_sandbox'] = $this->config->get('tltbraintree3DS_default_account_sandbox');
        }

        $this->load->model('localisation/currency');
		
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		$data['default_currency'] = $this->config->get('config_currency');

		if (isset($this->request->post['tltbraintree3DS_merchant_account'])) {
			$data['tltbraintree3DS_merchant_account'] = $this->request->post['tltbraintree3DS_merchant_account'];
		} else {
			$data['tltbraintree3DS_merchant_account'] = $this->config->get('tltbraintree3DS_merchant_account');
		}

        if (isset($this->request->post['tltbraintree3DS_public_key'])) {
			$data['tltbraintree3DS_public_key'] = $this->request->post['tltbraintree3DS_public_key'];
		} else {
			$data['tltbraintree3DS_public_key'] = $this->config->get('tltbraintree3DS_public_key');
		}

        if (isset($this->request->post['tltbraintree3DS_private_key'])) {
			$data['tltbraintree3DS_private_key'] = $this->request->post['tltbraintree3DS_private_key'];
		} else {
			$data['tltbraintree3DS_private_key'] = $this->config->get('tltbraintree3DS_private_key');
		}

        if (isset($this->request->post['tltbraintree3DS_merchant_account_sandbox'])) {
            $data['tltbraintree3DS_merchant_account_sandbox'] = $this->request->post['tltbraintree3DS_merchant_account_sandbox'];
        } else {
            $data['tltbraintree3DS_merchant_account_sandbox'] = $this->config->get('tltbraintree3DS_merchant_account_sandbox');
        }

        if (isset($this->request->post['tltbraintree3DS_public_key_sandbox'])) {
            $data['tltbraintree3DS_public_key_sandbox'] = $this->request->post['tltbraintree3DS_public_key_sandbox'];
        } else {
            $data['tltbraintree3DS_public_key_sandbox'] = $this->config->get('tltbraintree3DS_public_key_sandbox');
        }

        if (isset($this->request->post['tltbraintree3DS_private_key_sandbox'])) {
            $data['tltbraintree3DS_private_key_sandbox'] = $this->request->post['tltbraintree3DS_private_key_sandbox'];
        } else {
            $data['tltbraintree3DS_private_key_sandbox'] = $this->config->get('tltbraintree3DS_private_key_sandbox');
        }

        if (isset($this->request->post['tltbraintree3DS_debug'])) {
			$data['tltbraintree3DS_debug'] = $this->request->post['tltbraintree3DS_debug'];
		} else {
			$data['tltbraintree3DS_debug'] = $this->config->get('tltbraintree3DS_debug');
		}

		if (isset($this->request->post['tltbraintree3DS_mode'])) {
			$data['tltbraintree3DS_mode'] = $this->request->post['tltbraintree3DS_mode'];
		} else {
			$data['tltbraintree3DS_mode'] = $this->config->get('tltbraintree3DS_mode');
		}

		if (isset($this->request->post['tltbraintree3DS_method'])) {
			$data['tltbraintree3DS_method'] = $this->request->post['tltbraintree3DS_method'];
		} else {
			$data['tltbraintree3DS_method'] = $this->config->get('tltbraintree3DS_method');
		}

		if (isset($this->request->post['tltbraintree3DS_order_status_id'])) {
			$data['tltbraintree3DS_order_status_id'] = $this->request->post['tltbraintree3DS_order_status_id'];
		} else {
			$data['tltbraintree3DS_order_status_id'] = $this->config->get('tltbraintree3DS_order_status_id'); 
		}

        if (isset($this->request->post['tltbraintree3DS_tls12'])) {
            $data['tltbraintree3DS_tls12'] = $this->request->post['tltbraintree3DS_tls12'];
        } else {
            $data['tltbraintree3DS_tls12'] = $this->config->get('tltbraintree3DS_tls12');
        }

        if (isset($this->request->post['tltbraintree3DS_3d'])) {
            $data['tltbraintree3DS_3d'] = $this->request->post['tltbraintree3DS_3d'];
        } else {
            $data['tltbraintree3DS_3d'] = $this->config->get('tltbraintree3DS_3d');
        }

        if (isset($this->request->post['tltbraintree3DS_3d_version'])) {
            $data['tltbraintree3DS_3d_version'] = $this->request->post['tltbraintree3DS_3d_version'];
        } elseif ($this->config->has('tltbraintree3DS_3d_version')) {
            $data['tltbraintree3DS_3d_version'] = $this->config->get('tltbraintree3DS_3d_version');
        } else {
            $data['tltbraintree3DS_3d_version'] = '1';
        }

        if (isset($this->request->post['tltbraintree3DS_paypal'])) {
            $data['tltbraintree3DS_paypal'] = $this->request->post['tltbraintree3DS_paypal'];
        } else {
            $data['tltbraintree3DS_paypal'] = $this->config->get('tltbraintree3DS_paypal');
        }

        if (isset($this->request->post['tltbraintree3DS_googlepay'])) {
            $data['tltbraintree3DS_googlepay'] = $this->request->post['tltbraintree3DS_googlepay'];
        } else {
            $data['tltbraintree3DS_googlepay'] = $this->config->get('tltbraintree3DS_googlepay');
        }

        if (isset($this->request->post['tltbraintree3DS_googlepay_mid'])) {
            $data['tltbraintree3DS_googlepay_mid'] = $this->request->post['tltbraintree3DS_googlepay_mid'];
        } else {
            $data['tltbraintree3DS_googlepay_mid'] = $this->config->get('tltbraintree3DS_googlepay_mid');
        }

        $this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['tltbraintree3DS_geo_zone_id'])) {
			$data['tltbraintree3DS_geo_zone_id'] = $this->request->post['tltbraintree3DS_geo_zone_id'];
		} else {
			$data['tltbraintree3DS_geo_zone_id'] = $this->config->get('tltbraintree3DS_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['tltbraintree3DS_status'])) {
			$data['tltbraintree3DS_status'] = $this->request->post['tltbraintree3DS_status'];
		} else {
			$data['tltbraintree3DS_status'] = $this->config->get('tltbraintree3DS_status');
		}

		if (isset($this->request->post['tltbraintree3DS_total'])) {
			$data['tltbraintree3DS_total'] = $this->request->post['tltbraintree3DS_total'];
		} else {
			$data['tltbraintree3DS_total'] = $this->config->get('tltbraintree3DS_total');
		}

		if (isset($this->request->post['tltbraintree3DS_sort_order'])) {
			$data['tltbraintree3DS_sort_order'] = $this->request->post['tltbraintree3DS_sort_order'];
		} else {
			$data['tltbraintree3DS_sort_order'] = $this->config->get('tltbraintree3DS_sort_order');
		}

        if (isset($this->request->post['tltbraintree3DS_sdk'])) {
            $data['tltbraintree3DS_sdk'] = $this->request->post['tltbraintree3DS_sdk'];
        } elseif ($this->config->has('tltbraintree3DS_sdk')) {
            $data['tltbraintree3DS_sdk'] = $this->config->get('tltbraintree3DS_sdk');
        } else {
            $data['tltbraintree3DS_sdk'] = 'https://js.braintreegateway.com/web/dropin/1.19.0/js/dropin.min.js';
        }

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/tltbraintree3DS.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/tltbraintree3DS')) {
			$this->error['warning'] = $this->language->get('error_permission');

            return !$this->error;
		}

        if (!$this->request->post['tltbraintree3DS_sdk']) {
            $this->error['sdk'] = $this->language->get('error_sdk');
        }

        if (!$this->request->post['tltbraintree3DS_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
        
		if (!$this->request->post['tltbraintree3DS_default_account']) {
			$this->error['default_account'] = $this->language->get('error_merchant_account');
		}

		if (strcmp($this->request->post['tltbraintree3DS_default_account'], $this->request->post['tltbraintree3DS_merchant']) === 0) {
			$this->error['default_account'] = $this->language->get('error_mismatch');
		}

		if (!$this->request->post['tltbraintree3DS_use_default']) {
			$default_currency = $this->config->get('config_currency');
			foreach ($this->request->post['tltbraintree3DS_merchant_account'] as $currency_id => $value) {
				if (!$value['code'] && $currency_id != $default_currency) {
					$this->error['merchant_account'][$currency_id] = $this->language->get('error_merchant_account');
				}
				if (strcmp($value['code'], $this->request->post['tltbraintree3DS_merchant']) === 0) {
					$this->error['merchant_account'][$currency_id] = $this->language->get('error_mismatch');
				}
				if (strcmp($value['code'], $this->request->post['tltbraintree3DS_default_account']) === 0) {
					$this->error['merchant_account'][$currency_id] = $this->language->get('error_mismatch_default');
				}
			}
		}

        if (!$this->request->post['tltbraintree3DS_public_key']) {
			$this->error['public'] = $this->language->get('error_public');
		}

		if (!$this->request->post['tltbraintree3DS_private_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

        if (!$this->request->post['tltbraintree3DS_merchant_sandbox']) {
            $this->error['merchant_sandbox'] = $this->language->get('error_merchant');
        }

        if (!$this->request->post['tltbraintree3DS_default_account_sandbox']) {
            $this->error['default_account_sandbox'] = $this->language->get('error_merchant_account');
        }

        if (strcmp($this->request->post['tltbraintree3DS_default_account_sandbox'], $this->request->post['tltbraintree3DS_merchant_sandbox']) === 0) {
            $this->error['default_account_sandbox'] = $this->language->get('error_mismatch');
        }

        if (!$this->request->post['tltbraintree3DS_use_default_sandbox']) {
            $default_currency = $this->config->get('config_currency');
            foreach ($this->request->post['tltbraintree3DS_merchant_account_sandbox'] as $currency_id => $value) {
                if (!$value['code'] && $currency_id != $default_currency) {
                    $this->error['merchant_account_sandbox'][$currency_id] = $this->language->get('error_merchant_account');
                }
                if (strcmp($value['code'], $this->request->post['tltbraintree3DS_merchant_sandbox']) === 0) {
                    $this->error['merchant_account_sandbox'][$currency_id] = $this->language->get('error_mismatch');
                }
                if (strcmp($value['code'], $this->request->post['tltbraintree3DS_default_account_sandbox']) === 0) {
                    $this->error['merchant_account_sandbox'][$currency_id] = $this->language->get('error_mismatch_default');
                }
            }
        }

        if (!$this->request->post['tltbraintree3DS_public_key_sandbox']) {
            $this->error['public_sandbox'] = $this->language->get('error_public');
        }

        if (!$this->request->post['tltbraintree3DS_private_key_sandbox']) {
            $this->error['key_sandbox'] = $this->language->get('error_key');
        }

        if ($this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
	}

    /**
     * Check and load Braintree library
     *
     * @return bool
     */
    private function checkLibrary()
    {
        if (file_exists(DIR_SYSTEM . '../../vendor/braintree/braintree_php/lib/Braintree.php') || file_exists(DIR_SYSTEM . 'braintree/lib/Braintree.php')) {
            return true;
        } else {
            return false;
        }
    }
}