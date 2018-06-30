<?php

use App\Model\Entity\Transaction;

/**
 * @var \App\View\AppView                                  $this         App view component
 * @var Transaction[]|\Cake\Collection\CollectionInterface $transactions Array of Transactions
 * @var int                                                $userId       The ID of User for whom build report
 * @var float                                              $amount       Total wallet turnover
 * @var float                                              $baseAmount   Total wallet turnover in USD
 * @var array                                              $users        List of Users names with ID keys
 */

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?php
            echo $this->Form->create(null, ['url' => '/reports/download/' . $userId, 'target' => '_blank']);
            echo $this->Form->control(
                'date_from',
                [
                    'type' => 'hidden',
                    'value' => $this->request->getData('date_from')
                        ? implode(' ', $this->request->getData('date_from'))
                        : null,
                ]
            );
            echo $this->Form->control(
                'date_to',
                [
                    'type' => 'hidden',
                    'value' => $this->request->getData('date_to')
                        ? implode(' ', $this->request->getData('date_to'))
                        : null,
                ]
            );
            echo $this->Form->button(
                'Download CSV',
                [
                    'class' => 'btn btn_yellow',
                    'type' => 'submit',
                ]
            );
            echo $this->Form->end();
            ?></li>
    </ul>
</nav>
<div class="transactions index large-9 medium-8 columns content">
    <h3><?= __('Transactions') ?></h3>
    <h5><?= __('Total amount') . ': ' . $amount ?></h5>
    <h5><?= __('Total USD amount') . ': ' . $baseAmount ?></h5>
    <?php
    echo $this->Form->create(null, ['url' => '/reports/' . ($this->request->getData('user_id') ?: $userId)]);

    echo $this->Form->control('user_id', ['options' => $users]);

    echo $this->Form->control('date_from', ['type' => 'datetime', 'second' => true]);

    echo $this->Form->control('date_to', ['type' => 'datetime', 'second' => true]);

    echo $this->Form->button('Submit', ['id' => 'submit']);

    echo $this->Form->end();
    ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('wallet_id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('amount') ?></th>
            <th scope="col"><?= $this->Paginator->sort('base_amount') ?></th>
            <th scope="col"><?= $this->Paginator->sort('created') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?= $this->Number->format($transaction->id) ?></td>
                <td><?= $transaction->has('wallet') ? $this->Html->link($transaction->wallet->id,
                        ['controller' => 'Wallets', 'action' => 'view', $transaction->wallet->id]) : '' ?></td>
                <td><?= $this->Number->format($transaction->amount) ?></td>
                <td><?= $this->Number->format($transaction->base_amount) ?></td>
                <td><?= h($transaction->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $transaction->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $transaction->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $transaction->id],
                        ['confirm' => __('Are you sure you want to delete # {0}?', $transaction->id)]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
