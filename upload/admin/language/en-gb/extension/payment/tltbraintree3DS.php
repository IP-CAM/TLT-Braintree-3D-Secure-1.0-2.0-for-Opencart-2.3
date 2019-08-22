<?php
// Heading
$_['heading_title']     = 'TLT Braintree 3DS';

// Text
$_['text_payment']		= 'Payment';
$_['text_success']		= 'Success: You have modified Braintree account details!';
$_['text_tltbraintree3DS']= '<img src="view/image/payment/tltbraintree3DS.png" alt="TLT Braintree 3DS" title="TLT Braintree 3DS" style="border: 1px solid #EEEEEE;" />';
$_['text_test']         = 'Sandbox';
$_['text_live']         = 'Production';
$_['text_authorization']= 'Authorization';
$_['text_charge']       = 'Charge';
$_['text_edit']          = 'Edit TLT Braintree';
$_['text_default_currency']   = '<b>Default Currency</b>. ';
$_['text_braintree']    = 'Braintree library is not installed. Please download and install it from <a target="new" href="https://developers.braintreepayments.com/start/hello-server/php">here</a>.';

// Tabs
$_['tab_general']       = 'General';
$_['tab_sandbox']       = 'Sandbox';
$_['tab_production']    = 'Production';
$_['tab_paypal']        = 'PayPal';
$_['tab_googlepay']     = 'Google Pay';

// Entry
$_['entry_sdk']     	 = 'Javascript SDK';
$_['entry_merchant']     = 'Merchant ID';
$_['entry_use_default']  = 'Charge in Default Currency';
$_['entry_default_account'] = 'Default Merchant Account ID';
$_['entry_merchant_account'] = 'Merchant Account ID';
$_['entry_public']       = 'Public Key';
$_['entry_key']          = 'Private Key';
$_['entry_debug']		 = 'Debug Mode';
$_['entry_mode']         = 'Transaction Mode';
$_['entry_method']       = 'Transaction Method';
$_['entry_total']        = 'Total';
$_['entry_order_status'] = 'Order Status';
$_['entry_geo_zone']     = 'Geo Zone';
$_['entry_status']       = 'Status';
$_['entry_sort_order']   = 'Sort Order';
$_['entry_tls12']        = 'Force TLS 1.2';
$_['entry_3d']           = '3D Secure';
$_['entry_3d_version']   = '3D Secure version';
$_['entry_paypal']       = 'PayPal';
$_['entry_googlepay']    = 'Google Pay';
$_['entry_googlepay_mid']= 'Google Merchant ID';

// Help
$_['help_total']		 = 'The checkout total the order must reach before this payment method becomes active.';
$_['help_merchant_account'] = 'Enter Merchant Account ID for the currency. You should get the Merchants Accounts for all of your currencies!';
$_['help_use_default']   = 'Always use default currency for payments. If you have more currencies you must notify your customer, that the payment will be done in your default currency.';
$_['help_debug']		 = 'Logs additional information to the system log';
$_['help_tls12']         = 'Force to use TLS 1.2 environment. Readme.txt for more information.';

// Error
$_['error_warning']      = 'Warning: Please check the form carefully for errors!';
$_['error_permission']   = 'Warning: You do not have permission to modify payment: Braintree TLT edition!';
$_['error_sdk']          = 'Javascript SDK Required!';
$_['error_public']       = 'Public Key Required!';
$_['error_key']          = 'Private Key Required!';
$_['error_merchant']     = 'Merchant ID Required!';
$_['error_merchant_account']     = 'Merchant Account ID Required!';
$_['error_mismatch']     = 'Merchant ID and Merchant Account ID cannot be the same!';
$_['error_mismatch_default']     = 'Default Merchant Account ID and Merchant Account ID for another currency cannot be the same!';
