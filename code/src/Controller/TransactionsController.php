<?php
declare(strict_types=1);

namespace App\Controller;

use App\Logic\ReportLogic;
use App\Model\Entity\Transaction;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\NotImplementedException;
use Cake\Http\Response;
use Cake\I18n\Time;

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
     * @return Response|void
     */
    public function index()
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     *
     * @return Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Add method
     *
     * @return Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     *
     * @return Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     *
     * @return Response|null Redirects to index.
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotImplementedException('Method is not implemented.');
    }

    /**
     * Report method
     *
     * @param int $userId The ID of User to show report
     *
     * @return Response|null|void
     * @throws \InvalidArgumentException
     * @throws \Cake\Http\Exception\NotFoundException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     */
    public function report($userId)
    {
        $data['id'] = $this->request->getData('user_id') ?: $userId;
        $data['date_from'] = $this->formatDate('date_from');
        $data['date_to'] = $this->formatDate('date_to');

        $reports = (new ReportLogic())->getReport($data);

        $amount = $reports->sumOf('amount');
        $baseAmount = $reports->sumOf('base_amount');

        $transactions = $this->paginate($reports);

        $users = $this->Transactions->Wallets->Users->find('list', ['limit' => 200]);

        $this->set(compact('transactions', 'userId', 'amount', 'baseAmount', 'users'));
    }

    /**
     * @param int $userId The ID of User to download report
     *
     * @return Response|null
     * @throws \Cake\Http\Exception\NotFoundException
     * @throws \InvalidArgumentException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     */
    public function download($userId): ?Response
    {
        $this->disableAutoRender();

        $data['id'] = $userId;
        $data['date_from'] = $this->formatDate('date_from');
        $data['date_to'] = $this->formatDate('date_to');

        /** @var Transaction[] $reports */
        $reports = (new ReportLogic())->getReport($data)->toArray();

        $delimiter = ',';

        $outputFileName = 'Report-' . date('YmdHis') . '.csv';
        $filePath = TMP . $outputFileName;
        $tempFile = fopen($filePath, 'wb+');

        if (!$tempFile) {
            throw new \InvalidArgumentException('Can\'t create report file.');
        }

        $headLine = [
            'id' => 'Transaction ID',
            'wallet_id' => 'Wallet ID',
            'amount' => 'Operation Amount',
            'base_amount' => 'Operation Base Amount',
            'created' => 'Operation Date',
        ];

        // use the default csv handler
        fputcsv($tempFile, $headLine, $delimiter);

        // loop through the array
        foreach ($reports as $operation) {
            $line = [
                'id' => $operation->id,
                'wallet_id' => $operation->wallet_id,
                'amount' => $operation->amount,
                'base_amount' => $operation->base_amount,
                'created' => $operation->created->format('M/d/Y H:i:s'),
            ];

            // use the default csv handler
            fputcsv($tempFile, $line, $delimiter);
        }

        fclose($tempFile);

        // modify the header to be CSV format
        $this->response = $this->response->withHeader('Content-Type', 'application/csv');
        $this->response = $this->response->withHeader(
            'Content-Disposition',
            'attachement; filename="' . $outputFileName . '";'
        );

        // output the file to be downloaded
        $this->response->withFile($filePath, ['download' => true]);

        unlink($filePath);

        return $this->response;
    }

    /**
     * @param string $param Name of date period boundary
     *
     * @return null|string
     * @throws \InvalidArgumentException
     */
    private function formatDate($param): ?string
    {
        $incomeFormat = 'Y m d H i s';
        $datetime = \is_array($this->request->getData($param))
            ? implode(' ', $this->request->getData($param))
            : (string)$this->request->getData($param);

        return $datetime
            ? Time::createFromFormat($incomeFormat, $datetime)->format('Y-m-d H:i:s')
            : null;
    }
}
