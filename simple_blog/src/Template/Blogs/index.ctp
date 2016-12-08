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
 */
?>
<?php if (empty($last)): ?>
  <p>このブログはまだ執筆されていません。</p>
<?php elseif (empty($contents)): ?>
  <p>指定されたページは存在しません。</p>
<?php else: ?>

<?php foreach ($contents as $content): ?>
  <article class="content">
    <h2><a href="contents/<?= $content->id ?>"><?= h($content->title) ?></a></h2>
    <?= $this->fetch('contentHeader') ?>
    <div class="summary"><?= nl2br(h($content->summary), false) ?></div>
  </article>
<?php endforeach; ?>

<?= $this->fetch('paging') ?>

<?php endif; ?>
