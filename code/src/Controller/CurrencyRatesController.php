<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CurrencyRates Controller
 *
 * @property \App\Model\Table\CurrencyRatesTable $CurrencyRates
 *
 * @method \App\Model\Entity\CurrencyRate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CurrencyRatesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Currency']
        ];
        $currencyRates = $this->paginate($this->CurrencyRates);

        $this->set(compact('currencyRates'));
    }

    /**
     * View method
     *
     * @param string|null $id Currency Rate id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $currencyRate = $this->CurrencyRates->get($id, [
            'contain' => ['Currency']
        ]);

        $this->set('currencyRate', $currencyRate);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $currencyRate = $this->CurrencyRates->newEntity();
        if ($this->request->is('post')) {
            $currencyRate = $this->CurrencyRates->patchEntity($currencyRate, $this->request->getData());
            if ($this->CurrencyRates->save($currencyRate)) {
                $this->Flash->success(__('The currency rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The currency rate could not be saved. Please, try again.'));
        }
        $currency = $this->CurrencyRates->Currency->find('list', ['limit' => 200]);
        $this->set(compact('currencyRate', 'currency'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Currency Rate id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $currencyRate = $this->CurrencyRates->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $currencyRate = $this->CurrencyRates->patchEntity($currencyRate, $this->request->getData());
            if ($this->CurrencyRates->save($currencyRate)) {
                $this->Flash->success(__('The currency rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The currency rate could not be saved. Please, try again.'));
        }
        $currency = $this->CurrencyRates->Currency->find('list', ['limit' => 200]);
        $this->set(compact('currencyRate', 'currency'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Currency Rate id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $currencyRate = $this->CurrencyRates->get($id);
        if ($this->CurrencyRates->delete($currencyRate)) {
            $this->Flash->success(__('The currency rate has been deleted.'));
        } else {
            $this->Flash->error(__('The currency rate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
