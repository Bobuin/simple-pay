<?php
declare(strict_types=1);

namespace App\Controller;

use App\Logic\CurrencyRatesLogic;
use App\Logic\WalletLogic;
use App\Model\Entity\Wallet;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\BadRequestException;

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
        $this->paginate = [
            'contain' => ['Currencies'],
        ];
        $wallets = $this->paginate($this->Wallets);

        $this->set(compact('wallets'));
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
        $wallet = $this->Wallets->get($id, [
            'contain' => ['Currencies', 'Transactions', 'Users'],
        ]);

        $this->set('wallet', $wallet);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $wallet = $this->Wallets->newEntity();
        if ($this->request->is('post')) {
            /** @var array $data */
            $data = $this->request->getData();
            $wallet = $this->Wallets->patchEntity($wallet, $data);
            if ($this->Wallets->save($wallet)) {
                $this->Flash->success(__('The wallet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The wallet could not be saved. Please, try again.'));
        }
        $currency = $this->Wallets->Currencies->find('list', ['limit' => 200]);
        $this->set(compact('wallet', 'currency'));
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
        $wallet = $this->Wallets->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            /** @var array $data */
            $data = $this->request->getData();
            $wallet = $this->Wallets->patchEntity($wallet, $data);
            if ($this->Wallets->save($wallet)) {
                $this->Flash->success(__('The wallet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The wallet could not be saved. Please, try again.'));
        }
        $currency = $this->Wallets->Currencies->find('list', ['limit' => 200]);
        $this->set(compact('wallet', 'currency'));
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
        $this->request->allowMethod(['post', 'delete']);
        $wallet = $this->Wallets->get($id);
        if ($this->Wallets->delete($wallet)) {
            $this->Flash->success(__('The wallet has been deleted.'));
        } else {
            $this->Flash->error(__('The wallet could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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

        if (empty($data)) {
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

        $wallets = (new WalletLogic())->transfer($data);

        $this->set('wallets', $wallets);
        $this->set('_serialize', ['wallets']);
    }
}
