<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Blog'), ['action' => 'edit', $blog->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Blog'), ['action' => 'delete', $blog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $blog->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Blogs'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Blog'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Contents'), ['controller' => 'Contents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Content'), ['controller' => 'Contents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Oauth'), ['controller' => 'Oauth', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Oauth'), ['controller' => 'Oauth', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="blogs view large-9 medium-8 columns content">
    <h3><?= h($blog->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($blog->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mail Address') ?></th>
            <td><?= h($blog->mail_address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($blog->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($blog->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Login') ?></th>
            <td><?= h($blog->last_login) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Contents') ?></h4>
        <?php if (!empty($blog->contents)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Blog Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Summary') ?></th>
                <th scope="col"><?= __('Date') ?></th>
                <th scope="col"><?= __('Visible') ?></th>
                <th scope="col"><?= __('Text') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($blog->contents as $contents): ?>
            <tr>
                <td><?= h($contents->id) ?></td>
                <td><?= h($contents->blog_id) ?></td>
                <td><?= h($contents->title) ?></td>
                <td><?= h($contents->summary) ?></td>
                <td><?= h($contents->date) ?></td>
                <td><?= h($contents->visible) ?></td>
                <td><?= h($contents->text) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Contents', 'action' => 'view', $contents->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Contents', 'action' => 'edit', $contents->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Contents', 'action' => 'delete', $contents->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contents->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Oauth') ?></h4>
        <?php if (!empty($blog->oauth)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Blog Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Access Token') ?></th>
                <th scope="col"><?= __('Access Secret') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($blog->oauth as $oauth): ?>
            <tr>
                <td><?= h($oauth->blog_id) ?></td>
                <td><?= h($oauth->type) ?></td>
                <td><?= h($oauth->access_token) ?></td>
                <td><?= h($oauth->access_secret) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Oauth', 'action' => 'view', $oauth->blog_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Oauth', 'action' => 'edit', $oauth->blog_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Oauth', 'action' => 'delete', $oauth->blog_id], ['confirm' => __('Are you sure you want to delete # {0}?', $oauth->blog_id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
