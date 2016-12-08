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
<?php if (!$content): ?>
  <p>指定された記事は存在しません。削除されたか非表示に設定されている可能性があります。</p>
<?php else: ?>
  <article class="content">
    <h2><?= h($content->title) ?></h2>
    <?= $this->element('contentHeader', ["content" => $content]) ?>
    <div class="text"><?= nl2br(h($content->text), false) ?></div>
  </article>
<?php endif; ?>
