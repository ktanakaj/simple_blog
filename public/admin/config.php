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

$blog = new Blog();
$message = '';

if (isPost()) {
	// 設定実行時の処理
	foreach ($_POST as $key => $value) {
		// 基本は全て代入し、不正な値が入ると困る値は上書き
		$blog->{$key} = $value;
	}
	if (isset($_BLOG)) {
		// 新規作成時以外は現在のブログIDを設定
		$blog->id = $_BLOG->id;
	}
	$blog->password = null;
	if (!is_null($blog->password_raw) && $blog->password_raw !== '') {
		// パスワードは新しい値が指定されている場合のみ
		$blog->setHashPassword($value);
	}
	if ($blog->save()) {
		$message = '保存しました。';
	}
} else if (isset($_BLOG)) {
	// 認証状態でのアクセス（初回の新規作成以外）。現在のブログ情報を使用
	$blog = $_BLOG;
}

$body = render('config', ['blog' => $blog, 'message' => $message]);
echo render('admintemplate', ['body' => $body]);
