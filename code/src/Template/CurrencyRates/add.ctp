<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CurrencyRate $currencyRate
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Currency Rates'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Currency'), ['controller' => 'Currency', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Currency'), ['controller' => 'Currency', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="currencyRates form large-9 medium-8 columns content">
    <?= $this->Form->create($currencyRate) ?>
    <fieldset>
        <legend><?= __('Add Currency Rate') ?></legend>
        <?php
            echo $this->Form->control('currency_id', ['options' => $currency]);
            echo $this->Form->control('rate');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
