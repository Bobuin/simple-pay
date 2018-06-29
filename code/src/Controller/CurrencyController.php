<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Currency Controller
 *
 * @property \App\Model\Table\CurrencyTable $Currency
 *
 * @method \App\Model\Entity\Currency[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CurrencyController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $currency = $this->paginate($this->Currency);

        $this->set(compact('currency'));
    }

    /**
     * View method
     *
     * @param string|null $id Currency id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $currency = $this->Currency->get($id, [
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
        $currency = $this->Currency->newEntity();
        if ($this->request->is('post')) {
            $currency = $this->Currency->patchEntity($currency, $this->request->getData());
            if ($this->Currency->save($currency)) {
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
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $currency = $this->Currency->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $currency = $this->Currency->patchEntity($currency, $this->request->getData());
            if ($this->Currency->save($currency)) {
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
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $currency = $this->Currency->get($id);
        if ($this->Currency->delete($currency)) {
            $this->Flash->success(__('The currency has been deleted.'));
        } else {
            $this->Flash->error(__('The currency could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
