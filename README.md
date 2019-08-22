#TLT Braintree with 3D Secure 1.0/2.0 support for Opencart 2.x

---------
IMPORTANT
---------

If you use TLT Braintree 3.x (without 3DS support), you can use this extension at the same time with it. Nothing in TLT Braintree 3.x will be overwritten or changed.

If you get connection error like 'Cannot connect to the bank. Please try again later or use PayPal.' switch the debug mode to on, try to pay and check Opencart error log.

Message like 'Debug Info: #0 /path_to_opencart/system/braintree/lib/Braintree/Http.php(47): Braintree\Util::throwStatusCodeException(403)' means, that the Merchant Account ID value is not valid.

Open the Braintree settings and enter value from your Braintree credentials. Merchant account ID and Merchant ID are two different values with distinct purposes.

More information about it you can get from Braintree support article:
https://articles.braintreepayments.com/control-panel/important-gateway-credentials#merchant-account-id-vs.-merchant-id

Please also note, that Braintree credentials for sandbox and production are different. Don't mismatch it!

This extension uses Braintree Javascript V3 SDK and has been tested with Braintree PHP library ver. 3.40.0.

-----------------
BRAINTREE LIBRARY
-----------------

Braintree library must be installed either manually other using composer.

Manual install:

1. Download the library from https://developers.braintreepayments.com/start/hello-server/php

2. Unpack the archive and upload the 'lib' folder to the 'system/braintree' folder of your opencart installation

If you use composer, the 'vendor' folder must be on the same level as your opencart http-root.

IF YOUR PHP VERSION LESS, THAN 7.2 YOU MUST INSTALL BRAINTREE LIBRARY VERSION 3.40.0!

You can install it via composer or download from here:
https://github.com/braintree/braintree_php/releases/tag/3.40.0

---------
3D SECURE
---------

You can enable 3D secure support in module settings. Your Javascript SDK version must be 1.19.0 or above! Please check in module settings.

3D Secure 2.0 (3DS 2.0) can be used to be prepared to the next iteration of the 3DS authentication protocol, which satisfies the Strong Customer Authentication (SCA) requirements going into effect in 2019 for European merchants transacting with European customers.
To use 3DS 2.0 in production you should wait, until Braintree will enable this feature for you account. Please read this article to get more info about 3D Secure 2.0:

https://developers.braintreepayments.com/guides/3d-secure/migration/javascript/v3

Credit cards for testing 3DS 2.0 you can find here:
https://developers.braintreepayments.com/guides/3d-secure/migration/javascript/v3#client-side-sandbox-testing

---------------------
Google Pay and PayPal
---------------------

To use those payment methods you should configure them in Braintree Control Panel.

More info about configuration of Google Pay you can find in this article:
https://developers.braintreepayments.com/guides/google-pay/configuration/javascript/v3
To use Google Pay in production, you'll also need to work with Google to go live.

-------
INSTALL
-------

1a. Install tltbraintree3DS.ocmod.zip via Opencart extension installer
- OR -
1b. Upload all files from 'upload' folder to the http-root of the opencart installation. No files will be overwritten, if you install the extension first time.

2. The Braintree library is not preinstalled.

3. Find TLT Braintree 3DS extension in the payment extension list, install it, and set up parameters, which you get from Braintree.

4. Double check that the default merchant account currency matches to your store default currency.

5. Check the credit cards logos, which you accept. To change this list you should change the files in image/payment folder.

5. Check the front-end messages for your customers. To change it edit the file catalog/language/english/payment/tltbraintree3DS.php.

-----
NOTES
-----

If your store is multi-currency you should get from Braintree Merchant Account ID for each currency used in your store. Otherwise a customer will be charged in your default currency. In this case you MUST notify your customer about it.
