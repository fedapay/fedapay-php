<?php

// FedaPay singleton
require(dirname(__FILE__) . '/lib/FedaPay.php');

// Utilities
require(dirname(__FILE__) . '/lib/Util/Inflector.php');
require(dirname(__FILE__) . '/lib/Util/Util.php');
require(dirname(__FILE__) . '/lib/Util/RandomGenerator.php');

// HttpClient
require(dirname(__FILE__) . '/lib/HttpClient/ClientInterface.php');
require(dirname(__FILE__) . '/lib/HttpClient/CurlClient.php');

// Errors
require(dirname(__FILE__) . '/lib/Error/Base.php');
require(dirname(__FILE__) . '/lib/Error/ApiConnection.php');
require(dirname(__FILE__) . '/lib/Error/InvalidRequest.php');
require(dirname(__FILE__) . '/lib/Error/SignatureVerification.php');

// API operations
require(dirname(__FILE__) . '/lib/ApiOperations/All.php');
require(dirname(__FILE__) . '/lib/ApiOperations/Create.php');
require(dirname(__FILE__) . '/lib/ApiOperations/CreateInBatch.php');
require(dirname(__FILE__) . '/lib/ApiOperations/Search.php');
require(dirname(__FILE__) . '/lib/ApiOperations/Delete.php');
require(dirname(__FILE__) . '/lib/ApiOperations/Request.php');
require(dirname(__FILE__) . '/lib/ApiOperations/Retrieve.php');
require(dirname(__FILE__) . '/lib/ApiOperations/Save.php');
require(dirname(__FILE__) . '/lib/ApiOperations/Update.php');

// Plumbing
require(dirname(__FILE__) . '/lib/Requestor.php');
require(dirname(__FILE__) . '/lib/FedaPayObject.php');
require(dirname(__FILE__) . '/lib/Resource.php');

// FedaPay API Resources
require(dirname(__FILE__) . '/lib/Account.php');
require(dirname(__FILE__) . '/lib/ApiKey.php');
require(dirname(__FILE__) . '/lib/Currency.php');
require(dirname(__FILE__) . '/lib/Balance.php');
require(dirname(__FILE__) . '/lib/Customer.php');
require(dirname(__FILE__) . '/lib/Event.php');
require(dirname(__FILE__) . '/lib/Log.php');
require(dirname(__FILE__) . '/lib/PhoneNumber.php');
require(dirname(__FILE__) . '/lib/Transaction.php');
require(dirname(__FILE__) . '/lib/Payout.php');
require(dirname(__FILE__) . '/lib/Page.php');
require(dirname(__FILE__) . '/lib/Invoice.php');

// Webhooks
require(dirname(__FILE__) . '/lib/Webhook.php');
require(dirname(__FILE__) . '/lib/WebhookSignature.php');
