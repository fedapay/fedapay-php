<?php

namespace Fedapay;

use PHPUnit\Framework\TestCase;
use Fedapay;
/**
 * Base class for Fedapay test cases, provides some utility methods for creating
 * objects.
 */
class Test extends TestCase
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

  protected function setUp()
  {
      //ApiRequestor::setHttpClient(HttpClient\CurlClient::instance());

      // Peg the API version so that it can be varied independently of the
      // one set on the test account.
      Fedapay::setApiVersion('1.0.0');

      $this->mock = null;
  }

  protected function mockRequest($method, $path, $params = array(), $return = array('id' => 'myId'), $rcode = 200, $base = 'https://api.fedapay.com')
  {
      $mock = $this->setUpMockRequest();
      $mock->expects($this->at($this->call++))
           ->method('request')
           ->with(strtolower($method), $base . $path, $this->anything(), $params, false)
           ->willReturn(array(json_encode($return), $rcode, array()));
  }

  private function setUpMockRequest()
  {
      if (!$this->mock) {
          self::authorizeFromEnv();
          $this->mock = $this->getMock('\Fedapay\HttpClient\ClientInterface');
          ApiRequestor::setHttpClient($this->mock);
      }
      return $this->mock;
  }

  /**
   * Create a valid test customer.
   */
  protected static function createTestCustomer(array $attributes = array())
  {
      self::authorizeFromEnv();

      return Customer::create(
          $attributes + array(
              'firstname' => 'toto',
              'lastname' => 'toto',
              'email' => 'toto@gmail.com',
              'phone' => '99999999'
          )
      );
  }


}
