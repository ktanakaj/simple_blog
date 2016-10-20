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
 * @param array $tags タグ情報配列。
 */
?>
<nav class="tags">
  <h2>タグ</h2>
  <?php if (!empty($tags)): ?>
    <ul>
      <?php foreach ($tags as $tag): ?>
        <li><a href="?tag=<?= urlencode($tag->name) ?>"><?= htmlspecialchars($tag->name, ENT_HTML5, APP_CHARSET) ?></a></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</nav>
