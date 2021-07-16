<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $countries
 *
 */

$this->extend('../layout/TwitterBootstrap/signin');
$this->Html->css('main.css', ['block' => true]);
?>
<div class="card col-sm-12 col-md-4">
    <?= $this->Html->image('/img/logo-big.png', ['class' => 'img-fluid']) ?>
    <div class="card-body">
        <?= $this->Form->create($user) ?>
        <fieldset>
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
        <div class="text-center mt-3">
            <p style="color:#9f0507;"><b>OR</b></p>
            <a class="btn btn-primary btn-block" href="/login">Login</a>
        </div>
    </div>
</div>
