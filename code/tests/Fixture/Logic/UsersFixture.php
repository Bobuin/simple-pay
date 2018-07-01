<?php
namespace App\Test\Fixture\Logic;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    public $import = ['model' => 'Users'];

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
                'name' => 'Lorem ipsum dolor sit amet',
                'country' => 'Lorem ipsum dolor sit amet',
                'city' => 'Lorem ipsum dolor sit amet',
                'wallet_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'Lorem ipsum dolor sit amet',
                'country' => 'Lorem ipsum dolor sit amet',
                'city' => 'Lorem ipsum dolor sit amet',
                'wallet_id' => 3
            ],
        ];
        parent::init();
    }
}
