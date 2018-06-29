<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CurrencyRate[]|\Cake\Collection\CollectionInterface $currencyRates
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Currency Rate'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Currency'), ['controller' => 'Currency', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Currency'), ['controller' => 'Currency', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="currencyRates index large-9 medium-8 columns content">
    <h3><?= __('Currency Rates') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('currency_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($currencyRates as $currencyRate): ?>
            <tr>
                <td><?= $this->Number->format($currencyRate->id) ?></td>
                <td><?= $currencyRate->has('currency') ? $this->Html->link($currencyRate->currency->name, ['controller' => 'Currency', 'action' => 'view', $currencyRate->currency->id]) : '' ?></td>
                <td><?= $this->Number->format($currencyRate->rate) ?></td>
                <td><?= h($currencyRate->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $currencyRate->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $currencyRate->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $currencyRate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $currencyRate->id)]) ?>
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
