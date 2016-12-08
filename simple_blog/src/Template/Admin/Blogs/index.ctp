<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @version    0.3
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 *
 * @param Blog $blog ブログ情報。
 * @param array $contents ブログ記事の配列。
 * @param int $page 現在のページ数。
 * @param int $last 最終ページ数。
 * @param string $message 通知メッセージ。
 */
?>
<table id="config" class="table table-bordered">
  <tbody>
    <tr><th>ブログ名</th><td><?= h($blog->title) ?></td></tr>
    <tr><th>メールアドレス</th><td><?= h($blog->mail_address) ?></td></tr>
    <tr><th>最終ログイン日時</th><td><?= $blog->last_login ?></td></tr>
  </tbody>
</table>

<?php if (empty($contents)): ?>
  <p>指定されたページは存在しません。</p>
<?php else: ?>

<table id="contents" class="table table-bordered">
  <thead>
    <tr><th>投稿日時</th><th>タイトル</th><th>状態</th><th></th></tr>
  </thead>
  <tbody>
    <?php foreach ($contents as $content): ?>
      <tr>
        <td><?= $content->date ?></td>
        <td><?= $this->Html->link($content->title, ['controller' => 'Contents', 'action' => 'edit', $content->id]) ?></td>
        <td><?= $content->visible ? '表示' : '非表示' ?></td>
        <td><?= $this->Form->postLink('削除', ['controller' => 'Contents', 'action' => 'delete', $content->id], ['confirm' => '本当に削除しますか？']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $this->fetch('paging') ?>

<?php endif; ?>

<ul>
  <li><?= $this->Html->link('新しい記事の作成', ['controller' => 'Contents', 'action' => 'add']) ?></li>
  <li><?= $this->Html->link('ブログ全体の設定', ['controller' => 'Blogs', 'action' => 'edit', $blog->id]) ?></li>
</ul>
