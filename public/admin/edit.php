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

$content = new Content();
$message = '';

if (isPost()) {
	// 投稿時の処理
	foreach ($_POST as $key => $value) {
		// 基本は全て代入し、不正な値が入ると困る値は上書き
		$content->{$key} = $value;
	}
	// ブログIDは認証時に取得した値を用いる
	$content->blog_id = $_BLOG->id;
	if (!empty($content->date_now)) {
		// 現在日時を使用する場合は、日時を更新
		$content->setNow();
	}
	if ($content->save()) {
		$message = '保存しました。';
		$content->date_now = '';
		if ($content->isPublic() && !empty($content->twitter) && $twitter = $_BLOG->twitterAuth()) {
			$content->twitter = '';
			if ($twitter->tweet($content->title, BLOG_ROOT . '/?id=' . $content->id)) {
				$message .= '投稿をTwitterに通知しました。';
			} else {
				$message .= 'Twitterへの通知はエラーのため完了しませんでした。';
			}
		}
	}
} else if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	// 編集時は指定された記事を初期値で取得
	$result = Content::findById($_GET['id'], $_BLOG->id, false);
	if ($result) {
		$content = $result;
	} else {
		headerForNotFound();
		$message = '指定された記事は存在しません。削除済みの可能性があります。';
	}
} else {
	// 新規作成時は日付に現在日時を初期値で設定
	$content->visible = true;
	$content->date_now = '1';
	$content->setNow();
	$content->twitter = '1';
}

$body = render('edit', ['content' => $content, 'message' => $message, 'twitter' => $_BLOG->twitterAuth()]);
echo render('admintemplate', ['body' => $body]);
