<?php

namespace App\Test\Fixture\Logic;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TransactionsFixture
 *
 */
class TransactionsFixture extends TestFixture
{

    public $import = ['model' => 'Transactions'];

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
                'wallet_id' => 1,
                'amount' => 1.5,
                'base_amount' => 1.5,
                'created' => date('Y-m-d H:i:s', strtotime('6 hours ago')),
            ],
        ];
        parent::init();
    }
}
