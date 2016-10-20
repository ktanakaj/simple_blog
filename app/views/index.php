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
 */
?>
<?php if (empty($last)): ?>
  <p>このブログはまだ執筆されていません。</p>
<?php elseif (empty($contents)): ?>
  <p>指定されたページは存在しません。</p>
<?php else: ?>

<?php foreach ($contents as $content): ?>
  <article class="content">
    <h2><a href="?id=<?= $content->id ?>"><?= htmlspecialchars($content->title, ENT_HTML5, APP_CHARSET) ?></a></h2>
    <?php require 'includes/contentheader.php'; ?>
    <div class="summary"><?= nl2br(htmlspecialchars($content->summary, ENT_HTML5, APP_CHARSET), false) ?></div>
  </article>
<?php endforeach; ?>

<?php require 'includes/paging.php'; ?>

<?php endif; ?>
