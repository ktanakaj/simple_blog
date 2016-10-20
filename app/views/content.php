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
<?php if (!$content): ?>
  <p>指定された記事は存在しません。削除されたか非表示に設定されている可能性があります。</p>
<?php else: ?>
  <article class="content">
    <h2><?= htmlspecialchars($content->title, ENT_HTML5, APP_CHARSET) ?></h2>
    <?php require 'includes/contentheader.php'; ?>
    <div class="text"><?= nl2br(htmlspecialchars($content->text, ENT_HTML5, APP_CHARSET), false) ?></div>
  </article>
<?php endif; ?>
  