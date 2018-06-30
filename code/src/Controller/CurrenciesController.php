<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Currency;
use Cake\Datasource\ResultSetInterface;

/**
 * Currencies Controller
 *
 * @property \App\Model\Table\CurrenciesTable $Currencies
 *
 * @method Currency[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class CurrenciesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $currencies = $this->paginate($this->Currencies);

        $this->set(compact('currencies'));
    }

    /**
     * View method
     *
     * @param string|null $id Currency id.
     *
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $currency = $this->Currencies->get($id, [
            'contain' => ['CurrencyRates', 'Wallets']
        ]);

        $this->set('currency', $currency);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $currency = $this->Currencies->newEntity();
        if ($this->request->is('post')) {
            /** @var array $data */
            $data = $this->request->getData();
            $currency = $this->Currencies->patchEntity($currency, $data);
            if ($this->Currencies->save($currency)) {
                $this->Flash->success(__('The currency has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The currency could not be saved. Please, try again.'));
        }
        $this->set(compact('currency'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Currency id.
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $currency = $this->Currencies->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            /** @var array $data */
            $data = $this->request->getData();
            $currency = $this->Currencies->patchEntity($currency, $data);
            if ($this->Currencies->save($currency)) {
                $this->Flash->success(__('The currency has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The currency could not be saved. Please, try again.'));
        }
        $this->set(compact('currency'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Currency id.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $currency = $this->Currencies->get($id);
        if ($this->Currencies->delete($currency)) {
            $this->Flash->success(__('The currency has been deleted.'));
        } else {
            $this->Flash->error(__('The currency could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}