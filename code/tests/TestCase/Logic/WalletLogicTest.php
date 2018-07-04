<?php
declare(strict_types=1);

namespace App\Test\TestCase\Logic;

use App\Logic\WalletLogic;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;

/**
 * Class WalletLogicTest
 *
 * @package App\Test\TestCase\Logic
 */
class WalletLogicTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Logic\currencies',
        'app.Logic\currency_rates',
        'app.Logic\transactions',
        'app.Logic\wallets',
    ];

    public function testCreateWalletWrongCurrency(): void
    {
        $currencyCode = 'BLA';

        try {
            (new WalletLogic())->createWallet($currencyCode);
        } catch (BadRequestException $exception) {
            static::assertEquals('The requested currency is not exists.', $exception->getMessage());
        }
    }

    /**
     * Test createWallet method
     */
    public function testCreateWallet(): void
    {
        $currencyId = 1;
        $currencyCode = 'EUR';
        $wallet = (new WalletLogic())->createWallet($currencyCode);

        static::assertEquals($currencyId, $wallet->currency_id);
    }

    public function testTransferWrongSenderWallet(): void
    {
        $data = [
            'from_wallet_id' => 101,
            'to_wallet_id' => 2,
            'amount' => 100,
            'transfer_currency' => 'sender',
        ];

        try {
            (new WalletLogic())->transfer($data);
        } catch (BadRequestException $exception) {
            static::assertEquals('The requested sender wallet is not exists.', $exception->getMessage());
        }
    }

    public function testTransferWrongReceiverWallet(): void
    {
        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 102,
            'amount' => 100,
            'transfer_currency' => 'sender',
        ];

        try {
            (new WalletLogic())->transfer($data);
        } catch (BadRequestException $exception) {
            static::assertEquals('The requested receiver wallet is not exists.', $exception->getMessage());
        }
    }

    public function testTransferWrongDirection(): void
    {
        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 2,
            'amount' => 100,
            'transfer_currency' => 'bla-bla',
        ];

        try {
            (new WalletLogic())->transfer($data);
        } catch (BadRequestException $exception) {
            static::assertEquals(
                'Wrong "transfer_currency" value. It can be "sender" or "receiver".',
                $exception->getMessage()
            );
        }
    }

    public function testTransferWrongAmount(): void
    {
        // Send over balanced 100 EUR in EUR currency
        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 2,
            'amount' => 200,
            'transfer_currency' => 'sender',
        ];

        try {
            (new WalletLogic())->transfer($data);
            static::fail('Transfer with wrong result balance !');
        } catch (\Exception $exception) {
            static::assertInstanceOf(\InvalidArgumentException::class, $exception);
            static::assertEquals('Not enough funds on wallet to make transfer.', $exception->getMessage());
        }

        // Send over balanced 100 EUR in RUB currency
        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 2,
            'amount' => 100000,
            'transfer_currency' => 'receiver',
        ];

        try {
            (new WalletLogic())->transfer($data);
            static::fail('Transfer with wrong result balance !');
        } catch (\Exception $exception) {
            static::assertInstanceOf(\InvalidArgumentException::class, $exception);
            static::assertEquals('Not enough funds on wallet to make transfer.', $exception->getMessage());
        }
    }

    /**
     * Test transfer method
     */
    public function testTransferBySenderCurrency(): void
    {
        // Send 100 EUR
        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 2,
            'amount' => 100,
            'transfer_currency' => 'sender',
        ];

        $result = (new WalletLogic())->transfer($data);

        [$sender, $receiver] = $result;

        static::assertNotEmpty($result);
        static::assertEquals(0, $sender['balance']);
        static::assertEquals(3727.3, $receiver['balance']);
    }

    /**
     * Test transfer method
     */
    public function testTransferByReceiverCurrency(): void
    {
        // Send 100 RUB
        $data = [
            'from_wallet_id' => 1,
            'to_wallet_id' => 2,
            'amount' => 100,
            'transfer_currency' => 'receiver',
        ];

        $result = (new WalletLogic())->transfer($data);

        [$sender, $receiver] = $result;

        static::assertNotEmpty($result);
        static::assertEquals(96.34, $sender['balance']);
        static::assertEquals(1100, $receiver['balance']);
    }
}
