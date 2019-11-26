<?php

namespace FedaPay\ApiOperations;

/**
 * trait Request
 */
trait Request
{
    /**
     * Validate request params
     * @param array $params
     * @throws Error\InvalidRequest
     */
    protected static function _validateParams($params = null)
    {
        if ($params && !is_array($params)) {
            $message = 'You must pass an array as the first argument to FedaPay API '
               . 'method calls.  (HINT: an example call to create a customer '
               . "would be: \"FedaPay\\Customer::create(array('firstname' => toto, "
               . "'lastname' => 'zoro', 'email' => 'admin@gmail.com', 'phone' => '66666666'))\")";
            throw new FedaPay\Error\InvalidRequest($message);
        }
    }

    /**
     * Static method to send request
     * @param string $method
     * @param string $path
     * @param array $params
     * @param array $headers
     *
     * @return array
     */
    protected static function _staticRequest($method, $path, $params = [], $headers = [])
    {
        $requestor = self::getRequestor();

        $response = $requestor->request($method, $path, $params, $headers);

        $options = [
            'apiVersion' => \FedaPay\FedaPay::getApiVersion(),
            'environment' => \FedaPay\FedaPay::getEnvironment()
        ];

        return [$response, $options];
    }
}
