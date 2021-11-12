<?php

namespace FedaPay;

/**
 * Class Balance
 *
 * @property int $id
 * @property int $currency_id
 * @property int $account_id
 * @property int $amount
 * @property string $mode
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @package FedaPay
 */
class Balance extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
}
