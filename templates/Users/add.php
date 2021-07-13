<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $countries
 *
 */

$this->extend('../layout/TwitterBootstrap/signin');
?>
<div class="card col-sm-12 col-md-4">
    <div class="card-body">
        <?= $this->Form->create($user) ?>
        <fieldset>
            <legend><?= __('Add User') ?></legend>
            <?php
            echo $this->Form->control('username');
            echo '<div class="form-group">';
            echo $this->Form->label('Country Code');
            echo $this->Form->select('country_code', $countries);
            echo '</div>';
            echo $this->Form->control('phone');
            echo $this->Form->control('password');
            echo $this->Form->control('name');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Register'), ['class' => 'btn btn-primary btn-block']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
