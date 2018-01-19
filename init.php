<?php

// Fedapay singleton
require(dirname(__FILE__) . '/lib/Fedapay.php');

// Utilities
require(dirname(__FILE__) . '/lib/Util/Inflector.php');
require(dirname(__FILE__) . '/lib/Util/Util.php');

// Errors
require(dirname(__FILE__) . '/lib/Error/Base.php');
require(dirname(__FILE__) . '/lib/Error/ApiConnection.php');
require(dirname(__FILE__) . '/lib/Error/InvalidRequest.php');

// Plumbing
require(dirname(__FILE__) . '/lib/Requestor.php');
require(dirname(__FILE__) . '/lib/Resource.php');
require(dirname(__FILE__) . '/lib/FedapayObject.php');

// Fedapay API Resources

require(dirname(__FILE__) . '/lib/Currency.php');
require(dirname(__FILE__) . '/lib/Customer.php');
require(dirname(__FILE__) . '/lib/Event.php');
require(dirname(__FILE__) . '/lib/Transaction.php');
