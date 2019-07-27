<?php

namespace FedaPay;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 *
 * @package FedaPay
 */
class Customer extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
    use ApiOperations\Create;
    use ApiOperations\Update;
    use ApiOperations\Save;
    use ApiOperations\Delete;
}
