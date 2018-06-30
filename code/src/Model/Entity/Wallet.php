<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Wallet Entity
 *
 * @property int                             $id
 * @property int                             $currency_id
 * @property float                           $balance
 *
 * @property \App\Model\Entity\Currency      $currency
 * @property \App\Model\Entity\Transaction[] $transactions
 * @property \App\Model\Entity\User[]        $users
 */
class Wallet extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'currency_id' => true,
        'balance' => true,
        'currency' => true,
        'transactions' => true,
        'users' => true
    ];
}
