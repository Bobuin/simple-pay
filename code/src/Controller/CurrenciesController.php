<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Currency;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\BadRequestException;

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
        $this->set('_serialize', ['currencies']);
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
            'contain' => ['CurrencyRates', 'Wallets'],
        ]);

        $this->set('currency', $currency);
        $this->set('_serialize', ['currency']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Http\Exception\BadRequestException
     */
    public function add()
    {
        $this->request->allowMethod('post');

        $currency = $this->Currencies->newEntity();

        /** @var array $data */
        $data = $this->request->getData();
        $currency = $this->Currencies->patchEntity($currency, $data);

        if (!$this->Currencies->save($currency)) {
            throw new BadRequestException(__('The currency could not be saved. Please, try again.'));
        }

        $this->set(compact('currency'));
        $this->set('_serialize', ['currency']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Currency id.
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);

        $currency = $this->Currencies->get($id);

        /** @var array $data */
        $data = $this->request->getData();
        $currency = $this->Currencies->patchEntity($currency, $data);
        if (!$this->Currencies->save($currency)) {
            throw new BadRequestException(__('The currency could not be saved. Please, try again.'));
        }

        $this->set(compact('currency'));
        $this->set('_serialize', ['currency']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Currency id.
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $currency = $this->Currencies->get($id);

        if (!$this->Currencies->delete($currency)) {
            throw new BadRequestException(__('The currency could not be deleted. Please, try again.'));
        }

        $success = true;
        $message = __('The currency has been deleted.');

        $this->set(compact('success', 'message'));
        $this->set('_serialize', ['success', 'message']);
    }
}
