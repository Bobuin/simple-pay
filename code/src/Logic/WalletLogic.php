<?php
declare(strict_types=1);

namespace App\Logic;

use App\Model\Entity\Currency;
use App\Model\Entity\Wallet;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;

/**
 * Class WalletLogic
 *
 * @package Logic
 */
class WalletLogic
{
    private $walletsTable;

    /**
     * WalletLogic constructor.
     */
    public function __construct()
    {
        $this->walletsTable = TableRegistry::getTableLocator()->get('Wallets');
    }

    /**
     * @param string $walletCurrency Currency code for new Wallet
     *
     * @return Wallet
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function createWallet($walletCurrency): Wallet
    {
        /** @var Currency|null $currency */
        $currency = $this->walletsTable->Currencies
            ->find()
            ->where(['code' => $walletCurrency])
            ->first();

        if (null === $currency) {
            throw new BadRequestException(__('The requested currency is not exists.'));
        }

        $wallet = $this->walletsTable->newEntity();

        /** @var Wallet $wallet */
        $wallet = $this->walletsTable->patchEntity($wallet, ['currency_id' => $currency->id]);

        try {
            $saved = $this->walletsTable->save($wallet);
        } catch (\Exception $exception) {
            throw new BadRequestException(__('The wallet could not be saved. Error: ' . $exception->getMessage()));
        }

        if (!$saved) {
            throw new BadRequestException(__('The wallet could not be saved. Please, try again. Error: ' .
                json_encode($wallet->getErrors())));
        }

        return $wallet;
    }

    /**
     * @param array $data Funds transfer data: sender, receiver, amount
     *
     * @return Wallet[]
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function transfer($data): array
    {
        $transferAmount = $data['amount'];

        /** @var Wallet|null $senderWallet */
        $senderWallet = $this->walletsTable
            ->find()
            ->where(['Wallets.id' => $data['from_wallet_id']])
            ->first();

        if (null === $senderWallet) {
            throw new BadRequestException(__('The requested sender wallet is not exists.'));
        }

        /** @var Wallet|null $receiverWallet */
        $receiverWallet = $this->walletsTable
            ->find()
            ->where(['Wallets.id' => $data['to_wallet_id']])
            ->first();

        if (null === $receiverWallet) {
            throw new BadRequestException(__('The requested receiver wallet is not exists.'));
        }

        $currencyRatesLogic = new CurrencyRatesLogic();

        switch ($data['transfer_currency']) {
            case 'sender':
                $senderCurrencyRate = $currencyRatesLogic->getRate($senderWallet->currency_id);
                $senderAmount = $transferAmount;
                $senderBaseAmount = round($senderAmount / $senderCurrencyRate, 2, PHP_ROUND_HALF_DOWN);

                $receiverCurrencyRate = $currencyRatesLogic->getRate($receiverWallet->currency_id);
                $receiverBaseAmount = $senderBaseAmount;
                $receiverAmount = $receiverBaseAmount * $receiverCurrencyRate;
                break;
            case 'receiver':
                $receiverCurrencyRate = $currencyRatesLogic->getRate($receiverWallet->currency_id);
                $receiverAmount = $transferAmount;
                $receiverBaseAmount = round($receiverAmount / $receiverCurrencyRate, 2, PHP_ROUND_HALF_DOWN);

                $senderCurrencyRate = $currencyRatesLogic->getRate($senderWallet->currency_id);
                $senderBaseAmount = $receiverBaseAmount;
                $senderAmount = $senderBaseAmount * $senderCurrencyRate;
                break;
            default:
                throw
                new BadRequestException('Wrong "transfer_currency" value. It can be "sender" or "receiver".');
        }

        // Take from sender
        $senderWallet = $this->addFunds($senderWallet, (-1) * $senderAmount, (-1) * $senderBaseAmount);

        try {
            // Move to receiver
            $receiverWallet = $this->addFunds($receiverWallet, $receiverAmount, $receiverBaseAmount);
        } catch (\Exception $exception) {
            // Return Funds if fail
            $this->addFunds($senderWallet, $senderAmount, $senderBaseAmount);

            throw new BadRequestException(__('There is an error occurred during transfer operation. Error: ' .
                $exception->getMessage()));
        }

        return [
            $senderWallet,
            $receiverWallet,
        ];
    }

    /**
     * @param Wallet $wallet       Target Wallet entity to add funds.
     * @param float  $amount       Funds amount to add in Wallet currency
     * @param float  $baseAmount   Funds amount in base currency
     *
     * @return Wallet
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function addFunds(Wallet $wallet, $amount, $baseAmount): Wallet
    {
        $walletData = [
            'balance' => round($wallet->balance + $amount, 2, PHP_ROUND_HALF_DOWN),
            'transactions' => [
                [
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'base_amount' => $baseAmount,
                ],
            ],
        ];

        $wallet->setDirty('transactions');

        /** @var Wallet $walletEntity */
        $walletEntity = $this->walletsTable->patchEntity($wallet, $walletData, ['associated' => ['Transactions']]);

        try {
            /** @var Wallet|false $saved */
            $saved = $this->walletsTable->save($walletEntity);
        } catch (\Exception $exception) {
            throw new BadRequestException(__('The wallet could not be saved. Error: ' . $exception->getMessage()));
        }

        if (!$saved) {
            throw new BadRequestException(__('The wallet could not be saved. Please, try again. Error: ' .
                json_encode($wallet->getErrors())));
        }

        return $saved;
    }
}
