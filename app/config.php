<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @version    0.2
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 */

// ※ 以下の値は環境に合わせて変更する

/**
 * アプリケーション名。
 * 
 * HTTPヘッダーにも出力しているため、英数字限定。
 */
define('APP_NAME', 'Simple Blog');
/** 管理画面タイトル。 */
define('ADMIN_TITLE', APP_NAME . ' 管理画面');

// ※ ただし、ビューなどは可能な限り相対パスを用いている
/** アプリケーションURL。 */
define('BLOG_ROOT', (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["CONTEXT_PREFIX"]);
/** 管理画面URL。 */
define('ADMIN_ROOT', BLOG_ROOT . '/admin');

/**
 * アプリのデフォルト文字コード。
 * 
 * ビューも含めて全て同じ文字コードを使うことが想定されている。
 * 変更する場合は、このコードだけでなくビューなどのファイルのコード自体も修正が必要。
 */
define('APP_CHARSET', 'UTF-8');

/** DB接続用のDSN。 */
define('DATA_SOURCE_NAME', 'mysql:host=localhost;dbname=simple_blog');
/** DB接続用のユーザー名。 */
define('DB_USER', 'simple_blog');
/** DB接続用のパスワード。 */
define('DB_PASSWORD', 'simple_blog01');

/** TwitterのAPIキー。 */
define('TWITTER_API_KEY', '______DUMMY_API_KEY______');
/** TwitterのAPIシークレット。 */
define('TWITTER_API_SECRET', '______DUMMY_API_SECRET______');
