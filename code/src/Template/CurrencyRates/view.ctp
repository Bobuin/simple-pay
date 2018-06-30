<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CurrencyRate $currencyRate
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Currency Rate'), ['action' => 'edit', $currencyRate->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Currency Rate'), ['action' => 'delete', $currencyRate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $currencyRate->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Currency Rates'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Currency Rate'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Currencies'), ['controller' => 'Currencies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Currency'), ['controller' => 'Currencies', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="currencyRates view large-9 medium-8 columns content">
    <h3><?= h($currencyRate->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Currency') ?></th>
            <td><?= $currencyRate->has('currency') ? $this->Html->link($currencyRate->currency->name, ['controller' => 'Currencies', 'action' => 'view', $currencyRate->currency->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($currencyRate->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($currencyRate->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($currencyRate->created) ?></td>
        </tr>
    </table>
</div>
