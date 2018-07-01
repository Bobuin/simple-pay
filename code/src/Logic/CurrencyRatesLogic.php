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
    private $currencyRatesTable;
    private $todayDate;

    /**
     * CurrencyRatesLogic constructor.
     */
    public function __construct()
    {
        $this->currencyRatesTable = TableRegistry::getTableLocator()->get('CurrencyRates');
        $this->todayDate = Time::now()->format('Y-m-d');
    }

    /**
     * @param int $currencyId Wallet currency ID
     *
     * @return float
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function getRate(int $currencyId): float
    {
        /** @var Currency|null $currency */
        $currency = $this->currencyRatesTable->Currencies
            ->find()
            ->where(['id' => $currencyId])
            ->first();

        if (null === $currency) {
            throw new BadRequestException(__('The requested currency is not exists.'));
        }

        $currencyRate = 1;
        if (self::BASE_CURRENCY_CODE !== $currency->code) {
            /** @var CurrencyRate|null $todayRate */
            $todayRate = $this->currencyRatesTable
                ->find()
                ->where([
                    'created' => $this->todayDate,
                    'currency_id' => $currency->id,
                ])
                ->orderDesc('id')
                ->first();

            if (null === $todayRate) {
                throw new BadRequestException(__('There is no ' . $currency->code . ' currency rate for today.'));
            }

            $currencyRate = round($todayRate->rate / self::CURRENCY_PRECISION, 2, PHP_ROUND_HALF_DOWN);
        }

        return $currencyRate;
    }

    /**
     * @param array $data Currency rate data: currency code, rate
     *
     * @return CurrencyRate
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function addRate($data): CurrencyRate
    {
        /** @var Currency|null $currency */
        $currency = $this->currencyRatesTable->Currencies
            ->find()
            ->where(['code' => $data['currency']])
            ->first();

        if (null === $currency) {
            throw new BadRequestException(__('The requested currency is not exists.'));
        }

        $data['currency_id'] = $currency->id;

        $currencyRate = $this->currencyRatesTable->newEntity();

        $currencyRate = $this->currencyRatesTable->patchEntity($currencyRate, $data);

        try {
            $saved = $this->currencyRatesTable->save($currencyRate);
        } catch (\Exception $exception) {
            throw new BadRequestException(__('The currency rate could not be saved. Error: ' .
                $exception->getMessage()));
        }

        if (!$saved) {
            throw new BadRequestException(__('The currency rate could not be saved. Please, try again. Error: ' .
                json_encode($currencyRate->getErrors())));
        }

        return $currencyRate;
    }
}
