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
require_once(dirname(__FILE__) . '/../controllerbase.php');

/**
 * ベーシック認証用のレスポンスを返しプログラムを終了する。
 * @return void
 */
function exitForAuth() {
	header('WWW-Authenticate: Basic realm="' . APP_NAME . '"');
	exitForError('HTTP/1.0 401 Unauthorized', 'Authorization Required', 'ブログを編集するには、登録したメールアドレスとパスワードを入力してください。');
}

/**
 * リダイレクト用のレスポンスを返しプログラムを終了する。
 * @param $url リダイレクト先URL。
 * @return void
 */
function exitForRedirect($url) {
	header("Location: $url");
	exit;
}

// もしDBにブログが一件も登録されていない場合、認証無しで新規ブログ作成の画面を表示する
if (Blog::count() == 0) {
	if (preg_match('|.*/config.php|', $_SERVER['SCRIPT_NAME'])) {
		return;
	} else {
		exitForRedirect('config.php');
	}
}

// ベーシック認証用のパラメータが来ていない場合、即終了
if (!isset($_SERVER['PHP_AUTH_USER'])) {
	exitForAuth();
}

// パラメータが来ている場合、ブログに登録されている情報で認証
// ※ 認証が必要な画面では、ログインしたブログ情報を参照可能
$_BLOG = Blog::authorize($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
if (!$_BLOG) {
	exitForAuth();
}
