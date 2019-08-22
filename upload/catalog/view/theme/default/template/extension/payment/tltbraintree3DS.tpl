<?php if ($error_library) { ?>
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_library; ?></div>
	</div>
</div>
<?php } else { ?>
<?php if ($testmode) { ?>
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_testmode; ?></div>
	</div>
</div>
<?php } ?>
<div class="row">
	<div class="col-sm-12">
		<div id="braintree-messages" class="alert alert-danger"></div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div id="braintree-info-messages" class="alert alert-info"></div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<form id="payment-form">
			<div id="braintree-container"></div>
			<div class="buttons">
				<div class="pull-right">
					<button id="submitButton" type="submit" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" disabled><?php echo $button_confirm; ?></button>
				</div>
			</div>
		</form>
	</div>
</div>

<style>
	[data-braintree-id="choose-a-way-to-pay"] {
		font-weight: bold;
	}
</style>

<script type="text/javascript">
    var sdk = '<?php echo $sdk; ?>';
    var threeDSecureEnabled = '<?php echo $threeDSecureEnabled; ?>';
    var threeDSecureVersion = '<?php echo $threeDSecureVersion; ?>';
    var dropinInstance = null;

    var messagesContainer = $('#braintree-messages');
    var infoMessagesContainer = $('#braintree-info-messages');
    var submitButton = $('#submitButton');

    var info_message = true;
    var counter = 0;
    var debug = '<?php echo $debug; ?>' === '1';

    var processPayment = false;
    var paymentNonce = null;

    messagesContainer.hide();
	infoMessagesContainer.hide();

    if (window.braintree && window.braintree.dropin) {
        writeLog('Script ' + sdk + ' already loaded.');
    } else {
        addScript(sdk, 'dropin');
    }

	loadBraintree();

    function addScript(src, scriptID){
        var script = document.createElement('script');
        script.id = scriptID + '-script';
        script.src = src;
        script.async = true;
        document.head.appendChild(script);

        writeLog('Loading script ' + src, debug);
    }

	function dropinSetup(clientToken) {
		braintree.dropin.create({
			authorization: clientToken,
			container: '#braintree-container',
			locale: '<?php echo $locale; ?>',
			card: {
				clearFieldsAfterTokenization: false
			},
			//<?php if ($threeDSecureEnabled) { echo PHP_EOL; ?>
			threeDSecure: {
				amount: '<?php echo $amount; ?>'
			},
			//<?php } echo PHP_EOL; ?>
			//<?php if ($paypal) { echo PHP_EOL; ?>
            paypal: {
                flow: 'checkout',
                amount: '<?php echo $amount; ?>',
                currency: '<?php echo $currency; ?>',
                displayName: '<?php echo $config_name; ?>',
                locale: '<?php echo $locale; ?>',
                landingPageType: 'login',
                buttonStyle: {
                    color: 'gold',
                    label: 'paypal',
                    tagline: false
                }
            },
            //<?php } echo PHP_EOL; ?>
            //<?php if ($googlepay) { echo PHP_EOL; ?>
            googlePay: {
                googlePayVersion: 2,
                //<?php if (!$testmode ) { echo PHP_EOL; ?>
                merchantId: '<?php echo $googlepay_mid; ?>',
                //<?php } echo PHP_EOL; ?>
                transactionInfo: {
                    totalPriceStatus: 'FINAL',
                    totalPrice: '<?php echo $amount; ?>',
                    currencyCode: '<?php echo $currency; ?>'
                }
            }
            //<?php } echo PHP_EOL; ?>
        }, function (createErr, instance) {
			infoMessagesContainer.hide().html('');

			if (createErr) {
				messagesContainer.show().html('<i class="fa fa-info-circle"></i> <?php echo $error_loading; ?>');

				writeError('Drop-In create error', debug);
				writeError(createErr, debug);
			} else {
				dropinInstance = instance;

				writeLog('Drop-In created', debug);

				submitButton.prop('disabled', false).on('click', function (event) {
					event.preventDefault();

					writeLog('Submit button clicked', debug);

					messagesContainer.hide().html('');
					submitButton.button('loading');

					dropinInstance.requestPaymentMethod(
                        //<?php if ($threeDSecureVersion == '2') { echo PHP_EOL; ?> 
					    {
	                        threeDSecure: {
	                            email: "<?php echo $email; ?>",
	                            billingAddress: {
	                                givenName: "<?php echo $billingAddress['givenName']; ?>",
	                                surname: "<?php echo $billingAddress['surname']; ?>",
	                                phoneNumber: "<?php echo $billingAddress['phoneNumber']; ?>",
	                                streetAddress: "<?php echo $billingAddress['streetAddress']; ?>",
	                                extendedAddress: "<?php echo $billingAddress['extendedAddress']; ?>",
	                                locality: "<?php echo $billingAddress['locality']; ?>",
	                                region: "<?php echo $billingAddress['region']; ?>",
	                                postalCode: "<?php echo $billingAddress['postalCode']; ?>",
	                                countryCodeAlpha2: "<?php echo $billingAddress['countryCodeAlpha2']; ?>"
	                            }
	                        }
	                    }, 
						//<?php } echo PHP_EOL; ?>
						function (requestPaymentMethodErr, payload) {
						if (requestPaymentMethodErr) {
							submitButton.button('reset');
							messagesContainer.show().html('<i class="fa fa-info-circle"></i> <?php echo $error_payment_method; ?>');

							writeError('Request payment method error', debug);
							writeError(requestPaymentMethodErr, debug);
						} else {
							if (payload.liabilityShifted || threeDSecureEnabled !== '1' || payload.type !== 'CreditCard') {
								writeLog(payload, debug);
								writeLog('Will try to make payment', debug);

								makePayment(payload.nonce);
							} else {
								submitButton.button('reset');
								messagesContainer.show().html('<i class="fa fa-info-circle"></i> <?php echo $error_3D; ?>');

								dropinInstance.clearSelectedPaymentMethod();
							}
						}
					});

				});
			}
		});
	}

	function makePayment(nonce) {
		$.ajax({
			url: 'index.php?route=extension/payment/tltbraintree3DS/send',
			type: 'post',
			data: 'payment_method_nonce=' + nonce,
			dataType: 'json',
			cache: false,
			complete: function () {
				infoMessagesContainer.hide().html('');
			},
			success: function (json) {
				if (json['error']) {
					writeError('Payment sent, but error occurred', debug);

					messagesContainer.show().html(json['error']);
					submitButton.button('reset');

					restartPaymentFlow();
				}

				if (json['success']) {
					window.location.replace(json['success']);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
                messagesContainer.show().html('<?php echo $error_network; ?>');

				writeError('Server error', debug);
				writeError(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText, debug);

				submitButton.button('reset');

				restartPaymentFlow();
			}
		});
	}

    function restartPaymentFlow() {
        writeLog('Reinitialize gateway.');
        submitButton.prop('disabled', true).off('click');
        infoMessagesContainer.show().html('<i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_reinit; ?>');

        dropinInstance.teardown();

        $.ajax({
            url: 'index.php?route=extension/payment/tltbraintree3DS/generateToken',
            type: 'post',
            data: '',
            dataType: 'json',
            cache: false,
            complete: function () {
                infoMessagesContainer.hide().html('');
            },
            success: function (json) {
                if (json['error']) {
                    writeError('Token cannot be generated.', debug);

                    messagesContainer.show().html(json['error']);
                }

                if (json['clientToken']) {
                    dropinSetup(json['clientToken']);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                messagesContainer.show().html('<?php echo $error_loading; ?>');

                writeError('Server error', debug);
                writeError(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText, debug);
            }
        });
    }

    function loadBraintree() {
		if (counter > 150) {
			infoMessagesContainer.hide().html('');
			messagesContainer.show().html('<i class="fa fa-info-circle"></i> <?php echo $error_loading; ?>');

			return;
		}

		if (window.braintree && window.braintree.dropin) {
			dropinSetup('<?php echo $clientToken; ?>');
		} else {
			if (info_message) {
				info_message = false;
				infoMessagesContainer.show().html('<i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_gateway_loading; ?>');
			}

			setTimeout(function () {
				loadBraintree();
				counter++;
			}, 50);
		}
	}

    function writeLog(message, debugEnabled) {
        if (debugEnabled) {
            console.log(message);
        }
    }

    function writeError(message, debugEnabled) {
        if (debugEnabled) {
            console.error(message);
        }
    }
</script>
<?php } ?>