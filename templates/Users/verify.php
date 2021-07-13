<?php
$this->extend('../layout/TwitterBootstrap/signin');
$this->Html->css('main.css', ['block' => true]);
?>
<div class="card">
    <div class="card-body">
        <?= $this->Form->create(null, ['url' => ['action' => 'login']]) ?>
        <fieldset>
            <legend><?= __('Please enter your 2FA code') ?></legend>
            <?= $this->Form->control('code') ?>
        </fieldset>
        <?= $this->Form->button(__('Continue'), ['class' => 'btn btn-primary btn-block']); ?>
        <?= $this->Form->end() ?>
    </div>
</div>
