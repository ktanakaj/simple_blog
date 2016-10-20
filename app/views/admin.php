<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage views
 * @version    0.1
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2014 Koichi Tanaka
 * 
 * @param Blog $blog ブログ情報。
 * @param array $contents ブログ記事の配列。
 * @param int $page 現在のページ数。
 * @param int $last 最終ページ数。
 * @param string $message 通知メッセージ。
 */
?>
<?php require 'includes/message.php'; ?>
<table id="config">
  <tbody>
    <tr><th>ブログ名</th><td><?= htmlspecialchars($blog->title, ENT_HTML5, APP_CHARSET) ?></td></tr>
    <tr><th>メールアドレス</th><td><?= htmlspecialchars($blog->mail_address, ENT_HTML5, APP_CHARSET) ?></td></tr>
    <tr><th>Twitter連携</th><td>
      <?php if ($blog->twitterAuth()):?>
        <form method="post">有効 <input type="submit" name="remove" value="解除する"></form>
      <?php else: ?>
        <form action="twitter.php" method="post">無効 <input type="submit" value="有効にする"></form>
      <?php endif; ?>
    </td></tr>
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
        <td><a href="edit.php?id=<?= $content->id ?>"><?= htmlspecialchars($content->title, ENT_HTML5, APP_CHARSET) ?></a></td>
        <td><?= $content->visible ? '表示' : '非表示' ?></td>
        <td><form method="post"><input type="hidden" name="id" value="<?= $content->id ?>"><input type="submit" value="削除" onclick="return window.confirm('本当に削除しますか？')"></form></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require 'includes/paging.php'; ?>

<?php endif; ?>

<ul>
  <li><a href="edit.php">新しい記事の作成</a></li>
  <li><a href="config.php">ブログ全体の設定</a></li>
</ul>
