<?php

namespace FedaPay\ApiOperations;

/**
 * trait Save
 */
trait Save
{
    /**
     * Update the resource
     * @param array $headers the request headers
     *
     * @return Resource the updated API resource
     */
    public function save($headers = [])
    {
        $params = $this->serializeParameters();
        $className = static::className();
        $url = $this->instanceUrl();

        list($response, $opts) = static::_staticRequest('put', $url, $params, $headers);

        $klass = $opts['apiVersion'] . '/' . $className;

        $json = $response[$klass];
        $this->refreshFrom($json, $opts);

        return $this;
    }
}
