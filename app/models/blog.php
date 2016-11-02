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

/**
 * ブログ全体の情報を扱うモデルクラス。
 *
 * DBの"BLOGS"テーブルに相当する。
 * ブログ名や所有者の情報を持つ。
 *
 * @package  SimpleBlog
 */
class Blog extends ModelBase
{
	/**
	 * ブログIDからブログ情報を取得する。
	 * @param int $id ブログID。
	 * @return Blog ブログ情報、取得失敗時はnull。
	 */
	public static function findById(int $id) : ?Blog {
		return parent::getModel("SELECT * FROM BLOGS WHERE ID = :id", ['id' => $id]);
	}

	/**
	 * メールアドレスからブログ情報を取得する。
	 * @param string $mailAddress メールアドレス。
	 * @return Blog ブログ情報、取得失敗時はnull。
	 */
	public static function findByMailAddress(string $mailAddress) : ?Blog {
		return parent::getModel("SELECT * FROM BLOGS WHERE MAIL_ADDRESS = :mail_address", ['mail_address' => $mailAddress]);
	}

	/**
	 * ブログ情報を1件取得する。
	 *
	 * 現行のアプリは1ブログしか想定していないため。
	 * 一応、必ずIDの若いブログを返す。
	 * @return Blog ブログ情報、取得失敗時はnull。
	 */
	public static function findAtHead() : ?Blog {
		return parent::getModel("SELECT * FROM BLOGS ORDER BY ID LIMIT 1");
	}

	/**
	 * ブログ情報の登録件数を返す。
	 * @return int 登録件数。
	 */
	public static function count() : int {
		$row = parent::getRow("SELECT COUNT(*) AS CNT FROM BLOGS");
		return $row['cnt'];
	}

	/**
	 * ブログ情報をDBに保存する。
	 *
	 * 保存前にはバリデートを行う。
	 * ブログIDが設定されている場合はINSERTを、
	 * 設定されていない場合はUPDATEを実行する。
	 * @return bool 保存が成功した場合true、失敗した場合false。
	 */
	public function save() : bool {
		if (!$this->validate()) {
			return false;
		}

		if (empty($this->id)) {
			return $this->insert();
		} else {
			return $this->update();
		}
	}

	/**
	 * 有効なブログ情報かのチェックを行う。
	 * @return bool 問題ない場合はtrue、不可の場合はfalse。
	 */
	protected function validate() : bool {
		$this->errors = [];
		$this->addErrorIfBlank('title', 'タイトル');
		$this->addErrorIfBlank('mail_address', 'メールアドレス');
		if (empty($this->id)) {
			// パスワードは新規登録時以外は変更時のみ指定する
			$this->addErrorIfBlank('password', 'パスワード');
		}
		if ($this->password_raw !== $this->password_raw2) {
			// パスワードの一致チェックは、モデルの値ではなくフォームとしての平文パスワードに対して行う
			$this->errors[] = 'パスワードの再入力が一致しません。正しい値を入力してください。';
		}
		return empty($this->errors);
	}

	/**
	 * ブログ情報をDBに登録する。
	 * @return bool 保存が成功した場合true、失敗した場合false。
	 */
	protected function insert() : bool {
		return parent::executeAndGetId(
				"INSERT INTO BLOGS (TITLE, MAIL_ADDRESS, PASSWORD)"
				. " VALUES (:title, :mail_address, :password)",
				[
					'title' => $this->title,
					'mail_address' => $this->mail_address,
					'password' => $this->password,
				]);
	}

	/**
	 * ブログ情報をDBに上書きする。
	 * @return bool 保存が成功した場合true、失敗した場合false。
	 */
	protected function update() : bool {
		$query = "UPDATE BLOGS SET TITLE = :title"
				. ", MAIL_ADDRESS = :mail_address";
		$data = [
			'title' => $this->title,
			'mail_address' => $this->mail_address,
			'id' => (int) $this->id,
		];

		if (!is_null($this->password) && $this->password !== '') {
			// パスワードは新しい値が指定された場合のみ更新
			$query .= ", PASSWORD = :password";
			$data['password'] = $this->password;
		}
		$query .= " WHERE ID = :id";

		return parent::execute($query, $data);
	}

	/**
	 * 指定されたメールアドレスとパスワードのブログが登録されているか認証する。
	 *
	 * 認証成功時は最終ログイン日時を更新する。
	 * @param string $mailAddress メールアドレス。
	 * @param string $password 平文パスワード。
	 * @return Blog 認証OKの場合そのブログ、不可の場合null。
	 */
	public static function authorize(string $mailAddress, string $password) : ?Blog {
		$blog = self::findByMailAddress($mailAddress);
		if (!$blog) {
			return null;
		}
		if (crypt($password, $blog->password) === $blog->password) {
			// 最終ログイン日時を更新、モデルは前回の値を表示したいので更新しない
			$blog->touch();
			return $blog;
		}
		return null;
	}

	/**
	 * 平文のパスワードからプロパティにハッシュ化パスワードを保存する。
	 * @param string $raw 平文パスワード。
	 * @return void
	 */
	public function setHashPassword(string $raw) : void {
		$this->password = crypt($raw);
	}

	/**
	 * ブログ管理者の最終ログイン日時を更新する。
	 * @return bool 更新が成功した場合true、失敗した場合false。
	 */
	protected function touch() : bool {
		return parent::execute(
				"UPDATE BLOGS SET LAST_LOGIN = NOW() WHERE ID = :id",
				['id' => (int) $this->id]);
	}

	/**
	 * ブログに登録されている記事を取得する。
	 * @param int $page ページ番号、未指定時は全て。デフォルトは未指定。
	 * @param int $pageMax 1ページ当たりの表示件数。
	 * @param string $tag タグによる絞り込みを行う場合のタグ名。
	 * @param bool $public 表示対象コンテンツのみ対象とする場合はtrue。デフォルトtrue。
	 * @return array ブログ記事の配列。
	 */
	public function contents(int $page = null, int $pageMax = 10, ?string $tag = null, bool $public = true) : array {
		if (empty($this->id)) {
			return [];
		}
		return Content::find($this->id, $page, $pageMax, $tag, $public);
	}

	/**
	 * ブログに登録されている記事数を取得する。
	 * @param string $tag タグによる絞り込みを行う場合のタグ名。
	 * @param bool $public 表示対象コンテンツのみ対象とする場合はtrue。デフォルトtrue。
	 * @return int 記事の件数。
	 */
	public function countContents(?string $tag = null, bool $public = true) : int {
		if (empty($this->id)) {
			return 0;
		}
		return Content::count($this->id, $tag, $public);
	}

	/**
	 * ブログに登録されている記事の全タグ名を取得する。
	 * @param bool $public 表示対象コンテンツのみ対象とする場合はtrue。デフォルトtrue。
	 * @return array タグの配列。
	 */
	public function tags(bool $public = true) : array {
		if (empty($this->id)) {
			return [];
		}
		return Tag::findByBlogId($this->id, $public);
	}

	/**
	 * ブログのTwitter認証情報を取得する。
	 * @return Twitter 認証情報、取得失敗時はnull。
	 */
	public function twitterAuth() : ?Twitter {
		if (empty($this->id)) {
			return null;
		}
		return Twitter::findByBlogId($this->id);
	}
}
