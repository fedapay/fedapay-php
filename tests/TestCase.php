<?php

namespace Fedapay;

use PHPUnit\Framework\TestCase;
/**
 * Base class for Stripe test cases, provides some utility methods for creating
 * objects.
 */
class TestCase extends TestCase
{
  const API_KEY = 'sk_test_ankA0xsaLBW9C2f-bNyJEzQV';


  protected static function authorizeFromEnv()
  {
      $apiKey = getenv('FEDAPAY_API_KEY');
      if (!$apiKey) {
          $apiKey = self::API_KEY;
      }

      FEDAPAY::setApiKey($apiKey);
  }

}
