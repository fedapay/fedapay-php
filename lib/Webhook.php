<?php

namespace FedaPay;

/**
 * Class Webhook
 *
 * @property int $id
 * @property string $public_key
 * @property string $private_key
 * @property string $created_at
 * @property string $updated_at
 *
 * @package FedaPay
 */
abstract class Webhook
{
    const DEFAULT_TOLERANCE = 300;

    /**
     * Returns an Event instance using the provided JSON payload. Throws a
     * \UnexpectedValueException if the payload is not valid JSON, and a
     * \FedaPay\SignatureVerificationException if the signature verification
     * fails for any reason.
     *
     * @param string $payload the payload sent by FedaPay.
     * @param string $sigHeader the contents of the signature header sent by
     *  FedaPay.
     * @param string $secret secret used to generate the signature.
     * @param int $tolerance maximum difference allowed between the header's
     *  timestamp and the current time
     * @return \FedaPay\Event the Event instance
     * @throws \UnexpectedValueException if the payload is not valid JSON,
     * @throws \FedaPay\Error\SignatureVerification if the verification fails.
     */
    public static function constructEvent($payload, $sigHeader, $secret, $tolerance = self::DEFAULT_TOLERANCE)
    {
        WebhookSignature::verifyHeader($payload, $sigHeader, $secret, $tolerance);
        $data = json_decode($payload, true);
        $jsonError = json_last_error();
        if ($data === null && $jsonError !== JSON_ERROR_NONE) {
            $msg = "Invalid payload: $payload "
              . "(json_last_error() was $jsonError)";
            throw new \UnexpectedValueException($msg);
        }

        return new Event($data);
    }
}
