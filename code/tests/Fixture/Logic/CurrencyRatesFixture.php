<?php

namespace App\Test\Fixture\Logic;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CurrencyRatesFixture
 *
 */
class CurrencyRatesFixture extends TestFixture
{

    public $import = ['model' => 'CurrencyRates'];

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'currency_id' => 1,
                'rate' => 110,
                'created' => date('Y-m-d'),
            ],
            [
                'id' => 2,
                'currency_id' => 2,
                'rate' => 3000,
                'created' => date('Y-m-d'),
            ],
        ];
        parent::init();
    }
}
