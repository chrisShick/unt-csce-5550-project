<?php
$this->extend('../layout/TwitterBootstrap/signin');
$this->Html->css('main.css', ['block' => true]);
?>
<div class="col-sm-12 col-md-4">
    <div class="card">
        <?= $this->Html->image('/img/logo-big.png', ['class' => 'img-fluid']) ?>
        <div class="card-body">
            <?= $this->Flash->render() ?>
            <?= $this->Form->create() ?>
            <fieldset>
                <?= $this->Form->control('username', ['required' => true]) ?>
                <?= $this->Form->control('password', ['required' => true]) ?>
            </fieldset>
            <?= $this->Form->submit(__('Login'), ['class' => 'btn btn-primary btn-block']); ?>
            <?= $this->Form->end() ?>
            <div class="text-center mt-3">
                <p style="color:#9f0507;"><b>OR</b></p>
                <a class="btn btn-primary btn-block" href="/register">Register Now</a>
            </div>
        </div>
    </div>
</div>

