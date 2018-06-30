<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CurrencyRate Entity
 *
 * @property int                        $id
 * @property int                        $currency_id
 * @property int                        $rate
 * @property \Cake\I18n\FrozenDate      $created
 *
 * @property \App\Model\Entity\Currency $currency
 */
class CurrencyRate extends Entity
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
        'rate' => true,
        'created' => true,
        'currency' => true,
    ];
}
