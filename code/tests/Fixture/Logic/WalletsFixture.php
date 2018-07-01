<?php

namespace App\Test\Fixture\Logic;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WalletsFixture
 *
 */
class WalletsFixture extends TestFixture
{

    public $import = ['model' => 'Wallets'];

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
                'balance' => 100,
            ],
            [
                'id' => 2,
                'currency_id' => 2,
                'balance' => 1000,
            ],
        ];
        parent::init();
    }
}
