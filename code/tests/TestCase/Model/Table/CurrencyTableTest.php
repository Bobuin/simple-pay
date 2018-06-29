<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CurrencyTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CurrencyTable Test Case
 */
class CurrencyTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CurrencyTable
     */
    public $Currency;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.currency',
        'app.currency_rates',
        'app.wallets'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Currency') ? [] : ['className' => CurrencyTable::class];
        $this->Currency = TableRegistry::getTableLocator()->get('Currency', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Currency);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
