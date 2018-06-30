<?php
declare(strict_types=1);

namespace App\Logic;

use App\Model\Entity\Wallet;
use App\Model\Table\TransactionsTable;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * Class ReportLogic
 *
 * @package App\Logic
 */
class ReportLogic
{
    private $usersTable;
    private $walletsTable;
    private $transactionsTable;

    /**
     * ReportLogic constructor.
     */
    public function __construct()
    {
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
        $this->walletsTable = TableRegistry::getTableLocator()->get('Wallets');
        $this->transactionsTable = TableRegistry::getTableLocator()->get('Transactions');
    }

    /**
     * @uses TransactionsTable::findByUser()
     *
     * @param array $data Report params
     *
     * @return Query
     * @throws \Cake\Http\Exception\NotFoundException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     */
    public function getReport($data): Query
    {
        $exists = $this->usersTable->exists(['id' => $data['id']]);

        if (!$exists) {
            throw new NotFoundException(__('User with this ID is not exist.'));
        }

        /** @var Wallet $wallets */
        $wallet = $this->walletsTable
            ->find()
            ->contain('Users')
            ->where([
                'Users.id' => $data['id'],
            ])
            ->first();

        if (null === $wallet) {
            throw new NotFoundException(__('The requested wallet is not exists.'));
        }

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
