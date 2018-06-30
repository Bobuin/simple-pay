<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\CurrencyRatesController Test Case
 */
class CurrencyRatesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.currency_rates',
        'app.currencies',
    ];

    /**
     * Test index method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testIndex(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->get('/currency-rates');

        $this->assertResponseOk();
        $this->assertResponseCode(200);
        $this->assertResponseContains('currency_id');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAddWrongCurrency(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'currency' => 'XXX',
            'rate' => 1000,
        ];

        $this->post('/currency-rates/add', json_encode($data));

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $this->assertResponseContains('The requested currency is not exists.');
    }
    /**
     * Test add method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAdd(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'currency' => 'RUB',
            'rate' => 1000,
        ];

        $this->post('/currency-rates/add', json_encode($data));

        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
