<?php

namespace FedaPay\ApiOperations;

/**
 * trait Update
 */
trait Delete
{
    /**
     * Send a detele request
     * @param  array $headers
     */
    public function delete($headers = [])
    {
        $url = $this->instanceUrl();
        static::_staticRequest('delete', $url, [], $headers);

        return $this;
    }
}
