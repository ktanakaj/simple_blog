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

// リクエストトークンの検証用にセッションを用いる
// ※ 現状このアプリはこの処理でしかセッションを用いていないため。
//   他でも用いる場合は、破棄しないよう要改造
session_start();

if (isset($_REQUEST[Twitter::REQUEST_TOKEN_VERIFIER])) {
	// Twitterからのコールバック。認証情報を確認しDBに保存する
	if ($_SESSION['twitter_request_token'] !== $_REQUEST[Twitter::REQUEST_TOKEN_VERIFIER]) {
		session_destroy();
		exitForError('HTTP/1.0 409 Conflict', '', 'パラメータの検証に失敗しました。処理を最初からやり直してください。');
	}

	// コールバックされた情報から今度はアクセストークンを取得して保存する
	if (!Twitter::commitAuthorize(
			$_SESSION['twitter_request_token'],
			$_SESSION['twitter_request_secret'],
			$_REQUEST[Twitter::OAUTH_VERIFIER],
			$_BLOG->id)) {
		session_destroy();
		exitForError('HTTP/1.0 503 Service Unavailable', '', 'Twitterへの接続に失敗しました。時間をおいて再度実行してください。');
	}

	// セッションを破棄し、管理画面トップへ転送
	session_destroy();
	exitForRedirect('./');
} else {
	// 初回アクセス。Twitter接続用のパラメータを準備してTwitter側の認証ページに転送する
	$data = Twitter::startAuthorize(ADMIN_ROOT . '/twitter.php');
	if (!$data) {
		session_destroy();
		exitForError('HTTP/1.0 503 Service Unavailable', '', 'Twitterへの接続に失敗しました。時間をおいて再度実行してください。');
	}

	// リクエストトークンは戻ってきたときに一致を格納するためセッションに保存
	$_SESSION['twitter_request_token'] = $data['request_token'];
	$_SESSION['twitter_request_secret'] = $data['request_secret'];

	// Twitter側の認証ページに転送
	exitForRedirect($data['url']);
}