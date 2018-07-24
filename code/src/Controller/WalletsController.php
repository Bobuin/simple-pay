<?php
declare(strict_types=1);

namespace App\Controller;

use App\Logic\CurrencyRatesLogic;
use App\Logic\WalletLogic;
use App\Model\Entity\Wallet;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotImplementedException;

/**
 * Wallets Controller
 *
 * @property \App\Model\Table\WalletsTable $Wallets
 *
 * @method Wallet[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class WalletsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * View method
     *
     * @param string|null $id Wallet id.
     *
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Edit method
     *
     * @param string|null $id Wallet id.
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Delete method
     *
     * @param string|null $id Wallet id.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Add funds to Wallet method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function addFunds()
    {
        $this->request->allowMethod('post');

        /** @var array $data */
        $data = $this->request->getData();

        if (empty($data) || $data['amount'] <= 0) {
            throw new BadRequestException(__('Wrong request data.'));
        }

        /** @var Wallet|null $wallet */
        $wallet = $this->Wallets
            ->find()
            ->where(['Wallets.id' => $data['wallet_id']])
            ->first();

        if (null === $wallet) {
            throw new BadRequestException(__('The requested wallet is not exists.'));
        }

        $currencyRate = (new CurrencyRatesLogic())->getRate($wallet->currency_id);

        $baseAmount = round($data['amount'] / $currencyRate, 2, PHP_ROUND_HALF_DOWN);

        $wallet = (new WalletLogic())->addFunds($wallet, $data['amount'], $baseAmount);

        $this->set('wallet', $wallet);
        $this->set('_serialize', ['wallet']);
    }

    /**
     * Transfer funds method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \InvalidArgumentException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function transfer()
    {
        $this->request->allowMethod('post');

        /** @var array $data */
        $data = $this->request->getData();

        if (empty($data)) {
            throw new BadRequestException(__('Wrong request data.'));
        }

        try {
            $wallets = (new WalletLogic())->transfer($data);
        } catch (\InvalidArgumentException $exception) {
            throw new BadRequestException($exception->getMessage());
        }

        $this->set('wallets', $wallets);
        $this->set('_serialize', ['wallets']);
    }
}
