<?php
declare(strict_types=1);

namespace App\Controller;

use App\Logic\CurrencyRatesLogic;
use App\Model\Entity\CurrencyRate;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\NotImplementedException;

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
        $this->set('_serialize', ['currencyRates']);
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
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     */
    public function add()
    {
        $this->request->allowMethod('post');

        /** @var array $data */
        $data = $this->request->getData();

        $currencyRate = (new CurrencyRatesLogic())->addRate($data);

        $currencies = $this->CurrencyRates->Currencies->find('list', ['limit' => 200]);

        $this->set(compact('currencyRate', 'currencies'));
        $this->set('_serialize', ['currencyRate', 'currencies']);
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
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Delete method
     *
     * @param string|null $id Currency Rate id.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotImplementedException('Method is not implemented.');
    }
}
