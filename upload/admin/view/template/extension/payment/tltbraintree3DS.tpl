<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
  		<?php if ($braintree) { ?>
            <button type="button" form="form-braintree-tlt" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary disabled" onclick="#"><i class="fa fa-save"></i></button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
  		<?php } else { ?>
            <button type="submit" form="form-braintree-tlt" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
  		<?php } ?>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php if ($braintree) { ?>
      <div class="container-fluid">
        <div class="alert alert-danger">
        	<i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $braintree; ?><br />
        </div>
      </div>
  <?php } ?>
  <div class="container-fluid">
	<div class="alert alert-info">
    		<i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_donation; ?><br />
  			<?php echo $text_copyright; ?>
  	</div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tltbraintree3DS" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-sandbox" data-toggle="tab"><?php echo $tab_sandbox; ?></a></li>
            <li><a href="#tab-production" data-toggle="tab"><?php echo $tab_production; ?></a></li>
            <li><a href="#tab-paypal" data-toggle="tab"><?php echo $tab_paypal; ?></a></li>
            <li><a href="#tab-googlepay" data-toggle="tab"><?php echo $tab_googlepay; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-sdk"><?php echo $entry_sdk; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_sdk" value="<?php echo $tltbraintree3DS_sdk; ?>" placeholder="<?php echo $entry_sdk; ?>" id="input-sdk" class="form-control" />
                  <?php if ($error_sdk) { ?>
                  <div class="text-danger"><?php echo $error_sdk; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_total" value="<?php echo $tltbraintree3DS_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-mode"><?php echo $entry_mode; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_mode" id="input-mode" class="form-control">
                    <?php if ($tltbraintree3DS_mode == 'sandbox') { ?>
                    <option value="sandbox" selected="selected"><?php echo $text_test; ?></option>
                    <?php } else { ?>
                    <option value="sandbox"><?php echo $text_test; ?></option>
                    <?php } ?>
                    <?php if ($tltbraintree3DS_mode == 'production') { ?>
                    <option value="production" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="production"><?php echo $text_live; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-method"><?php echo $entry_method; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_method" id="input-method" class="form-control">
                    <?php if ($tltbraintree3DS_method == 'authorization') { ?>
                    <option value="authorization" selected="selected"><?php echo $text_authorization; ?></option>
                    <?php } else { ?>
                    <option value="authorization"><?php echo $text_authorization; ?></option>
                    <?php } ?>
                    <?php if ($tltbraintree3DS_method == 'charge') { ?>
                    <option value="charge" selected="selected"><?php echo $text_charge; ?></option>
                    <?php } else { ?>
                    <option value="charge"><?php echo $text_charge; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-3d"><?php echo $entry_3d; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_3d" id="input-3d" class="form-control">
                    <?php if ($tltbraintree3DS_3d) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-3d-version"><?php echo $entry_3d_version; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_3d_version" id="input-3d-version" class="form-control">
                    <?php if ($tltbraintree3DS_3d_version == '1') { ?>
                    <option value="1" selected="selected">1.0</option>
                    <option value="2">2.0</option>
                    <?php } else { ?>
                    <option value="1">1.0</option>
                    <option value="2" selected="selected">2.0</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_order_status_id" id="input-order-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $tltbraintree3DS_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $tltbraintree3DS_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_debug" id="input-debug" class="form-control">
                    <?php if ($tltbraintree3DS_debug) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-tls12"><span data-toggle="tooltip" title="<?php echo $help_tls12; ?>"><?php echo $entry_tls12; ?></span></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_tls12" id="input-tls12" class="form-control">
                    <?php if ($tltbraintree3DS_tls12) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_sort_order" value="<?php echo $tltbraintree3DS_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_status" id="input-status" class="form-control">
                    <?php if ($tltbraintree3DS_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-sandbox">
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-merchant-sandbox"><?php echo $entry_merchant; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_merchant_sandbox" value="<?php echo $tltbraintree3DS_merchant_sandbox; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant-sandbox" class="form-control" />
                  <?php if ($error_merchant_sandbox) { ?>
                  <div class="text-danger"><?php echo $error_merchant_sandbox; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-use-default-sandbox"><span data-toggle="tooltip" title="<?php echo $help_use_default; ?>"><?php echo $entry_use_default; ?>&nbsp;(<?php echo $default_currency; ?>)</span></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_use_default_sandbox" id="input-use-default-sandbox" class="form-control">
                    <?php if ($tltbraintree3DS_use_default_sandbox) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-default-account-sandbox"><?php echo $entry_default_account; ?>&nbsp;(<?php echo $default_currency; ?>)</label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_default_account_sandbox" value="<?php echo $tltbraintree3DS_default_account_sandbox; ?>" placeholder="<?php echo $entry_default_account; ?>" id="input-default-account-sandbox" class="form-control" />
                  <?php if ($error_default_account_sandbox) { ?>
                  <div class="text-danger"><?php echo $error_default_account_sandbox; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php foreach ($currencies as $currency) { ?>
              <?php if ($currency['code'] != $default_currency) { ?>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-merchant-account-sandbox<?php echo $currency['currency_id']; ?>"><?php echo $entry_merchant_account; ?> (<?php echo $currency['code']; ?><span data-toggle="tooltip" title="<?php echo $help_merchant_account; ?>">)</span></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_merchant_account_sandbox[<?php echo $currency['currency_id']; ?>][code]" value="<?php echo isset($tltbraintree3DS_merchant_account_sandbox[$currency['currency_id']]['code']) ? $tltbraintree3DS_merchant_account_sandbox[$currency['currency_id']]['code'] : ''; ?>" placeholder="<?php echo $entry_merchant_account; ?>" id="input-merchant-account-sandbox<?php echo $currency['currency_id']; ?>" class="form-control" />
                  <?php if (isset($error_merchant_account_sandbox[$currency['currency_id']])) { ?>
                  <div class="text-danger"><?php echo $error_merchant_account_sandbox[$currency['currency_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php } ?>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-public-sandbox"><?php echo $entry_public; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_public_key_sandbox" value="<?php echo $tltbraintree3DS_public_key_sandbox; ?>" placeholder="<?php echo $entry_public; ?>" id="input-public-sandbox" class="form-control" />
                  <?php if ($error_public_sandbox) { ?>
                  <div class="text-danger"><?php echo $error_public_sandbox; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-key-sandbox"><?php echo $entry_key; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_private_key_sandbox" value="<?php echo $tltbraintree3DS_private_key_sandbox; ?>" placeholder="<?php echo $entry_key; ?>" id="input-public-sandbox" class="form-control" />
                  <?php if ($error_key_sandbox) { ?>
                  <div class="text-danger"><?php echo $error_key_sandbox; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-production">
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-merchant"><?php echo $entry_merchant; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_merchant" value="<?php echo $tltbraintree3DS_merchant; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant" class="form-control" />
                  <?php if ($error_merchant) { ?>
                  <div class="text-danger"><?php echo $error_merchant; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-use-default"><span data-toggle="tooltip" title="<?php echo $help_use_default; ?>"><?php echo $entry_use_default; ?>&nbsp;(<?php echo $default_currency; ?>)</span></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_use_default" id="input-use-default" class="form-control">
                    <?php if ($tltbraintree3DS_use_default) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-default-account"><?php echo $entry_default_account; ?>&nbsp;(<?php echo $default_currency; ?>)</label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_default_account" value="<?php echo $tltbraintree3DS_default_account; ?>" placeholder="<?php echo $entry_default_account; ?>" id="input-default-account" class="form-control" />
                  <?php if ($error_default_account) { ?>
                  <div class="text-danger"><?php echo $error_default_account; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php foreach ($currencies as $currency) { ?>
                  <?php if ($currency['code'] != $default_currency) { ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-merchant-account<?php echo $currency['currency_id']; ?>"><?php echo $entry_merchant_account; ?> (<?php echo $currency['code']; ?><span data-toggle="tooltip" title="<?php echo $help_merchant_account; ?>">)</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="tltbraintree3DS_merchant_account[<?php echo $currency['currency_id']; ?>][code]" value="<?php echo isset($tltbraintree3DS_merchant_account[$currency['currency_id']]['code']) ? $tltbraintree3DS_merchant_account[$currency['currency_id']]['code'] : ''; ?>" placeholder="<?php echo $entry_merchant_account; ?>" id="input-merchant-account<?php echo $currency['currency_id']; ?>" class="form-control" />
                            <?php if (isset($error_merchant_account[$currency['currency_id']])) { ?>
                            <div class="text-danger"><?php echo $error_merchant_account[$currency['currency_id']]; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                  <?php } ?>
              <?php } ?>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-public"><?php echo $entry_public; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_public_key" value="<?php echo $tltbraintree3DS_public_key; ?>" placeholder="<?php echo $entry_public; ?>" id="input-public" class="form-control" />
                  <?php if ($error_public) { ?>
                  <div class="text-danger"><?php echo $error_public; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-key"><?php echo $entry_key; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_private_key" value="<?php echo $tltbraintree3DS_private_key; ?>" placeholder="<?php echo $entry_key; ?>" id="input-public" class="form-control" />
                  <?php if ($error_key) { ?>
                  <div class="text-danger"><?php echo $error_key; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-paypal">
              <div class="form-group">
                <label class="col-sm-3 col-form-label" for="input-paypal"><?php echo $entry_paypal; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_paypal" id="input-paypal" class="form-control">
                    <?php if ($tltbraintree3DS_paypal) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-googlepay">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="input-googlepay-mid"><?php echo $entry_googlepay_mid; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="tltbraintree3DS_googlepay_mid" value="<?php echo $tltbraintree3DS_googlepay_mid; ?>" placeholder="<?php echo $entry_googlepay_mid; ?>" id="input-googlepay-mid" class="form-control" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="input-googlepay"><?php echo $entry_googlepay; ?></label>
                <div class="col-sm-9">
                  <select name="tltbraintree3DS_googlepay" id="input-googlepay" class="form-control">
                    <?php if ($tltbraintree3DS_googlepay) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>