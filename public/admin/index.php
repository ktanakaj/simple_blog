<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage controllers
 * @version    0.1
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2014 Koichi Tanaka
 */
require_once(dirname(__FILE__) . '/auth.php');

/** 1ページの表示件数。 */
define('PAGE_MAX', 20);

$message = '';

if (isPost()) {
	if (isset($_POST['remove'])) {
		// Twitter連携の解除
		$oauth = $_BLOG->twitterAuth();
		if ($oauth) {
			$oauth->remove();
			$message = 'Twitter連携を解除しました。Twitterに登録済みのデータは削除されません。必要に応じて削除してください。';
		}
	} else if (!empty($_POST['id']) && is_numeric(($_POST['id']))) {
		// 記事の削除
		$content = Content::findById($_POST['id'], $_BLOG->id, false);
		if ($content) {
			$content->remove();
			$message = "\"{$content->title}\" を削除しました。";
		}
	}
}

$page = getPage();
$contents = $_BLOG->contents($page, PAGE_MAX, null, false);
if ($page != 1 && empty($contents)) {
	// 存在しないページの場合は404
	headerForNotFound();
}

$body = render('admin', ['blog' => $_BLOG, 'contents' => $contents, 'page' => $page, 'last' => ceil($_BLOG->countContents(null, false) / PAGE_MAX), 'message' => $message]);
echo render('admintemplate', ['body' => $body]);
