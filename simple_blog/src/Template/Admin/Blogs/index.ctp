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
<table id="config">
  <tbody>
    <tr><th>ブログ名</th><td><?= h($blog->title) ?></td></tr>
    <tr><th>メールアドレス</th><td><?= h($blog->mail_address) ?></td></tr>
    <tr><th>最終ログイン日時</th><td><?= $blog->last_login ?></td></tr>
  </tbody>
</table>

<?php if (empty($last)): ?>
  <p>このブログはまだ執筆されていません。</p>
<?php elseif (empty($contents)): ?>
  <p>指定されたページは存在しません。</p>
<?php else: ?>

<table id="contents">
  <thead>
    <tr><th>投稿日時</th><th>タイトル</th><th>状態</th><th></th></tr>
  </thead>
  <tbody>
    <?php foreach ($contents as $content): ?>
      <tr>
        <td><?= $content->date ?></td>
        <td><a href="contents/edit/<?= $content->id ?>"><?= h($content->title) ?></a></td>
        <td><?= $content->visible ? '表示' : '非表示' ?></td>
        <td><form action="contents/delete/<?= $content->id ?>" method="post"><input type="submit" value="削除" onclick="return window.confirm('本当に削除しますか？')"></form></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $this->fetch('paging') ?>

<?php endif; ?>

<ul>
  <li><a href="contents/add">新しい記事の作成</a></li>
  <li><a href="edit/<?= $blog->id ?>">ブログ全体の設定</a></li>
</ul>
