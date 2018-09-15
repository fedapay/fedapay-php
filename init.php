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

// Plumbing
require(dirname(__FILE__) . '/lib/Response.php');
require(dirname(__FILE__) . '/lib/Requestor.php');
require(dirname(__FILE__) . '/lib/FedaPayObject.php');
require(dirname(__FILE__) . '/lib/Resource.php');

// FedaPay API Resources
require(dirname(__FILE__) . '/lib/Account.php');
require(dirname(__FILE__) . '/lib/Currency.php');
require(dirname(__FILE__) . '/lib/Customer.php');
require(dirname(__FILE__) . '/lib/Event.php');
require(dirname(__FILE__) . '/lib/Log.php');
require(dirname(__FILE__) . '/lib/Transaction.php');
