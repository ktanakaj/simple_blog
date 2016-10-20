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
 * @param Content $content ブログ記事。
 */
?>
<header>
  <?php if (!empty($content->tags)): ?>
    <p class="tags">
      <?php foreach ($content->tags as $tag): ?>
        <a href="?tag=<?= urlencode($tag) ?>"><?= htmlspecialchars($tag, ENT_HTML5, APP_CHARSET) ?></a>
      <?php endforeach; ?>
    </p>
  <?php endif; ?>
  <p class="date"><?= $content->date ?></p>
</header>