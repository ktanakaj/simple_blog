<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage controllers
 * @version    0.2
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 */

/** アプリケーションのルートとなるパス。 */
define('APP_ROOT', dirname(__FILE__) . '/../app/');
/** モデルクラスのパス。 */
define('MODELS_DIR', APP_ROOT . 'models/');
/** ビューファイルのパス。 */
define('VIEWS_DIR', APP_ROOT . 'views/');

require_once(APP_ROOT . '/config.php');

// クラスの自動読み込みの設定
spl_autoload_register(function(string $class) : bool {
	// 名前空間は使用しないという前提のもと簡略化
	$paths = [
		APP_ROOT . strtolower($class) . '.php',
		MODELS_DIR . strtolower($class) . '.php'
	];

	foreach ($paths as $path) {
		if (is_file($path)) {
			require_once $path;
			return true;
		}
	}

	return false;
});

/**
 * 指定されたビューの出力。
 * 画面表示のためには呼び出し元でecho等を用いる。
 * @param string $view ビュー名。
 * @param array $data ビューに渡すパラメータ。変数に展開される。
 * @return string 出力したビュー。
 */
function render(string $view, array $data = []) : string {
	extract($data);
	ob_start();
	require VIEWS_DIR . $view . '.php';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * リクエストがPOSTによるものか？
 * @return bool POSTの場合true, それ以外はfalse。
 */
function isPost() : bool {
	return $_SERVER["REQUEST_METHOD"] === "POST";
}

/**
 * 404用のヘッダーを設定する。
 * プログラムは終了しない。
 * @return void
 */
function headerForNotFound() : void {
	header('HTTP/1.0 404 Not Found');
}

/**
 * エラー用のHTTPヘッダーとエラー画面を出力し、プログラムを終了する。
 * @param string $header 出力するHTTPヘッダー。デフォルトは500エラー。
 * @param string $subject エラーの見出し。
 * @param string $message エラーメッセージ。
 * @return void
 */
function exitForError(string $header = 'HTTP/1.0 500 Internal Server Error', string $subject = '', string $message = '') : void {
	header($header);
	echo render('error', ['subject' => $subject, 'message' => $message]);
	exit;
}

/**
 * GETパラメータからページ番号を取得する。
 * @return int 取得したページ番号、未指定や不正な値には1を返す。
 */
function getPage() : int {
	$page = 1;
	if (!empty($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
		$page = (int) $_GET['page'];
	}
	return $page;
}

/**
 * 元々のGETパラメータを復元した文字列を返す。
 * @param mixed $ignores 復元しないパラメータ。配列で複数指定可能。
 * @return string GETパラメータ、URLエンコードが実施された値。
 */
function getOriginalParams($ignores = []) : string {
	$str = '';
	if (!is_array($ignores)) {
		$ignores = [$ignores];
	}
	foreach ($_GET as $key => $value) {
		if (!in_array($key, $ignores)) {
			if (!empty($str)) {
				$str .= '&';
			}
			$str .= urlencode($key) . '=' . urlencode($value);
		}
	}
	return $str;
}
