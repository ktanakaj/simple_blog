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
 * @param int $page 現在のページ数。
 * @param int $last 最終ページ数。
 */

// 元のパラメータを復元
$orgParam = '&' . getOriginalParams('page');
if ($orgParam === '&') {
	$orgParam = '';
}
?>
<?php if ($page > 1 || $page < $last): ?>
<nav class="paging">
  <p class="page_no">(<?= $page ?>/<?= $last ?>)</p>
<p class="navi">
  <?php if ($page > 1): ?>
    &lt;&lt; <a href="?page=<?= ($page - 1) . $orgParam ?>">新しい記事へ</a>
  <?php endif; ?>
  <?php if ($page < $last): ?>
    <a href="?page=<?= ($page + 1) . $orgParam ?>">過去の記事へ</a> &gt;&gt;
  <?php endif; ?>
  </p>
</nav>
<?php endif; ?>
