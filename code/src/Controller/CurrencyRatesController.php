<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\CurrencyRate;
use Cake\Datasource\ResultSetInterface;

/**
 * CurrencyRates Controller
 *
 * @property \App\Model\Table\CurrencyRatesTable $CurrencyRates
 *
 * @method CurrencyRate[]|ResultSetInterface paginate($object = null, array $settings = [])
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
            'contain' => ['Currencies'],
        ];
        $currencyRates = $this->paginate($this->CurrencyRates);

        $this->set(compact('currencyRates'));
    }

    /**
     * View method
     *
     * @param string|null $id Currency Rate id.
     *
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $currencyRate = $this->CurrencyRates->get($id, [
            'contain' => ['Currencies'],
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
            /** @var array $data */
            $data = $this->request->getData();
            $currencyRate = $this->CurrencyRates->patchEntity($currencyRate, $data);
            if ($this->CurrencyRates->save($currencyRate)) {
                $this->Flash->success(__('The currency rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The currency rate could not be saved. Please, try again.'));
        }
        $currencies = $this->CurrencyRates->Currencies->find('list', ['limit' => 200]);
        $this->set(compact('currencyRate', 'currencies'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Currency Rate id.
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $currencyRate = $this->CurrencyRates->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            /** @var array $data */
            $data = $this->request->getData();
            $currencyRate = $this->CurrencyRates->patchEntity($currencyRate, $data);
            if ($this->CurrencyRates->save($currencyRate)) {
                $this->Flash->success(__('The currency rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The currency rate could not be saved. Please, try again.'));
        }
        $currencies = $this->CurrencyRates->Currencies->find('list', ['limit' => 200]);
        $this->set(compact('currencyRate', 'currencies'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Currency Rate id.
     *
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
