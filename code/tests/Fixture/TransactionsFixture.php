<?php
namespace App\Test\Fixture;

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
                'amount' => 15,
                'base_amount' => 1.5,
                'created' => '2018-06-30 05:34:38'
            ],
        ];
        parent::init();
    }
}
