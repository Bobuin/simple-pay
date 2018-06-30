<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $id
 * @property int $wallet_id
 * @property float $amount
 * @property float $base_amount
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Wallet $wallet
 */
class Transaction extends Entity
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
        'wallet_id' => true,
        'amount' => true,
        'base_amount' => true,
        'created' => true,
        'wallet' => true
    ];
}
