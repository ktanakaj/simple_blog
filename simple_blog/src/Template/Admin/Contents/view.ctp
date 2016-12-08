<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Content'), ['action' => 'edit', $content->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Content'), ['action' => 'delete', $content->id], ['confirm' => __('Are you sure you want to delete # {0}?', $content->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Contents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Content'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Blogs'), ['controller' => 'Blogs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Blog'), ['controller' => 'Blogs', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Tags'), ['controller' => 'Tags', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Tag'), ['controller' => 'Tags', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="contents view large-9 medium-8 columns content">
    <h3><?= h($content->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Blog') ?></th>
            <td><?= $content->has('blog') ? $this->Html->link($content->blog->title, ['controller' => 'Blogs', 'action' => 'view', $content->blog->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($content->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Summary') ?></th>
            <td><?= h($content->summary) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($content->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($content->date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Visible') ?></th>
            <td><?= $content->visible ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Text') ?></h4>
        <?= $this->Text->autoParagraph(h($content->text)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Tags') ?></h4>
        <?php if (!empty($content->tags)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Content Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($content->tags as $tags): ?>
            <tr>
                <td><?= h($tags->id) ?></td>
                <td><?= h($tags->content_id) ?></td>
                <td><?= h($tags->name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Tags', 'action' => 'view', $tags->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Tags', 'action' => 'edit', $tags->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tags', 'action' => 'delete', $tags->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tags->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
