<?php

// Fedapay singleton
require(dirname(__FILE__) . '/lib/Fedapay.php');

// Utilities
require(dirname(__FILE__) . '/lib/Util/RequestOptions.php');
require(dirname(__FILE__) . '/lib/Util/Util.php');

// HttpClient
require(dirname(__FILE__) . '/lib/HttpClient/ClientInterface.php');
require(dirname(__FILE__) . '/lib/HttpClient/CurlClient.php');

// Errors
require(dirname(__FILE__) . '/lib/Error/Base.php');
require(dirname(__FILE__) . '/lib/Error/Api.php');
require(dirname(__FILE__) . '/lib/Error/ApiConnection.php');
require(dirname(__FILE__) . '/lib/Error/Authentication.php');
require(dirname(__FILE__) . '/lib/Error/InvalidRequest.php');

// Plumbing
require(dirname(__FILE__) . '/lib/ApiResponse.php');
require(dirname(__FILE__) . '/lib/ApiRequestor.php');
require(dirname(__FILE__) . '/lib/ApiResource.php');

// Fedapay API Resources

require(dirname(__FILE__) . '/lib/Collection.php');
require(dirname(__FILE__) . '/lib/Customer.php');
