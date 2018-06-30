<?php
declare(strict_types=1);

namespace App\Logic;

use App\Model\Entity\Wallet;
use App\Model\Table\TransactionsTable;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * Class ReportLogic
 *
 * @package App\Logic
 */
class ReportLogic
{
    private $transactionsTable;
    private $walletsTable;

    /**
     * ReportLogic constructor.
     */
    public function __construct()
    {
        $this->walletsTable = TableRegistry::getTableLocator()->get('Wallets');
        $this->transactionsTable = TableRegistry::getTableLocator()->get('Transactions');
    }

    /**
     * @uses TransactionsTable::findByUser()
     *
     * @param array $data Report params
     *
     * @return Query
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     */
    public function getReport($data): Query
    {
        /** @var Wallet $wallets */
        $wallet = $this->walletsTable
            ->find()
            ->contain('Users')
            ->where([
                'Users.id' => $data['id'],
            ])
            ->first();

        return $this->transactionsTable->find(
            'byUser',
            [
                'dateFrom' => $data['date_from'],
                'dateTo' => $data['date_to'],
            ]
        )
            ->where(['Transactions.wallet_id' => $wallet->id]);
    }
}
