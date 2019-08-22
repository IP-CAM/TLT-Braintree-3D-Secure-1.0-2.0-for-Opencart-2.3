<?php
define('DROPIN', true);

class ControllerExtensionPaymentTltBraintree3DS extends Controller {
	public function index() {
		$this->language->load('extension/payment/tltbraintree3DS');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_gateway_loading'] = $this->language->get('text_gateway_loading');
		$data['text_help'] = $this->language->get('text_help');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_reinit'] = $this->language->get('text_reinit');

        $data['entry_number'] = $this->language->get('entry_number');
        $data['entry_date'] = $this->language->get('entry_date');
        $data['entry_cvv'] = $this->language->get('entry_cvv');

        $data['error_3D'] = $this->language->get('error_3D');
        $data['error_loading'] = $this->language->get('error_loading');
        $data['error_payment_method'] = $this->language->get('error_payment_method');
        $data['error_form'] = $this->language->get('error_form');
        $data['error_card'] = $this->language->get('error_card');
        $data['error_network'] = $this->language->get('error_network');

		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$debugmode = $data['debug'] = $this->config->get('tltbraintree3DS_debug');

        if ($this->loadLibrary()) {
            $data['error_library'] = '';
		} else {
			$this->log->write('BRAINTREE: Cannot load library.');
			$data['error_library'] = $this->language->get('error_library');

            return $this->load->view('extension/payment/tltbraintree3DS', $data);
		}

		if ($this->config->get('tltbraintree3DS_mode') == 'sandbox') {
            $braintreemode = 'BRAINTREE Sandbox: ';
            $data['testmode'] = true;
            $isProduction = false;

            $gateway = new Braintree_Gateway([
                'environment' => 'sandbox',
                'merchantId' => $this->config->get('tltbraintree3DS_merchant_sandbox'),
                'publicKey' => $this->config->get('tltbraintree3DS_public_key_sandbox'),
                'privateKey' => $this->config->get('tltbraintree3DS_private_key_sandbox'),
                'sslVersion' => $this->config->get('tltbraintree3DS_tls12') ? 6 : null
            ]);
        } else {
            $braintreemode = 'BRAINTREE: ';
            $data['testmode'] = false;
            $isProduction = true;

            $gateway = new Braintree_Gateway([
                'environment' => 'production',
                'merchantId' => $this->config->get('tltbraintree3DS_merchant'),
                'publicKey' => $this->config->get('tltbraintree3DS_public_key'),
                'privateKey' => $this->config->get('tltbraintree3DS_private_key'),
                'sslVersion' => $this->config->get('tltbraintree3DS_tls12') ? 6 : null
            ]);
        }

		$paymentDetails = $this->getPaymentDetails($braintreemode, $isProduction, $debugmode);

        try {
            $data['clientToken'] = $gateway->clientToken()->generate([
                'merchantAccountId' => $paymentDetails['merchantAccountId']
            ]);
        } catch (Exception $e) {
            $this->log->write('BRAINTREE: Cannot generate client token.');

            if ($debugmode) {
                $excmessage = 'Code: ' . $e->getCode() . ' ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine();
                $debuginfo = $e->getTraceAsString();
                $this->log->write('Error! ' . $excmessage);
                $this->log->write('Info: ' . $debuginfo);
            }

            $data['clientToken'] = '';
            $data['error_library'] = $this->language->get('error_connection');

            return $this->load->view('extension/payment/tltbraintree3DS', $data);
        }

        $order_info = $paymentDetails['orderInfo'];

        $data['paypal'] = $this->config->get('tltbraintree3DS_paypal');

        if ($isProduction) {
            $data['googlepay'] = $this->config->get('tltbraintree3DS_googlepay_mid') ? $this->config->get('tltbraintree3DS_googlepay') : 0;

            $this->log->write('TLT Braintree 3DS: Google Merchant ID needed for production! Google Pay disabled.');
        } else {
            $data['googlepay'] = $this->config->get('tltbraintree3DS_googlepay');
        }

        $data['googlepay_mid'] = $this->config->get('tltbraintree3DS_googlepay_mid');

        $data['config_name'] = $this->config->get('config_name');

        $data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], '', false);

        $data['currency'] = $order_info['currency_code'];

        $data['sdk'] = $this->config->get('tltbraintree3DS_sdk');

        // Set up 3D version
        $data['threeDSecureEnabled'] = $this->config->get('tltbraintree3DS_3d');
        $data['threeDSecureVersion'] = ($data['threeDSecureEnabled'] && $this->config->has('tltbraintree3DS_3d_version')) ? $this->config->get('tltbraintree3DS_3d_version') : '1';

        // Fill data for 3DS 2.0
        if ($data['threeDSecureEnabled'] && $data['threeDSecureVersion'] == '2') {
            $this->load->model('localisation/country');
            $this->load->model('localisation/zone');

            $country = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
            $zone = $order_info['payment_zone_id'] ? $this->model_localisation_zone->getZone($order_info['payment_zone_id']) : array();

            $data['email'] = $order_info['email'];

            $data['billingAddress'] = array(
                'givenName' => $order_info['payment_firstname'],
                'surname' => $order_info['payment_lastname'],
                'phoneNumber' => preg_replace('~\D+~', '', $order_info['telephone']),
                'streetAddress' => $order_info['payment_address_1'],
                'extendedAddress' => $order_info['payment_address_2'],
                'locality' => $order_info['payment_city'],
                'region' => isset($zone['code']) ? $zone['code'] : '',
                'postalCode' => $order_info['payment_postcode'],
                'countryCodeAlpha2' => isset($country['iso_code_2']) ? $country['iso_code_2'] : ''
            );

            $country = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
            $zone = $order_info['payment_zone_id'] ? $this->model_localisation_zone->getZone($order_info['shipping_zone_id']) : array();

            $data['additionalInformation'] = array(
                'shippingGivenName' => $order_info['shipping_firstname'],
                'shippingSurname' => $order_info['shipping_lastname'],
                'shippingPhone' => preg_replace('~\D+~', '', $order_info['telephone']),
                'streetAddress' => $order_info['shipping_address_1'],
                'extendedAddress' => $order_info['shipping_address_2'],
                'locality' => $order_info['shipping_city'],
                'region' => isset($zone['code']) ? $zone['code'] : '',
                'postalCode' => $order_info['shipping_postcode'],
                'countryCodeAlpha2' => isset($country['iso_code_2']) ? $country['iso_code_2'] : ''
            );
        }

        $this->load->model('extension/payment/tltbraintree3DS');

        $locales = $this->model_extension_payment_tltbraintree3DS->locales();

        $data['locale'] = isset($locales[$this->config->get('config_language')]) ? $locales[$this->config->get('config_language')] : 'en_US';

		$data['text_testmode'] = $this->language->get('text_testmode');

		if ($data['threeDSecureVersion'] == '2' && !DROPIN) {
            return $this->load->view('extension/payment/tltbraintree3DS20', $data);
        } else {
            return $this->load->view('extension/payment/tltbraintree3DS', $data);
        }
	}

    /**
     * Make the payment
     * $_POST["payment_method_nonce"] nonce from Braintree Client SDK
     */
	public function send() {
        $this->loadLibrary();

        if ($this->config->get('tltbraintree3DS_mode') == 'sandbox') {
            $braintreemode = 'BRAINTREE Sandbox: ';
            $data['testmode'] = true;
            $isProduction = false;

            $gateway = new Braintree_Gateway([
                'environment' => 'sandbox',
                'merchantId' => $this->config->get('tltbraintree3DS_merchant_sandbox'),
                'publicKey' => $this->config->get('tltbraintree3DS_public_key_sandbox'),
                'privateKey' => $this->config->get('tltbraintree3DS_private_key_sandbox'),
                'sslVersion' => $this->config->get('tltbraintree3DS_tls12') ? 6 : null
            ]);
        } else {
            $braintreemode = 'BRAINTREE: ';
            $data['testmode'] = false;
            $isProduction = true;

            $gateway = new Braintree_Gateway([
                'environment' => 'production',
                'merchantId' => $this->config->get('tltbraintree3DS_merchant'),
                'publicKey' => $this->config->get('tltbraintree3DS_public_key'),
                'privateKey' => $this->config->get('tltbraintree3DS_private_key'),
                'sslVersion' => $this->config->get('tltbraintree3DS_tls12') ? 6 : null
            ]);
        }
		
		$debugmode = $this->config->get('tltbraintree3DS_debug');

        $paymentDetails = $this->getPaymentDetails($braintreemode, $isProduction, $debugmode);

        $amount = $paymentDetails['amount'];

        $merchantAccountId = $paymentDetails['merchantAccountId'];

        $order_info = $paymentDetails['orderInfo'];

        if ($debugmode) {
            $this->log->write('Merchant Account: ' . $merchantAccountId);
        }

        if ($this->config->get('tltbraintree3DS_method') == 'charge') {
            $submitForSettlement = 'true';
        } else {
            $submitForSettlement = 'false';
        }

        $nonce = $_POST["payment_method_nonce"];

        $json = array();
        $result = null;

        try {
            $result = $gateway->transaction()->sale([
                'amount' => $amount,
                'paymentMethodNonce' => $nonce,
                'merchantAccountId' => $merchantAccountId,
                'orderId' => $this->session->data['order_id'],
                'options' => [
                    'submitForSettlement' => $submitForSettlement
                ],
                'customer' => [
                    'firstName' => html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8'),
                    'lastName' => html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8'),
                    'email' => $order_info['email']
                ]
            ]);

            if ($result->success) {
                if ($debugmode) {
                    $this->log->write($braintreemode . $result);
                }

                $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('tltbraintree3DS_order_status_id'), $result->transaction->status, false);
                $json['success'] = $this->url->link('checkout/success');
            } elseif ($result->transaction) {
                $this->log->write($braintreemode . 'Error! ' . $result->transaction->processorResponseCode . '. ' . $result->transaction->processorResponseText);
                $this->load->language('extension/payment/tltbraintree3DS');
                if (in_array($result->transaction->processorResponseCode, array('2000', '2001', '2002', '2003', '2038', '2046'))) {
                    $json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_bank');
                    $json['redirect'] = $this->url->link('checkout/checkout', '', true);
                } elseif ($result->transaction->processorResponseCode == '3000') {
                    $json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_connection');
                    $json['redirect'] = $this->url->link('checkout/checkout', '', true);
                } else {
                    $json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_transaction');
                }
            } else {
                $this->log->write($braintreemode . sizeof($result->errors) . ' total error(s)');
                foreach($result->errors->deepAll() as $error) {
                    $this->log->write($braintreemode . 'Error! ' . $error->code . ". " . $error->message);
                }
                $this->load->language('extension/payment/tltbraintree3DS');
                $json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_transaction');
            }
        } catch (Exception $e) {
            $this->load->language('extension/payment/tltbraintree3DS');
            $excmessage = 'Code ' . $e->getCode() . ' ' . $e->getMessage() . ' File ' . $e->getFile() . ' Line ' . $e->getLine();
            $debuginfo = $e->getTraceAsString();
            $this->log->write($braintreemode . 'Error! ' . $excmessage);

            if ($debugmode) {
                $this->log->write('Debug Info: ' . $debuginfo);
            }

            $json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_connection');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    /**
     * Generate client token
     */
	public function generateToken()
    {
        $debugmode = $data['debug'] = $this->config->get('tltbraintree3DS_debug');

        if ($this->config->get('tltbraintree3DS_mode') == 'sandbox') {
            $braintreemode = 'BRAINTREE Sandbox: ';
            $data['testmode'] = true;
            $isProduction = false;

            $gateway = new Braintree_Gateway([
                'environment' => 'sandbox',
                'merchantId' => $this->config->get('tltbraintree3DS_merchant_sandbox'),
                'publicKey' => $this->config->get('tltbraintree3DS_public_key_sandbox'),
                'privateKey' => $this->config->get('tltbraintree3DS_private_key_sandbox'),
                'sslVersion' => $this->config->get('tltbraintree3DS_tls12') ? 6 : null
            ]);
        } else {
            $braintreemode = 'BRAINTREE: ';
            $data['testmode'] = false;
            $isProduction = true;

            $gateway = new Braintree_Gateway([
                'environment' => 'production',
                'merchantId' => $this->config->get('tltbraintree3DS_merchant'),
                'publicKey' => $this->config->get('tltbraintree3DS_public_key'),
                'privateKey' => $this->config->get('tltbraintree3DS_private_key'),
                'sslVersion' => $this->config->get('tltbraintree3DS_tls12') ? 6 : null
            ]);
        }

        $paymentDetails = $this->getPaymentDetails($braintreemode, $isProduction, $debugmode);

        try {
            $json['clientToken'] = $gateway->clientToken()->generate([
                'merchantAccountId' => $paymentDetails['merchantAccountId']
            ]);
        } catch (Exception $e) {
            $this->log->write('BRAINTREE: Cannot generate client token.');

            if ($debugmode) {
                $excmessage = 'Code: ' . $e->getCode() . ' ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine();
                $debuginfo = $e->getTraceAsString();
                $this->log->write('Error! ' . $excmessage);
                $this->log->write('Info: ' . $debuginfo);
            }

            $json['clientToken'] = '';
            $json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_connection');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
     * Get payment details
     *
     * @param string $braintreemode prefix for error log
     * @param bool $isProduction
     * @param bool $debugmode
     * @return array
     */
    private function getPaymentDetails($braintreemode, $isProduction = false, $debugmode = true)
    {
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $currency = $default_currency = $this->config->get('config_currency');

        if (($isProduction && $this->config->get('tltbraintree3DS_use_default')) || (!$isProduction && $this->config->get('tltbraintree3DS_use_default_sandbox')) || ($order_info['currency_code'] == $default_currency)) {
            $merchantAccountId = $isProduction ? $this->config->get('tltbraintree3DS_default_account') : $this->config->get('tltbraintree3DS_default_account_sandbox');

            $amount = $this->currency->format($order_info['total'], $default_currency, '', false);

            if ($order_info['currency_code'] != $default_currency) {
                $this->log->write($braintreemode . 'Warning! Order currency differs from the Merchant Account currency. The card will be charged for ' . $amount . ' ' . $default_currency);
            } elseif ($debugmode) {
                $this->log->write($braintreemode . 'The card will be charged for ' . $amount . ' ' . $default_currency);
            }
        } else {
            $merchantAccountArray = $isProduction ? $this->config->get('tltbraintree3DS_merchant_account') : $this->config->get('tltbraintree3DS_merchant_account_sandbox');

            if (isset($merchantAccountArray[$order_info['currency_id']])) {
                $merchantAccountId = $merchantAccountArray[$order_info['currency_id']]['code'];

                if ($merchantAccountId) {
                    $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], '', false);
                    if ($debugmode) {
                        $this->log->write($braintreemode . 'The card will be charged for ' . $amount . ' ' . $order_info['currency_code']);

                        $currency = $order_info['currency_code'];
                    }
                } else {
                    $merchantAccountId = $isProduction ? $this->config->get('tltbraintree3DS_default_account') : $this->config->get('tltbraintree3DS_default_account_sandbox');

                    $amount = $this->currency->format($order_info['total'], $default_currency, '', false);

                    $this->log->write($braintreemode . 'Warning! Merchant Account is not set for the order currency ('. $order_info['currency_code'] .'). Default merchant account (' . $default_currency .') will be used. The card will be charged for ' . $amount . ' ' . $default_currency);
                }
            } else {
                $merchantAccountId = $isProduction ? $this->config->get('tltbraintree3DS_default_account') : $this->config->get('tltbraintree3DS_default_account_sandbox');

                $amount = $this->currency->format($order_info['total'], $default_currency, '', false);

                $this->log->write($braintreemode . 'Warning! Merchant Account is not set for the order currency ('. $order_info['currency_code'] .'). Default merchant account (' . $default_currency .') will be used. The card will be charged for ' . $amount . ' ' . $default_currency);
            }
        }

        return array(
            'amount' => $amount,
            'currency' => $currency,
            'merchantAccountId' => $merchantAccountId,
            'orderInfo' => $order_info
        );
    }

    /**
     * Check and load Braintree library
     *
     * @return bool
     */
    private function loadLibrary()
    {
        if (file_exists(DIR_SYSTEM . '../../vendor/braintree/braintree_php/lib/Braintree.php')) {
            require_once(DIR_SYSTEM . '../../vendor/braintree/braintree_php/lib/Braintree.php');

            return true;
        } elseif (file_exists(DIR_SYSTEM . 'braintree/lib/Braintree.php')) {
            require_once(DIR_SYSTEM . 'braintree/lib/Braintree.php');

            return true;
        } else {
            return false;
        }
    }
}
