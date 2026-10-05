<?php

namespace FedaPay;

/**
 * Class SubLedgerBalance
 *
 * @property string $merchant_id
 * @property boolean $known
 * @property array $balances
 *
 * @package FedaPay
 */
class SubLedgerBalance extends Resource
{
    use ApiOperations\All;
}
