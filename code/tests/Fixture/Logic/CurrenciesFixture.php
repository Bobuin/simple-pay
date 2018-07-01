<?php

namespace App\Test\Fixture\Logic;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CurrenciesFixture
 *
 */
class CurrenciesFixture extends TestFixture
{

    public $import = ['model' => 'Currencies'];

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
                'code' => 'EUR',
                'name' => 'Europe Union Euro',
            ],
            [
                'id' => 2,
                'code' => 'RUB',
                'name' => 'Russian Ruble',
            ],
            [
                'id' => 3,
                'code' => 'UAH',
                'name' => 'Ukrainian Hryvnya'
            ],
        ];
        parent::init();
    }
}
