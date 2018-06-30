<?php
declare(strict_types=1);

namespace App\Logic;

use App\Model\Entity\Currency;
use App\Model\Entity\CurrencyRate;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

/**
 * Class CurrencyRatesLogic
 *
 * @package App\Logic
 */
class CurrencyRatesLogic
{
    private const CURRENCY_PRECISION = 100;
    private const BASE_CURRENCY_CODE = 'USD';
    private $tableCurrencyRates;

    /**
     * CurrencyRatesLogic constructor.
     */
    public function __construct()
    {
        $this->tableCurrencyRates = TableRegistry::getTableLocator()->get('CurrencyRates');
    }

    /**
     * @param Currency $currency Wallet currency
     *
     * @return float
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function getRate(Currency $currency): float
    {
        $currencyRate = 1;
        if (self::BASE_CURRENCY_CODE !== $currency->code) {
            $todayDate = Time::now()->format('Y-m-d');

            /** @var CurrencyRate|null $todayRate */
            $todayRate = $this->tableCurrencyRates
                ->find()
                ->where([
                    'created' => $todayDate,
                    'currency_id' => $currency->id,
                ])
                ->first();

            if (null === $todayRate) {
                throw new BadRequestException(__('There is no ' . $currency->code . ' currency rate for today.'));
            }

            $currencyRate = round($todayRate->rate / self::CURRENCY_PRECISION, 2, PHP_ROUND_HALF_DOWN);
        }

        return $currencyRate;
    }
}
