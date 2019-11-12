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
        $path = $this->instanceUrl();
        static::_staticRequest('delete', $path, [], $headers);

        return $this;
    }
}
