<?php

use Migrations\AbstractMigration;


class Simplepay extends AbstractMigration
{
    /**
     * Migrate Up
     * @return  void
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function up(): void
    {
        $this->table('currency')
            ->addColumn('code', 'string', [
                'default' => null,
                'limit' => 3,
                'null' => true,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->create();

        $this->table('currency_rates')
            ->addColumn('currency_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('rate', 'integer', [
                'default' => null,
                'limit' => 8,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'currency_id',
                ]
            )
            ->create();

        $this->table('transactions')
            ->addColumn('wallet_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('amount', 'decimal', [
                'default' => null,
                'null' => true,
                'precision' => 10,
                'scale' => 2,
            ])
            ->addColumn('base_amount', 'decimal', [
                'default' => null,
                'null' => true,
                'precision' => 10,
                'scale' => 2,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'wallet_id',
                ]
            )
            ->create();

        $this->table('users')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('country', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('city', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('wallet_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addIndex(
                [
                    'wallet_id',
                ]
            )
            ->create();

        $this->table('wallets')
            ->addColumn('currency_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('balance', 'decimal', [
                'default' => null,
                'null' => true,
                'precision' => 10,
                'scale' => 2,
            ])
            ->addIndex(
                [
                    'currency_id',
                ]
            )
            ->create();

        $this->table('currency_rates')
            ->addForeignKey(
                'currency_id',
                'currency',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('transactions')
            ->addForeignKey(
                'wallet_id',
                'wallets',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('users')
            ->addForeignKey(
                'wallet_id',
                'wallets',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('wallets')
            ->addForeignKey(
                'currency_id',
                'currency',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();
    }

    /**
     * Migrate Down
     * @return  void
     */
    public function down(): void
    {
        $this->table('currency_rates')
            ->dropForeignKey(
                'currency_id'
            );

        $this->table('transactions')
            ->dropForeignKey(
                'wallet_id'
            );

        $this->table('users')
            ->dropForeignKey(
                'wallet_id'
            );

        $this->table('wallets')
            ->dropForeignKey(
                'currency_id'
            );

        $this->dropTable('currency');
        $this->dropTable('currency_rates');
        $this->dropTable('transactions');
        $this->dropTable('users');
        $this->dropTable('wallets');
    }
}
