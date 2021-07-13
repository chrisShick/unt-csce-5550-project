<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\User $identity
 * @var array $countries
 */
?>
<?php $this->extend('/layout/TwitterBootstrap/dashboard'); ?>
<?php $this->Html->css('main.css', ['block' => true]) ?>

<?php $this->start('tb_actions'); ?>
<li><?= $this->Html->link(__('Profile'), ['action' => 'view', $identity->id], ['class' => 'nav-link']) ?></li>
<?php
if ($identity->user_role->title === 'Admin') :
?>
    <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'nav-link']) ?></li>
    <li><?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'nav-link']) ?> </li>
    <li><?= $this->Html->link(__('List User Roles'), ['controller' => 'UserRoles', 'action' => 'index'], ['class' => 'nav-link']) ?></li>
    <li><?= $this->Html->link(__('New User Role'), ['controller' => 'UserRoles', 'action' => 'add'], ['class' => 'nav-link']) ?></li>
<?php
endif;
?>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', '<ul class="nav flex-column">' . $this->fetch('tb_actions') . '</ul>'); ?>

<div class="row" style="margin-bottom: 40px;">
    <div class="col-md-4">
        <div class="card card-user">
            <div class="image">
                <img src="/img/damir-bosnjak.jpg" alt="...">
            </div>
            <div class="card-body">
                <div class="author">
                    <a href="#">
                        <img class="avatar border-gray" src="/img/default-avatar.png" alt="...">
                        <h5 class="title"><?= h($user->name) ?></h5>
                    </a>
                    <p class="description">
                        <?= h($user->getPhoneNumber()) ?>
                    </p>
                </div>
                <p class="description text-center">
                    <?= h($user->username) ?>
                </p>
            </div>
            <div class="card-footer">
                <hr>
                <div class="button-container">
                    <div class="row">
                        <div class="col-md-6 col-6 ml-auto">
                            <h5>12<br><small>Vaults</small></h5>
                        </div>
                        <div class="col-md-6 col-6 ml-auto mr-auto">
                            <h5>25<br><small>Secrets</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-user">
            <div class="card-header">
                <h5 class="card-title">Edit Profile</h5>
            </div>
            <div class="card-body">
                <?= $this->Form->create($user, ['novalidate']) ?>
                <?php
                if ($identity->user_role->title === 'Admin') :
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('user_role_id', ['options' => $userRoles]); ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('username'); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('password'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('name'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        echo '<div class="form-group">';
                        echo $this->Form->label('Country Code');
                        echo $this->Form->select('country_code', $countries, ['style' => 'height: 40px;']);
                        echo '</div>';
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('phone'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="update ml-auto mr-auto">
                        <?= $this->Form->button(__('Update Profile'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
