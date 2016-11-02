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
 * OAUTH認証情報を扱うモデルクラス。
 *
 * DBの"OAUTH"テーブルに相当する。
 * 接続先やアクセストークン等の情報を持つ。
 *
 * @package  SimpleBlog
 */
class OAuth extends ModelBase
{
	/**
	 * ブログIDと接続先種別から認証情報を取得する。
	 * @param int $blogId ブログID。
	 * @param string $type 接続先種別。
	 * @return OAuth 認証情報、取得失敗時はnull。
	 */
	public static function findByPK(int $blogId, string $type) : ?OAuth {
		return parent::getModel(
				"SELECT * FROM OAUTH WHERE BLOG_ID = :blog_id AND TYPE = :type",
				[
					'blog_id' => $blogId,
					'type' => $type,
				]);
	}

	/**
	 * 認証情報をDBに保存する。
	 * 
	 * 保存前にはバリデートを行う。
	 * データが無い場合はINSERTを、既に存在する場合はUPDATEを実行する。
	 * @return bool 保存が成功した場合true、失敗した場合false。
	 */
	public function save() : bool {
		if (!$this->validate()) {
			return false;
		}

		if (self::findByPK($this->blog_id, $this->type)) {
			return $this->update();
		} else {
			return $this->insert();
		}
	}

	/**
	 * 有効な認証情報かのチェックを行う。
	 * @return bool 問題ない場合はtrue、不可の場合はfalse。
	 */
	protected function validate() : bool {
		$this->errors = [];
		$this->addErrorIfNotNumeric('blog_id', 'ブログID');
		if (!$this->addErrorIfBlank('type', '接続先種別')
				&& $this->type !== 'twitter') {
			$this->errors[] = '想定外の接続先種別が指定されました。処理をやり直してください。';
		}
		$this->addErrorIfBlank('access_token', 'アクセストークン');
		$this->addErrorIfBlank('access_secret', 'アクセスシークレット');
		return empty($this->errors);
	}

	/**
	 * 認証情報をDBに登録する。
	 * @return bool 保存が成功した場合true、失敗した場合false。
	 */
	protected function insert() : bool {
		return parent::execute(
				"INSERT INTO OAUTH (BLOG_ID, TYPE, ACCESS_TOKEN, ACCESS_SECRET)"
				. " VALUES (:blog_id, :type, :access_token, :access_secret)",
				[
					'blog_id' => (int) $this->blog_id,
					'type' => $this->type,
					'access_token' => $this->access_token,
					'access_secret' => $this->access_secret,
				]);
	}

	/**
	 * 認証情報をDBに上書きする。
	 * @return bool 保存が成功した場合true、失敗した場合false。
	 */
	protected function update() : bool {
		return parent::execute(
				"UPDATE OAUTH SET ACCESS_TOKEN = :access_token"
				. ", ACCESS_SECRET = :access_secret"
				. " WHERE BLOG_ID = :blog_id AND TYPE = :type",
				[
					'access_token' => $this->access_token,
					'access_secret' => $this->access_secret,
					'blog_id' => (int) $this->blog_id,
					'type' => $this->type,
				]);
	}

	/**
	 * 認証情報をDBから削除する。
	 * @return bool 削除が成功した場合true、失敗した場合false。
	 */
	public function remove() : bool {
		return parent::execute(
				"DELETE FROM OAUTH WHERE BLOG_ID = :blog_id AND TYPE = :type",
				[
					'blog_id' => (int) $this->blog_id,
					'type' => $this->type,
				]);
	}
}
