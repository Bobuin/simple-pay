<?php
declare(strict_types=1);

namespace App\Logic;

use App\Model\Entity\Currency;
use App\Model\Entity\Wallet;
use App\Model\Table\WalletsTable;
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
        $toWalletExist = $this->walletsTable->exists(['id' => $data['from_wallet_id']]);

        if (!$toWalletExist) {
            throw new BadRequestException(__('The requested receiver wallet is not exists.'));
        }

        $fromWalletExist = $this->walletsTable->exists(['id' => $data['to_wallet_id']]);

        if (!$fromWalletExist) {
            throw new BadRequestException(__('The requested sender wallet is not exists.'));
        }

        // Take from sender
        $senderWallet = $this->addFunds([
            'wallet_id' => $data['from_wallet_id'],
            'amount' => $data['amount'] * (-1),
        ]);

        $senderBaseAmount = $senderWallet->transactions[0]->base_amount;

        /** @var Wallet $receiverWallet */
        $receiverWallet = $this->walletsTable
            ->find()
            ->contain('Currencies')
            ->where(['Wallets.id' => $data['to_wallet_id']])
            ->first();

        $receiverCurrencyRate = (new CurrencyRatesLogic())->getRate($receiverWallet->currency);
        $receiverBaseAmount = $senderBaseAmount * $receiverCurrencyRate * (-1);

        try {
            // Move to receiver
            $receiverWallet = $this->addFunds([
                'wallet_id' => $data['to_wallet_id'],
                'amount' => $receiverBaseAmount,
            ]);
        } catch (\Exception $exception) {
            // Return Funds if fail
            $this->addFunds([
                'wallet_id' => $data['from_wallet_id'],
                'amount' => $data['amount'],
            ]);

            throw new BadRequestException(__('There is an error occurred during transfer operation. Error: ' .
                $exception->getMessage()));
        }

        return [
            $senderWallet,
            $receiverWallet,
        ];
    }

    /**
     * @param array $data Data for funding: wallet ID and amount
     *
     * @return Wallet
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function addFunds($data): Wallet
    {
        /** @var Wallet|null $wallet */
        $wallet = $this->walletsTable
            ->find()
            ->contain('Currencies')
            ->where(['Wallets.id' => $data['wallet_id']])
            ->first();

        if (null === $wallet) {
            throw new BadRequestException(__('The requested wallet is not exists.'));
        }

        $currencyRate = (new CurrencyRatesLogic())->getRate($wallet->currency);

        $walletData = [
            'balance' => $wallet->balance + $data['amount'],
            'transactions' => [
                [
                    'wallet_id' => $wallet->id,
                    'amount' => $data['amount'],
                    'base_amount' => round($data['amount'] / $currencyRate, 2, PHP_ROUND_HALF_DOWN),
                ],
            ],
        ];

        $wallet->setDirty('transactions');

        /** @var Wallet $wallet */
        $wallet = $this->walletsTable->patchEntity($wallet, $walletData, ['associated' => ['Transactions']]);

        try {
            /** @var Wallet|false $saved */
            $saved = $this->walletsTable->save($wallet);
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
