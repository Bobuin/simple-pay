<?php
declare(strict_types=1);

namespace App\Controller;

use App\Logic\ReportLogic;
use App\Model\Entity\Transaction;
use Cake\Datasource\ResultSetInterface;
use Cake\I18n\FrozenTime;

/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 *
 * @method Transaction[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransactionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Wallets'],
        ];
        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     *
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => ['Wallets'],
        ]);

        $this->set('transaction', $transaction);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $transaction = $this->Transactions->newEntity();
        if ($this->request->is('post')) {
            /** @var array $data */
            $data = $this->request->getData();
            $transaction = $this->Transactions->patchEntity($transaction, $data);
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $wallets = $this->Transactions->Wallets->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'wallets'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            /** @var array $data */
            $data = $this->request->getData();
            $transaction = $this->Transactions->patchEntity($transaction, $data);
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $wallets = $this->Transactions->Wallets->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'wallets'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id);
        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__('The transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Report method
     *
     * @param int $userId The ID of User to show report
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     */
    public function report($userId)
    {
        /** @var array $data */
        $data = $this->request->getData();

        $data['id'] = $userId;
        $data['date_from'] = $this->request->getData('date_from');
        $data['date_to'] = $this->request->getData('date_to');

        $reports = (new ReportLogic())->getReport($data);

        $transactions = $this->paginate($reports);

        $this->set(compact('transactions', 'userId'));
    }

    /**
     * @param int $userId The ID of User to download report
     *
     * @return \Cake\Http\Response|null|void
     * @throws \InvalidArgumentException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     */
    public function download($userId)
    {
        $this->disableAutoRender();

        /** @var array $data */
        $data = $this->request->getData();

        $data['id'] = $userId;

        /** @var Transaction[] $reports */
        $reports = (new ReportLogic())->getReport($data)->toArray();

        $delimiter = ',';
        $outputFileName = 'Report-' . date('YmdHis') . '.csv';
        $tempMemory = fopen('php://memory', 'wb');

        FrozenTime::setToStringFormat('M/d/Y H:m:s');
        // loop through the array
        foreach ($reports as $operation) {
            $line = [
                'id' => $operation->id,
                'wallet_id' => $operation->wallet_id,
                'amount' => $operation->amount,
                'base_amount' => $operation->base_amount,
                'created' => $operation->created,
            ];

            // use the default csv handler
            fputcsv($tempMemory, $line, $delimiter);
        }

        fseek($tempMemory, 0);

        // modify the header to be CSV format
        $this->response = $this->response->withHeader('Content-Type', 'application/csv');
        $this->response = $this->response->withHeader(
            'Content-Disposition',
            'attachement; filename="' . $outputFileName . '";'
        );

        // output the file to be downloaded
        fpassthru($tempMemory);

        return $this->response;
    }
}
