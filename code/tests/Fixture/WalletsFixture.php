<?php
namespace App\Test\Fixture;

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
                'balance' => 1.5
            ],
            [
                'id' => 2,
                'currency_id' => 1,
                'balance' => 0
            ],
        ];
        parent::init();
    }
}
