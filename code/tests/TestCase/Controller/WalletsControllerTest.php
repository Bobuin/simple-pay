<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\WalletsController Test Case
 */
class WalletsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.wallets',
        'app.currency_rates',
        'app.currencies',
        'app.transactions',
        'app.users',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testAddFundsEmptyData(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/wallets/add-funds');
        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('Wrong request data.');
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testAddFundsWrongWallet(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'wallet_id' => 9,
            'amount' => 100,
        ];

        $this->post('/wallets/add-funds', json_encode($data));

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('The requested wallet is not exists.');
    }

    /**
     * Test addFund method
     *
     * @throws \PHPUnit\Exception
     */
    public function testAddFundsWrongAmount(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'wallet_id' => 1,
            'amount' => 0,
        ];

        $this->post('/wallets/add-funds', json_encode($data));

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('Wrong request data.');

        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'wallet_id' => 1,
            'amount' => -100,
        ];

        $this->post('/wallets/add-funds', json_encode($data));

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('Wrong request data.');
    }

    /**
     * Test addFund method
     *
     * @throws \PHPUnit\Exception
     */
    public function testAddFunds(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'wallet_id' => 1,
            'amount' => 100,
        ];

        $this->post('/wallets/add-funds', json_encode($data));

        $this->assertResponseOk();
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testTransferEmptyData(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('wallets/transfer');

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('Wrong request data.');
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testTransferWrongWallets(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'from_wallet_id' => 9,
            'to_wallet_id' => 8,
            'amount' => 100,
            'transfer_currency' => 'sender',
        ];

        $this->post('wallets/transfer', json_encode($data));

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('The requested sender wallet is not exists.');
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testTransferWrongAmount(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 2,
            'amount' => 200,
            'transfer_currency' => 'sender',
        ];

        $this->post('wallets/transfer', json_encode($data));

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('Not enough funds on wallet to make transfer.');
    }

    /**
     * Test transfer method
     *
     * @throws \PHPUnit\Exception
     */
    public function testTransfer(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 2,
            'amount' => 1,
            'transfer_currency' => 'sender',
        ];

        $this->post('wallets/transfer', json_encode($data));

        $this->assertResponseOk();
    }
}
