<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\TransactionsController Test Case
 */
class TransactionsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.currency_rates',
        'app.currencies',
        'app.transactions',
        'app.users',
        'app.wallets',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
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
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
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

    /**
     * @throws \PHPUnit\Exception
     */
    public function testReportFailUser(): void
    {
        $this->get('/reports/100');
        $this->assertResponseError();
        $this->assertResponseCode(404);
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testReport(): void
    {
        $this->get('/reports/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Total amount: 15');
        $this->assertResponseContains('Total USD amount: 1.5');
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testReportWithDate(): void
    {
        $data = [
            'date_from' => [
                'year' => 2017,
                'month' => 12,
                'day' => 1,
                'hour' => 12,
                'minute' => 15,
                'second' => 30,
            ],
            'date_to' => [
                'year' => 2018,
                'month' => 12,
                'day' => 1,
                'hour' => 12,
                'minute' => 15,
                'second' => 30,
            ],
        ];

        $this->post('/reports/1', $data);

        $this->assertResponseOk();
        $this->assertResponseContains('Total amount: 15');
        $this->assertResponseContains('Total USD amount: 1.5');
    }

    /**
     * Test download method
     *
     * @throws \PHPUnit\Exception
     */
    public function testDownloadWithDate(): void
    {
        $data = [
            'date_from' => [
                'year' => 2017,
                'month' => 12,
                'day' => 1,
                'hour' => 12,
                'minute' => 15,
                'second' => 30,
            ],
            'date_to' => [
                'year' => 2018,
                'month' => 12,
                'day' => 1,
                'hour' => 12,
                'minute' => 15,
                'second' => 30,
            ],
        ];

        $this->post('/reports/download/1', $data);

        $this->assertResponseOk();
        $this->assertResponseContains('bla');
    }
}
