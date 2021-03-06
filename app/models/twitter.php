<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage models
 * @version    0.2
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 */
require(dirname(__FILE__) . '/../../vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter全体に関する処理を扱うクラス。
 *
 * Twitter API用のライブラリをラップする。
 * また認証情報などを管理する。
 *
 * @package  SimpleBlog
 */
class Twitter extends OAuth
{
	/** Twitterからのリクエストトークン検証用パラメータのキー値。 */
	public const REQUEST_TOKEN_VERIFIER = 'oauth_token';
	/** Twitterからの認証用パラメータのキー値。 */
	public const OAUTH_VERIFIER = 'oauth_verifier';
	/** ツィートの最大文字数。 */
	public const MAX_TWEET = 140;
	/** TwitterのユーザーページのURL。 */
	public const TWITTER_USER_URL = 'https://twitter.com/{$screen_name}';
	/** TwitterのステータスページのURL。 */
	public const TWITTER_TWEET_URL = 'https://twitter.com/{$screen_name}/status/{$id_str}';

	/** Twitterへの接続。 */
	private $_connection = null;

	/**
	 * コンストラクタ。
	 */
	public function __construct() {
		$this->type = 'twitter';
	}

	/**
	 * Twitterへの接続を取得する。
	 *
	 * 接続が開始されていない場合のみ接続を取得、
	 * それ以外は取得済みの接続を返す。
	 * 一度のリクエストでは常に同じ接続が返される。
	 *
	 * @return TwitterOAuth TwitterOAuthの接続。
	 */
	protected function connection() : TwitterOAuth {
		if (is_null($this->_connection)) {
			// アクセストークンを使用して汎用のコネクションを作成
			$this->_connection = new TwitterOAuth(TWITTER_API_KEY, TWITTER_API_SECRET, $this->access_token, $this->access_secret);
		}
		return $this->_connection;
	}

	/**
	 * ブログIDからTwitter情報を取得する。
	 * @param int $blogId ブログID。
	 * @return Twitter Twitter情報、取得失敗時はnull。
	 */
	public static function findByBlogId(int $blogId) : ?Twitter {
		return parent::findByPK($blogId, 'twitter');
	}

	/**
	 * 指定されたメッセージをツィートする。
	 * 140文字を超えた場合は自動で短縮する。
	 * @param string $tweet ツィート。URLと合わせて140文字を超える場合は自動で短縮される。
	 * @param string $url URL。
	 * @return bool ツィート成功時はtrue, 失敗時はfalse。
	 */
	public function tweet(string $tweet, string $url = '') : bool {
		$add = '';
		if (!empty($url)) {
			$add = ' ' . $url;
		}
		try {
			$this->connection()->post('statuses/update', [
				'status' => $this->trimByLength($tweet, self::MAX_TWEET - mb_strlen($add, APP_CHARSET)) . $add
			]);
			return true;
		} catch (Exception $e) {
			error_log($e);
			return false;
		}
	}

	/**
	 * 文字列が指定した長さより長い場合、切り詰める。
	 * @param string $str 切り詰める文字列。
	 * @param int $length 最大文字数。$trimmarkerより短い文字数にはならない。
	 * @param string $trimmarker 切り詰めた場合に後ろに付ける文字列。デフォルトは'...'。
	 * @return string 切り詰めた文字列。
	 */
	private function trimByLength(string $str, int $length, string $trimmarker = '...') : string {
		$count = mb_strlen($str, APP_CHARSET);
		if ($count <= $length){
			return $str;
		}
		return mb_substr($str, 0, $length - mb_strlen($trimmarker, APP_CHARSET), APP_CHARSET) . $trimmarker;
	}

	/**
	 * 自分のタイムラインを取得する。
	 * @param int $count 最大取得件数。
	 * @return array タイムライン。取得失敗時はnull。
	 */
	public function timeline(int $count = 10) : ?array {
		try {
			return $this->connection()->get('statuses/user_timeline', ['count' => $count]);
		} catch (Exception $e) {
			error_log($e);
			return null;
		}
	}

	/**
	 * タイムライン情報からユーザー情報を取得する。
	 *
	 * ユーザー情報はAPIからも取れるが、呼び出し回数を消費するため。
	 * @param array $timeline タイムライン。配列だが、timeline()の戻り値をそのまま渡してもよい。
	 * @return stdClass ユーザー情報。取得失敗時はnull。
	 */
	public function userFromTimeline(?array $timeline) : ?stdClass {
		if (empty($timeline)) {
			return null;
		}
		return $timeline[0]->user;
	}

	/**
	 * Twitterとの初回認証開始処理。
	 *
	 * 指定されたURLにコールバックする認証処理を開始し、必要なパラメータを返す。
	 * @param string $url Twitterからの認証結果を受け取るURL。
	 * @return array 転送先URL'url', リクエストトークン'request_token', シークレット'request_secret'を含んだ配列。接続失敗時はnullを返す。
	 */
	public static function startAuthorize(string $url) : ?array {
		try {
			// リクエストトークン取得用のコネクションを作成
			$connection = new TwitterOAuth(TWITTER_API_KEY, TWITTER_API_SECRET);

			// Twitterからリクエストトークンを取得
			$requestToken = $connection->oauth('oauth/request_token', ['oauth_callback' => $url]);
			$data = [
				'request_token' => $requestToken['oauth_token'],
				'request_secret' => $requestToken['oauth_token_secret'],
			];

			// 受け取ったリクエストトークンを付けた認証用URLを作成
			$data['url'] = $connection->url('oauth/authorize', array('oauth_token' => $data['request_token']));
			return $data;
		} catch (Exception $e) {
			error_log($e);
			return null;
		}
	}

	/**
	 * Twitterとの初回認証確定処理。
	 *
	 * Twitterからコールバックされた情報からアクセストークンを取得、DBに保存する。
	 * @param string $requestToken 認証開始時に取得したリクエストトークン。
	 * @param string $requestSecret 認証開始時に取得したリクエストシークレット。
	 * @param string $oauthVerifier Twitterから返された認証用パラメータ。
	 * @param string $blogId 認証したブログのID。
	 * @return Twitter Twitter認証情報。接続失敗時などはnullを返す。
	 */
	public static function commitAuthorize(string $requestToken, string $requestSecret, string $oauthVerifier, string $blogId) : ?Twitter {
		try {
			// アクセストークン取得用のコネクションを作成
			$connection = new TwitterOAuth(TWITTER_API_KEY, TWITTER_API_SECRET, $requestToken, $requestSecret);

			// Twitterからアクセストークンを取得
			$accessToken = $connection->oauth("oauth/access_token", ["oauth_verifier" => $oauthVerifier]);

			// アクセストークンをDBに格納
			$oauth = new static();
			$oauth->blog_id = $blogId;
			$oauth->access_token = $accessToken['oauth_token'];
			$oauth->access_secret = $accessToken['oauth_token_secret'];
			if (!$oauth->save()) {
				// ※ 通常失敗することはない
				return null;
			}
			return $oauth;
		} catch (Exception $e) {
			error_log($e);
			return null;
		}
	}
}
