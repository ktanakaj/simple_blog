<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @version    0.3
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 *
 * @param Content $content ブログ記事。
 */
?>
<header>
  <?php if (!empty($content->tags)): ?>
    <p class="tags">
      <?php foreach ($content->tags as $tag): ?>
        <?= $this->Html->link($tag, ['action' => 'tags', urlencode($tag)]) ?>
      <?php endforeach; ?>
    </p>
  <?php endif; ?>
  <p class="date"><?= $content->date ?></p>
</header>