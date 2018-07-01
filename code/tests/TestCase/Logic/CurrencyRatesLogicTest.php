<?php
declare(strict_types=1);

namespace App\Test\TestCase\Logic;

use App\Logic\CurrencyRatesLogic;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;

/**
 * Class CurrencyRatesLogicTest
 *
 * @package App\Test\TestCase\Logic
 */
class CurrencyRatesLogicTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Logic\currencies',
        'app.Logic\currency_rates',
    ];

    public function testGetRateNoCurrency(): void
    {
        $currencyId = 100;

        try {
            (new CurrencyRatesLogic())->getRate($currencyId);
        } catch (BadRequestException $exception) {
            $this->assertEquals('The requested currency is not exists.', $exception->getMessage());
        }
    }

    public function testGetRateNoRate(): void
    {
        $currencyId = 3;

        try {
            (new CurrencyRatesLogic())->getRate($currencyId);
        } catch (BadRequestException $exception) {
            $this->assertEquals('There is no UAH currency rate for today.', $exception->getMessage());
        }
    }

    public function testGetRate()
    {
        $currencyId = 1;
        $currencyRate = (new CurrencyRatesLogic())->getRate($currencyId);

        $this->assertNotEmpty($currencyRate);
        $this->assertEquals(1.1, $currencyRate);
    }
}
