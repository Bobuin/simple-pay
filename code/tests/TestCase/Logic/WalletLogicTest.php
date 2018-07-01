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
            $this->assertEquals('The requested currency is not exists.', $exception->getMessage());
        }
    }

    public function testCreateWallet(): void
    {
        $currencyId = 1;
        $currencyCode = 'EUR';
        $wallet = (new WalletLogic())->createWallet($currencyCode);

        $this->assertEquals($currencyId, $wallet->currency_id);
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
            $this->assertEquals('The requested sender wallet is not exists.', $exception->getMessage());
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
            $this->assertEquals('The requested receiver wallet is not exists.', $exception->getMessage());
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
            $this->assertEquals(
                'Wrong "transfer_currency" value. It can be "sender" or "receiver".',
                $exception->getMessage()
            );
        }
    }

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

        $sender = $result[0];
        $receiver = $result[1];

        $this->assertNotEmpty($result);
        $this->assertEquals(0, $sender['balance']);
        $this->assertEquals(3727.3, $receiver['balance']);
    }

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

        $sender = $result[0];
        $receiver = $result[1];

        $this->assertNotEmpty($result);
        $this->assertEquals(96.34, $sender['balance']);
        $this->assertEquals(1100, $receiver['balance']);
    }
}
