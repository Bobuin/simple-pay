<?php

namespace App\Test\Fixture;

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
                'rate' => 1,
                'created' => date('Y-m-d'),
            ],
        ];
        parent::init();
    }
}
