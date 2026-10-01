<?php

namespace FedaPay;

/**
 * Class PaymentRequest
 *
 * @property int $id
 * @property integer $balance_amount_before
 * @property integer $balance_amount_after
 * @property string $status
 * @property integer $amount
 * @property string $mode
 * @property string $receipt_info
 * @property string $commission
 * @property integer $fixed_commission
 * @property integer $fees
 * @property integer $amount_transferred
 * @property int $sourceable_id
 * @property string $sourceable_type
 * @property string $approved_at
 * @property string $transferred_at
 * @property boolean $to_transfer
 * @property string $declined_at
 * @property string $canceled_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $freezed_at
 * @property int $approved_user_id
 * @property int $transferred_user_id
 * @property array $metadata
 * @property int $user_id
 * @property int $balance_id
 * @property string $reference
 * @property string $transaction_key
 * @property string $declined_reason
 * @property string $flags
 * @property string $created_via
 * 
 * @package FedaPay
 */
class PaymentRequest extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Search;
    use ApiOperations\Retrieve;
    use ApiOperations\CreateInBatch;
    use ApiOperations\Create;
    use ApiOperations\Update;
    use ApiOperations\Save;
    use ApiOperations\Delete;
}
