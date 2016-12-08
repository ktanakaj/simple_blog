<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Blogs'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Contents'), ['controller' => 'Contents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Content'), ['controller' => 'Contents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Oauth'), ['controller' => 'Oauth', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Oauth'), ['controller' => 'Oauth', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="blogs form large-9 medium-8 columns content">
    <?= $this->Form->create($blog) ?>
    <fieldset>
        <legend><?= __('Add Blog') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('mail_address');
            echo $this->Form->input('password');
            echo $this->Form->input('last_login', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
